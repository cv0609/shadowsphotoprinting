<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartData;
use App\Models\Country;
use App\Models\Customer as Customer_user;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\State;
use App\Models\OrderDetail;
use App\Models\OrderBillingDetails;
use Illuminate\Support\Facades\Session;
use App\Mail\MakeOrder;
use App\Mail\AdminNotifyOrder;
use Illuminate\Support\Facades\Mail;

class MyAccountController extends Controller
{
    protected $stripe;
    protected $CartService;

    public function __construct(StripeService $stripe,CartService $CartService)
    {
        $this->stripe = $stripe;
        $this->CartService = $CartService;
    }

    public function dashboard(){
        return view('front-end.dashboard');
    }
    public function orders(){
        $orders = Order::withCount('orderDetails')->where('user_id',Auth::user()->id)->orderBy('id','desc')->paginate(10);
        return view('front-end.orders',compact('orders'));
    }
    public function downloads(){
        return view('front-end.downloads');
    }
    public function address(){
        return view('front-end.address');
    }
    public function payment_method(){
        return view('front-end.payment-method');
    }
    public function account_details(){
        return view('front-end.account-details');
    }
    public function my_coupons(){
        return view('front-end.my-coupons');
    }
    public function view_order($order_id){
        $orders = Order::withCount('orderDetails','orderBillingShippingDetails')->whereId($order_id)->first();
        return view('front-end.view-order',compact('orders'));
    }
}
