<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\BlogsController;
use App\Http\Controllers\admin\PagesController;
use App\Http\Controllers\admin\ProductsController;
use App\Http\Controllers\admin\PhotoForSaleController;
use App\Http\Controllers\admin\GiftCardController;
use App\Http\Controllers\admin\CouponController;
use App\Http\Controllers\admin\VariationsController;
use App\Http\Controllers\admin\BrandAmbassadorController;

use App\Http\Controllers\PagesController as BasePagesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\MyAccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\HandCraftController;
use App\Http\Controllers\admin\TestPrintController;
use App\Http\Controllers\admin\NewsletterController;
use App\Http\Controllers\admin\SalePopupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AmbassadorController;
use App\Http\Controllers\ShippingController as FrontendShippingController;


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

// Route::get('/test', [UserController::class, 'index'])->name('test');


Route::get('/download-pdf', [BasePagesController::class, 'downloadPDF'])->name('download.pdf');

Route::get('/sitemap.xml', function () {
    return response()->file(public_path('sitemap.xml'));
});

Route::prefix('admin')->group(function () {

    Route::get('/login',[AuthController::class,'login'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('admin.login.post');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard',[AuthController::class,'dashboard'])->name('admin.dashboard');
        Route::post('/admin-logout', [AuthController::class, 'logout'])->name('admin-logout');
        Route::get('set-index',[AuthController::class,'setIndex'])->name('admin.index');
        Route::resource('/pages',PagesController::class);
        Route::resource('/blogs',BlogsController::class);
        Route::get('/generate-blog-pdf/{blog}',[BlogsController::class,'generateBlogPDF'])->name('generate-blog-pdf');

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
        Route::post('/product-order-update', [ProductsController::class, 'updateProductOrder'])->name('product-order-update');



        Route::prefix('test-print')->group(function () {
            Route::get('/products', [TestPrintController::class, 'products'])->name('test-print-product-list');
            Route::get('/product-add', [TestPrintController::class, 'productAdd'])->name('test-print-product-add');
            Route::post('/product-save', [TestPrintController::class, 'productSave'])->name('test-print-product-save');
            Route::get('/product-show/{cat_id}', [TestPrintController::class, 'productShow'])->name('test-print-product-show');
            Route::post('/product-update', [TestPrintController::class, 'productUpdate'])->name('test-print-product-update');
            Route::delete('/product-delete/{product_id}', [TestPrintController::class, 'productDistroy'])->name('test-print-product-delete');
            Route::get('/get-products', [TestPrintController::class, 'getProudcts'])->name('get-products');
        });

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

        Route::get('/hand-craft',[HandCraftController::class,'products'])->name('hand-craft-list');
        Route::get('/hand-craft-product-add',[HandCraftController::class,'productAdd'])->name('hand-craft-product-add');
        Route::post('/hand-craft-product-save',[HandCraftController::class,'productSave'])->name('hand-craft-product-save');
        Route::get('/hand-craft-product-show/{slug}',[HandCraftController::class,'productShow'])->name('hand-craft-product-show');
        Route::post('/hand-craft-product-update',[HandCraftController::class,'productUpdate'])->name('hand-craft-product-update');
        Route::delete('/hand-craft-product-delete/{product_id}',[HandCraftController::class,'productDistroy'])->name('hand-craft-product-delete');



        Route::get('/hand-craft-categories',[HandCraftController::class,'productCategory'])->name('hand-craft-categories-list');
        Route::get('/hand-craft-categories-add',[HandCraftController::class,'productCategoryAdd'])->name('hand-craft-categories-add');
        Route::post('/hand-craft-categories-save',[HandCraftController::class,'productCategorySave'])->name('hand-craft-categories-save');
        Route::post('/hand-craft-categories-update',[HandCraftController::class,'productCategoryUpdate'])->name('hand-craft-categories-update');
        Route::get('/hand-craft-categories-show/{category_id}',[HandCraftController::class,'productCategoryShow'])->name('hand-craft-categories-show');
        Route::delete('/hand-craft-categories-delete/{category_id}',[HandCraftController::class,'productCategoryDistroy'])->name('hand-craft-categories-delete');

        Route::get('/coupons',[CouponController::class,'coupons'])->name('coupons-list');
        Route::get('/coupon-add',[CouponController::class,'couponAdd'])->name('coupon-add');
        Route::post('/coupon-save',[CouponController::class,'couponSave'])->name('coupon-save');
        Route::get('/coupon-show/{id}', [CouponController::class, 'couponShow'])->name('coupon-show');
        Route::post('/coupon-update',[CouponController::class,'couponUpdate'])->name('coupon-update');
        Route::delete('/coupon-delete/{id}',[CouponController::class,'couponDistroy'])->name('coupon-delete');
        Route::get('/coupon-update-status',[CouponController::class,'couponUpdateStatus'])->name('coupon-update-status');

        Route::get('/shipping',[ShippingController::class,'shipping'])->name('shipping-list');
        Route::get('/shipping-add',[ShippingController::class,'shippingAdd'])->name('shipping-add');
        Route::post('/shipping-save',[ShippingController::class,'shippingSave'])->name('shipping-save');
        Route::get('/shipping-show/{id}', [ShippingController::class, 'shippingShow'])->name('shipping-show');
        Route::post('/shipping-update',[ShippingController::class,'shippingUpdate'])->name('shipping-update');
        Route::post('/shipping-status-update',[ShippingController::class,'updateStatus'])->name('shipping-status-update');

        Route::get('/orders',[OrderController::class,'index'])->name('orders-list');
        Route::get('/order-detail/{order_number}',[OrderController::class,'orderDetail'])->name('order-detail');
        Route::get('/search-orders', [OrderController::class, 'search'])->name('orders.search');
        Route::get('/download-order-zip/{order_id}', [OrderController::class, 'downloadOrderzip'])->name('download-order-zip');
        Route::post('/update-order',[OrderController::class,'updateOrder'])->name('update-order');
        Route::get('/refund-order/{order_id}',[OrderController::class,'refundOrder'])->name('refund-order');
        Route::post('/add-note',[OrderController::class,'addNote'])->name('add-note');

        Route::get('sizes',[VariationsController::class,'sizes'])->name('sizes-list');
        Route::get('size-add',[VariationsController::class,'addSize'])->name('size-add');
        Route::post('size-save',[VariationsController::class,'saveSize'])->name('size-save');
        Route::post('edit-size-save',[VariationsController::class,'saveEditSize'])->name('edit-size-save');
        Route::delete('size-delete/{id}',[VariationsController::class,'deleteSize'])->name('size-delete');
        Route::get('size-edit/{id}',[VariationsController::class,'editSize'])->name('size-edit');

        Route::get('size-types',[VariationsController::class,'sizesType'])->name('size-types-list');
        Route::get('size-type-add',[VariationsController::class,'addSizeType'])->name('size-type-add');
        Route::post('size-type-save',[VariationsController::class,'saveSizeType'])->name('size-type-save');
        Route::delete('size-type-delete/{id}',[VariationsController::class,'deleteSizeType'])->name('size-type-delete');
        Route::get('size-type-edit/{id}',[VariationsController::class,'editSizeType'])->name('size-type-edit');
        Route::post('edit-size-type-save',[VariationsController::class,'editSizeTypeSave'])->name('edit-size-type-save');
        Route::get('news-letter',[NewsletterController::class,'allNewsLetter'])->name('news-letter');
        Route::get('news-letter-add',[NewsletterController::class,'addNewsLetter'])->name('news-letter-add');
        Route::post('news-letter-add',[NewsletterController::class,'saveNewsLetter'])->name('news-letter-save');
        Route::get('news-letter-update-status',[NewsletterController::class,'updateStatus'])->name('news-letter-update-status');
        Route::delete('/news-letter-delete/{id}',[NewsletterController::class,'newsletterDistroy'])->name('news-letter-delete');
        Route::get('news-letter-edit/{id}',[NewsletterController::class,'editnewsletter'])->name('news-letter-edit');
        Route::post('news-letter-edit-save',[NewsletterController::class,'newsletterUpdateStatus'])->name('news-letter-edit-save');

        Route::prefix('sale')->group(function () {
            Route::get('/popup', [SalePopupController::class, 'index'])->name('popup-index');
            Route::get('/add-sale-popup', [SalePopupController::class, 'addSalePopup'])->name('add-sale-popup');
            Route::post('/add-sale-popup-save', [SalePopupController::class, 'addSalePopupSave'])->name('add-sale-popup-save');
            Route::get('/sale-popup-update-status', [SalePopupController::class, 'salePopupUpdateStatus'])->name('sale-popup-update-status');
            Route::get('/edit-popup-show/{id}', [SalePopupController::class, 'editPopupShow'])->name('edit-popup-show');
            Route::post('/edit-sale-popup-save', [SalePopupController::class, 'editSalePopupSave'])->name('edit-sale-popup-save');
            Route::delete('/sale-popup-delete/{id}', [SalePopupController::class, 'salePopupDelete'])->name('sale-popup-delete');
        });
        Route::prefix('ambassador')->group(function () {
            Route::get('/', [BrandAmbassadorController::class, 'index'])->name('brand.index');
            Route::get('/request', [BrandAmbassadorController::class, 'request'])->name('brand.requests');
            Route::post('/{id}/approve', [BrandAmbassadorController::class, 'approve'])->name('admin.ambassador.approve');
            Route::post('/{id}/reject', [BrandAmbassadorController::class, 'reject'])->name('admin.ambassador.reject');

        });



    });
});

