<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\Admin\PagesController;
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

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->group(function () {
    Route::get('/login',[AuthController::class,'login'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('admin.login.post');
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard',[AuthController::class,'dashboard'])->name('admin.dashboard');
        Route::post('/admin-logout', [AuthController::class, 'logout'])->name('admin-logout');
        Route::resource('/pages',PagesController::class);
    });
});

Route::get('/home',[BasePagesController::class,'home'])->name('home');
Route::get('/shop',[ShopController::class,'shop'])->name('shop');
Route::get('/blog',[BlogController::class,'blog'])->name('blog');
Route::get('/fun-facts',[BasePagesController::class,'funFacts'])->name('fun.facts');
Route::get('/our-products',[ProductController::class,'ourProducts'])->name('our.products');
Route::get('/scrapbook-prints',[ProductController::class,'scrapbookPrints'])->name('scrapbook.prints');
Route::get('/posters-panoramics',[ProductController::class,'postersPanoramics'])->name('posters.panoramics');
Route::get('/prints-enlargements',[ProductController::class,'printEnlargements'])->name('prints.enlargements');
Route::get('/photos-for-sale',[ProductController::class,'photosForsale'])->name('photos.for.sale');
Route::get('/central-west-n-s-w',[ProductController::class,'centralWest'])->name('central.west');
Route::get('/childrens-photos',[ProductController::class,'childrensPhotos'])->name('childrens.photos');
Route::get('/giftcard',[ProductController::class,'giftCard'])->name('gift.card');