<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    public $user;
    public $table;
    public $data;
    public $error;
    public $message;

    public function __construct() {
        $this->table = (Object) [
            'users' => 'users_v2',
            'profiles' => 'user_profiles',
        ];
    }

    public function C($data) {
        try {
            // check dupes before insert || params ['username']
            if (count($this->r('user<-profile', 'username', $data['username'] )->get()) == 0) {
                $id = DB::table($this->table->users)->insertGetId($data);
                $this->data = $id;

                $this->error = false;
            } else {
                $this->error = true;
                $this->message = 'User Already Exist';
            }
            
        } catch (Throwable $th) {
            $this->error = true;
            $this->message = $th;
        }
        echo $this->message;
        return $this;
    }

    public function R($type, $target = null, $value = null) {
        try {
            switch ($type) {
                case "users<-profiles":
                    $this->data = DB::select("select 
                        a.*, b.*, a.id as user_id, b.id as profile_id 
                        from ".$this->table->users." as a
                        left join ".$this->table->profiles." as b on a.id = b.user_id
                    ");
                    break;
                case "user<-profile":
                    $this->data = DB::select("select 
                        a.*, b.*, a.id as user_id, b.id as profile_id 
                        from ".$this->table->users." as a
                        left join ".$this->table->profiles." as b on a.id = b.user_id where ".$target." = '".$value."'
                    ");
                    break;
            }

            $this->error = false;
        } catch (Throwable $th) {
            $this->error = true;
            $this->message = $th;
        }

        return $this;
    }

    public function U($id, $data) {
        try {
            DB::table($this->table->users)->where('id', $id)
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
            DB::table($this->table->users)->where('id', $id)->delete();

            $this->error = false;
        } catch (Throwable $th) {
            $this->error = true;
            $this->message = $th;
        }
    }

    public function get () {
        return $this->data;
    }

    public function first() {
        return $this->data[0];
    }

    
}
