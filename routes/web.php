<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\Admin\BlogsController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PagesController as BasePagesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;


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
        Route::get('/product-categories-delete/{category_id}',[ProductsController::class,'productCategoryDistroy'])->name('product-categories-delete');
    });
});

Route::get('/{slug?}',[BasePagesController::class,'pages']);
// Route::get('/shop',[ShopController::class,'shop'])->name('shop');
// Route::get('/blog',[BlogController::class,'blog'])->name('blogs');
// Route::get('/fun-facts',[BasePagesController::class,'funFacts'])->name('fun-facts');
// Route::get('/our-products',[ProductController::class,'ourProducts'])->name('our-products');
// Route::get('/scrapbook-prints',[ProductController::class,'scrapbookPrints'])->name('scrapbook-prints');
// Route::get('/canvas-prints',[ProductController::class,'canvasPrints'])->name('canvas-prints');
// Route::get('/posters-panoramics',[ProductController::class,'postersPanoramics'])->name('posters-panoramics');
// Route::get('/prints-enlargements',[ProductController::class,'printEnlargements'])->name('prints-enlargements');
// Route::get('/photos',[ProductController::class,'photos'])->name('photos');
// Route::get('/central-west-n-s-w',[ProductController::class,'centralWest'])->name('central-west');
// Route::get('/childrens-photos',[ProductController::class,'childrensPhotos'])->name('childrens-photos');
// Route::get('/countryside-victoria',[ProductController::class,'countrySidevictoria'])->name('countryside-victoria');
// Route::get('/outback-n-s-w',[ProductController::class,'outback'])->name('outback');
// Route::get('/poems',[ProductController::class,'poems'])->name('poems');
// Route::get('/giftcard',[ProductController::class,'giftCard'])->name('giftcard');
// Route::get('/get-a-quote',[BasePagesController::class,'quote'])->name('quote');

