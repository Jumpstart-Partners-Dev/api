<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class FilesController extends Controller
{
    //

    public function uploadview()
    {
        return view('upload');
    }

    public function parseCSV(Request $req) {
        // Storage::disk('local')->put('csvfile.csv', $req->file('file'));
         //Storage::disk('local')->put('example.txt', 'Contents');
        $file = Storage::disk('local')->path('file1.csv');
        $parsed = pathinfo($file);
        
        $file_handle = fopen($parsed['dirname']."/".$parsed['basename'], 'r');
        $header = fgetcsv($file_handle);
        $store_ids = [];
        $counter = 0;
        while (!feof($file_handle)) {
            $arr = fgetcsv($file_handle);
            $counter++;
            $id = '';
            $username = $arr[5];
            $password = $arr[8];
            $aff_url = $arr[4];

            $data = DB::table('stores')->where('name', 'like', '%'.$arr[1].'%')->get('alias');
            if (sizeof($data) == 1) {
                $data = DB::table('stores')->where('name', 'like', '%'.$arr[1].'%')->first();
                $id = $data->id;
            } elseif (sizeof($data) >=2) {
                $aliasArr = [];
                foreach ($data as $key => $value) {
                    array_push($aliasArr, $value->alias);
                }
                usort($aliasArr, function($a, $b) {
                    return strlen($a) - strlen($b);
                });
                $data2 = DB::table('stores')->where('alias', $aliasArr[0])->first();
                $id = $data2->id;
            }
            
            $new_aff = str_replace("LẺ","LE",$arr[0]);
            $aff_id = DB::table('aff_types')->where('aff_type', $new_aff)->first();
            if (empty($aff_id)) {
                $aff_type = 0;
            } else {
                $aff_type = $aff_id->id;
            }
            $result = DB::table('store_commissions')->insert([
                'store_id' => $id,
                'username' => $username,
                'password' => $password,
                'affiliate_url' => $aff_url,
                'aff_type_Id' => $aff_type
            ]);
        }
        fclose($file_handle);
        // print_r(json_encode($store_ids));
    }
}
