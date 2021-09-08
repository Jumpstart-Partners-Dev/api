<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscribeController extends Controller
{
    public function getSubscribers() {
        $result = DB::table('subscribers')->get();
        echo json_encode(['data' => $result, 'status' => 0]);
    }
}
