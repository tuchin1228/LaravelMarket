<?php

use App\Http\Controllers\News;
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

Route::post('/uploadimage/{article_id}/{date}', [News::class, 'uploadimage'])->name('Uploadimage');

Route::post('/uploadProductimage/{product_id}/{type}', [Product::class, 'uploadimage'])->name('Uploadimage');

Route::post('/uploadimage', function () {return 32131;})->name('Uploadimg');
