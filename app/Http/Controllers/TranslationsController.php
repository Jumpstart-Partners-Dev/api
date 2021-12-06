<?php
namespace App\Http\Controllers;

// require_once ('vendor/autoload.php');

use \Statickidz\GoogleTranslate;
use Illuminate\Http\Request;

class TranslationsController extends Controller
{
    public function translate () {
        $sourceLang = 'en';
        $lang = 'la';
        $sourceWord = 'hello';

        $trans = new GoogleTranslate();
        $result = $trans->translate($sourceLang, $lang, $sourceWord);
        echo $result;
    }
}
