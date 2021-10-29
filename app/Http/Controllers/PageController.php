<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

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
        if ($req->hasFile('banner')){
            $path = Storage::disk('public2')->putFile('banners', $req->file('banner'));

            $page = DB::table('pages')->insert([
                'title' => $req->title,
                'slug' => $req->slug,
                'banner' => $path,
                'description' => $req->description,
                'pagetext' => $req->pagetext
            ]);
        } else {
            $page = DB::table('pages')->insert([
                'title' => $req->title,
                'slug' => $req->slug,
                'description' => $req->description,
                'pagetext' => $req->pagetext
            ]);
        }
        
        if(!is_null($page)) {
            echo json_encode(['data' => $page, 'status' => 200]);
        } else {
            echo json_encode(['data' => $page, 'status' => 0]);
        }
    }

    public function update (Request $req, $id)
    {
        if ($req->hasFile('banner')){
            $path = Storage::disk('public2')->putFile('banners', $req->file('banner'));

            $page = DB::table('pages')->where('id', $id)->update([
                'title' => $req->title,
                'slug' => $req->slug,
                'banner' => $path,
                'description' => $req->description,
                'pagetext' => $req->pagetext
            ]);
        } else {
            $page = DB::table('pages')->where('id', $id)->update([
                'title' => $req->title,
                'slug' => $req->slug,
                'description' => $req->description,
                'pagetext' => $req->pagetext
            ]);
        }
        
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

    public function get_content ($id, $type)
    {
        // $data = DB::table('page_contents')->where('page_id', $id)->where('type', $type)->get();

        // if(!is_null($data)) {
        //     echo json_encode(['data' => $data, 'status' => 200]);
        // } else {
        //     echo json_encode(['data' => $data, 'status' => 0]);
        // }

        $query1 = "SELECT a.id, 
            b.name, b.logo, b.alias, b.store_url, b.affiliate_url, 
            c.id as c_id, c.title, c.currency, c.exclusive, c.expire_date, c.comment_count, c.discount, c.cash_back, c.coupon_code as code, 
            c.coupon_type as type, c.coupon_image as image, d.foreign_key_right as go
            FROM public.page_contents as a
            left join stores as b
            on a.store_id = b.id
            left join coupons as c
            on a.coupon_id = c.id
            left join properties as d
            on c.id = d.foreign_key_left where a.page_id = '".$id."' and a.type = '".$type."'";
        $result = DB::select($query1);
        if (empty($result)) $result = [];
        echo json_encode(['data' => $result, 'status' => 0]);
    }
    public function create_content (Request $req)
    {
        $data = DB::table('page_contents')->insert([
            'page_id' => $req->page_id,
            'store_id' => $req->store_id,
            'coupon_id' => $req->coupon_id,
            'type' => $req->type
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
