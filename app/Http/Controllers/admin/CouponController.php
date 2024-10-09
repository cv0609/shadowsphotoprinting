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
      $coupons = Coupon::paginate();
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

      if ($request->coupon_type == '0' && $request->minimum_spend <= $request->amount) {
        return redirect()->back()->withInput()->with(['minimum_amount' => 'Minimum amount must be greater than the discount amount.']);
      }

      if ($request->coupon_type == '1') {
         if($request->amount > 100){
            return redirect()->back()->withInput()->with(['percent_error' => 'Percentage cannot be greater than 100.']);
         }
      }

      Coupon::create(['code'=>$request->code,'type'=>$request->coupon_type,'amount'=>$request->amount,'minimum_spend'=>$request->minimum_spend,'maximum_spend'=>$request->maximum_spend,'start_date'=>$request->start_date,'end_date'=>$request->end_date,'products'=>$productsId,'product_category'=>$product_category,'auto_applied'=>$request->auto_applied,'use_limit'=>$request->use_limit]);
      return redirect()->route('coupons-list')->with('success', 'Coupon created successfully!');
   }

   public function couponShow($id)
   {
      $products = Product::get();
      $coupon_detail = Coupon::whereId($id)->first();
      $ProductCategory = ProductCategory::get();

      // dd($coupon_detail);

      return view('admin.coupons.edit', compact('coupon_detail','products','ProductCategory'));
   }

   public function couponUpdate(Request $request)
   {
      // dd($request->all());
      $productsId =(isset($request->products) && !empty($request->products)) ? implode(',',$request->products) : null;
      $product_category =(isset($request->product_category) && !empty($request->product_category)) ? implode(',',$request->product_category) : null;

      if ($request->coupon_type == '0' && $request->minimum_spend <= $request->amount) {
        return redirect()->back()->with(['minimum_amount' => 'Minimum amount must be greater than the discount amount.']);
      }

      if ($request->coupon_type == '1') {
         if($request->amount > 100){
            return redirect()->back()->with(['percent_error' => 'Percentage cannot be greater than 100.']);
         }
      }

      Coupon::whereId($request->coupon_id)->update(['code'=>$request->code,'type'=>$request->coupon_type,'amount'=>$request->amount,'minimum_spend'=>$request->minimum_spend,'maximum_spend'=>$request->maximum_spend,'start_date'=>$request->start_date,'end_date'=>$request->end_date,'products'=>$productsId,'auto_applied'=>$request->auto_applied,'use_limit'=>$request->use_limit,'product_category'=>$product_category]);
      return redirect()->route('coupons-list')->with('success', 'Coupon updated successfully!');
   }

   public function couponDistroy($id)
   {
      $coupon_detail = Coupon::whereId($id)->delete();
      return redirect()->route('coupons-list')->with('success','Coupon is deleted successfully');
   }

   public function couponUpdateStatus(Request $request){
      Coupon::where('id', $request->coupon_id)->update(['is_active' => $request->checkedValue]);
      return response()->json(['error' => false, 'message' => 'Status updated successfully.','checked' =>$request->checkedValue]);
   }
}
