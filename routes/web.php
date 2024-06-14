<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\BlogsController;
use App\Http\Controllers\admin\PagesController;
use App\Http\Controllers\admin\ProductsController;
use App\Http\Controllers\admin\PhotoForSaleController;
use App\Http\Controllers\admin\CouponController;
use App\Http\Controllers\PagesController as BasePagesController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::prefix('admin')->group(function () {
    Route::get('/login',[AuthController::class,'login'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('admin.login.post');
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard',[AuthController::class,'dashboard'])->name('admin.dashboard');
        Route::post('/admin-logout', [AuthController::class, 'logout'])->name('admin-logout');
        Route::resource('/pages',PagesController::class);
        Route::resource('/blogs',BlogsController::class);

        Route::get('/product-categories',[ProductsController::class,'productCategory'])->name('product-categories-list');
        Route::get('/product-categories-add',[ProductsController::class,'productCategoryAdd'])->name('product-categories-add');
        Route::post('/product-categories-save',[ProductsController::class,'productCategorySave'])->name('product-categories-save');
        Route::get('/product-categories-show/{category_id}',[ProductsController::class,'productCategoryShow'])->name('product-categories-show');
        Route::post('/product-categories-update',[ProductsController::class,'productCategoryUpdate'])->name('product-categories-update');
        Route::delete('/product-categories-delete/{category_id}',[ProductsController::class,'productCategoryDistroy'])->name('product-categories-delete');

        Route::get('/products',[ProductsController::class,'products'])->name('product-list');
        Route::get('/product-add',[ProductsController::class,'productAdd'])->name('product-add');
        Route::post('/product-save',[ProductsController::class,'productSave'])->name('product-save');
        Route::get('/product-show/{slug}',[ProductsController::class,'productShow'])->name('product-show');
        Route::post('/product-update',[ProductsController::class,'productUpdate'])->name('product-update');
        Route::delete('/product-delete/{product_id}',[ProductsController::class,'productDistroy'])->name('product-delete');

        Route::get('/photos-for-sale-categories',[PhotoForSaleController::class,'productCategory'])->name('photos-for-sale-categories-list');
        Route::get('/photos-for-sale-categories-add',[PhotoForSaleController::class,'productCategoryAdd'])->name('photos-for-sale-categories-add');
        Route::post('/photos-for-sale-categories-save',[PhotoForSaleController::class,'productCategorySave'])->name('photos-for-sale-categories-save');
        Route::get('/photos-for-sale-categories-show/{category_id}',[PhotoForSaleController::class,'productCategoryShow'])->name('photos-for-sale-categories-show');
        Route::post('/photos-for-sale-categories-update',[PhotoForSaleController::class,'productCategoryUpdate'])->name('photos-for-sale-categories-update');
        Route::delete('/photos-for-sale-categories-delete/{category_id}',[PhotoForSaleController::class,'productCategoryDistroy'])->name('photos-for-sale-categories-delete');

        Route::get('/photos-for-sale-products',[PhotoForSaleController::class,'products'])->name('photos-for-sale-product-list');
        Route::get('/photos-for-sale-product-add',[PhotoForSaleController::class,'productAdd'])->name('photos-for-sale-product-add');
        Route::post('/photos-for-sale-product-save',[PhotoForSaleController::class,'productSave'])->name('photos-for-sale-product-save');
        Route::get('/photos-for-sale-product-show/{slug}',[PhotoForSaleController::class,'productShow'])->name('photos-for-sale-product-show');
        Route::post('/photos-for-sale-product-update',[PhotoForSaleController::class,'productUpdate'])->name('photos-for-sale-product-update');
        Route::delete('/photos-for-sale-product-delete/{product_id}',[PhotoForSaleController::class,'productDistroy'])->name('photos-for-sale-product-delete');

        Route::get('/coupons',[CouponController::class,'coupons'])->name('coupons-list');


    });
});

Route::get('/blog-detail/{slug}',[BasePagesController::class,'blogDetail'])->name('blog-detail');
Route::get('/our-products/photos-for-sale/{slug?}',[BasePagesController::class,'PhotosForSale'])->name('photos-for-sale');
Route::get('/gift-card',[BasePagesController::class,'giftCard'])->name('gift-card');

Route::get('/{slug?}',[BasePagesController::class,'pages']);
Route::get('{route?}/{slug?}',[BasePagesController::class,'pages']);


