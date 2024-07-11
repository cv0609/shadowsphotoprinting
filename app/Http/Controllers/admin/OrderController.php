<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\CartService;

class OrderController extends Controller
{
    protected $CartService;
    public function __construct(CartService $CartService)
    {
        $this->CartService = $CartService;
    }

    public function index()
    {
        $orders = Order::get();
        return view('admin.orders.index',compact('orders'));
    }

    public function orderDetail($orderNumber)
    {
      $orderDetail = Order::where(['order_number'=>$orderNumber])->with('orderDetails','OrderBillingDetail')->first();
      //dd($orderDetail->OrderBillingDetail);
      $OrderTotal = $this->CartService->getOrderTotal($orderNumber);
      return view('admin.orders.order_details',compact('orderDetail','OrderTotal'));
    }
}
