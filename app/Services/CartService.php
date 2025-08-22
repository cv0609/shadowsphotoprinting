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
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CartService
{
    public function getCartTotal()
    {
        $affiliate_sales = null;

        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();

            if(Auth::user()->role === 'affiliate'){
                $affiliate_id = Auth::user()->affiliate->id;
                $affiliate_sales = \App\Models\AffiliateSale::getTotalsForAffiliate($affiliate_id);
            }

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
        
        $shippingCharge = 0;
        // Calculate the total after applying the discount
        $shippingCharge = $this->getShippingCharges($cart);

        $couponCode = Session::get('coupon'); // Assume coupon code is stored in session
        $discount = 0;
        $coupon_code = "";
        $coupon_id = "";
        $totalAfterDiscount = $subtotal;
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
                
                $discount = min($discount, $subtotal); // Ensure discount is not more than subtotal
                $coupon_id = $coupon->id;

                if($coupon->is_gift_card != '1'){
                    $totalAfterDiscount = $subtotal - $discount;
                }else{
                    $totalAfterDiscount = max(0, $subtotal - $discount); // Ensure total is never negative
                }
            }
        }

        if($cart->shutter_point == '1'){
            $totalAfterDiscount = max(0, $totalAfterDiscount - $affiliate_sales->total_commission); // Ensure total is never negative
        }



        $order_type = 0;
        if(Session::has('order_type')){
            $order_type = Session::get('order_type');
            if($order_type != 0){
                $shippingCharge = 0;
            }
        }

        $totalAfterShipping = $totalAfterDiscount + $shippingCharge;

        $data = ['subtotal' => $subtotal, 'total' => $totalAfterShipping, 'coupon_discount' => $discount, "coupon_code" => $coupon_code, 'coupon_id' => $coupon_id, "shippingCharge" => $shippingCharge];
        \Log::info('$data',$data);
        return $data;
    }

    public function getShippingCharges($cart){
        // Check if user has selected shipping from session
        $selectedShipping = session('selected_shipping');

        
        if ($selectedShipping) {
            // Return the selected shipping price from session
            return (float) $selectedShipping['price'];
        }
        
        // Check for test print items first
        // $hasTestPrint = false;
        // $hasRegularPrint = false;

        // foreach ($cart->items as $items) {
        //     if ($items->is_test_print == '1') {
        //         $hasTestPrint = true;
        //     }

        //     if ($items->is_test_print == '0') {
        //         $hasRegularPrint = true;
        //     }

        //     if ($hasTestPrint && $hasRegularPrint) {
        //         break;
        //     }
        // }

        // // If there are test print items, use the old test print shipping logic
        // if ($hasTestPrint) {
        //     $shipping_with_test_print = 0;
        //     $testPrintShipping = $this->getTestPrintShippingCharge()->amount;

        //     if ($hasTestPrint && $hasRegularPrint) {
        //         $shipping_with_test_print += $testPrintShipping + $this->getShippingCharge()->amount;
        //     } elseif ($hasTestPrint) {
        //         $shipping_with_test_print += $testPrintShipping;
        //     } elseif ($hasRegularPrint) {
        //         $shipping_with_test_print += $this->getShippingCharge()->amount;
        //     }
        //     return $shipping_with_test_print;
        // }

        // If no test print items, use the new dynamic shipping calculation
        $cartShippingService = new CartShippingService();
        
        // Convert cart items to the format expected by CartShippingService
        $cartItems = [];
        foreach ($cart->items as $item) {
            $cartItems[] = [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'product_type' => $item->product_type,
                'is_test_print' => $item->is_test_print // Add this field
            ];
        }
        
        // Log cart items being sent to CartShippingService
        Log::info('Cart items being sent to CartShippingService:', [
            'cart_items' => $cartItems
        ]);
        
        // Get shipping options from CartShippingService
        $shippingOptions = $cartShippingService->calculateShipping($cartItems);
        
        // Log shipping options for debugging
        Log::info('Shipping options from CartShippingService:', [
            'shipping_options' => $shippingOptions,
            'cart_items_count' => count($cartItems)
        ]);
        
        // Auto-select the appropriate shipping option
        $selectedShipping = null;
        
        // Auto-select Australia Post snail_mail if available
        $auspostSnailOption = collect($shippingOptions)->first(function($option) {
            return $option['carrier'] === 'auspost' && $option['service'] === 'snail_mail';
        });
        
        if ($auspostSnailOption) {
            $selectedShipping = $auspostSnailOption;
        } else {
            // Fallback: Select first option
            $selectedShipping = $shippingOptions[0] ?? null;
        }
        
        // Store the auto-selected shipping in session
        if ($selectedShipping) {
            session([
                'selected_shipping' => [
                    'carrier' => $selectedShipping['carrier'],
                    'service' => $selectedShipping['service'],
                    'price' => (float) $selectedShipping['price']
                ]
            ]);
            return (float) $selectedShipping['price'];
        }
        
        // Return 0 if no shipping options are available
        return 0;
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
        $currentDate = Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d');
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
            ->orderByDesc('qty')
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
                // $coupon->used++;
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

    
    public function addWaterMark($url)
    {
        // Extract the filename
        $fileName = basename($url);

        // Define paths
        $testPrintPath = public_path('testprint/');
        $watermarkPath = public_path('assets/images/order_images/watermark.png');

        // Ensure the testprint directory exists
        if (!file_exists($testPrintPath)) {
            mkdir($testPrintPath, 0777, true);
        }

        // Download the image
        $imagePath = $testPrintPath . $fileName;
        
        // Create stream context to disable SSL verification (for development)
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        
        file_put_contents($imagePath, file_get_contents($url, false, $context));

        // Load the image with Intervention Image
        $img = Image::make($imagePath);

        // Load and resize watermark
        $watermark = Image::make($watermarkPath)->resize($img->width(), $img->height());

        // Apply watermark
        $img->insert($watermark, 'top-left');

        // Generate new filename
        $outputImageName = 'watermarked_' . uniqid() . '_' . pathinfo($fileName, PATHINFO_FILENAME) . '.jpg';
        $outputImagePath = $testPrintPath . $outputImageName;

        // Save the watermarked image
        $img->save($outputImagePath);

        // Return the relative path for database storage
        return 'testprint/' . $outputImageName;
    }
}
