<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    echo 'Ready!';
});


Route::get('/store/{alias}', 'StoreController@index');
Route::get('/store/getStores', 'StoreController@getStores');
Route::post('/store/search', 'StoreController@searchStores');
Route::get('/session/filterCoupon', 'StoreController@filterCoupon');
Route::get('/request-coupon', 'StoreController@requestCoupon');

Route::get('/blog', 'BlogController@all');
Route::get('/amp/blog', 'BlogController@allAmp');
Route::get('/blog/{slug}', 'BlogController@view');
Route::get('/amp/blog/{slug}', 'BlogController@viewAmp');

Route::get('/store/getTopStore', 'StoreController@getLatestStore');
Route::prefix('manage')->group(function() {
    Route::get('/getTopStores', 'ManageController@getTopStores');
    Route::get('/getBestStores', 'ManageController@getBestStores');
    Route::post('/searchStores', "ManageController@searchStores");
    Route::post('/searchBlogs', "ManageController@searchBlogs");
    Route::post('/searchCodes', "ManageController@searchCodes");
    Route::post('/addStore', "ManageController@addStore");
    Route::post('/reorder', "ManageController@reorder");
    Route::post('/removeStore', 'ManageController@removeStore');
    Route::post('/getSeoDetails', 'ManageController@getSeoDetails');
    Route::post('/saveSeoDetails', 'ManageController@saveSeoDetails');
    Route::get('/getAffTypes', 'ManageController@getAffTypes');
});
Route::prefix('subscribe')->group(function() {
    Route::get('/getSubscribers', "SubscribeController@getSubscribers");
});


Route::prefix('homepage')->group(function() {
    Route::get('/getContents', 'HomepageContentController@get_contents');
});

Route::prefix('commissions')->group(function() {
    Route::post('/all', 'CommissionController@getAll');
    Route::post('/updateSettings', 'CommissionController@updateSettings');
});


Route::prefix('page')->group(function() {
    Route::get('/index', "PageController@index");
    Route::get('/get/{id}', "PageController@get");
    Route::post('/create', "PageController@create");
    Route::post('/update/{id}', "PageController@update");
    Route::post('/delete/{id}', "PageController@delete");
    Route::get('/content/index', "PageController@index_content");
    Route::get('/content/{id}/{type}', "PageController@get_content");
    Route::post('/content/create', "PageController@create_content");
    Route::post('/content/update/{id}', "PageController@update_content");
    Route::post('/content/delete/{id}', "PageController@delete_content");
});

Route::prefix('users')->group(function() {
    Route::post('/register', 'UserController@register');
    Route::get('/test', function () {
        echo 'ok';
    });
    Route::get('getAll', 'UserController@getAll');
    Route::post('setAble', 'UserController@setAble');
});

Route::prefix('auth')->group(function(){
    Route::post('/login', 'AuthController@login');
    Route::post('/changepass', 'AuthController@changepass');
});

Route::prefix('admin')->group(function() {
    Route::get('/getStores', "StoreController@admin_getStores");
    Route::get('/getStore/{id}', "StoreController@admin_getStore");
});

Route::get('curltest', "TestController@curltest");
Route::get('mock', "TestController@mock");
Route::get('checkfields', 'TestController@checkfields');
Route::post('/test/automate', 'TestController@automate');
Route::post('/test/page', 'TestController@page');
Route::get('phpinfo', function() {
    phpinfo();
});

// Route::get('/search', "SearchController");



// Route::prefix('store')->group(function() {
//     Route::get('getTopStore', 'StoreController@index');
// });