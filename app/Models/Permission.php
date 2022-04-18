<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public $data;

    protected $table = 'permissions';

    public function getAll () {
        
        return json_encode($this->data = $this->all());
    }

    
}
