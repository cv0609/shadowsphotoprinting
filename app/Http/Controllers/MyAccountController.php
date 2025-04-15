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
use App\Mail\ForgotPasswordMail;
use App\Mail\AdminNotifyOrder;
use App\Models\UserDetails;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Blog;


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

        $page_content = ["meta_title"=>config('constant.account.dashboard.meta_title'),"meta_description"=>config('constant.account.dashboard.meta_description')];
                
        return view('front-end.profile.dashboard',compact('page_content'));
    }

    public function orders(){

        $page_content = ["meta_title"=>config('constant.account.orders.meta_title'),"meta_description"=>config('constant.account.orders.meta_description')];

        $orders = Order::withCount('orderDetails')->where('user_id',Auth::user()->id)->orderBy('id','desc')->paginate(10);
        return view('front-end.profile.orders',compact('orders','page_content'));
    }

    public function downloads(){
        return view('front-end.profile.downloads');
    }

    public function address(){
        $page_content = ["meta_title"=>config('constant.account.address.meta_title'),"meta_description"=>config('constant.account.address.meta_description')];

        $details_check = $this->CartService->checkAuthUserAddress();
        $user_details = UserDetails::where('user_id',Auth::user()->id)->first();
        return view('front-end.profile.address',compact('details_check','user_details','page_content'));
    }

    public function payment_method(){

        $page_content = ["meta_title"=>config('constant.account.payment_method.meta_title'),"meta_description"=>config('constant.account.payment_method.meta_description')];

        return view('front-end.profile.payment-method',compact('page_content'));
    }

    public function account_details(){
        $user = User::whereId(Auth::user()->id)->first();

        $page_content = ["meta_title"=>config('constant.account.account_details.meta_title'),"meta_description"=>config('constant.account.account_details.meta_description')];

        return view('front-end.profile.account-details',compact('user','page_content'));
    }

    public function my_coupons(){

        $page_content = ["meta_title"=>config('constant.account.my_coupons.meta_title'),"meta_description"=>config('constant.account.my_coupons.meta_description')];


        return view('front-end.profile.my-coupons',compact('page_content'));
    }

    public function view_order($order_id){
        $page_content = ["meta_title"=>config('constant.account.view_order.meta_title'),"meta_description"=>config('constant.account.view_order.meta_description')];

        $orders = Order::withCount('orderDetails','orderBillingShippingDetails')->whereId($order_id)->first();
        return view('front-end.profile.view-order',compact('orders','page_content'));
    }

    public function addAddress($slug){

        $page_content = ["meta_title"=>config('constant.account.addAddress.meta_title'),"meta_description"=>config('constant.account.addAddress.meta_description')];

        $countries = Country::find(14);
        return view('front-end.profile.add-address',compact('countries','slug','page_content'));
    }

    public function editAddress($slug){

        $page_content = ["meta_title"=>config('constant.account.editAddress.meta_title'),"meta_description"=>config('constant.account.editAddress.meta_description')];

        $user_details = UserDetails::where('user_id',Auth::user()->id)->first();
        $countries = Country::find(14);
        return view('front-end.profile.edit-address',compact('user_details','countries','slug','page_content'));
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

        $address = [
            'user_id' => $auth_id,
            'fname' => $fname ?? null,
            'lname' => $lname ?? null,
            'street1' => $street1 ?? null,
            'street2' => $street2 ,
            'state' => $state_name->name ?? null,
            'company_name' => $company_name ?? null,
            'country_region' => $request->slug == 'billing' ? config('constant.default_country') : '',
            'state' => $state_name->name ?? null,
            'postcode' => $postcode ?? null,
            'phone' => $phone ?? null,
            'suburb' => $suburb ?? null,
            'email' => $email ?? null,
            'username' => $username ?? null,

            'ship_fname' => $ship_fname ?? null,
            'ship_lname' => $ship_lname ?? null,
            'ship_street1' => $ship_street1 ?? null,
            'ship_street2' => $ship_street2 ,
            'ship_state' => $ship_state_name->name ?? null,
            'ship_company' => $ship_company ?? null,
            'ship_country_region' => $request->slug == 'shipping' ? config('constant.default_country') : '',
            'ship_postcode' => $ship_postcode ?? null,
            'isShippingAddress' => '1' ?? null,
            'ship_suburb' => $ship_suburb ?? null,
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

    public function saveAccountDetails(Request $request)
    {
        $request->validate([
            'username' => 'required|max:50',
            'email' => 'required|max:100|email',
            'password' => 'nullable|min:6|confirmed',
            'current_password' => 'required_with:password',
        ]);
    
        $oldPassword = $request->input('current_password');
        $data = [
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ];
    
        if ($oldPassword) {
            if (!Hash::check($oldPassword, Auth::user()->password)) {
                return back()->withErrors(['current_password' => 'The provided password does not match our records.']);
            }
        }
    
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
    
        User::whereId(Auth::id())->update($data);
    
        return redirect()->back()->with('success', 'Account details updated successfully.');
    }    

    public function updateProfilePic(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'assets/images/profile/'.$filename;
            $file->move(public_path('assets/images/profile'), $filename);
            $user->image = $path;
            $user->save();
        }

        return back()->with('success', 'Profile picture updated successfully.');
    }
}