Route::get('/forgot-password',[LoginController::class,'forgotPassword'])->name('forgot-password');
Route::get('/email-verify',[LoginController::class,'emailVerify'])->name('email.verify');
Route::post('/email-verification',[LoginController::class,'emailVerification'])->name('email-verification');
Route::post('/reset-password-save',[LoginController::class,'resetPasswordSave'])->name('reset-password-save');
Route::get('/password-reset', [LoginController::class, 'resetPasswordForm'])->name('password.reset');
Route::post('/forgot-save',[LoginController::class,'forgotSave'])->name('forgot-save');
Route::get('/blog-detail/{slug}',[BasePagesController::class,'blogDetail'])->name('blog-detail');
Route::post('/send-quote',[BasePagesController::class,'sendQuote'])->name('send-quote');
Route::get('/our-products/photos-for-sale/{slug?}',[BasePagesController::class,'PhotosForSale'])->name('photos-for-sale');

Route::get('/our-products/hand-craft/{slug?}',[BasePagesController::class,'handCraft'])->name('hand-craft');
Route::get('/our-products/hand-craft-details/{slug?}',[BasePagesController::class,'handCraftDetails'])->name('hand-craft-details');

Route::get('/our-products/photos-for-sale-details/{slug?}',[BasePagesController::class,'PhotosForSaleDetails'])->name('photos-for-sale-details');
Route::get('/our-products/gift-card',[BasePagesController::class,'giftCard'])->name('gift-card');
Route::get('/our-products/gift-card-detail/{slug}',[BasePagesController::class,'giftCard_detail'])->name('gift-card-detail');
Route::get('/our-products/bulkprints',[BasePagesController::class,'bulkprints'])->name('bulkprints');
Route::get('bulkprints-product/{slug}',[BasePagesController::class,'bulkprints_details'])->name('bulkprints-product');
Route::get('/faq',[BasePagesController::class,'accordion'])->name('faq');

