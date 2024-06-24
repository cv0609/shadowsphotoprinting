<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\CartService;
use Session;

class CartController extends Controller
{
    protected $CartService;
    public function __construct(CartService $CartService)
    {
        $this->CartService = $CartService;
    }
    public function addToCart(Request $request)
    {
       $session_id = Session::getId();
       $cart = Cart::firstOrCreate(["user_email"=>"","coupon_id"=>null,"session_id"=>$session_id]);

       if($cart)
         {
            $cart_items = $request->cart_items;
            $cartId = $cart->id;
            foreach($cart_items as $cart_item)
              {
                $Images = [];
                $data = ["cart_id"=>$cartId,"product_id"=>$cart_item['product_id'],"quantity"=>$cart_item['quantity']];
                if(isset($request->selectedImages))
                 {
                    foreach($request->selectedImages as $selectedImages)
                    {
                        $tempImagePath = $selectedImages;
                        $permanentImagePath = '/assets/images/order_images/' . basename($tempImagePath);

                        // Move the image from temp to permanent storage
                        Storage::disk('public')->move($tempImagePath, $permanentImagePath);
                        $Images[] = $permanentImagePath;

                    }

                    $data['selected_images'] =  implode(',',$Images);

                 }

                CartData::insert($data);
              }
         }
    }

    public function cart()
    {
        $session_id = Session::getId();
        $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        $total = $this->CartService->getCartTotal();
        return view('front-end.cart',compact('cart','total'));
    }

    public function removeFromCart(Request $request)
    {
        $session_id = Session::getId();
        $cart = Cart::where('session_id', $session_id)->first();

        if ($cart) {
            $cartItem = CartItem::where('cart_id', $cart->id)
                                ->where('product_id', $request->product_id)
                                ->delete();
        }

        return response()->json(['message' => 'Item removed from cart']);
    }
}
