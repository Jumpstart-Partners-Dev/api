<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function index ()
    {
        $page = DB::table('pages')->get();

        if(!is_null($page)) {
            echo json_encode(['data' => $page, 'status' => 200]);
        } else {
            echo json_encode(['data' => $page, 'status' => 0]);
        }
    }

    public function get ($id)
    {
        $page = DB::table('pages')->where('id', $id)->first();

        if(!is_null($page)) {
            echo json_encode(['data' => $page, 'status' => 200]);
        } else {
            echo json_encode(['data' => $page, 'status' => 0]);
        }
    }
    

    public function create (Request $req)
    {
        $page = DB::table('pages')->insert([
            'title' => $req->title
        ]);
        if(!is_null($page)) {
            echo json_encode(['data' => $page, 'status' => 200]);
        } else {
            echo json_encode(['data' => $page, 'status' => 0]);
        }
    }

    public function update (Request $req, $id)
    {
        $page = DB::table('pages')->where('id', $id)->update(['title' => $req->title]);
        if(!is_null($page)) {
            echo json_encode(['data' => $page, 'status' => 200]);
        } else {
            echo json_encode(['data' => $page, 'status' => 0]);
        }
    }

    public function delete (Request $req, $id)
    {
        $page = DB::table('pages')->where('id', $id)->delete();

        echo json_encode(['data' => $page, 'status' => 0]);    
    }

    //page-contents

    public function index_content ()
    {
        $data = DB::table('page_contents')->get();

        if(!is_null($data)) {
            echo json_encode(['data' => $data, 'status' => 200]);
        } else {
            echo json_encode(['data' => $data, 'status' => 0]);
        }
    }

    public function get_content ($id)
    {
        $data = DB::table('page_contents')->where('id', $id)->get();

        if(!is_null($data)) {
            echo json_encode(['data' => $data, 'status' => 200]);
        } else {
            echo json_encode(['data' => $data, 'status' => 0]);
        }
    }
    public function create_content (Request $req)
    {
        $data = DB::table('page_contents')->insert([
            'page_id' => $req->page_id,
            'store_id' => $req->store_id,
            'coupon_id' => $req->coupon_id
        ]);
        if(!is_null($data)) {
            echo json_encode(['data' => $data, 'status' => 200]);
        } else {
            echo json_encode(['data' => $data, 'status' => 0]);
        }
    }

    public function update_content (Request $req, $id)
    {
        $data = DB::table('page_contents')->where('id', $id)->update([
            'page_id' => $req->page_id,
            'store_id' => $req->store_id,
            'coupon_id' => $req->coupon_id
        ]);
        if(!is_null($data)) {
            echo json_encode(['data' => $data, 'status' => 200]);
        } else {
            echo json_encode(['data' => $data, 'status' => 0]);
        }
    }

    public function delete_content (Request $req, $id)
    {
        $data = DB::table('page_contents')->where('id', $id)->delete();
            
        echo json_encode(['data' => $data, 'status' => 0]);
    }


}
