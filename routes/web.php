<?php

use App\Http\Controllers\Carousel;
use Illuminate\Support\Facades\Route;

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

// 輪播
Route::prefix('Carousel')->group(function () {

    //顯示所有輪播
    Route::get('/', [Carousel::class, 'index'])->name('Carousel');

    //新增輪播頁面
    Route::get('/AddCarousel', [Carousel::class, 'add_index'])->name('AddCarousel');

    //編輯輪播頁面
    Route::get('/EditCarousel/{editId}', [Carousel::class, 'edit_index'])->name('EditCarousel');

    //新增輪播
    Route::post('/AddCarousel', [Carousel::class, 'add_carousel'])->name('UploadCarousel');

    //更新輪播
    Route::post('/UpdateCarousel', [Carousel::class, 'update_carousel'])->name('UpdateCarousel');

    //刪除輪播
    Route::post('/DeleteCarousel', [Carousel::class, 'delete_carousel'])->name('DeleteCarousel');

});
