<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomepageContentController extends Controller
{
    public function get_contents() {
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
        $result1 = DB::select($query1);

        $orderBy = 'ORDER BY last_added_coupon DESC';
        $queryStoreSlide = "SELECT id, name, logo, alias, store_url, affiliate_url, cash_back_json, total_coupons_available, coupon_count FROM stores WHERE  show_in_homepage = 1 AND status = 'published' AND (publish_date IS NULL OR publish_date < NOW()) AND total_coupons_available > 0 $orderBy LIMIT 20";
        
        $query2 = "SELECT b.id, 
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
        $result2 = DB::select($query2);

        if (!empty($result1) && !empty($result2)) {
            return json_encode(['topStore' => $result1, 'bestStore' => $result2]);
        } else {
            return json_encode(['data' => "Error"]);
        }
        
    }
}
