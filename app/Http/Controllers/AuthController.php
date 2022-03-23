<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Cache;
use Session;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{
    public $user;

    public function __construct() {
        $this->user = new User; // App\Model\User
    }
    public function login(Request $req) {
        $this->user->R('user<-profile', 'username', $req->username );
        $success = false;
        $user = null;

        if (count($this->user->get()) != 0) {
            $user = $this->user->first();

            if (Hash::check($req->password, $user->password)) { $success = true; }
        }

        echo json_encode(['success' => $success, 'user' => $user]);
    }

    public function changepass(Request $req) {
        $this->user->U($req->id, ['password' => Hash::make($req->password), 'reset' => 0]);
    }
}
