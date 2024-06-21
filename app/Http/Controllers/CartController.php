<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
       $cart = Cart::create(["user_id"=>"","coupon_id"=>null]);

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
}
