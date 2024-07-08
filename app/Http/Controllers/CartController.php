<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartData;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Shipping;
use App\Models\State;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\CartService;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    protected $CartService;
    public function __construct(CartService $CartService)
    {
        $this->CartService = $CartService;
    }


    public function addToCart(Request $request)
    {
        $auth_id = '';
        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
        }

        $session_id = Session::getId();
        $cart = Cart::firstOrCreate(["user_email" => $auth_id, "coupon_id" => null, "session_id" => $session_id]);
        $insertData = [];

        if ($cart) {
            $cart_items = $request->cart_items;

            if($request->item_type == 'gift_card'){
                $giftcard['from'] = $request->from;
                $giftcard['giftcard_msg'] = $request->giftcard_msg;
                $giftcard['reciept_email'] = $request->reciept_email;
            }

            $cartId = $cart->id;

           foreach ($cart_items as $cart_item) {
                $product_id = $cart_item['product_id'];
                $quantity = $cart_item['quantity'];

                // If the product does not exist in the cart, create a new cart item
                if (isset($request->selectedImages)) {
                    foreach ($request->selectedImages as $selectedImage) {
                        $tempFileName = basename($selectedImage); // Extracts the filename from the URL/path

                        // Example: Temporary storage path
                        $tempImagePath = 'public/temp/' . $tempFileName;

                        // Example: Permanent storage path
                        $permanentImagePath = 'public/assets/images/order_images/' . $tempFileName;
                        Storage::move($tempImagePath, $permanentImagePath);
                        $ImagePath = 'storage/assets/images/order_images/' . $tempFileName;

                        // $insertData = ["cart_id" => $cartId, "product_id" => $product_id, "quantity" => $quantity,"selected_images"=>$ImagePath];

                        $insertData = [
                            "cart_id" => $cartId,
                            "product_id" => $product_id,
                            "quantity" => $quantity,
                            "selected_images" => $ImagePath,
                            "item_type" => "ddd"
                        ];
    
                        if($request->item_type == 'gift_card'){
                            $insertData = array_merge($insertData, [
                                'item_desc' => json_decode($giftcard),
                            ]);
                        }



                        $existingCartItem = CartData::where('cart_id', $cartId)
                        ->where('product_id', $product_id)
                        ->where('selected_images', $ImagePath)
                        ->first();

                        if ($existingCartItem) {
                        // If the product already exists in the cart, increase the quantity
                        $existingCartItem->quantity += $quantity;
                        $existingCartItem->save();
                        }
                        else
                         {
                            CartData::create($insertData);

                         }
                    }

                }


            }

        }
    }




    public function cart()
    {

        $session_id = Session::getId();

        $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        $countries = Country::find(14);
        $CartTotal = $this->CartService->getCartTotal();
        $shipping = $this->CartService->getShippingCharge();
        if(!empty($cart) && isset($cart->items) && !$cart->items->isEmpty())
          {

            return view('front-end.cart',compact('cart','CartTotal','shipping','countries'));
          }
          else
           {

             return redirect('shop');
           }
    }

    public function removeFromCart($product_id)
    {
        $session_id = Session::getId();
        $cart = Cart::where('session_id', $session_id)->first();

        if ($cart) {
            $CartData = CartData::where('cart_id', $cart->id)
                                ->where('product_id', $product_id)
                                ->delete();
        }
        return redirect()->route('cart')->with('success','Item removed from cart');
    }

    public function applyCoupon(Request $request)
     {
       $coupon = Coupon::where('code', $request->coupon_code)->first();
        $total = $this->CartService->getCartTotal();

        // $product = Product::find($request->product_id);
        // $productCategories = $product->categories->pluck('id')->toArray();

        if (!$coupon) {
            return ['success' => false, 'message' => 'Coupon does not exist'];
        }

        if ($coupon->isExpired()) {
            return ['success' => false, 'message' => 'Coupon has expired'];
        }

        $session_id = Session::getId();
        $cart = Cart::where('session_id', $session_id)->with('items.product')->first();

        if (!$cart) {
            return ['success' => false, 'message' => 'Cart is empty'];
        }

        if ($total['subtotal'] < $coupon->minimum_cart_total) {
            return ['success' => false, 'message' => 'Cart total is less than the minimum required to apply this coupon'];
        }

        if($total['subtotal'] < $coupon->minimum_spend || $total['subtotal'] > $coupon->maximum_spend)
        {
            return ['success' => false, 'message' => 'you can use this coupon between '.$coupon->minimum_spend.' To '.$coupon->maximum_spend.'amount' ];
        }

        if ($coupon->use_limit && $coupon->total_use >= $coupon->use_limit) {
            return ['success' => false, 'message' => 'This coupon has reached its usage limit.' ];
        }

        // if (!empty($coupon->categories) && !array_intersect($productCategories, explode(',',$coupon->categories))) {
        //     return back()->withErrors(['code' => 'This coupon is not applicable to the selected product\'s category.']);
        // }

        // if (!empty($coupon->products) && !in_array($product->id, explode(',',$coupon->products))) {
        //     return back()->withErrors(['code' => 'This coupon is not applicable to the selected product.']);
        // }

        $amount = 0;
        if($coupon->type == "0")
          {
           $amount = $coupon->amount;

          }
          elseif($coupon->type == "1")
           {
              $amount = ($coupon->amount / 100) * $total;

           }
        $coupon->used++;
        $coupon->save();

        Session::put('coupon', [
            'code' => $coupon->code,
            'discount_amount' => $amount,
        ]);
        return ['success' => true, 'total' => $total['subtotal'] - $coupon->discount_amount];



    }

   public function billingDetails(Request $request)
    {

        $state_name = State::whereId($request->state)->select('name')->first();
        $session_data = ['country'=>$request->country,'state'=>$state_name['name'],'state_id'=>$request->state, 'city'=>$request->city, 'postcode'=>$request->postcode];
        Session::put('billing_details', $session_data);
        return  redirect('cart');
    }

    public function updateCart(Request $request)
    {
        foreach($request->data as $data)
        {
            CartData::whereId($data['rowId'])->update(['quantity'=>$data['quantity']]);

        }
        session()->flash('success', 'Cart updated successfully.');
    }

    public function getCartCount(Request $request)
    {
        $session_id = Session::getId();

        // Fetch the cart with items
        $cart = Cart::where('session_id', $session_id)->with('items')->first();

        // Calculate the total count of items in the cart
        $itemCount = 0;
        if ($cart) {
            $itemCount = $cart->items->sum('quantity');
        }

        return $itemCount;
    }
}
