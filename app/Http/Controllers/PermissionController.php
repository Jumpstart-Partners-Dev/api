<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    //
    public $permission;

    public function __construct() {
        $this->permission = new Permission;
    }
    public function getAll () {
        return json_encode(['data' => Permission::all()]);
        //  $data = $this->permission->getAll();
        // print_r($data->areas);
    }
}
