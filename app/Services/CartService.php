<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\GiftCardCategory;
use App\Models\product_sale;
use App\Models\PhotoForSaleProduct;
use App\Models\HandCraftProduct;
use App\Models\UserDetails;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class CartService
{
    public function getCartTotal()
    {
        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
        } else {
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        }

        $data = [];

        if (!$cart) {
            return 0;
        }


        $subtotal = $cart->items->reduce(function ($carry, $item) {

            if ($item->product_type == 'gift_card' || $item->product_type == 'photo_for_sale' || $item->product_type == 'hand_craft') {
                $product_price = $item->product_price;
            } else {

                $currentDate = Carbon::now();
                $currentDate = date('Y-m-d', strtotime($currentDate->toDateTimeString()));

                $sale_price = product_sale::where('sale_start_date', '<=', $currentDate)->where('sale_end_date', '>=', $currentDate)->where('product_id', $item->product_id)->first();

                if (!empty($item->is_test_print) && $item->is_test_print == '1') {
                    $product_price =  $item->test_print_price;
                } else {
                    if (isset($sale_price) && !empty($sale_price)) {
                        $product_price = $sale_price->sale_price;
                    } else {
                        $product_price = $item->product->product_price;
                    }
                }
            }
            return $carry + ($product_price * $item->quantity);
        }, 0);

        $couponCode = Session::get('coupon'); // Assume coupon code is stored in session
        $discount = 0;
        $coupon_code = "";
        $coupon_id = "";
        if ($couponCode) {
            $coupon = Coupon::where(['code' => $couponCode['code']])->where('is_active', true)->first();

            $coupon_code = $couponCode;
            if ($coupon) {
                if ($coupon->type == '1') {
                    $discount = ($subtotal * $coupon->amount) / 100;
                } elseif ($coupon->type == '0') {
                    $discount = $coupon->amount;
                }
                // Ensure the discount does not exceed the total
                $discount = min($discount, $subtotal);
                $coupon_id = $coupon->id;
            }
        }

        // Calculate the total after applying the discount
        $totalAfterDiscount = $subtotal - $discount;

        $shipping = $this->getShippingCharge();

        // if($shipping->status == "1" && Session::has('billing_details')){
        //     $shippingCharge = $shipping->amount; // Example shipping charge
        // }
        // else
        // {
        // $shippingCharge = 0;
        // }
        $shippingCharge = 0;

        $totalAfterShipping = $totalAfterDiscount + $shippingCharge;

        $data = ['subtotal' => $subtotal, 'total' => $totalAfterShipping, 'coupon_discount' => $discount, "coupon_code" => $coupon_code, 'coupon_id' => $coupon_id, "shippingCharge" => $shippingCharge];
        return $data;
    }

    public function getProductDetailsByType($product_id, $product_type)
    {
        switch ($product_type) {
            case 'shop':
                $product = Product::whereId($product_id)->first();
                break;
            case 'gift_card':
                $product = GiftCardCategory::whereId($product_id)->first();
                break;
            case 'photo_for_sale':
                $product = PhotoForSaleProduct::whereId($product_id)->first();
                break;
            case 'hand_craft':
                $product = HandCraftProduct::whereId($product_id)->first();
                break;
            default:
                $product = null;
                break;
        }
        return $product;
    }


    public function getProductSalePrice($product_id)
    {
        $currentDate = Carbon::now();
        $currentDate = date('Y-m-d', strtotime($currentDate->toDateTimeString()));
        $product_price = null;

        $sale_price = product_sale::where('sale_start_date', '<=', $currentDate)->where('sale_end_date', '>=', $currentDate)->where('product_id', $product_id)->first();

        if (isset($sale_price) && !empty($sale_price)) {
            $product_price = $sale_price->sale_price;
        }
        return $product_price;
    }


    public function getShippingCharge()
    {
        $shipping = Shipping::first();
        return $shipping;
    }

    public function getTestPrintShippingCharge()
    {
        $shipping = Shipping::where('is_test_print', '1')->first();
        return $shipping;
    }


    public function getOrderTotal($OrderNumber)
    {

        $order = Order::where(["order_number" => $OrderNumber])->with('orderDetails.product')->first();
        $data = [];

        if (!$order) {
            return 0;
        }

        $subtotal = $order->orderDetails->reduce(function ($carry, $item) {

            if ($item->product_type == 'gift_card' || $item->product_type == 'photo_for_sale' || $item->product_type == 'hand_craft') {
                $product_price = $item->product_price;
            } else {

                $currentDate = Carbon::now();
                $currentDate = date('Y-m-d', strtotime($currentDate->toDateTimeString()));

                $sale_price = product_sale::where('sale_start_date', '<=', $currentDate)->where('sale_end_date', '>=', $currentDate)->where('product_id', $item->product_id)->first();

                if (isset($sale_price) && !empty($sale_price)) {
                    $product_price = $sale_price->sale_price;
                } else {
                    $product_price = $item->product->product_price;
                }
            }
            return $carry + ($product_price * $item->quantity);
        }, 0);

        $couponCode = $order->coupon_code;
        $discount = 0;
        $coupon_code = "";
        $coupon_id = "";
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
            $coupon_code = $couponCode;
            if ($coupon) {
                if ($coupon->type == '1') {
                    $discount = ($subtotal * $coupon->amount) / 100;
                } elseif ($coupon->type == '0') {
                    $discount = $coupon->amount;
                }
                // Ensure the discount does not exceed the total
                $discount = min($discount, $subtotal);
                $coupon_id = $coupon->id;
            }
        }

        // Calculate the total after applying the discount
        $totalAfterDiscount = $subtotal - $discount;
        $shipping = $this->getShippingCharge();

        if ($shipping->status == "1" && isset($order->shipping_charge) && !empty($order->shipping_charge)) {
            $shippingCharge = $order->shipping_charge; // Example shipping charge
        } else {
            $shippingCharge = 0;
        }

        $totalAfterShipping = $totalAfterDiscount + $shippingCharge;
        $data = ['subtotal' => $subtotal, 'total' => $totalAfterShipping, 'coupon_discount' => $discount, "coupon_code" => $coupon_code, 'coupon_id' => $coupon_id, "shippingCharge" => $shippingCharge];
        return $data;
    }

    public function autoAppliedCoupon($productId = null, $productCategoryId = null, $cartCount = null)
    {
        $CartTotal = $this->getCartTotal();
        $currentDate = Carbon::now()->toDateString();
        $cartCount = (int)$cartCount;

        // Fetch the applicable coupon
        $coupon = Coupon::where('is_active', '1')
            ->where('auto_applied', '1')
            ->where('start_date', '<=', $currentDate)
            ->where('qty', '<=', $cartCount)
            ->where('end_date', '>=', $currentDate)
            ->where(function ($query) use ($CartTotal) {
                $query->where('minimum_spend', '<=', $CartTotal['subtotal'])
                      ->orWhere('minimum_spend', 0.00); // Include 0.00 as valid
            })
            ->where(function ($query) use ($CartTotal) {
                $query->where('maximum_spend', '>=', $CartTotal['subtotal'])
                      ->orWhere('maximum_spend', 0.00); // Include 0.00 as valid
            })
            ->where(function ($query) use ($productCategoryId) {
                $query->whereNull('product_category')
                    ->orWhereRaw('FIND_IN_SET(?, product_category)', [$productCategoryId]);
            })
            ->where(function ($query) use ($productId) {
                $query->whereNull('products')
                    ->orWhereRaw('FIND_IN_SET(?, products)', [$productId]);
            })
            ->first();

        // If a coupon is found, calculate the discount
        if ($coupon) {
            $amount = 0;

            // Calculate discount based on type
            if ($coupon->type == "0") {
                $amount = $coupon->amount; // Fixed amount discount
            } elseif ($coupon->type == "1") {
                $amount = ($coupon->amount / 100) * $CartTotal['subtotal']; // Percentage discount
            }
            // Increment usage count if maximum usage is not exceeded
            if ($coupon->use_limit === null || $coupon->used < $coupon->use_limit) {
                // \Log::info($coupon->used);    
                // \Log::info($coupon->use_limit);    
                $coupon->used++;
                $coupon->save();

                // Store coupon details in the session
                Session::put('coupon', [
                    'code' => $coupon->code,
                    'discount_amount' => $amount,
                ]);

                return true; // Coupon successfully applied
            } else {
                // Handle case when coupon usage limit is reached
                Session::forget('coupon');
                return false;
            }
        }

        // No coupon found, clear session
        Session::forget('coupon');
        return false;
    }



    public function checkAuthUserAddress()
    {
        $user_details = UserDetails::where('user_id', Auth::user()->id)->first();
        if (isset($user_details) && !empty($user_details)) {
            return true;
        } else {
            return false;
        }
    }

    public function getProductStock($slug, $product_id)
    {
        $category_data = ProductCategory::where('slug', $slug)->first();

        if ($category_data) {
            $category_data->load(['getProductStock' => function ($query) use ($product_id) {
                $query->where('product_id', $product_id);
            }]);
        }
        if ($category_data) {
            return $category_data;
        } else {
            return [];
        }
    }

    public function addWaterMark($wtimagePath, $tempFileName)
    {
        $watermarkPath = public_path('assets/images/order_images/watermark.png');

        $img = Image::make($wtimagePath);

        $width = $img->width();
        $height = $img->height();

        $watermark = Image::make($watermarkPath)->resize($width, $height);

        $img->insert($watermark, 'top-left');

        $outputDir = public_path('assets/images/watermark');

        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $outputImageName = 'watermarked_' . uniqid() . '_' . pathinfo($tempFileName, PATHINFO_FILENAME) . '.jpg';
        $outputImagePath = $outputDir . '/' . $outputImageName;

        $img->save($outputImagePath);

        $wtrelativeImagePath = 'assets/images/watermark/' . $outputImageName;

        return $wtrelativeImagePath;
    }
}