Route::post('/user-register',[LoginController::class,'registerUser'])->name('user-register');
Route::post('/user-login',[LoginController::class,'login'])->name('user-login');
Route::get('/user-logout',[LoginController::class,'logout'])->name('user-logout');
Route::post('/shop-upload-image',[ShopController::class,'uploadImage'])->name('shop-upload-image');
Route::get('/shop-upload-image-csrf-refresh',[ShopController::class,'uploadImageCsrfRefresh'])->name('shop-upload-image-csrf-refresh');
Route::get('/shop-detail',[ShopController::class,'shopDetail'])->name('shop-detail');
Route::post('/products-by-category',[ShopController::class,'getProductsBycategory'])->name('products-by-category');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::get('/cart', [CartController::class, 'cart'])->name('cart')->middleware('checkout');
Route::get('/remove-from-cart/{product_id}', [CartController::class, 'removeFromCart'])->name('remove-from-cart');
Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('apply-coupon');
Route::post('/order-type', [CartController::class, 'orderType'])->name('order-type');
Route::post('/shutter-point', [CartController::class, 'shutterPoint'])->name('shutter-point');


/*** Photographer Brand Ambassador  */
Route::get('/apply-form', [AmbassadorController::class, 'applyForm'])->name('apply-form');
Route::post('/apply-form', [AmbassadorController::class, 'saveForm'])->name('apply-form.post');
Route::get('/featured-photographers', [AmbassadorController::class, 'featuredPhotographers'])->name('featured-photographers');
Route::get('/photographer-brand-ambassador', [AmbassadorController::class, 'photographerBrandAmbassador'])->name('photographer-brandAmbassador');



