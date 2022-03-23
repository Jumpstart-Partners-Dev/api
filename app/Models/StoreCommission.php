<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StoreCommission extends Model
{
    public $store_commission;
    public $table;
    public $data;
    public $error;
    public $message;
    public $query;
    public $limit;
    public $page;
    public $count;
    public $lastpage;

    public function __construct() {
        $this->table = (Object) [
            'store_commissions' => 'store_commissions',
            'stores' => 'stores'
        ];
        $this->query = '';
        $this->limit = 20;
        $this->page = 1;
    }

    public function C($data) {
        try {
            //code...
        } catch (Throwable $th) {
            $this->error = true;
            $this->message = $th;
        }

        return $this;
    }

    public function R($type, $target = null, $value = null) {
        try {
            switch($type) {
                case 'store_commissions<-stores':
                    $cond = "";

                    //condition builder
                    if($target != null) {
                        foreach ($target as $idx => $key) {
                            if ($idx == 0) {
                                $cond .= " where ".$key[0]." ".$key[1]." ".$key[2].""; 
                            } else {
                                $cond .= " and ".$key[0]." ".$key[1]." ".$key[2].""; 
                            }
                        }
                    }

                    $c = DB::select("select count(*) as count 
                        from ".$this->table->store_commissions." as a 
                        left join ".$this->table->stores." as b on a.store_id = b.id
                        " . $cond);

                    $this->count = $c[0]->count;
                    $this->lastpage = ceil($this->count / $this->limit);

                    $q = "select 
                        a.*, b.logo, b.name, a.id as store_commission_id, b.id as store_id 
                        from ".$this->table->store_commissions." as a
                        left join ".$this->table->stores." as b on a.store_id = b.id " . $cond;

                    $q .= "order by id asc limit " . $this->limit . " offset " . (($this->page - 1) * $this->limit);
                    
                    $this->query = $q;
                    $this->data = DB::select($q);
                    
                    break;
            }
        } catch (Throwable $th) {
            $this->error = true;
            $this->message = $th;
        }

        return $this;
    }

    public function U($id, $data) {
        try {
            DB::table($this->table->store_commissions)->where('id', $id)
                ->update($data);
        
            $this->error = false;
        } catch (Throwable $th) {
            $this->error = true;
            $this->message = $th;
        }

        return $this;
    } 

    public function D($id) {
        try {
            //code...
        } catch (Throwable $th) {
            $this->error = true;
            $this->message = $th;
        }

        return $this;
    }

    public function get () {
        return $this->data;
    }

    public function first() {
        return $this->data[0];
    }
}
