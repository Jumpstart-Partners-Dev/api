<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Cache;
use Session;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class TestController extends Controller
{
    //
    public function curltest() {
        
            $url = 'https://store.racquetworld.com/affiliate-edit.html';
            $search = '$';
            $div = '.u-width-4--m';

            $arr = [
                'Affiliate_Code' => '',
                'Affiliate_Password' => ''
            ];
            // $arr->append(array('Action' => 'ALGI'));
            $arr['Action'] = '';
            //https://www.africansportingcreations.com/affiliate-edit.html
            // $auth = Http::asForm()->post('', $arr);

            // $response = Http::withHeaders($auth->headers())->get('https://store.racquetworld.com/affiliate-edit.html', $arr);
            
            // echo "<pre>";
            // print_r($auth->object());
            // echo "</pre>";

            $response = Http::withHeaders([
                'authority' => 'store.racquetworld.com',
                'cache-control' => 'max-age=0',
                'sec-ch-ua' => '" Not;A Brand";v="99", "Google Chrome";v="97", "Chromium";v="97"',
                'sec-ch-ua-mobile' => '?0',
                'sec-ch-ua-platform' => '"Windows"',
                'upgrade-insecure-requests' => '1',
                'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36',
                'origin' => 'https://store.racquetworld.com',
                'content-type' => 'application/x-www-form-urlencoded',
                'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'sec-fetch-site' => 'same-origin',
                'sec-fetch-mode' => 'navigate',
                'sec-fetch-user' => '?1',
                'sec-fetch-dest' => 'document',
                'referer' => 'https://store.racquetworld.com/affiliate-login.html',
                'accept-language' => 'en-US,en;q=0.9',
                'cookie' => 'mm5-RW-basket-id=0469e082c27e81f9706aef4b80dba5f8; _ga=GA1.2.1109616410.1642571556; _gid=GA1.2.1738255064.1642571556; _gat=1; _clck=wrk5y1|1|ey9|0; mm5-RW-affiliate-session=57a0a5704529a8c53e24ed8d799599e5; _uetsid=008bc9a078ec11ec9e5823d01d5330b9; _uetvid=008beb9078ec11ec888e179e5516f766; _clsk=shggf4|1642571573724|2|1|f.clarity.ms/collect; _gali=js-AFED',
            
                ])
                ->get($url, [
                    'Action' => 'ALGI',
                    'Affiliate_Code' => 'XUANHOANGLE',
                    'Affiliate_Password' => 'Takenlimitfor12@!'
                ]);
            
            $crawler = new Crawler($response->body());
            // $crawler = new Crawler("<body><p>Foo <span>Bar</span></p></body>");
            
            // $nodeValues = $crawler->filter('li')->children()->each(function (Crawler $node, $i) {
            //     return $node;
            // });

            $nodeValues = $crawler->filter($div)->each(function (Crawler $node, $i) {
                return $node->text();
            });

            $comm = '';

            foreach($nodeValues as $a) {
                if(strpos($a, $search) !== false) {
                    $commission = str_replace($search, "", $a);

                    $comm =  $commission;
                } else {
                    echo '';
                }
            }

            $res['url'] = $url;
            if($comm == '') {
                $res['status'] = 'failed';
            } else {
                $res['target'] = $search . ' on ' . $div;
                $res['commission'] = $comm;
            }
            

            echo '<pre>';
            echo print_r($res);
            echo '</pre>';

            echo $response->body();

        // $headers = array(
        //     'authority' => 'store.racquetworld.com',
        //     'cache-control' => 'max-age=0',
        //     'sec-ch-ua' => '"Chromium";v="96", "Opera GX";v="82", ";Not A Brand";v="99"',
        //     'sec-ch-ua-mobile' => '?0',
        //     'sec-ch-ua-platform' => '"Windows"',
        //     'upgrade-insecure-requests' => '1',
        //     'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 OPR/82.0.4227.44',
        //     'origin' => 'https://store.racquetworld.com',
        //     'content-type' => 'application/x-www-form-urlencoded',
        //     'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        //     'sec-fetch-site' => 'same-origin',
        //     'sec-fetch-mode' => 'navigate',
        //     'sec-fetch-user' => '?1',
        //     'sec-fetch-dest' => 'document',
        //     'referer' => 'https://store.racquetworld.com/affiliate-login.html',
        //     'accept-language' => 'en-US,en;q=0.9',
        //     'cookie' => 'mm5-RW-basket-id=564d55e207a391166cc8cbbd90bffe59; _ga=GA1.2.666259834.1640694311; _gid=GA1.2.1261743339.1640694311; _gat=1; _gali=js-AFED; mm5-RW-affiliate-session=1bd154e3986a2b17420271fb7d57b482'
        // );
        // $data = array(
        //     'Action' => 'ALGI',
        //     'Affiliate_Code' => 'XUANHOANGLE',
        //     'Affiliate_Password' => 'Takenlimitfor12@!'
        // );
        // $response = Requests::post('https://store.racquetworld.com/affiliate-edit.html', $headers, $data);
    }

    public function checkfields() {
        $data = DB::table('store_commissions')->where('id', '5867')->first();

        echo '<pre>';
        // print_r($data);
        echo '</pre>';

        $url = 'https://www.decorativetrimmings.com/affiliate-edit.html';

        $response = Http::get($url);

        // echo $response->body();

        $crawler = new Crawler($response->body());

        // $nodeValues = $crawler->filter('form')->each(function (Crawler $node, $i) {
        //     return $node->form();
        // });

        $node = $crawler->selectButton('Log in');

        echo $node->text('empty');

        $form = $node->form();

        echo '<pre>';
        // print_r($form);
        echo '</pre>';  

        $uri = $form->getUri();
        $method = $form->getMethod();
        $name = $form->getName();
        $values = $form->getValues();

        echo $uri . '<br>';
        echo $method . '<br>';
        echo $name . '<br>';

        // $attributes = $crawler
        //     ->filterXpath('//form/input')
        //     ->extract(['action']);

        echo '<pre>';
        print_r($values);
        echo '</pre>';
    }
}
