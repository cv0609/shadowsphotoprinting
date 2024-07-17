<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\CartService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\OrderDetail;
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
        $zipFileName = "$order->order_number.zip";
        $zipFilePath = public_path('order_zip/'.$zipFileName);

        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($uniqueOrderDetails as $details) {
                $productId = $details->product_id;
                $quantity = $details->quantity;
                $productType = $details->product_type;

                $productDetails = $this->CartService->getProductDetailsByType($productId, $productType);

                $productTitle = isset($productDetails->product_title) ? $productDetails->product_title : '';

                $quantityFolder = "$productTitle/qty_{$quantity}/";
                $zip->addEmptyDir($quantityFolder);


                $orderDetails = OrderDetail::where('product_id', $productId)->where('order_id', $order_id)->where('quantity',$quantity)->get();

                $fileCounter = [];

                if($productType == 'shop'){
                    foreach ($orderDetails as $image) {
                        $imagePath = $image->selected_images;
                        if (file_exists($imagePath)) {
                            $baseName = pathinfo($imagePath, PATHINFO_FILENAME);
                            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
    
                            if (!isset($fileCounter[$baseName])) {
                                $fileCounter[$baseName] = 0;
                            }
    
                            $fileCounter[$baseName]++;
                            $uniqueFileName = $quantityFolder . $baseName . '_' . $fileCounter[$baseName] . '.' . $extension;
                            $zip->addFile($imagePath, $uniqueFileName);
                        }
                    }
                }

                if($productType == 'gift_card' || $productType == 'photo_for_sale'){
                    $imagePath = $productDetails->product_image;
                    if (file_exists($imagePath)) {
                        $baseName = pathinfo($imagePath, PATHINFO_FILENAME);
                        $extension = pathinfo($imagePath, PATHINFO_EXTENSION);

                        if (!isset($fileCounter[$baseName])) {
                            $fileCounter[$baseName] = 0;
                        }

                        $fileCounter[$baseName]++;
                        $uniqueFileName = $quantityFolder . $baseName . '_' . $fileCounter[$baseName] . '.' . $extension;
                        $zip->addFile($imagePath, $uniqueFileName);
                    }
                }
            }

            $zip->close();

            if (file_exists($zipFilePath)) {
                return response()->download($zipFilePath);
            } else {
                return response()->json(['message' => 'Failed to create the zip file.'], 500);
            }
        } else {
            return response()->json(['message' => 'Failed to open zip file.'], 500);
        }
    }

    









}
