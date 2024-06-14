<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
   public function coupons()
    {
       $coupons = Coupon::paginate('10');
       return view('admin.coupons.index',compact('coupons'));
    }
}
