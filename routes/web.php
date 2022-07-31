<?php

use App\Models\InstagramData;
use Illuminate\Support\Facades\Route;
use InstagramScraper\Instagram;
use InstagramScraper\MediaProxy;
use Phpfastcache\Helper\Psr16Adapter;
use App\Helpers\Helper;
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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/MediaProxy', function (Request $request) {
    $proxy = new MediaProxy(['allowedReferersRegex' => "/(toc_api\.test|localhost)$/"]);
    //$proxy = new MediaProxy(['allowedReferersRegex' => "/(".env('PROXY_API_URL')."|".env('PROXY_FORNT_URL').")$/"]);

    $proxy->handle($_GET, $_SERVER);
});

Route::get('/fetchInstaData', function () {
    return Helper::fetchInstagramData();
});

Route::get('/unauthorized', function () {
    return view('errors.unauthorized');
})->name('unauthorized');
