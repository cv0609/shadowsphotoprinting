<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\PagesController as BasePagesController;
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
Route::get('/shop',[UserController::class,'shop'])->name('shop');
Route::get('/blog',[UserController::class,'blog'])->name('blog');
Route::get('/fun-facts',[UserController::class,'funFacts'])->name('fun.facts');
Route::get('/our-products',[UserController::class,'ourProducts'])->name('our.products');
Route::get('/scrapbook-prints',[UserController::class,'scrapbookPrints'])->name('scrapbook.prints');
Route::get('/posters-panoramics',[UserController::class,'postersPanoramics'])->name('posters.panoramics');
Route::get('/prints-enlargement',[UserController::class,'printEnlargements'])->name('prints.enlargements');