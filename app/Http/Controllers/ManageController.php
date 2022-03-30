<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageController extends Controller
{
    //
    public function getTopStores() {
        $query1 = "SELECT b.id, 
            b.name, b.logo, b.alias, b.store_url, b.affiliate_url, 
            c.id as c_id, c.title, c.currency, c.exclusive, c.expire_date, c.comment_count, c.discount, c.cash_back, c.coupon_code as code, 
            c.coupon_type as type, c.coupon_image as image, d.foreign_key_right as go
            FROM public.top_stores as a
            left join stores as b
            on a.store_id = b.id
            left join coupons as c
            on a.coupon_id = c.id
            left join properties as d
            on c.id = d.foreign_key_left
            ORDER BY a.order ASC";
        $result = DB::select($query1);
        if (empty($result)) $result = [];
        echo json_encode(['data' => $result, 'status' => 0]);
    }

    public function getBestStores() {
        $orderBy = 'ORDER BY last_added_coupon DESC';
        $queryStoreSlide = "SELECT id, name, logo, alias, store_url, affiliate_url, cash_back_json, total_coupons_available, coupon_count FROM stores WHERE  show_in_homepage = 1 AND status = 'published' AND (publish_date IS NULL OR publish_date < NOW()) AND total_coupons_available > 0 $orderBy LIMIT 20";
        
        $query1 = "SELECT b.id, 
            b.name, b.logo, b.alias, b.store_url, b.affiliate_url, 
            c.id as c_id, c.title, c.currency, c.exclusive, c.expire_date, c.comment_count, c.discount, c.cash_back, c.coupon_code as code, 
            c.coupon_type as type, c.coupon_image as image, d.foreign_key_right as go
            FROM public.best_stores as a
            left join stores as b
            on a.store_id = b.id
            left join coupons as c
            on a.coupon_id = c.id
            left join properties as d
            on c.id = d.foreign_key_left
            ORDER BY a.order ASC";
        $result = DB::select($query1);
        if (empty($result)) $result = [];
        echo json_encode(['data' => $result, 'status' => 0]);
    }

    public function getPopularStores() {
        
    }

    public function searchStores(Request $req) {
        $sl = strtolower($req->search);
        $strs = explode(' ', $sl);
        $where = '';
        $s = '';
        foreach ($strs as $key => $str) {
            if($key == 0) {
                $where .= "where alias like '%".$str."%'";
            } else {
                $where .= " and alias like '%".$str."%'";
            }
            $s .= $str;
        }
        $query1 = "select id, name, coupon_count, deal_count, logo, alias from stores where alias = '".$s."' limit 100";
        $result = DB::select($query1);
        if (empty($result)) {
            $query2 = "select id, name, coupon_count, deal_count, logo, alias from stores ".$where." limit 50";
            $result = DB::select($query2);
        }
        echo json_encode(['data' => $result, 'status' => 0]);
    }

    public function searchBlogs(Request $req) {
        $sl = strtolower($req->search);
        $strs = explode(' ', $sl);
        $where = '';
        $s = '';
        foreach ($strs as $key => $str) {
            if($key == 0) {
                $where .= "where slug like '%".$str."%'";
            } else {
                $where .= " and slug like '%".$str."%'";
            }
            $s .= $str;
        }
        $query1 = "select id, title as name, thumbnail as logo, slug as alias from blogs where slug = '".$s."' limit 100";
        $result = DB::select($query1);
        if (empty($result)) {
            $query2 = "select id, title as name, thumbnail as logo, slug as alias from blogs ".$where." limit 50";
            $result = DB::select($query2);
        }
        echo json_encode(['data' => $result, 'status' => 0]);
    }

    public function searchCodes(Request $req) {
        $query1 = "select id, title, coupon_type, coupon_code, created_at, status from coupons
            where store_id = '" . $req->store_id. "'
            order by created_at desc 
            limit 100";
        $result = DB::select($query1);
        if (empty($result)) $result = [];
        echo json_encode(['data' => $result, 'status' => 0]);
    }

    public function addStore(Request $req) {
        $target = $req->target;
        $store = $req->store_id;
        $coupon = $req->coupon_id;

        $id = DB::table($target)->insertGetId([
            'store_id' => $store, 
            'coupon_id' => $coupon
        ]);

        DB::table($target)
            ->where('id', $id)
            ->update([
                'order' => $id
            ]);
        
    }

    public function reorder(Request $req) {
        $target = $req->target;
        $stores = json_decode($req->stores);
        foreach($stores as $key => $store) {
            DB::table($target)
                ->where('store_id', $store->id)
                ->update(['order' => $key + 1]);
        }
    }

    public function removeStore(Request $req) {
        $target = $req->target;
        $store = $req->store_id;

        DB::table($target)
            ->where('store_id', $store)
            ->delete();
    }

    public function getSeoDetails(Request $req) {
        $query1 = "select * from seo
            where ref_id = '" . $req->id. "' 
            and type = '" . $req->type . "'";
        $result = DB::select($query1);
        if (empty($result)) $result = [];
        echo json_encode(['data' => $result, 'status' => 0]);
    }

    public function saveSeoDetails(Request $req) {
        $cnt = DB::table('seo')->where('ref_id', $req->id)->count();
        if($cnt == 0) {
            DB::table('seo')->insert([
                'ref_id' => $req->id,
                'type' => $req->type,
                'title' => $req->title,
                'description' => $req->description
            ]);
        } else {
            DB::table('seo')
                ->where('ref_id', $req->id)
                ->where('type', $req->type)
                ->update([
                    'title' => $req->title,
                    'description' => $req->description
                ]);
        }
        echo json_encode(['status' => 0]);
    }

    public function getAffTypes () {
        $data = DB::table('aff_types')->get();

        if(!is_null($data)) {
            return json_encode(['data' => $data, 'status' => 200]);
        } else {
            return json_encode(['data' => $data, 'status' => 0]);
        }
    }
}
