<?php
namespace App\Http\Controllers;

// require_once ('vendor/autoload.php');

use \Statickidz\GoogleTranslate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TranslationsController extends Controller
{

    public function ref_string (Request $req) {
        $data = DB::table('ref_strings')->insert([
            'ref_string' => $req->ref_string,
        ]);
        return json_encode($data);
    }

    public function translate (Request $req) {
        $data = DB::table('ref_strings')->where('id', $req->id)->first();

        if ($data) {
            $sourceLang = $req->sourceLang;
            $targetLang = $req->targetLang;
            $sourceWord = $data->ref_string;
    
            $trans = new GoogleTranslate();
            $result = $trans->translate($sourceLang, $targetLang, $sourceWord);
            if ($result) {
                $data2 = DB::table('translation_master')->insert([
                    'ref_id' => $data->id,
                    'lang' => $targetLang,
                    'translation' => $result
                ]);
                return json_encode($data2);
            }
        }
       
    }
}
