<?php

use App\Http\Controllers\About;
use App\Http\Controllers\CarouselApi;
use App\Http\Controllers\News;
use App\Http\Controllers\NewsApi;
use App\Http\Controllers\AboutApi;
use App\Http\Controllers\Product;
use App\Http\Controllers\ContactApi;
use App\Http\Controllers\ProductApi;
use App\Http\Controllers\UserApi;
use App\Http\Controllers\CartApi;
use App\Http\Controllers\CheckoutApi;
use App\Http\Controllers\OrderApi;
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

Route::prefix('About')->group(function () {

    //取得顯示首頁的關於我們
    Route::get('/showHomeAbout', [AboutApi::class, 'showHomeAbout'])->name('GetAbout');


    //關於我們頁面(所有關於我們)
    Route::get('/GetAbout', [AboutApi::class, 'getabout'])->name('GetAbout');
});

Route::prefix('News')->group(function () {

    //取得最新兩則新聞
    Route::get('/GetTwoNews', [NewsApi::class, 'twonews'])->name('GetTwoNews');

    //最新消息頁面(所有消息分類+所有消息)
    Route::get('/GetNews', [NewsApi::class, 'getnews'])->name('GetNews');


    //最新消息頁面(所有消息分類+特定消息)
    Route::get('/GetNews/{id}', [NewsApi::class, 'getSpecificNews'])->name('GetSpecificNews');

    //最新消息詳細內容(id=>categoryId)
    Route::get('/GetNews/{id}/{articleId}', [NewsApi::class, 'getNewsDetail'])->name('GetNewsDetail');
});


Route::prefix('Contact')->group(function () {

    //取得問題分類
    Route::get('/GetContactCategry', [ContactApi::class, 'GetContactCategry'])->name('GetContactCategry');

    //取得問題分類
    Route::post('/submitContact', [ContactApi::class, 'submitContact'])->name('SubmitContact');
});

Route::prefix('User')->group(function () {


    //登入
    Route::post('/login', [UserApi::class, 'login'])->name('Login');

    //註冊
    Route::post('/register', [UserApi::class, 'register'])->name('Register');

    //Line會員註冊
    Route::post('/lineregister', [UserApi::class, 'LineRegister'])->name('LineRegister');

    //連結Line會員
    Route::post('/linkline', [UserApi::class, 'Linkline'])->name('Linkline');

    //取得會員資訊
    Route::post('/userinfo', [UserApi::class, 'userinfo'])->name('Userinfo');

    //編輯會員資訊
    Route::post('/edit_userinfo', [UserApi::class, 'edit_userinfo'])->name('EditUserinfo');

    //編輯會員密碼
    Route::post('/edit_password', [UserApi::class, 'edit_password'])->name('EditUserPassword');

    //重設會員密碼
    Route::post('/reset_password', [UserApi::class, 'reset_password'])->name('ResetUserPassword');



    //檢查登入狀態
    Route::post('/checkAuth', [UserApi::class, 'checkAuth'])->name('CheckAuth');
});




Route::prefix('Cart')->group(function () {

    //加入購物車
    Route::post('/add_to_cart', [CartApi::class, 'AddToCart'])->name('AddToCart');

    //取得購物車
    Route::post('/getcart', [CartApi::class, 'getcart'])->name('GetCart');

    //更改購物車內商品數量
    Route::post('/setcartproductcount', [CartApi::class, 'SetCartProductCount'])->name('SetCartProductCount');

    //更改購物車內加購品數量
    Route::post('/setcartproductadditioncount', [CartApi::class, 'SetCartProductAdditionCount'])->name('SetCartProductAdditionCount');

    //刪除購物車內商品
    Route::post('/removecartproduct', [CartApi::class, 'RemoveCartProduct'])->name('RemoveCartProduct');
});


Route::prefix('Checkout')->group(function () {

    //結帳
    Route::post('/checkout', [CheckoutApi::class, 'CheckOut'])->name('CheckOut');
    

});


Route::prefix('Order')->group(function () {

    //取得所有訂單
    Route::post('/order', [OrderApi::class, 'order'])->name('GetOrder');

    //取得訂單
    Route::post('/{orderId}', [OrderApi::class, 'GetOrderDetail'])->name('GetOrderDetail');

    
    Route::post('/pay/ConfirmUrl', [OrderApi::class, 'ConfirmUrl'])->name('ConfirmUrl');
    
    //Linepay online api
    Route::post('/pay/{orderId}', [OrderApi::class, 'pay'])->name('Pay');

    //取消訂單
    Route::post('/cancel/{orderId}', [OrderApi::class, 'CancelOrder'])->name('CancelOrder');


    
});



Route::prefix('Product')->group(function () {

    //取得所有商品分類+商品
    Route::get('/category', [ProductApi::class, 'index'])->name('ProductIndex');

    //取得指定商品分類+商品
    Route::get('/category/{categoryId}', [ProductApi::class, 'GetProductByCategory'])->name('GetProductByCategory');

    //取得指定商品分類+商品
    Route::get('/product/{productId}', [ProductApi::class, 'GetProduct'])->name('GetProduct');
});

Route::post('/uploadimage/{article_id}/{date}', [News::class, 'uploadimage'])->name('UploadArticleimage');

Route::post('/uploadProductimage/{product_id}/{type}', [Product::class, 'uploadimage'])->name('UploadProductimage');

Route::post('/uploadAboutimage/{about_id}', [About::class, 'uploadimage'])->name('UploadAboutimage');

Route::post('/KeywordSearchProduct', [Product::class, 'KeywordSearchProduct'])->name('KeywordSearchProduct');

Route::post('/KeywordSearchAdditionalProduct', [Product::class, 'KeywordSearchAdditionalProduct'])->name('KeywordSearchAdditionalProduct');

//加購產品指定主檔
Route::post('/assignProduct', [Product::class, 'product_additional_assign'])->name('ProductAdditionalAssign');
