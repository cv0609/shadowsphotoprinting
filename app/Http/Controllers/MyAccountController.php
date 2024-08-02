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
use App\Http\Requests\AddUserAddressRequest;
use App\Http\Requests\EditUserAddressRequest;
use App\Models\Order;
use App\Models\State;
use App\Models\OrderDetail;
use App\Models\OrderBillingDetails;
use Illuminate\Support\Facades\Session;
use App\Mail\MakeOrder;
use App\Mail\AdminNotifyOrder;
use App\Models\UserDetails;
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
        return view('front-end.profile.dashboard');
    }

    public function orders(){
        $orders = Order::withCount('orderDetails')->where('user_id',Auth::user()->id)->orderBy('id','desc')->paginate(10);
        return view('front-end.profile.orders',compact('orders'));
    }

    public function downloads(){
        return view('front-end.profile.downloads');
    }

    public function address(){
        $details_check = $this->CartService->checkAuthUserAddress();
        $user_details = UserDetails::where('user_id',Auth::user()->id)->first();
        return view('front-end.profile.address',compact('details_check','user_details'));
    }

    public function payment_method(){
        return view('front-end.profile.payment-method');
    }

    public function account_details(){
        return view('front-end.profile.account-details');
    }

    public function my_coupons(){
        return view('front-end.profile.my-coupons');
    }

    public function view_order($order_id){
        $orders = Order::withCount('orderDetails','orderBillingShippingDetails')->whereId($order_id)->first();
        return view('front-end.profile.view-order',compact('orders'));
    }

    public function addAddress(){
        $countries = Country::find(14);
        return view('front-end.profile.add-address',compact('countries'));
    }

    public function editAddress($slug){
        $user_details = UserDetails::where('user_id',Auth::user()->id)->first();
        $countries = Country::find(14);
        return view('front-end.profile.edit-address',compact('user_details','countries','slug'));
    }

    public function saveAddress(AddUserAddressRequest $request){
        $auth_id = Auth::user()->id;
        $fname = $request->input('fname');
        $lname = $request->input('lname');
        $street1 = $request->input('street1');
        $street2 = $request->input('street2');
        $state = $request->input('state');
        $postcode = $request->input('postcode');
        $phone = $request->input('phone');
        $suburb = $request->input('suburb');
        $email = $request->input('email');
        $username = $request->input('username');
        $company_name = $request->input('company_name');

        $state_name = State::whereId($state)->select('name')->first();

        $address = [
            'user_id' => $auth_id,
            'fname' => $fname ?? null,
            'lname' => $lname ?? null,
            'street1' => $street1 ?? null,
            'street2' => $street2 ,
            'state' => $state_name->name ?? null,
            'company_name' => $company_name ?? null,
            'country_region' => config('constant.default_country'),
            'state' => $state_name->name ?? null,
            'postcode' => $postcode ?? null,
            'phone' => $phone ?? null,
            'suburb' => $suburb ?? null,
            'email' => $email ?? null,
            'username' => $username ?? null,
        ];

        if(isset($address) && !empty($address)){
            UserDetails::create($address);
            return redirect()->route('address')->with('success','Address added successfully.');
        }
    }

    public function editSaveAddress(EditUserAddressRequest $request){
      
        $auth_id = Auth::user()->id;

        $fname = $request->input('fname'); 
        $lname = $request->input('lname'); 
        $street1 = $request->input('street1'); 
        $street2 = $request->input('street2'); 
        $state = $request->input('state'); 
        $postcode = $request->input('postcode'); 
        $phone = $request->input('phone');
        $suburb = $request->input('suburb'); 
        $email = $request->input('email');
        $username = $request->input('username');
        $company_name = $request->input('company_name'); 

        $ship_fname = $request->input('ship_fname'); 
        $ship_lname = $request->input('ship_lname'); 
        $ship_street1 = $request->input('ship_street1'); 
        $ship_street2 = $request->input('ship_street2'); 
        $ship_state = $request->input('ship_state'); 
        $ship_company = $request->input('ship_company'); 
       
        $ship_postcode = $request->input('ship_postcode');
        $ship_suburb = $request->input('ship_suburb'); 

        $state_name = State::whereId($state)->select('name')->first();
        $ship_state_name = State::whereId($ship_state)->select('name')->first();

        if($request->slug == 'shipping'){

            $address = [
                'ship_fname' => $ship_fname ?? null,
                'ship_lname' => $ship_lname ?? null,
                'ship_street1' => $ship_street1 ?? null,
                'ship_street2' => $ship_street2 ,
                'ship_state' => $ship_state_name->name ?? null,
                'ship_company' => $ship_company ?? null,
                'ship_country_region' => config('constant.default_country'),
                'ship_postcode' => $ship_postcode ?? null,
                'isShippingAddress' => '1' ?? null,
                'ship_suburb' => $ship_suburb ?? null,
            ];
          
        }else{
            $address = [
                'fname' => $fname ?? null,
                'lname' => $lname ?? null,
                'street1' => $street1 ?? null,
                'street2' => $street2 ,
                'state' => $state_name->name ?? null,
                'company_name' => $company_name ?? null,
                'country_region' => config('constant.default_country'),
                'state' => $state_name->name ?? null,
                'postcode' => $postcode ?? null,
                'phone' => $phone ?? null,
                'suburb' => $suburb ?? null,
                'email' => $email ?? null,
                'username' => $username ?? null,
            ];
            
        }

        if(isset($address) && !empty($address)){
            UserDetails::where('user_id',$auth_id)->update($address);
        }
        return redirect()->route('address')->with('success','Address updated successfully.');
    }
}
