<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\CartService;
use Carbon\Carbon;

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
      $OrderTotal = $this->CartService->getOrderTotal($orderNumber);
      return view('admin.orders.order_details',compact('orderDetail','OrderTotal'));
    }

    public function search(Request $request)
    {
        $searchTerm  = $request->input('query');
        $startDate = ($request->input('start_date')) ? $request->input('start_date') : "";
        $endDate = ($request->input('end_date')) ? $request->input('end_date') : "";
        $orders_result = Order::query();

        if ($searchTerm) {
            $orders_result->where('order_number', 'LIKE', "%{$searchTerm}%");
        }

        if (!empty($startDate) && !empty($endDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $orders_result->whereBetween('created_at', [$startDate, $endDate]);
        }
        $orders = $orders_result->get();
        if(empty($orders))
        {
            $orders = Order::get();
        }
        echo view('admin.orders.order_search',compact('orders'));
    }
}
