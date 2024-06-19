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
      $productCategories = Coupon::get();
      return view('admin.coupons.add',compact('productCategories'));
   }

   public function couponSave(CouponRequest $request)
   {
      $validated = $request->validated();
      Coupon::create($validated);

      return view('admin.coupons.index')->with('success', 'Coupon created successfully!');
   }

   public function couponShow(Request $request, $type)
   {
      $coupons = Coupon::where('type', $type)->get();

      return view('admin.coupons.edit', compact('coupons'));
   }

}
