<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingRequest;
use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function shipping()
    {
        $shippings = Shipping::paginate(10);
        return view('admin.Shipping.index',compact('shippings'));
    }

    public function shippingAdd()
    {
        return view('admin.Shipping.add');
    }

    public function shippingSave(ShippingRequest $request)
    {
        Shipping::create(['country'=>$request->country,'shipping_method'=>$request->shipping_method,'amount'=>$request->amount,]);
        return redirect()->route('shipping-list')->with('success', 'Shipping Add successfully!');
    }

    public function shippingShow($id)
    {
        $shipping = Shipping::whereId($id)->first();
        return view('admin.Shipping.edit',compact('shipping'));
    }

    public function shippingUpdate(Request $request)
    {
        Shipping::whereId($request->shipping_id)->update(['shipping_method'=>$request->shipping_method,'amount'=>$request->amount,'status'=>$request->status]);
        return redirect()->route('shipping-list')->with('success', 'Shipping updated successfully!');
    }

    public function updateStatus(Request $request)
      {
        Shipping::whereId($request->shipping_id)->update(["status"=>$request->status]);
      }
}
