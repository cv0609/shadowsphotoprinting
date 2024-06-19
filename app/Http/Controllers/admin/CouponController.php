<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
   public function coupons()
   {
      $coupons = Coupon::paginate('10');
      return view('admin.coupons.index',compact('coupons'));
   }

   public function couponAdd()
   {
      return view('admin.coupons.add');
   }

   public function couponSave(CouponRequest $request)
   {
      Coupon::create(['code'=>$request->code,'type'=>$request->coupon_type,'amount'=>$request->coupon_type,'minimum_spend'=>$request->minimum_spend,'maximum_spend'=>$request->maximum_spend,'start_date'=>$request->start_date,'end_date'=>$request->end_date]);
      return view('admin.coupons.index')->with('success', 'Coupon created successfully!');
   }

   public function couponShow(Request $request, $type)
   {
      $coupons = Coupon::where('type', $type)->get();

      return view('admin.coupons.edit', compact('coupons'));
   }

}
