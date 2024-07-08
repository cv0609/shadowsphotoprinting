<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\CartService;

class OrderController extends Controller
{
    private $CartService;
    public function __construct(CartService $CartService)
    {
         $this->CartService = $CartService;
    }
    public function index()
    {
        $orders = Order::get();
        return view('admin.orders.index',compact('orders'));
    }
}
