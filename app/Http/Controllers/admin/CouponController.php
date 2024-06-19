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
      return redirect()->route('coupons-list')->with('success', 'Coupon created successfully!');
   }

   public function couponShow($id)
   {
      $coupon_detail = Coupon::whereId($id)->first();
      return view('admin.coupons.edit', compact('coupon_detail'));
   }

   public function couponUpdate(Request $request)
   {
      Coupon::whereId($request->coupon_id)->update(['code'=>$request->code,'type'=>$request->coupon_type,'amount'=>$request->coupon_type,'minimum_spend'=>$request->minimum_spend,'maximum_spend'=>$request->maximum_spend,'start_date'=>$request->start_date,'end_date'=>$request->end_date]);
      return redirect()->route('coupons-list')->with('success', 'Coupon updated successfully!');
   }

   public function couponDistroy($id)
   {
      $coupon_detail = Coupon::whereId($id)->delete();
      return redirect()->route('coupons-list')->with('success','Card is deleted successfully');
   }
}