Route::get('/reset-coupon', [CartController::class, 'resetCoupon'])->name('reset-coupon');

// August 2025 Promotion Email Route
Route::post('/august-promotion/send-email', [UserController::class, 'augustPromotionEmail'])->name('august.promotion.email');

Route::post('/billing-details',[CartController::class,'billingDetails'])->name('billing-details');
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('update-cart');
Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout')->middleware('checkout');
Route::post('/create-customer', [PaymentController::class, 'createCustomer']);

Route::post('/free-order', [PaymentController::class, 'freeOrderCheckout'])->name('free_order.checkout');



Route::post('/charge-customer', [PaymentController::class, 'chargeCustomer']);
Route::get('/thank-you/{order_id}', [PaymentController::class,'thankyou'])->name('thankyou');
Route::get('/promotions', [BasePagesController::class, 'promotions'])->name('promotions');
Route::get('/promotion-detail/{slug}', [BasePagesController::class, 'promotionDetail'])->name('promotion-detail');

Route::middleware(['myAccount'])->group(function () {
    Route::get('/my-account', [MyAccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/my-account/ambassador', [AmbassadorController::class, 'ambassador'])->name('ambassador');

    Route::get('/my-account/blog', [AmbassadorController::class, 'blog'])->name('ambassador.blog');
    Route::get('/my-account/blog/create', [AmbassadorController::class, 'create'])->name('ambassador.blog.create');
    Route::post('/my-account/blog/save', [AmbassadorController::class, 'save'])->name('ambassador.blog.save');
    Route::get('/my-account/blog/{id}', [AmbassadorController::class, 'viewBlog'])->name('ambassador.blog.view');
    Route::post('/my-account/blog/{id}', [AmbassadorController::class, 'saveBlog'])->name('ambassador.blog.update');
    Route::post('/my-account/blog/{blog}', [AmbassadorController::class, 'saveBlog'])->name('ambassador.blog.destroy');


    Route::get('/my-account/orders', [MyAccountController::class, 'orders'])->name('orders');
    Route::get('/my-account/downloads', [MyAccountController::class, 'downloads'])->name('downloads');
    Route::get('/my-account/address', [MyAccountController::class, 'address'])->name('address');
    Route::get('/my-account/payment-method', [MyAccountController::class, 'payment_method'])->name('payment-method');
    Route::get('/my-account/account-details', [MyAccountController::class, 'account_details'])->name('account-details');
    Route::get('/my-account/my-coupons', [MyAccountController::class, 'my_coupons'])->name('my-coupons');
    Route::get('/my-account/view-order/{order_id}', [MyAccountController::class, 'view_order'])->name('view-order');
    Route::get('/my-account/add-address/{slug}', [MyAccountController::class, 'addAddress'])->name('add-address');
    Route::post('/my-account/save-address', [MyAccountController::class, 'saveAddress'])->name('save-address');
    Route::get('/my-account/edit-address/{slug}', [MyAccountController::class, 'editAddress'])->name('edit-address');
    Route::post('/my-account/edit-save-address', [MyAccountController::class, 'editSaveAddress'])->name('edit-save-address');
    Route::post('/my-account/account-details-save', [MyAccountController::class, 'saveAccountDetails'])->name('account-details-save');

    Route::post('/my-account/update-pic', [MyAccountController::class, 'updateProfilePic'])->name('profile.update-pic');
});

Route::get('/home2',[BasePagesController::class,'pages2']);

Route::prefix('afterpay')->group(function () {
    Route::post('/checkout', [PaymentController::class, 'afterPayCheckout'])->name('afterPay.checkout');
    Route::get('/success', [PaymentController::class, 'afterpaySuccess'])->name('checkout.success');
    Route::get('/cancel', [PaymentController::class, 'afterpayCancel'])->name('checkout.cancel');
    Route::get('/order/success', [PaymentController::class, 'orderSuccess'])->name('order.success');
});

// Cart Shipping Routes (Re-added)
Route::prefix('cart-shipping')->group(function () {
    Route::post('/calculate', [FrontendShippingController::class, 'calculateShipping'])->name('cart-shipping.calculate');
Route::post('/calculate-per-category', [FrontendShippingController::class, 'calculateShippingPerCategory'])->name('cart-shipping.calculate-per-category');
Route::post('/calculate-total-shipping', [FrontendShippingController::class, 'calculateTotalShipping'])->name('cart-shipping.calculate-total');
Route::post('/save-shipping-session', [FrontendShippingController::class, 'saveShippingSession'])->name('cart-shipping.save-session');
Route::post('/clear-shipping-session', [FrontendShippingController::class, 'clearShippingSession'])->name('cart-shipping.clear-session');
    Route::post('/quantity-options', [FrontendShippingController::class, 'getShippingForQuantity'])->name('cart-shipping.quantity-options');
    Route::get('/tiers', [FrontendShippingController::class, 'getShippingTiers'])->name('cart-shipping.tiers');
    Route::post('/update-selection', [FrontendShippingController::class, 'updateShippingSelection'])->name('cart-shipping.update-selection');
    Route::post('/clear-selection', [FrontendShippingController::class, 'clearShippingSelection'])->name('cart-shipping.clear-selection');
    Route::get('/get-session-shipping', [FrontendShippingController::class, 'getSessionShipping'])->name('cart-shipping.get-session');
    Route::get('/get-cart-items', [FrontendShippingController::class, 'getCartItems'])->name('cart-shipping.get-cart-items');
});

// Cart total route
Route::get('/cart/get-updated-total', [CartController::class, 'getUpdatedTotal'])->name('cart.get-updated-total');

Route::get('/test-cart', function() {
    if (\Illuminate\Support\Facades\Auth::check()) {
        $cart = \App\Models\Cart::where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->with('items.product')->first();
    } else {
        $cart = \App\Models\Cart::where('session_id', \Illuminate\Support\Facades\Session::getId())->with('items.product')->first();
    }
    
    return response()->json([
        'cart_exists' => !empty($cart),
        'items_count' => $cart ? $cart->items->count() : 0,
        'items' => $cart ? $cart->items->map(function($item) {
            return [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'product_type' => $item->product_type,
                'is_test_print' => $item->is_test_print
            ];
        }) : []
    ]);
});

Route::get('/more-info', [BasePagesController::class, 'moreInfo'])->name('more-info');

Route::get('/{slug?}',[BasePagesController::class,'pages']);
Route::get('{route?}/{slug?}',[BasePagesController::class,'pages']);
