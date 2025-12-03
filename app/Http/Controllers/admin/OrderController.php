<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\AfterPayLogs;
use App\Services\CartService;
use App\Services\StripeService;
use App\Services\AfterPayService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\OrderDetail;
use App\Models\OrderBillingDetails;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\Order\CancelOrder;
use App\Mail\Order\RefundOrder;
use App\Mail\Order\OnHoldOrder;
use App\Mail\Order\CompleteOrder;

use ZipArchive;

class OrderController extends Controller
{
    protected $CartService;
    protected $StripeService;
    protected $AfterPayService;

    public function __construct(CartService $CartService, StripeService $StripeService, AfterPayService $AfterPayService)
    {
        $this->CartService = $CartService;
        $this->StripeService = $StripeService;
        $this->AfterPayService = $AfterPayService;
    }

    public function index()
    {
        $orders = Order::with('orderBillingShippingDetails')->orderBy('id', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function orderDetail($orderNumber)
    {
        $orderDetail = Order::where('order_number', $orderNumber)
            ->with(['orderDetails', 'orderBillingShippingDetails'])
            ->withCount('orderDetails')
            ->withSum('orderDetails', 'quantity')
            ->first();

        $stripe = $this->StripeService->retrivePaymentDetails($orderDetail->payment_id);

        $stripe_fee = 0;
        // dd($stripe);

        if (isset($stripe) && isset($stripe->fee) && !empty($stripe->fee)) {
            $stripe_fee = floatval($stripe->fee) / 100;
        }

        $OrderTotal = $this->CartService->getOrderTotal($orderNumber);

        return view('admin.orders.order_details', compact('orderDetail', 'OrderTotal', 'stripe_fee'));
    }

    public function search(Request $request)
    {
        $searchTerm  = $request->input('query');

        $startDate = ($request->input('start_date')) ? $request->input('start_date') : "";
        $endDate = ($request->input('end_date')) ? $request->input('end_date') : "";
        $orders_result = Order::query();

        if ($searchTerm) {
            $orders_result->where('order_number', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('orderBillingShippingDetails', function ($query) use ($searchTerm) {
                    $query->where('fname', 'LIKE', "%{$searchTerm}%");
                });
        }

        if (!empty($startDate) && !empty($endDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $orders_result->whereBetween('created_at', [$startDate, $endDate]);
        }
        $orders = $orders_result->with('orderBillingShippingDetails')->get();

        if (empty($orders)) {
            $orders = Order::with('orderBillingShippingDetails')->orderBy('id', 'desc')->paginate(10);
        }
        echo view('admin.orders.order_search', compact('orders'));
    }

    public function downloadOrderzip($orderId)
    {
        $order = Order::with('orderDetails')->find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $uniqueOrderDetails = $order->orderDetails->groupBy('product_id')
            ->flatMap(function ($details) {
                return $details->countBy('quantity')->count() === 1
                    ? [$details->first()]
                    : $details;
            });

        $zip = new ZipArchive();
        $zipFileName = "{$order->order_number}.zip";
        $zipFilePath = public_path("order_zip/{$zipFileName}");

        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($uniqueOrderDetails as $details) {
                $productId = $details->product_id;
                $quantity = $details->quantity;
                $productType = $details->product_type;

                $productDetails = $this->CartService->getProductDetailsByType($productId, $productType);
                $productTitle = $productDetails->product_title ?? '';
                $quantityFolder = "{$productTitle}/qty_{$quantity}/";

                $zip->addEmptyDir($quantityFolder);

                $orderDetails = OrderDetail::where('product_id', $productId)
                    ->where('order_id', $orderId)
                    ->where('quantity', $quantity)
                    ->get();

                $fileCounter = [];

                switch ($productType) {
                    case 'shop':
                        $this->addShopImagesToZip($zip, $orderDetails, $quantityFolder, $fileCounter, $quantity);
                        break;

                    case 'photo_for_sale':
                    case 'hand_craft':
                        $this->addProductImageToZip($zip, $productDetails->product_image ?? '', $quantityFolder, $fileCounter, $quantity);
                        break;

                    case 'gift_card':
                        $this->addProductImageToZip($zip, $productDetails->product_image ?? '', $quantityFolder, $fileCounter, $quantity);
                        break;
                }
            }

            $zip->close();

            return file_exists($zipFilePath)
                ? response()->download($zipFilePath)
                : response()->json(['message' => 'Failed to create the zip file.'], 500);
        } else {
            return response()->json(['message' => 'Failed to open zip file.'], 500);
        }
    }

    private function addShopImagesToZip($zip, $orderDetails, $folder, &$fileCounter, $quantity)
    {
        foreach ($orderDetails as $image) {
            $imagePath = $image->selected_images;
            // Loop based on quantity and add the same image multiple times
            for ($i = 1; $i <= $quantity; $i++) {
                $this->addFileToZip($zip, $imagePath, $folder, $fileCounter);
            }
        }
    }

    private function addProductImageToZip($zip, $imagePath, $folder, &$fileCounter, $quantity)
    {
        if (!empty($imagePath)) {
            $imageArray = explode(',', $imagePath);

            for ($i = 1; $i <= $quantity; $i++) {
                $this->addFileToZip($zip, $imageArray[0] ?? '', $folder, $fileCounter);
            }
        }
    }

    private function addFileToZip($zip, $imagePath, $folder, &$fileCounter)
    {
        if (strpos($imagePath, env('AWS_URL')) === 0) {
            // Define a folder to save downloaded images temporarily
            $downloadFolder = public_path('temp_images/');
            if (!file_exists($downloadFolder)) {
                mkdir($downloadFolder, 0777, true);
            }

            // Extract file name
            $fileName = basename($imagePath);
            $localFilePath = $downloadFolder . $fileName;

            // Download the image if it doesn't already exist
            if (!file_exists($localFilePath)) {
                file_put_contents($localFilePath, file_get_contents($imagePath));
            }

            // Use the downloaded file path
            $imagePath = $localFilePath;
        }

        if (file_exists($imagePath)) {
            \Log::info('first3', ['path' => $imagePath]);
            $baseName = pathinfo($imagePath, PATHINFO_FILENAME);
            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);

            if (!isset($fileCounter[$baseName])) {
                $fileCounter[$baseName] = 0;
            }

            $fileCounter[$baseName]++;
            $uniqueFileName = "{$folder}{$baseName}_{$fileCounter[$baseName]}.{$extension}";
            $zip->addFile($imagePath, $uniqueFileName);
        }
    }


    public function updateOrder(Request $request)
    {
        $orderDetail = Order::whereId($request->order_id)->with('orderDetails.product', 'orderBillingShippingDetails')->first();

        Order::whereId($request->order_id)->update(["order_status" => $request->order_status]);
        $status = '';
        if ($request->order_status == "0") {
            $status = "Processing";
        } elseif ($request->order_status == "1") {
            $status = "Completed";
            Mail::to($orderDetail->orderBillingShippingDetails->email)->send(new CompleteOrder($orderDetail));
        } elseif ($request->order_status == "2") {
            $status = "Cancelled";
            Mail::to($orderDetail->orderBillingShippingDetails->email)->send(new CancelOrder($orderDetail));
        } elseif ($request->order_status == "3") {
            $status = "Refunded";
            Mail::to($orderDetail->orderBillingShippingDetails->email)->send(new RefundOrder($orderDetail));
        }elseif ($request->order_status == "4") {
            $status = "On Hold";
            Mail::to($orderDetail->orderBillingShippingDetails->email)->send(new OnHoldOrder($orderDetail));
        }
        Session::flash('success', 'Order ' . $status . ' successfully');
    }

    public function refundOrder($order_id)
    {
        $order = Order::whereId($order_id)->first();
        $payment_method = $order->payment_method;
        $payment_status = $order->payment_status;
        $payment_id = $order->payment_id;
        $total = $order->total;

        if ($payment_method != 'afterPay') {
            $refundedData = $this->StripeService->refundOrder($order->payment_id);
            if (isset($refundedData['id']) && !empty($refundedData['id'])) {
                Order::where('id', $order_id)->update(['refund_id' => $refundedData['id'], 'payment_status' => $refundedData['object'], 'order_status' => '3']);
            }
        } else {
            if ($payment_status == 'APPROVED') {
                $afterPayRefund = $this->AfterPayService->refundPayment($payment_id, $total, $currency = 'AUD', $merchantReference = null);

                $log = new AfterPayLogs;
                $log->logs = json_encode($afterPayRefund) ?? '';
                $log->save();

                Order::where('id', $order_id)->update(['refund_id' => $afterPayRefund['refundId'], 'payment_status' => 'REFUNDED', 'order_status' => '3']);
            } else {
                return redirect()->back()->with('success', 'Payment refunded failed.');
            }
        }

        return redirect()->back()->with('success', 'Payment refunded successfully.');
    }

    public function addNote(Request $request)
    {
        $order_id = $request->order_id;
        $order_notes = $request->order_notes;
        OrderBillingDetails::where('order_id', $order_id)->update(['order_notes' => $order_notes]);
        return back()->with('success', 'Order notes added successfully.');
    }
}
