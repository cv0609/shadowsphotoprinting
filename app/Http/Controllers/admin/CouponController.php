<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductCategory;

class CouponController extends Controller
{
   public function coupons()
   {
      $coupons = Coupon::paginate('10');
      return view('admin.coupons.index',compact('coupons'));
   }

   public function couponAdd()
   {
      $products = Product::get();
      $ProductCategory = ProductCategory::get();
      return view('admin.coupons.add',compact('products','ProductCategory'));
   }

   public function couponSave(CouponRequest $request)
   {
      $productsId =(isset($request->products) && !empty($request->products)) ? implode(',',$request->products) : null;
      $product_category =(isset($request->product_category) && !empty($request->product_category)) ? implode(',',$request->product_category) : null;

      Coupon::create(['code'=>$request->code,'type'=>$request->coupon_type,'amount'=>$request->amount,'minimum_spend'=>$request->minimum_spend,'maximum_spend'=>$request->maximum_spend,'start_date'=>$request->start_date,'end_date'=>$request->end_date,'products'=>$productsId,'product_category'=>$product_category,'auto_applied'=>$request->auto_applied,'use_limit'=>$request->use_limit]);
      return redirect()->route('coupons-list')->with('success', 'Coupon created successfully!');
   }

   public function couponShow($id)
   {
      $products = Product::get();
      $coupon_detail = Coupon::whereId($id)->first();
      $ProductCategory = ProductCategory::get();

      return view('admin.coupons.edit', compact('coupon_detail','products','ProductCategory'));
   }

   public function couponUpdate(Request $request)
   {
      $productsId =(isset($request->products) && !empty($request->products)) ? implode(',',$request->products) : null;
      $product_category =(isset($request->product_category) && !empty($request->product_category)) ? implode(',',$request->product_category) : null;

      Coupon::whereId($request->coupon_id)->update(['code'=>$request->code,'type'=>$request->coupon_type,'amount'=>$request->amount,'minimum_spend'=>$request->minimum_spend,'maximum_spend'=>$request->maximum_spend,'start_date'=>$request->start_date,'end_date'=>$request->end_date,'products'=>$productsId,'auto_applied'=>$request->auto_applied,'use_limit'=>$request->use_limit,'product_category'=>$product_category]);
      return redirect()->route('coupons-list')->with('success', 'Coupon updated successfully!');
   }

   public function couponDistroy($id)
   {
      $coupon_detail = Coupon::whereId($id)->delete();
      return redirect()->route('coupons-list')->with('success','Coupon is deleted successfully');
   }
}
