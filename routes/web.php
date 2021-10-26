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
});
Route::prefix('subscribe')->group(function() {
    Route::get('/getSubscribers', "SubscribeController@getSubscribers");
});



Route::get('/page/index', "PageController@index");
Route::get('/page/get/{id}', "PageController@get");
Route::post('/page/create', "PageController@create");
Route::post('/page/update/{id}', "PageController@update");
Route::post('/page/delete/{id}', "PageController@delete");

Route::get('/page/content/index', "PageController@index_content");
Route::get('/page/content/get/{id}', "PageController@get_content");
Route::post('/page/content/create', "PageController@create_content");
Route::post('/page/content/update/{id}', "PageController@update_content");
Route::post('/page/content/delete/{id}', "PageController@delete_content");

// Route::prefix('store')->group(function() {
//     Route::get('getTopStore', 'StoreController@index');
// });