<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\BlogsController;
use App\Http\Controllers\admin\PagesController;
use App\Http\Controllers\admin\ProductsController;
use App\Http\Controllers\admin\PhotoForSaleController;
use App\Http\Controllers\admin\GiftCardController;
use App\Http\Controllers\admin\CouponController;
use App\Http\Controllers\PagesController as BasePagesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\OrderController;

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

        Route::get('/gift-card',[GiftCardController::class,'giftCard'])->name('gift-card-list');
        Route::get('/gift-card-add',[GiftCardController::class,'giftCardAdd'])->name('gift-card-add');
        Route::post('/gift-card-save',[GiftCardController::class,'giftCardSave'])->name('gift-card-save');
        Route::get('/gift-card-show/{category_id}',[GiftCardController::class,'giftCardShow'])->name('gift-card-show');
        Route::post('/gift-card-update',[GiftCardController::class,'giftCardUpdate'])->name('gift-card-update');
        Route::delete('/gift-card-delete/{category_id}',[GiftCardController::class,'giftCardDistroy'])->name('gift-card-delete');

        Route::get('/coupons',[CouponController::class,'coupons'])->name('coupons-list');
        Route::get('/coupon-add',[CouponController::class,'couponAdd'])->name('coupon-add');
        Route::post('/coupon-save',[CouponController::class,'couponSave'])->name('coupon-save');
        Route::get('/coupon-show/{id}', [CouponController::class, 'couponShow'])->name('coupon-show');
        Route::post('/coupon-update',[CouponController::class,'couponUpdate'])->name('coupon-update');
        Route::delete('/coupon-delete/{id}',[CouponController::class,'couponDistroy'])->name('coupon-delete');

        Route::get('/shipping',[ShippingController::class,'shipping'])->name('shipping-list');
        Route::get('/shipping-add',[ShippingController::class,'shippingAdd'])->name('shipping-add');
        Route::post('/shipping-save',[ShippingController::class,'shippingSave'])->name('shipping-save');
        Route::get('/shipping-show/{id}', [ShippingController::class, 'shippingShow'])->name('shipping-show');
        Route::post('/shipping-update',[ShippingController::class,'shippingUpdate'])->name('shipping-update');
        Route::post('/shipping-status-update',[ShippingController::class,'updateStatus'])->name('shipping-status-update');

        Route::get('/orders',[OrderController::class,'index'])->name('orders-list');
        Route::get('/order-detail/{order_number}',[OrderController::class,'orderDetail'])->name('order-detail');

    });
});

Route::get('/blog-detail/{slug}',[BasePagesController::class,'blogDetail'])->name('blog-detail');
Route::get('/our-products/photos-for-sale/{slug?}',[BasePagesController::class,'PhotosForSale'])->name('photos-for-sale');
Route::get('/our-products/photos-for-sale-details/{slug?}',[BasePagesController::class,'PhotosForSaleDetails'])->name('photos-for-sale-details');
Route::get('/our-products/gift-card',[BasePagesController::class,'giftCard'])->name('gift-card');
Route::get('/our-products/gift-card-detail/{slug}',[BasePagesController::class,'giftCard_detail'])->name('gift-card-detail');
Route::post('/user-register',[LoginController::class,'registerUser'])->name('user-register');
Route::post('/user-login',[LoginController::class,'login'])->name('user-login');
Route::get('/user-logout',[LoginController::class,'logout'])->name('user-logout');
Route::post('/shop-upload-image',[ShopController::class,'uploadImage'])->name('shop-upload-image');
Route::get('/shop-detail',[ShopController::class,'shopDetail'])->name('shop-detail');
Route::post('/products-by-category',[ShopController::class,'getProductsBycategory'])->name('products-by-category');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::get('/remove-from-cart/{product_id}', [CartController::class, 'removeFromCart'])->name('remove-from-cart');
Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('apply-coupon');
Route::post('/billing-details',[CartController::class,'billingDetails'])->name('billing-details');
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('update-cart');
Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout')->middleware('checkout');
Route::post('/create-customer', [PaymentController::class, 'createCustomer']);
Route::post('/charge-customer', [PaymentController::class, 'chargeCustomer']);
Route::get('/thank-you/{order_id}', [PaymentController::class,'thankyou'])->name('thankyou');


Route::get('/{slug?}',[BasePagesController::class,'pages']);
Route::get('{route?}/{slug?}',[BasePagesController::class,'pages']);


