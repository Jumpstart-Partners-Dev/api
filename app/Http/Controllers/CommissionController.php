<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Cache;
use Session;

use App\Models\StoreCommission;

class CommissionController extends Controller
{
    public $store_commissions;
    public $debug;
    public $data;

    public function __construct() {
        $this->store_commissions = new StoreCommission;
        $this->debug = true;
    }

    public function toJSON() {
        if($this->debug) {
            $this->data['error'] = $this->store_commissions->error;
            $this->data['message'] = $this->store_commissions->message;
            $this->data['query'] = $this->store_commissions->query;
            $this->data['count'] = $this->store_commissions->count;
            $this->data['lastpage'] = $this->store_commissions->lastpage;
            $this->data['page'] = $this->store_commissions->page;
        }
        echo json_encode($this->data);
    }

    public function getAll(Request $req) {
        $params = null;
        if ($req->params != null) {
            $params = json_decode($req->params);
        }
        if ($req->page != null) {
            $this->store_commissions->page = $req->page;
        }
        $s_c = $this->store_commissions->R('store_commissions<-stores', $params)->get();
        $this->data['store_commissions'] = $s_c;
        $this->toJSON();
        // echo count($s_c);
        // echo json_encode(['store_commimssions' => $s_c, 'error' => $this->store_commissions->error]);
    }

    public function updateSettings(Request $req) {
        // print_r($req->data);
        $e = json_decode($req->data);
        $this->store_commissions->U($req->id, [
            'secure_url' => $e->secure_url,
            'cors_header' => $e->cors_header,
            'extra_form_params' => $e->extra_form_params,
            'dom_target' => $e->dom_target,
            'search_str' => $e->search_str,
            'unpaid' => $e->unpaid,
            'settings_ok' => $e->settings_ok,
            'affiliate_id' => $e->affiliate_id,
            'affiliate_url' => $e->affiliate_url,
            'username' => $e->username,
            'password' => $e->password,
            'login_ok' => $e->login_ok,
            'unpaid' => $e->unpaid,
            'store_id' => $e->store_id
        ]);
    }
}
