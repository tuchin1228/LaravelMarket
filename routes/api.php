<?php

use App\Http\Controllers\About;
use App\Http\Controllers\CarouselApi;
use App\Http\Controllers\News;
use App\Http\Controllers\NewsApi;
use App\Http\Controllers\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 輪播
Route::prefix('Carousel')->group(function () {
    //取得所有輪播
    Route::get('/GetCarousel', [CarouselApi::class, 'index'])->name('CarouselApi');

});

Route::prefix('News')->group(function () {

    //取得最新兩則新聞
    Route::get('/GetTwoNews', [NewsApi::class, 'twonews'])->name('GetTwoNews');

});

Route::post('/uploadimage/{article_id}/{date}', [News::class, 'uploadimage'])->name('UploadArticleimage');

Route::post('/uploadProductimage/{product_id}/{type}', [Product::class, 'uploadimage'])->name('UploadProductimage');

Route::post('/uploadAboutimage/{about_id}', [About::class, 'uploadimage'])->name('UploadAboutimage');

Route::post('/KeywordSearchProduct', [Product::class, 'KeywordSearchProduct'])->name('KeywordSearchProduct');

Route::post('/KeywordSearchAdditionalProduct', [Product::class, 'KeywordSearchAdditionalProduct'])->name('KeywordSearchAdditionalProduct');

//加購產品指定主檔
Route::post('/assignProduct', [Product::class, 'product_additional_assign'])->name('ProductAdditionalAssign');
