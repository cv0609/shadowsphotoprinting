<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\CartService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

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

    public function downloadOrderzip($orderId)
    {
        $order_id = $orderId;

        $order = Order::with('orderDetails')->where('id', $order_id)->first();

        $uniqueOrderDetails = [];

        $orderDetailsGrouped = $order->orderDetails->groupBy('product_id');

        foreach ($orderDetailsGrouped as $productId => $details) {
            $quantities = $details->pluck('quantity')->unique();
            if ($quantities->count() === 1) {
                $uniqueOrderDetails[] = $details->first();
            } else {
                foreach ($details as $detail) {
                    $uniqueOrderDetails[] = $detail;
                }
            }
        }

        $zip = new ZipArchive();
        $zipFileName = "order_{$order->order_number}.zip";
        $zipFilePath = public_path('order_zip/'.$zipFileName); // Specify the path to save the zip file

        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($uniqueOrderDetails as $details) {
                $productId = $details->product_id;
                $quantity = $details->quantity;

                // Create folder structure: product_id/quantity/
                $quantityFolder = "$productId/quantity_{$quantity}/";
                $zip->addEmptyDir($quantityFolder); // Create the quantity folder

                // Hard-coded image path
                $hardCodedImagePath = public_path('assets/admin/images/1718088597.jpg'); // Adjust based on your actual structure



                if (file_exists($hardCodedImagePath)) {

                    $zip->addFile($hardCodedImagePath, $quantityFolder . basename($hardCodedImagePath)); // Add to the quantity folder
                } else {
                    dump("File does not exist: " . $hardCodedImagePath); // Debugging
                }
            }

            $zip->close();

            if (file_exists($zipFilePath)) {
                return response()->download($zipFilePath);
            } else {
                return response()->json(['message' => 'Failed to create the zip file.'], 500);
            }
        }


    }







}
