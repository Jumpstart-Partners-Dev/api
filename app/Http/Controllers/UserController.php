<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Cache;
use Session;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserController extends Controller
{
    public $user;

    public function __construct() {
        $this->user = new User; // App\Model\User
    }

    public function register(Request $req) {
        $this->user->C([
            'username' => $req->username,
            'password' => Hash::make($req->password),
            'enabled' => 1,
            'reset' => 1
        ]);

        echo json_encode(['id' => $this->user->data]);
    } 

    public function setAble(Request $req) {
        $this->user->U($req->id, ['enabled' => $req->state]);
    }

    public function resetPassword(Request $req) {
        $this->user->U($req->id, ['password' => Hash::make($req->password)]);
    }

    public function deleteUser($id) {
       return $this->user->D($id);
    }
    public function getAll() {
        echo json_encode(['users' => $this->user->R('users<-profiles')->get(), 'error' => $this->user->error]);
    }


    public function getProfile (Request $req) {
        try {
            $query = DB::table('user_profiles')->where('user_id',  $req->user_id);
    
            if($query->count() > 0) { $c = true; } else { $c = false; }
            echo json_encoode([
                'error' => false,
                'found' => $c,
                'profile' => $query->first() 
            ]);            
        } catch (\Throwable $th) {
            echo json_encode(['error' => true, 'message' => $th]);
        }
    }
}
