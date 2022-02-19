<?php

use App\Http\Controllers\Carousel;
use App\Http\Controllers\News;
use App\Http\Controllers\Product;
use App\Http\Controllers\User;
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

// 後台
Route::get('/backend', function () {
    return view('dashboard_layout');
});

// 輪播
Route::prefix('Carousel')->group(function () {

    //顯示所有輪播
    Route::get('/', [Carousel::class, 'index'])->name('Carousel');

    //顯示桌機輪播
    // Route::get('/desktop', [Carousel::class, 'index'])->name('Carousel');

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

    // 手機、桌機個別輪播
    Route::get('/{type}', [Carousel::class, 'rwd_index'])->name('RwdCarousel');

});

// 最新消息
Route::prefix('News')->group(function () {

    //顯示所有最新消息
    Route::get('/', [News::class, 'index'])->name('News');

    //新增最新消息頁面
    Route::get('/AddNews', [News::class, 'add_index'])->name('AddNews');

    //編輯最新消息頁面
    Route::get('/{article_id}/edit', [News::class, 'edit_news'])->name('EditNews');

    //最新消息分類頁面
    Route::get('/category', [News::class, 'category_news'])->name('CategoryNews');

    //新增最新消息分類頁面
    Route::get('/category/add', [News::class, 'category_add'])->name('CategoryAdd');

    //新增最新消息
    Route::post('/AddNews', [News::class, 'add_news'])->name('UploadNews');

    //新增最新消息分類
    Route::post('/AddCategory', [News::class, 'add_category'])->name('AddCategory');

    //編輯最新消息
    Route::post('/news/edit', [News::class, 'update_news'])->name('UpdateNews');

    //編輯最新消息分類
    Route::post('/category/edit', [News::class, 'category_update'])->name('CategoryUpdate');

    //刪除最新消息
    Route::post('/DeleteNews', [News::class, 'delete_news'])->name('DeleteNews');

    //刪除最新消息分類
    Route::post('/category/delete', [News::class, 'category_delete'])->name('CategoryDelete');

    //最新消息無用圖片管理
    Route::get('/imagenone', [News::class, 'imagenone'])->name('ImageNone');

    //最新消息刪除無用圖片管理
    Route::post('/deletenotuse', [News::class, 'deletenotuse'])->name('DeleteNotuse');

    //顯示某分類最新消息
    Route::get('/{cateId}', [News::class, 'cate_index'])->name('CategoryOfNews');

});

// 會員
Route::prefix('User')->group(function () {

    //顯示所有會員
    Route::get('/', [User::class, 'index'])->name('User');

    //編輯會員頁面
    Route::get('/{userId}/edit', [User::class, 'edit_index'])->name('EditUser');

    //編輯會員
    Route::post('/edit', [User::class, 'edit_user'])->name('UpdateUser');

    //刪除會員
    Route::post('/delete', [User::class, 'delete_user'])->name('DeleteUser');

    //搜尋會員
    Route::get('/search/{keyword}', [User::class, 'search_user'])->name('SearchUser');

});

// 商品
Route::prefix('Product')->group(function () {

    //顯示所有商品分類頁面
    Route::get('/category', [Product::class, 'product_category'])->name('ProductCategory');

    //新增商品分類頁面
    Route::get('/addCategory', [Product::class, 'product_category_add_page'])->name('ProductCategoryAddPage');

    //新增商品分類
    Route::post('/addCategory', [Product::class, 'product_category_add'])->name('ProductCategoryAdd');

    //編輯商品分類
    Route::post('/editCategory', [Product::class, 'product_category_edit'])->name('ProductCategoryEdit');

    //刪除商品分類
    Route::post('/deleteCategory', [Product::class, 'product_category_delete'])->name('ProductCategoryDelete');

    /************************************/

    //顯示所有標籤頁面
    Route::get('/tag', [Product::class, 'product_tag'])->name('ProductTag');

    //新增商品標籤頁面
    Route::get('/addTag', [Product::class, 'product_tag_add_page'])->name('ProductTagAddPage');

    //新增商品標籤
    Route::post('/addTag', [Product::class, 'product_tag_add'])->name('ProductTagAdd');

    //編輯商品標籤
    Route::post('/editTag', [Product::class, 'product_tag_edit'])->name('ProductTagEdit');

    //刪除商品標籤
    Route::post('/deleteTag', [Product::class, 'product_tag_delete'])->name('ProductTagDelete');

    /************************************/

    //顯示搜尋結果產品頁面
    Route::get('/search/{keyword}', [Product::class, 'search_product'])->name('SearchProduct');

    //刪除產品
    Route::post('/delete', [Product::class, 'product_delete'])->name('DeleteProduct');

    //建立商品主檔頁面
    Route::get('/create', [Product::class, 'product_create_page'])->name('CreateProductPage');

    //編輯商品主檔頁面
    Route::get('/edit/{productId}', [Product::class, 'product_edit_page'])->name('EditProductPage');

    //建立商品主檔
    Route::post('/create', [Product::class, 'product_create'])->name('CreateProduct');

    //更新商品主檔
    Route::post('/edit', [Product::class, 'product_edit'])->name('EditProduct');

    //商品無用圖片管理
    Route::get('/imagenone', [Product::class, 'imagenone'])->name('ProductImageNone');

    //最新消息刪除無用圖片管理
    Route::post('/delete_product_image_notuse', [Product::class, 'delete_product_image_notuse'])->name('DeleteProductImageNotuse');

    //顯示分類產品頁面
    Route::get('/{category}', [Product::class, 'product'])->name('CategoryProduct');

    //顯示所有產品頁面
    Route::get('/', [Product::class, 'product'])->name('AllProduct');

    //上傳商品圖
    Route::post('/uploadProductImage', [Product::class, 'upload_product_image'])->name('UploadProductImage');

    //刪除商品圖
    Route::post('/deleteProductImage', [Product::class, 'delete_product_image'])->name('DeleteProductImage');

    /************************************/

    //顯示所有產品頁面
    Route::get('/detail/{productId}', [Product::class, 'product_detail_page'])->name('ProductDetailPage');

    //新增商品子檔
    Route::post('/detail/detail_add', [Product::class, 'product_detail_add'])->name('AddProductDetail');

    //編輯商品子檔
    Route::post('/detail/detail_edit', [Product::class, 'product_detail_edit'])->name('EditProductDetail');

    //刪除商品子檔
    Route::post('/detail/detail_delete', [Product::class, 'product_detail_delete'])->name('DeleteProductDetail');

    /************************************/

    //加購產品頁面
    Route::get('/additional/products', [Product::class, 'product_additional_page'])->name('ProductAdditionalPage');

});
