<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function getLatestStore() {
        $stores = DB::table('stores')
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        echo json_encode(['data' => $stores]);
    }

    public function nameWithKeyword($name) {
        if (stripos($name, ' coupon') || stripos($name, ' coupons')) {
            return $name .= ' & Promo codes';
        } elseif (stripos($name, ' promo')) {
            return $name .= ' & Discount codes';
        } elseif (stripos($name, ' voucher')) {
            return $name .= ' & Coupon codes';
        } elseif (stripos($name, ' discount')) {
            return $name .= ' & Coupon codes';
        } else {
            return $name .= ' Coupons & Promo codes';
        }
    }

    public function checkStringInLastPosition($str, $findMe) {
        $l = strlen($str);
        if (strrpos($findMe, '-coupons') === 0)
            return false;
        $t = strrpos($findMe, '-coupons') + strlen($findMe); 
        if ($l === $t) {
            return true;
        }
        return false;
    }

    public function randomLatestStores($quantity) {
        $arrRandomKeywords = [
            '[STORENAME] Coupons',
            '[STORENAME] Discount',
            '[STORENAME] Discount Code',
            '[STORENAME] Promo Code',
            '[STORENAME] Coupon',
            '[STORENAME] Deal',
            '[STORENAME] Coupon [YEAR]',
            'Get [STORENAME] Discount Code',
            'Coupon [STORENAME]',
            'Get Coupons for [STORENAME]',
            '[DOMAIN] Coupon',
            'Coupon [DOMAIN]',
            'Discount [DOMAIN]',
            '[DOMAIN] Discount Code',
        ];
        $arr = [];
        $result = DB::select("select id,name,alias,store_url from stores where store_url not like '%etsy.com%' AND random() < 0.01 limit $quantity");
        $year = Carbon::now()->format('Y');
        foreach ($result as $item) {
            $domain = str_replace('https://', '', $item->store_url);
            $domain = str_replace('http://', '', $domain);
            $domain = str_replace('www.', '', $domain);
            $domain = str_replace('/', '', $domain);
            $ranKey = rand(0, 13);
            $t = [];
            $struct = $arrRandomKeywords[$ranKey];
            $newStoreName = str_replace('[STORENAME]', $item->name, $struct);
            $newStoreName = str_replace('[DOMAIN]', $domain, $newStoreName);
            $newStoreName = str_replace('[YEAR]', $year, $newStoreName);
            $t['name'] = $newStoreName;
            $t['alias'] = $item->alias;
            array_push($arr, $t);
        }
        return $arr;
    }

    public function indexForUpdateCoupon($alias) {
        $dataSeo = [
            'seo' => [
                'title' => 'Discount Voucher Title index controller overwrite',
                'keywords' => 'Discount Voucher keywords index controller overwrite',
                'description' => 'Discount Voucher description index controller overwrite',
                'image' => ''
            ]
        ];

        $dataPage = ['dcCount' => '9,419'];

        $sDetail = DB::table('stores')->where('alias', '=', $alias)->where('countrycode', '=', 'US')
            ->where('status', '=', 'published')->first();


        $sDetail = (array)$sDetail;

        $allData = array_merge($dataPage, $dataSeo);
        $allData['store'] = $sDetail;
        $allData['store']['coupons'] = [];
        $allData['store']['relateStores'] = [];
        $allData['store']['expiredCoupons'] = [];
        $allData['store']['similarStores'] = [];
        $allData['arrVerified'] = [];
        $allData['arrStickyCp'] = [];
        $allData['arrCouponsNormal'] = [];
        $allData['arrCouponsRemote'] = [];
        return $allData;
    }

    public function index(Re $request, $alias = '') {
        $data['refer'] = !empty($request->all()['ref']) ? $request->all()['ref'] : '';
        $storeAlias = strtolower($alias);

        if (strpos($request->path(), 'coupon-detail') !== FALSE) {
            $rm = str_replace('coupon-detail', '', $request->path());
            return redirect($rm, 301);
        }

        if ($storeAlias[strlen($storeAlias) - 1] == '-') {
            $rm = substr($storeAlias, 0, strlen($storeAlias) - 1);
            return redirect($rm, 301);
        }

        if (!empty($_GET['update-coupon'])) {
            $storeAlias = $alias;
            $dt = $this->indexForUpdateCoupon($storeAlias);
            return view('storeDetailForUpdateCoupon')->with($dt);
        }

        $store = Cache::remember('store_' . $storeAlias, 10080, function () use ($storeAlias) {
            return DB::table('stores')->select('stores.id AS id', 'name', 'logo', 'social_image', 'store_url', 'alias', 'affiliate_url', 'categories_id', 'best_store', 'custom_keywords', 'coupon_count', 'description', 'short_description', 'head_description', 'properties.foreign_key_right AS go', 'meta_title', 'meta_desc', 'cash_back_json', 'cash_back_total', 'cash_back_term', 'sid_name', 'update_coupon_from', 'note', 'store_url', 'has_order', 'social_links','review_links')
                ->leftJoin('properties', 'stores.id', '=', 'properties.foreign_key_left')
                ->where('stores.alias', '=', $storeAlias)
                ->where('stores.countrycode', '=', 'US')
                ->where('stores.status', '=', 'published')
                ->first();
        });

        if ($store && $store->note != 'ngach' && strpos($storeAlias, '-coupons') === false && strpos($request->path(), 'coupon-detail') === false) {
            if ($this->checkStringInLastPosition($storeAlias, '-coupons') === false) {
                return redirect(url('/' . $storeAlias . '-coupons' . (!empty($data['refer']) ? '?ref='.$data['refer'] : '') ), 301);
            }
        }

        if (!$store) {
            $storeAlias = str_replace('-coupons', '', $storeAlias);
            $store = Cache::remember('store_' . $storeAlias, 60 * 24 * 7, function () use ($storeAlias) {
                return DB::table('stores')->select('stores.id AS id', 'name', 'logo', 'social_image', 'store_url', 'alias', 'affiliate_url', 'categories_id', 'best_store', 'custom_keywords', 'coupon_count', 'description', 'short_description', 'head_description', 'properties.foreign_key_right AS go', 'meta_title', 'meta_desc', 'cash_back_json', 'cash_back_total', 'cash_back_term', 'sid_name', 'update_coupon_from', 'note', 'store_url', 'has_order','social_links','review_links')
                    ->leftJoin('properties', 'stores.id', '=', 'properties.foreign_key_left')
                    ->where('stores.alias', '=', $storeAlias)
                    ->where('stores.countrycode', '=', 'US')
                    ->where('stores.status', '=', 'published')
                    ->first();
            });
        }

        $store = (array)$store;
        $coupons = [];
        $childStores = [];

        $customFAQ = [];$customSavingTip = [];
        if ($store) {
            $storeId = $store['id'];
            $storeName = $this->nameWithKeyword($store['name']);
            $store['originalStoreName'] = $store['name'];
            $store['name'] = $storeName;

            /* Check if in Filter Mode */
            $sessionKey = 'coupon_type_' . $storeAlias;
            $arrFilterByCouponTypes = !empty(Session::get($sessionKey)) ? Session::get($sessionKey) : []; // this variable is an array of selected Coupon Type

            if(empty($arrFilterByCouponTypes)){
                $coupons = Cache::remember('coupons_' . $storeId, 30, function () use ($storeId) {
                    return DB::select(DB::raw(
                        "SELECT c.id,c.title,c.currency,c.exclusive,c.description,c.created_at,c.expire_date,c.discount,c.coupon_code AS code,c.coupon_type AS type,c.coupon_image AS image,c.sticky,c.verified,c.comment_count,c.latest_comments,c.number_used,c.cash_back,c.note,c.top_order,p.foreign_key_right AS go
                    FROM coupons c
                    JOIN properties p ON c.id = p.foreign_key_left
                    WHERE c.store_id = '{$storeId}'
                    AND c.status = 'published'
                    AND (c.expire_date >= NOW() OR c.expire_date IS NULL)
                    AND c.coupon_type NOT IN ('FAQ','Saving Tips')
                    ORDER BY 
                    CASE
                        WHEN c.sticky = 'top' THEN 5
                        WHEN c.sticky = 'hot' THEN 4
                        WHEN c.verified = 1 THEN 3
                        WHEN c.sticky IS NULL THEN 2
                        WHEN c.sticky = 'none' THEN 1
                        WHEN c.sticky = 'pending' THEN 0
                    END DESC,
                    CASE
                        WHEN c.coupon_type='Coupon Code' THEN 1
                        WHEN c.coupon_type='Deal Type' THEN 2
                        WHEN c.coupon_type='Great Offer' THEN 3
                    END ASC,
                    c.top_order ASC,
                    c.created_at DESC,
                    c.title ASC
                    LIMIT 25
                    "
                    ));
                });
            }else{
                if(!empty($arrFilterByCouponTypes[0]) AND $arrFilterByCouponTypes[0] == 'All'){
                    $queryFilterByCouponTypes = '';
                }else{
                    $strFilterByCouponTypes = "('" . join("','", $arrFilterByCouponTypes) . "')";
                    $queryFilterByCouponTypes = "AND c.coupon_type IN {$strFilterByCouponTypes}";
                }

                $coupons = DB::select(DB::raw(
                    "SELECT c.id,c.title,c.currency,c.exclusive,c.description,c.created_at,c.expire_date,c.discount,c.coupon_code AS code,c.coupon_type AS type,c.coupon_image AS image,c.sticky,c.verified,c.comment_count,c.latest_comments,c.number_used,c.cash_back,c.note,c.top_order,p.foreign_key_right AS go
                    FROM coupons c
                    JOIN properties p ON c.id = p.foreign_key_left
                    WHERE c.store_id = '{$storeId}'
                    AND c.status = 'published'
                    AND (c.expire_date >= NOW() OR c.expire_date IS NULL)
                    AND c.coupon_type NOT IN ('FAQ','Saving Tips')
                    {$queryFilterByCouponTypes}

                    ORDER BY 
                    CASE
                        WHEN c.sticky = 'top' THEN 5
                        WHEN c.sticky = 'hot' THEN 4
                        WHEN c.verified = 1 THEN 3
                        WHEN c.sticky IS NULL THEN 2
                        WHEN c.sticky = 'none' THEN 1
                        WHEN c.sticky = 'pending' THEN 0
                    END DESC,
                    CASE
                        WHEN c.coupon_type='Coupon Code' THEN 1
                        WHEN c.coupon_type='Deal Type' THEN 2
                        WHEN c.coupon_type='Great Offer' THEN 3
                    END ASC,
                    c.top_order ASC,
                    c.created_at DESC,
                    c.title ASC
                    LIMIT 100
                    "));
            }


            $couponsTypeFAQ = Cache::remember('coupons_type_FAQ' . $storeId, 30, function () use ($storeId) {
                return DB::select(DB::raw(
                    "SELECT c.id,c.title,c.currency,c.exclusive,c.description,c.created_at,c.expire_date,c.discount,c.coupon_code AS code,c.coupon_type AS type,c.coupon_image AS image,c.sticky,c.verified,c.comment_count,c.latest_comments,c.number_used,c.cash_back,c.note,c.top_order,p.foreign_key_right AS go
                    FROM coupons c
                    JOIN properties p ON c.id = p.foreign_key_left
                    WHERE c.store_id = '{$storeId}'
                    AND c.status = 'published'
                    AND (c.coupon_type = 'FAQ' OR c.coupon_type = 'Saving Tips')
                    ORDER BY top_order ASC 
                    "
                ));
            });

            if(!empty($couponsTypeFAQ)){
                foreach ($couponsTypeFAQ as $item) {
                    if($item->type == 'FAQ'){
                        array_push($customFAQ, $item);
                    }elseif ($item->type == 'Saving Tips'){
                        array_push($customSavingTip, $item);
                    }
                }
            }

            $storeUrl = $store['store_url'];
            $childStores = Cache::remember('childStores_' . $storeId, 60 * 24, function () use ($storeAlias, $storeUrl) {
                return DB::select(DB::raw(
                    "SELECT name,alias FROM stores where store_url='$storeUrl' AND countrycode='US' AND alias != '$storeAlias' AND status='published'"
                ));
            });

        }

        $data['store'] = $store;
        $data['store']['coupons'] = (array)$coupons;
        $data['store']['childStores'] = (array)$childStores;
        $data['store']['relateStores'] = [];
        $data['store']['customFAQ'] = (array)$customFAQ;
        $data['store']['customSavingTip'] = (array)$customSavingTip;

        if (empty($store)) {
            if (strpos($storeAlias, '-coupons') !== FALSE) {
                $rm = str_replace('-coupons', '', $request->path());
                return redirect($rm, 301);
            } else {
                return response(view('errors.404'), 404);
            }
        }

        $re = $request->input('c');
        if (!empty($re) && !empty($data['store']['coupons'])) {
            $couponGo = $re['link'];
            $getCodeCoupon = Cache::remember('getCodeCoupon_' . $couponGo, 60, function () use ($couponGo) {
                return DB::select("SELECT c.id,  c.title,  c.currency,  c.exclusive,  c.description,  c.expire_date,  c.comment_count,  c.discount,  c.comment_count,  c.discount,  c.coupon_type AS type,  c.coupon_code AS code, coupon_type AS type,  c.sticky,  p.foreign_key_right AS go, c.latest_comments FROM coupons AS c INNER JOIN properties AS p ON p.foreign_key_left = c.id AND p.key = 'coupon' WHERE p.foreign_key_right = '$couponGo' LIMIT 1");
            });
            if (!empty($getCodeCoupon)) {
                array_unshift($data['store']['coupons'], $getCodeCoupon);
            }
        }

        $data['store']['couponType'] = [
            ['coupon_type' => 'All'],
            ['coupon_type' => 'Coupon Code'],
            ['coupon_type' => 'Deal Type'],
            ['coupon_type' => 'Great Offer']
        ];

        $data['trendingStore'] = Cache::remember('random_trend_stores_' . $storeId, 60 * 24, function () {
            return $this->randomLatestStores( 20);
        });

        $data = $this->__getSEOConfig($data);
        $data['googleAdsense'] = AdsHelper::allowDisplayAdsenseAndAFS($storeAlias);

        $allowFAQs = $this->allowDisplayFAQs();
        $prn = Cache::remember('allowFAQs_'.$data['store']['alias'],60*60*24*7, function() use ($allowFAQs, $data){ // cache 7 days
            return in_array($data['store']['store_url'], $allowFAQs);
        });

        if( (empty($data['store']['note']) && !empty($data['store']['coupons'][0])) || $prn === true ){
            $storeName = $data['store']['name'];
            $storeName = str_replace(['\'','"'],'[[',$storeName);
            $latestVerifiedCoupon = DB::select("select * from coupons where store_id='{$data['store']['id']}' AND status='published' AND verified > 0 order by created_at desc limit 3");
            $str = '';
            if(!empty($latestVerifiedCoupon)){
                foreach ($latestVerifiedCoupon as $item) {
                    $t = str_replace(['\'','"'], '', $item->title);
                    $str.= "<b>{$t}</b> <br/>";
                }
            }

            $storeName = str_replace(["[["],"'",$storeName);
            $questions = [
                'q1' => [
                    'question' => "💰 How much can I save with {$storeName}?",
                    'answer' => "You can save an average of 15% at checkout with one valid coupon."
                ],'q2' => [
                    'question' => "⌚ How often do they release new coupon codes?",
                    'answer' => "New coupons will be released throughout the month. You can especially find great coupons on big holidays like Black Friday, Halloween, Cyber Monday, and more. "
                ],'q3' => [
                    'question' => "🛒 What is the best valid coupon that you can use?",
                    'answer' => "To save your time, top 3 first coupons are usually verified by our team:<br> {$str}"
                ],'q4' => [
                    'question' => "📩 Can I submit a $storeName?",
                    'answer' => "We accept coupon code submissions for many stores. Please see our <a href='https://couponsplusdeals.com/contact-us' title='Contact Us'>Contact Page</a> for more details and to submit your discount. Thank you very much!"
                ],'q5' => [
                    'question' => "😃 Can I use more than one  $storeName for my order?",
                    'answer' => "You can only use one coupon code per order. You should apply the code that gives you the best discount."
                ],
            ];
            $ignoreDisplayFAQs = ['0ce5e6c0-67dd-11e7-b0e0-db5d408b72d2','606e3d20-8c69-11e7-b450-5509ceadf0a0','355aff00-9466-11e7-a659-816afb3bd62b','7d18f8a0-7ac1-11e7-b1ee-ebf9ed8aed4b','fe284490-17a8-11e9-be37-fbc36c865246','55c4629f-1c38-4010-b62b-5e4461af48f5','5565c173-9024-4167-a631-4a8061af48f5','0aa56130-f65d-11e7-b4b2-bf7fc7bfb5e1','739b11c0-0285-11e8-8e24-912647ba793a','b0a39890-5f2c-11e7-9125-898990ce5f51','7b0a1cf0-679e-11e8-934b-fb675b2105b6','4033bc20-8cce-11e8-a443-09567e02f6f0','b955bb70-6205-11e7-8345-398949b6be67','3cf69930-a36b-11e7-a339-251d26a01b45','bf322070-7da7-11e7-9fc0-01785a3818a4','5b373940-02a4-11e8-b946-1f519939ee8c','5b23d900-8cce-11e8-9306-138acd29125a','d93335f0-714d-11e7-b074-6768eeb6b7e4','08fa0520-724c-11e9-864d-93a88f0d18a2','5565c033-4da4-4e71-bb8b-4a7d61af48f5','7e2cfbc0-bd8b-11e7-83fa-bbdb87d704ee','5565c08d-8fb4-4896-843a-4a8261af48f5','ec64dcf0-5f23-11e7-bc9a-0978980e4827','6583eb40-7150-11e7-a6ec-61e3003b4dec','d756c8a0-17b0-11e9-b6f1-3f1b5612c656','79af7a90-7695-11e7-bb54-d99699607878','b528de70-9228-11e7-8d80-cfd0cb4bdba8','aa6cbfb0-61f8-11e7-9dbe-37e191c6b286','b9c2e780-729f-11e7-8ea8-256ccbe28865','77ec1730-8cd3-11e8-8504-6b0a3772a60f','b6dc8570-2c14-11e8-8b8a-2f938e5c7c46','cf710d00-8cbe-11e9-9e4f-3533869f91ac','a6630800-8cce-11e8-b829-d14e09274964','96b54480-86ad-11e9-a9d2-11a930d81b39','b5e28800-5f29-11e7-af70-d9570754e0cb','22067ed0-f65a-11e7-879d-9d859cd81450','defd9df0-809b-11e7-8856-b328a8d6ae3d','d7eb6330-8c19-11e9-91a3-89a01b3f0bc9','98b58110-a36c-11e7-b4f1-298889b844d2','ef4e9260-eed7-11e8-b03b-713748ff605c','06d03bb0-eed8-11e8-add7-b5b8cc42b6d0','6212d710-8cd3-11e8-a566-b5dea56c0b0a','7946d010-6672-11e8-8d2d-47b2451cc3d5','a0f5f6d0-f65c-11e7-a5a9-61f8543ddceb','89ab2e50-33a5-11e9-ba1b-b9e21b93ce60','fed63670-8255-11e7-afa7-79886f5a26de','8e0903e0-3f41-11e8-9a01-15fd4cb2317a','25095e80-f658-11e7-b1d7-a7596719cf11','b9486d00-60b3-11e9-8f34-256e1259cc57','a77abe90-cd63-11e7-be3d-59d2eb36d2bd'];
            $data['questions'] = [];
            if(!in_array($data['store']['id'], $ignoreDisplayFAQs)){
                $data['questions'] = $questions;
            }

        }
        $data['aliasAMP'] = $storeAlias;

        return view('storeDetailNew')->with($data);
    }

    public function indexAMP(Re $request, $alias) {
        $storeAlias = $alias;
        $data = [];
        $storeCacheKey = 'store_' . $alias;
        $store = Cache::remember($storeCacheKey, 10080, function () use ($storeAlias) {
            return DB::table('stores')->select('stores.id AS id', 'name', 'logo', 'social_image', 'store_url', 'alias', 'affiliate_url', 'categories_id', 'best_store', 'custom_keywords', 'coupon_count', 'description', 'short_description', 'head_description', 'properties.foreign_key_right AS go', 'meta_title', 'meta_desc', 'cash_back_json', 'cash_back_total', 'cash_back_term', 'sid_name', 'update_coupon_from', 'note', 'store_url', 'has_order','social_links','review_links')
                ->leftJoin('properties', 'stores.id', '=', 'properties.foreign_key_left')
                ->where('stores.alias', '=', $storeAlias)
                ->where('stores.countrycode', '=', 'US')
                ->where('stores.status', '=', 'published')
                ->first();
        });
        if ($store && $store->note != 'ngach' && strpos($storeAlias, '-coupons') === false && strpos($request->path(), 'coupon-detail') === false) {
            if ($this->checkStringInLastPosition($storeAlias, '-coupons') === false) {
                return redirect(url('/amp/' . $storeAlias . '-coupons'), 301);
            }
        }
        if (!$store) {
            $storeAlias = str_replace('-coupons', '', $storeAlias);
            $store = Cache::remember('store_' . $storeAlias, 60 * 24 * 7, function () use ($storeAlias) {
                return DB::table('stores')->select('stores.id AS id', 'name', 'logo', 'social_image', 'store_url', 'alias', 'affiliate_url', 'categories_id', 'best_store', 'custom_keywords', 'coupon_count', 'description', 'short_description', 'head_description', 'properties.foreign_key_right AS go', 'meta_title', 'meta_desc', 'cash_back_json', 'cash_back_total', 'cash_back_term', 'sid_name', 'update_coupon_from', 'note', 'store_url', 'has_order','social_links','review_links')
                    ->leftJoin('properties', 'stores.id', '=', 'properties.foreign_key_left')
                    ->where('stores.alias', '=', $storeAlias)
                    ->where('stores.countrycode', '=', 'US')
                    ->where('stores.status', '=', 'published')
                    ->first();
            });
        }
        $store = (array)$store;
        $coupons = [];
        $childStores = [];

        if ($store) {
            $storeId = $store['id'];
            $storeName = $this->nameWithKeyword($store['name']);
            $store['name'] = $storeName;

            $coupons = Cache::remember('coupons_' . $storeId, 30, function () use ($storeId) {
                return DB::select(DB::raw(
                    "SELECT c.id,c.title,c.currency,c.exclusive,c.description,c.created_at,c.expire_date,c.discount,c.coupon_code AS code,c.coupon_type AS type,c.coupon_image AS image,c.sticky,c.verified,c.comment_count,c.latest_comments,c.number_used,c.cash_back,c.note,c.top_order,p.foreign_key_right AS go
                    FROM coupons c
                    JOIN properties p ON c.id = p.foreign_key_left
                    WHERE c.store_id = '{$storeId}'
                    AND c.status = 'published'
                    AND (c.expire_date >= NOW() OR c.expire_date IS NULL)
                    ORDER BY 
                    CASE
                        WHEN c.sticky = 'top' THEN 5
                        WHEN c.sticky = 'hot' THEN 4
                        WHEN c.verified = 1 THEN 3
                        WHEN c.sticky IS NULL THEN 2
                        WHEN c.sticky = 'none' THEN 1
                        WHEN c.sticky = 'pending' THEN 0
                    END DESC,
                    CASE
                        WHEN c.coupon_type='Coupon Code' THEN 1
                        WHEN c.coupon_type='Deal Type' THEN 2
                        WHEN c.coupon_type='Great Offer' THEN 3
                    END ASC,
                    c.top_order ASC,
                    c.created_at DESC,
                    c.title ASC
                    LIMIT 25
                    "
                ));
            });

            $storeUrl = $store['store_url'];
            $childStores = Cache::remember('childStores_' . $storeId, 60 * 24, function () use ($storeAlias, $storeUrl) {
                return DB::select(DB::raw(
                    "SELECT name,alias FROM stores where store_url='$storeUrl' AND countrycode='US' AND alias != '$storeAlias' AND status='published'"
                ));
            });
        }

        $data['store'] = $store;
        $data['store']['coupons'] = (array)$coupons;
        $data['store']['childStores'] = (array)$childStores;
        $data['store']['relateStores'] = [];

        if (empty($store)) {
            /* If not found */
            if (strpos($storeAlias, '-coupons') !== FALSE) {
                $rm = str_replace('-coupons', '', $request->path());
                return redirect($rm, 301);
            } else {
                return response(view('errors.404'), 404);
            }
        }

        $data['store']['couponType'] = [
            ['coupon_type' => 'Coupon Code'],
            ['coupon_type' => 'Deal Type'],
            ['coupon_type' => 'Great Offer']
        ];

        $data['trendingStore'] = Cache::remember('random_trend_stores_' . $storeId, 60 * 24, function () {
            return $this->randomLatestStores(20);
        });

        $data = $this->__getSEOConfig($data);
        $data['googleAdsense'] = AdsHelper::allowDisplayAdsenseAndAFS($storeAlias);

        $allowFAQs = $this->allowDisplayFAQs();
        $prn = Cache::remember('allowFAQs_'.$data['store']['alias'],60*60*24*7, function() use ($allowFAQs, $data){ // cache 7 days
            return in_array($data['store']['store_url'], $allowFAQs);
        });

        if( (empty($data['store']['note']) && !empty($data['store']['coupons'][0])) || $prn === true ){
            $storeName = $data['store']['name'];
            $storeName = str_replace(['\'','"'],'[[',$storeName);
            $latestVerifiedCoupon = DB::select("select * from coupons where store_id='{$data['store']['id']}' AND status='published' AND verified > 0 order by created_at desc limit 3");
            $str = '';
            if(!empty($latestVerifiedCoupon)){
                foreach ($latestVerifiedCoupon as $item) {
                    $t = str_replace(['\'','"'], '', $item->title);
                    $str.= "<b>{$t}</b> <br/>";
                }
            }
            $questions = [
                'q1' => [
                    'question' => "💰 How much can I save with {$storeName}?",
                    'answer' => "You can save an average of 15% at checkout with one valid coupon."
                ],'q2' => [
                    'question' => "⌚ How often do they release new coupon codes?",
                    'answer' => "New coupons will be released throughout the month. You can especially find great coupons on big holidays like Black Friday, Halloween, Cyber Monday, and more. "
                ],'q3' => [
                    'question' => "🛒 What is the best valid coupon that you can use?",
                    'answer' => "To save your time, top 3 first coupons are usually verified by our team:<br> {$str}"
                ],'q4' => [
                    'question' => "📩 Can I submit a $storeName?",
                    'answer' => "We accept coupon code submissions for many stores. Please see our <a href='https://couponsplusdeals.com/contact-us' title='Contact Us'>Contact Page</a> for more details and to submit your discount. Thank you very much!"
                ],'q5' => [
                    'question' => "😃 Can I use more than one  $storeName for my order?",
                    'answer' => "You can only use one coupon code per order. You should apply the code that gives you the best discount."
                ],
            ];
            $data['questions'] = $questions;
        }
        return view('amp-version.store-detail.store-detail-amp')->with($data);
    }

    public function getCouponDetailAMP($go) {
        $data['robots'] = 'noindex,nofollow';
        $data['couponGo'] = $go;
        $property = (Array)collect(\DB::select("select * from properties where foreign_key_right = '$go'"))->first();
        if (empty($property['foreign_key_left'])) return response(view('errors.404'), 404);

        $fkl = $property['foreign_key_left'];
        $coupon = (Array)(collect(\DB::select("select title,description,coupon_code,store_id from coupons where id='$fkl'"))->first());
        if (empty($coupon)) return response(view('errors.404'), 404);

        $storeId = $coupon['store_id'];
        $data['coupon'] = $coupon;
        $data['store'] = (Array)collect(\DB::select("select name,alias,logo,store_url from stores where id='$storeId'"))->first();

        return view('amp-version.store-detail.coupon-detail-amp')->with($data);
    }

    public function __getSEOConfig($data) {
        $greatestValue = 0;$curr = '';
        foreach ($data['store']['coupons'] as $c) {
            if(!empty($c->discount)){
                if($c->discount > $greatestValue){
                    $greatestValue = $c->discount;
                    $curr = $c->currency;
                }
            }
        }
        $maxDiscountVal = null;
        if($curr != '' && $greatestValue > 0){
            if($curr == '%') $maxDiscountVal = $greatestValue.$curr;
            elseif ($curr == '$') $maxDiscountVal = $curr.$greatestValue;
        }

        $month = date('F');
        $year = date('Y');
        $storeName = $data['store']['name'];
        $data['seoConfig'] = [
            'title' => '',
            'desc' => '',
            'keyword' => "$storeName coupon codes, $storeName coupon, $storeName discount, $storeName discount codes, $storeName promo codes",
        ];
        if($maxDiscountVal) $maxDiscount = $maxDiscountVal;
        else {
            $ar = [30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95];
            $ak = array_rand($ar);
            $maxDiscount = $ar[$ak];
        }

        $store_domain = str_replace(['http://', 'https://', 'www.'], '', $data['store']['store_url']);
        if($greatestValue == 0)
            $seoTitle = "$maxDiscount% Off $store_domain Coupons & Promo Codes, " . date("F Y");
        else
            $seoTitle = "$maxDiscount Off $store_domain Coupons & Promo Codes, " . date("F Y");
        $data['seoConfig']['title'] = $seoTitle;

        $descriptionStruct = "Save with $storeName coupons and promo codes for $month, $year. Today's top $storeName discount";
        if (sizeof($data['store']['coupons']) > 0) {
            $firstCp = $data['store']['coupons'][0];
            $couponTitle = $firstCp->title;
            $descriptionStruct .= ': ' . $couponTitle;
        }
        $data['seoConfig']['desc'] = $descriptionStruct;
        return $data;
    }

    public function _getSEOConfigForPopupGetCode($countryCode, $data) {
        $seoConfig = Cache::remember('home_seo_config', 60 * 24 * 365, function () use ($countryCode) {
            return DB::select("select * from seo_configs where countrycode='$countryCode'");
        });
        if (!empty($seoConfig)) {
            $rs = [];
            $title = '';
            $metaDescription = '';
            $metaKeyword = '';
            $siteName = '';
            $siteDesc = '';
            $storeHeaderH1 = '';
            $storeHeaderP = '';
            $disableNoindex = '';
            $seo_defaultStoreTitle = '';
            $seo_defaultStoreMetaDescription = '';
            $seo_defaultStoreMetaKeyword = '';
            $seo_defaultH1Store = '';
            $seo_defaultPStore = '';
            foreach ($seoConfig as $s) {
                if ($s->option_name == 'seo_storeTitle') {
                    $title = $s->option_value;
                }
                if ($s->option_name == 'seo_storeDesc') {
                    $metaDescription = $s->option_value;
                }
                if ($s->option_name == 'seo_storeKeyword') {
                    $metaKeyword = $s->option_value;
                }
                if ($s->option_name == 'seo_siteName') {
                    $siteName = $s->option_value;
                }
                if ($s->option_name == 'seo_siteDescription') {
                    $siteDesc = $s->option_value;
                }
                if ($s->option_name == 'seo_storeH1') {
                    $storeHeaderH1 = $s->option_value;
                }
                if ($s->option_name == 'seo_storeP') {
                    $storeHeaderP = $s->option_value;
                }
                if ($s->option_name == 'seo_disableStoreNoIndex') {
                    $disableNoindex = $s->option_value;
                }

                if ($s->option_name == 'seo_defaultStoreTitle') {
                    $seo_defaultStoreTitle = $s->option_value;
                }
                if ($s->option_name == 'seo_defaultStoreMetaDescription') {
                    $seo_defaultStoreMetaDescription = $s->option_value;
                }
                if ($s->option_name == 'seo_defaultStoreMetaKeyword') {
                    $seo_defaultStoreMetaKeyword = $s->option_value;
                }
                if ($s->option_name == 'seo_defaultH1Store') {
                    $seo_defaultH1Store = $s->option_value;
                }
                if ($s->option_name == 'seo_defaultPStore') {
                    $seo_defaultPStore = $s->option_value;
                }
            }
            if (isset($disableNoindex)) {
                $rs['disableNoindex'] = $disableNoindex;
            }

            $storeName = $data['store']['name'];
            if (sizeof($data['store']['coupons']) > 0) {
                $firstCp = $data['store']['coupons'][0];
                $firstCp = (array)$firstCp;
                $couponTitle = $firstCp['title'];
                $couponDiscount = $firstCp['discount'];
            } else {
                $couponTitle = '';
                $couponDiscount = '';
            }

            $configSelfSeoTitle = (isset($data['store']['meta_title']) && $data['store']['meta_title']) ? $data['store']['meta_title'] : null;
            $configSelfSeoDesc = (isset($data['store']['meta_desc']) && $data['store']['meta_desc']) ? $data['store']['meta_desc'] : null;
            $upToCashBack = sizeof($data['store']['cash_back_json']) > 0 ? (!empty($data['store']['cash_back_json'][0]['cash_back_percent']) ? $data['store']['cash_back_json'][0]['cash_back_percent'] . '%' : $data['store']['cash_back_json'][0]['currency'] . $data['store']['cash_back_json'][0]['cash_back']) : '';
            if (isset($title)) {
                if (!$couponDiscount) {
                    $rs['title'] = $this->seoConvert($configSelfSeoTitle ? $configSelfSeoTitle : $seo_defaultStoreTitle, $siteName, $siteDesc, $storeName,
                        $couponTitle, $couponDiscount, $upToCashBack, true);
                } else {
                    $firstCp = $data['store']['coupons'][0];
                    $firstCp = (array)$firstCp;
                    $rs['title'] = $this->seoConvert($configSelfSeoTitle ? $configSelfSeoTitle : $title, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount . $firstCp['currency'], $upToCashBack, true);
                }
            }
            if (isset($metaDescription)) {
                if (!$couponDiscount) {
                    $rs['desc'] = $this->seoConvert($configSelfSeoDesc ? $configSelfSeoDesc : $seo_defaultStoreMetaDescription, $siteName, $siteDesc, $storeName,
                        $couponTitle, $couponDiscount, $upToCashBack);
                } else {
                    $firstCp = $data['store']['coupons'][0];
                    $firstCp = (array)$firstCp;
                    $rs['desc'] = $this->seoConvert($configSelfSeoDesc ? $configSelfSeoDesc : $metaDescription, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount . $firstCp['currency'], $upToCashBack);
                }
            }
            if (isset($metaKeyword)) {
                if (!$couponDiscount) {
                    $rs['keyword'] = $this->seoConvert($seo_defaultStoreMetaKeyword, $siteName, $siteDesc, $storeName,
                        $couponTitle, $couponDiscount, $upToCashBack);
                } else {
                    $firstCp = $data['store']['coupons'][0];
                    $firstCp = (array)$firstCp;
                    $rs['keyword'] = $this->seoConvert($metaKeyword, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount . $firstCp['currency'], $upToCashBack);
                }
            }
            if (isset($storeHeaderH1)) {
                if (!$couponDiscount) {
                    $rs['storeHeaderH1'] = $this->seoConvert($seo_defaultH1Store, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount, $upToCashBack);
                } else {
                    $firstCp = $data['store']['coupons'][0];
                    $firstCp = (array)$firstCp;
                    $rs['storeHeaderH1'] = $this->seoConvert($storeHeaderH1, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount . $firstCp['currency'], $upToCashBack);
                }
            }
            if (isset($storeHeaderP)) {
                if (!$couponDiscount) {
                    $rs['storeHeaderP'] = $this->seoConvert($seo_defaultPStore, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount, $upToCashBack);
                } else {
                    $firstCp = $data['store']['coupons'][0];
                    $firstCp = (array)$firstCp;
                    $rs['storeHeaderP'] = $this->seoConvert($storeHeaderP, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount . $firstCp['currency'], $upToCashBack);
                }
            }
            $data['seoConfig'] = $rs;
            if (isset($siteName)) {
                $data['siteName'] = $siteName;
            }

            $isOpenPopup = strpos($_SERVER['REQUEST_URI'], '?c=');
            if ($isOpenPopup) {
                $couponFKR = substr($_SERVER['REQUEST_URI'], $isOpenPopup + 3, 6);
                $foundCp = $this->_submitHTTPGet(config('config.api_url') . 'properties/?where[foreignKeyRight]=' . $couponFKR
                    . '&findType=findOne&where[key]=coupon' . '&attributes[]=foreignKeyLeft', []);
                $openingCoupon = $this->_submitHTTPGet(config('config.api_url') . 'coupons/?where[id]=' . $foundCp['foreignKeyLeft']
                    . '&findType=findOne', []);
                $openingCouponTitle = $openingCoupon['title'];
                $openingCouponDesc = $openingCoupon['description'];

                $data['seoConfig']['originTitle'] = $data['seoConfig']['title'];
                $data['seoConfig']['originDesc'] = $data['seoConfig']['desc'];
                $data['seoConfig']['title'] = $openingCouponTitle;
                $data['seoConfig']['desc'] = $openingCouponDesc;
            }
        }
        return $data;
    }

    public function indexNewCouponStruct(Re $request, $couponGo, $couponTitle, $alias = '') {
        $storeAlias = strtolower($alias);
        if (!empty($_GET['update-coupon'])) {
            $storeAlias = $alias;
            $dt = $this->indexForUpdateCoupon($storeAlias);
            return view('storeDetailForUpdateCoupon')->with($dt);
        }

        $dataSeo = [
            'seo' => [
                'title' => 'Discount Voucher Title index controller overwrite',
                'keywords' => 'Discount Voucher keywords index controller overwrite',
                'description' => 'Discount Voucher description index controller overwrite',
                'image' => ''
            ]
        ];

        $dataPage = ['dcCount' => '9,419'];

        if (!Session::has('coupon_type_' . $storeAlias)) Session::put('coupon_type_' . $storeAlias, []);
        $store = Cache::remember('store_' . $storeAlias, 10080, function () use ($storeAlias) {
            return DB::table('stores')->select('stores.id AS id', 'name', 'logo', 'social_image', 'store_url', 'alias', 'affiliate_url', 'categories_id', 'best_store', 'custom_keywords', 'coupon_count', 'description', 'short_description', 'head_description', 'properties.foreign_key_right AS go', 'meta_title', 'meta_desc', 'cash_back_json', 'cash_back_total', 'cash_back_term', 'sid_name', 'update_coupon_from', 'note', 'store_url','social_links','review_links')
                ->leftJoin('properties', 'stores.id', '=', 'properties.foreign_key_left')
                ->where('stores.alias', '=', $storeAlias)
                ->where('stores.countrycode', '=', 'US')
                ->where('stores.status', '=', 'published')
                ->first();
        });
        /* neu la store bo */
        if ($store && $store->note != 'ngach' && strpos($storeAlias, '-coupons') === false && strpos($request->path(), 'coupon-detail') === false) {
            if ($this->checkStringInLastPosition($storeAlias, '-coupons') === false) {
                return redirect(url('/' . $storeAlias . '-coupons'));
            }
        }
        if (!$store) {
            $storeAlias = str_replace('-coupons', '', $storeAlias);
            $store['originalStoreName'] = $store['name'];
            $store = Cache::remember('store_' . $storeAlias, 60 * 24 * 7, function () use ($storeAlias) {
                return DB::table('stores')->select('stores.id AS id', 'name', 'logo', 'social_image', 'store_url', 'alias', 'affiliate_url', 'categories_id', 'best_store', 'custom_keywords', 'coupon_count', 'description', 'short_description', 'head_description', 'properties.foreign_key_right AS go', 'meta_title', 'meta_desc', 'cash_back_json', 'cash_back_total', 'cash_back_term', 'sid_name', 'update_coupon_from', 'note', 'store_url','social_links','review_links')
                    ->leftJoin('properties', 'stores.id', '=', 'properties.foreign_key_left')
                    ->where('stores.alias', '=', $storeAlias)
                    ->where('stores.countrycode', '=', 'US')
                    ->where('stores.status', '=', 'published')
                    ->first();
            });
        }
        $store = (array)$store;
        $coupons = [];
        $childStores = [];
        $expiredCoupons = [];
        if ($store) {
            $storeId = $store['id'];
            $storeName = $this->nameWithKeyword($store['name']);
            $store['name'] = $storeName;
            $coupons = Cache::remember('coupons_' . $storeId, 30, function () use ($storeId) {
                return DB::select(DB::raw(
                    "SELECT c.id,c.title,c.currency,c.exclusive,c.description,c.created_at,c.expire_date,c.discount,c.coupon_code AS code,c.coupon_type AS type,c.coupon_image AS image,c.sticky,c.verified,c.comment_count,c.latest_comments,c.number_used,c.cash_back,c.note,c.top_order,p.foreign_key_right AS go
                    FROM coupons c
                    JOIN properties p ON c.id = p.foreign_key_left
                    WHERE c.store_id = '{$storeId}'
                    AND c.status = 'published'
                    AND (c.expire_date >= NOW() OR c.expire_date IS NULL)
                    ORDER BY 
                    CASE
                        WHEN c.sticky = 'top' THEN 5
                        WHEN c.sticky = 'hot' THEN 4
                        WHEN c.verified = 1 THEN 3
                        WHEN c.sticky IS NULL THEN 2
                        WHEN c.sticky = 'none' THEN 1
                        WHEN c.sticky = 'pending' THEN 0
                    END DESC,
                    CASE
                        WHEN c.coupon_type='Coupon Code' THEN 1
                        WHEN c.coupon_type='Deal Type' THEN 2
                        WHEN c.coupon_type='Great Offer' THEN 3
                    END ASC,
                    c.top_order ASC,
                    c.created_at DESC,
                    c.title ASC
                    LIMIT 25
                    "
                ));
            });
            $storeUrl = $store['store_url'];
            $childStores = Cache::remember('childStores_' . $storeId, 60 * 24, function () use ($storeAlias, $storeUrl) {
                return DB::select(DB::raw(
                    "SELECT name,alias FROM stores where store_url='$storeUrl' AND countrycode='US' AND alias != '$storeAlias' AND status='published'"
                ));
            });
        }

        $data['store'] = $store;
        $data['store']['coupons'] = (array)$coupons;
        $data['store']['expiredCoupons'] = (array)$expiredCoupons;
        $data['store']['childStores'] = (array)$childStores;
        $data['store']['relateStores'] = [];

        if (empty($store)) {
            return response(view('errors.404'), 404);
        }

        $re = $request->input('c');
        if (!empty($re) && !empty($data['store']['coupons'])) {
            $getCodeCoupon = $this->_submitHTTPGet(config('config.api_url') . 'coupons/getCouponWithLinkGo/' . $re . '/', [
                'c_location' => config('config.location')
            ]);
            if (!empty($getCodeCoupon)) {
                array_unshift($data['store']['coupons'], $getCodeCoupon);
            }
        }
        /*  */
        Session::forget('store-detail-more-' . $data['store']['id']);
        if (Session::has('store-detail-more-' . $data['store']['id'])) {
            $limit = intval(Session::get('store-detail-more-' . $data['store']['id']));
            $result = $this->_submitHTTPPost(config('config.api_url') . 'stores/storeDetailV2ShowMore/', [
                'storeId' => $data['store']['id'],
                'c_location' => config('config.location'),
                'c_limit' => $limit,
                'c_offset' => 20,
                'couponTypes' => Session::get('coupon_type_' . $storeAlias)
            ]);

            if (!empty($result) && $result['code'] == 0) $data['store']['coupons'] = array_merge($data['store']['coupons'], $result['data']);
        }

        if (Session::has('user.id')) {
            $query = '?';
            $query .= 'uuids[]=' . $data['store']['id'] . '&';
            if (sizeof($data['store']['coupons'])) {
                foreach ($data['store']['coupons'] as $s) {
                    $query .= 'uuids[]=' . $s['id'] . '&';
                }
            }
            $data['likes'] = $this->_submitHTTPGet(config('config.api_url') . 'likes/getLikes' . $query . 'userId=' . Session::get('user.id'), ['c_location' => config('config.location')]);
            if (sizeof($data['store']['relateStores'])) {
                foreach ($data['store']['relateStores'] as $s) {
                    $query .= 'uuids[]=' . $s['id'] . '&';
                }
            }
            $query .= 'userId=' . Session::get('user.id');
            $data['favourites'] = $this->_submitHTTPGet(config('config.api_url') . 'favourites/getFavourites' . $query, ['c_location' => config('config.location')]);
        }

        $data = $this->_getSEOConfigForPopupGetCode(config('config.location'), $data);

        if (!empty($request->input('c'))) $couponGo = $request->input('c');
        if ($couponGo) {
            //title
            $getCgo = Cache::remember('coupon_title_' . $couponGo , 60 * 24, function () use ($couponGo) {
                return DB::select("SELECT c.title FROM coupons c LEFT JOIN properties p ON c.id = p.foreign_key_left WHERE p.foreign_key_right = '{$couponGo}' LIMIT 1");
            });
            if (!empty($getCgo[0]))
                $getCgo = $getCgo[0];
            else
                return redirect("/$storeAlias"); // return go to store when not found coupon go
            if (!empty($getCgo->title)) $data['seoConfig']['title'] = $getCgo->title;
            //end title
        }

        $allData = array_merge($dataPage, $dataSeo, $data);
        $allData['siteName'] = env('SITE_NAME', '');
        $allData['store']['couponType'] = [
            ['coupon_type' => 'Coupon Code'],
            ['coupon_type' => 'Deal Type'],
            ['coupon_type' => 'Great Offer']
        ];
        $allData['store']['countCouponVerified'] = 10;
        $allData['store']['todayCoupon'] = 2;
        $allData['store']['expiredCoupons'] = [];
        $allData['googleAdsense'] = AdsHelper::allowDisplayAdsenseAndAFS($storeAlias);
        return view('storeDetailNew')->with($allData);
    }

    public function getStores(Re $request) {
        if ($request->ajax()) {
            $keyword = $request->input('q');
            $data = $this->_submitHTTPGet(config('config.api_url') . 'stores/', [
                'where[$or][storeUrl][$ilike]' => '%' . $keyword . '%',
                'where[$or][name][$ilike]' => '%' . $keyword . '%',
                'where[status]' => 'published',
                'where[countrycode]' => config('config.location'),
                'attributes[]=id&attributes[]=name&attributes[]' => 'storeUrl',
                'c_location' => config('config.location')
            ]);
            return response()->json(['items' => $data]);
        }
        return response()->json(['items' => []]);
    }

    public function showMoreCoupons(Re $request) {
        $storeId = $request->input('storeId');
        $offset = !empty($request->input('offset')) ? (int)$request->input('offset') : 0;
        $store = $this->_submitHTTPGet(config('config.api_url') . 'stores/', [
            'where[id]' => $storeId,
            'findType' => 'findOne',
            'c_location' => config('config.location')
        ]);
        if ($request->ajax() && !empty($store)) {
            $data['data'] = DB::select(DB::raw(
                "SELECT 
                    c.id, c.title, c.currency, c.exclusive, c.description, c.created_at, c.expire_date, c.discount, c.coupon_code AS code, c.coupon_type AS type, c.coupon_image AS image, c.sticky, c.verified, c.comment_count, c.latest_comments, c.number_used, c.cash_back, c.note, c.top_order, p.foreign_key_right AS go
                    FROM coupons c
                    JOIN properties p ON c.id = p.foreign_key_left
                    WHERE c.store_id = '{$storeId}'
                    AND c.status = 'published'
                    AND (c.expire_date >= NOW() OR c.expire_date IS NULL)
                    ORDER BY 
                    CASE 
                        WHEN c.verified = 1 THEN 5 
                        WHEN c.sticky = 'top' THEN 4 
                        WHEN c.sticky = 'hot' THEN 3 
                        WHEN c.sticky = 'none' THEN 2 
                        WHEN c.sticky IS NULL THEN 1 
                    END DESC, 
                    c.coupon_type ASC,
                    c.top_order ASC,
                    c.created_at DESC,
                    c.title ASC
                    LIMIT 20
                    OFFSET {$offset}
                    "
            ));
            if (!empty($data)) {
                return response(view('elements.v2-parent-box-coupon', ['coupons' => $data['data']]));
            } else return response()->json(['status' => 'error', 'coupons' => []]);
        }
        return response()->json(['status' => 'error', 'coupons' => []]);
    }

    public function searchStores(Re $request) {
        if ($request->ajax()) {
            $keyword = $request->input('kw');
            $data = $this->_submitHTTPPost(config('config.api_url') . 'stores/search/', [
                'keyword' => $keyword,
                'c_location' => config('config.location')
            ]);
            return response()->json(['status' => 'success', 'items' => $data]);
        }
        return response()->json(['status' => 'error', 'items' => []]);
    }

    public function filterCoupon(Re $request) {
        if ($request->ajax()) {
            $params = $request->all();

            $sessionKey = 'coupon_type_' . $params['alias'];

            if($params['coupon_type'] == 'All'){
                if($params['checked'] === 'true'){
                    $filterCouponByType = ['All'];
                }else{
                    $filterCouponByType = [];
                }
            }else{
                $filterCouponByType = Session::has($sessionKey) ? Session::get($sessionKey) : [];
                if ($params['checked'] && !in_array($params['coupon_type'], $filterCouponByType)) {
                    array_push($filterCouponByType, $params['coupon_type']);
                } else
                    $filterCouponByType = array_diff($filterCouponByType, [$params['coupon_type']]);

                if (($key = array_search('All', $filterCouponByType)) !== false) {
                    unset($filterCouponByType[$key]);
                }

            }

            if(!empty($filterCouponByType)){
                Session::put($sessionKey, $filterCouponByType);
            }else{
                Session::forget($sessionKey);
            }

            return response()->json(['status' => 'success', 'data' => $filterCouponByType]);
        }
        return response()->json(['status' => 'error']);
    }

    public function requestCoupon(Re $request) {
        $params = $request->all();

        if(empty($params['storeId']) OR empty($params['storeName']) OR empty($params['detail']))
            return response(view('errors.404'), 404);

        $storeId = $params['storeId'];
        $storeName = $params['storeName'];
        $storeUrlDetail = $params['detail'];
        $obj = Store::find($storeId);
        if ($obj) {
            $obj->count_request_coupon = $obj->count_request_coupon + 1;
            $rs = $obj->save();
            Mail::send('emails.request-coupon', ['name' => $storeName, 'detail' => $storeUrlDetail, 'id' => $storeId], function ($message) {
                $message->to(env('MAIL_REQUEST_COUPON_TO', 'haiht369@gmail.com'), 'HaiHT')->subject('Find me coupon');
            });
        } else {
            $rs = false;
        }
        return json_encode($rs);
    }

    private function getAliasOfFather($storeAlias) {
        $check = DB::select("select id,store_url,note from stores where alias=? and countrycode='US' limit 1", [$storeAlias]);
        $findFather = DB::select("select id,name,alias,store_url,note from stores where store_url=? and countrycode='US' and id != ? and (note is null or note != 'ngach')", [$check[0]->store_url, $check[0]->id]);
        if (!empty($findFather)) {
            return $findFather[0]->alias;
        }
        return $storeAlias;
    }

    public function testAllowAjax(Re $request) {
        $url = $request->all()['url'];
        $hp = new HP();
        $html = $hp->getHtmlViaProxy($url);
        return $html;
    }

    public function updateCouponDPF(Re $request) {
        $params = $request->all();
        $storeId = $params['storeId'];
        $storeUrl = $params['storeUrl'];
        $updateCouponFrom = !empty($params['updateCouponFrom']) ? $params['updateCouponFrom'] : '';
        $hp = new HP();
        if (strpos($updateCouponFrom, 'dontpayfull.com') !== false) {
            $data = $this->getDontPayFull($updateCouponFrom);
            $result = $hp->getUniqueOnly($data, $storeId);
        } else {
            $updateCouponFrom = 'https://www.dontpayfull.com/at/' . $storeUrl;
            $data = $this->getDontPayFull($updateCouponFrom);
            $result = $hp->getUniqueOnly($data, $storeId);
        }
        return $result;
    }

    public function getDontPayFull($url) {
        $hp = new HP();
        $html = $hp->getHtmlViaProxy($url);
        $arr = [];
        
        foreach ($html->find('.obox') as $item) {
            $title = trim($item->find('h3', 0)->plaintext);
            if ($item->find('.odescription')) {
                $desc = trim($item->find('.odescription', 0)->plaintext);
            } else {
                $desc = '';
            }

            if ($item->find('.ocode', 0)) {
                $code = trim($item->find('.ocode', 0)->plaintext);
            } else {
                $code = '';
            }
            if ($item->find('.percent__label')) {
                $discountValue = trim($item->find('.percent__label', 0)->plaintext);
            } else {
                $discountValue = '';
            }
            $dataCpId = $item->{'data-id'};
            $verify = $item->find('.verified', 0) ? 1 : 0;

            $a = [];
            $a['title'] = $title;
            $a['desc'] = $desc;
            $a['code'] = $code;
            $a['discount'] = $discountValue;
            $a['data-id'] = $dataCpId;
            $a['verify'] = $verify;
            array_push($arr, $a);
        }
        return $arr;
    }

    public function clearCache($storeAlias) {
        $store = DB::table('stores')->where('alias', $storeAlias)->first(['id']);
        if ($store) {
            $keyStore = 'store_' . $storeAlias;
            $result['store'] = Cache::forget($keyStore);
            $result['coupon'] = Cache::forget('coupons_' . $store->id);
            $result['childStore'] = Cache::forget('childStores_' . $store->id);
            echo "<pre>";
            var_dump($result);
            die;
        } else {
            die('Alias not found');
        }
    }
}
