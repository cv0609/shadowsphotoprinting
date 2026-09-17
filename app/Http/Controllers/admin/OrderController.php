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
        $orders = Order::with(['orderBillingShippingDetails', 'shoppingCountry'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function orderDetail($orderNumber)
    {
        $orderDetail = Order::where('order_number', $orderNumber)
            ->with(['orderDetails', 'orderBillingShippingDetails', 'shoppingCountry'])
            ->withCount('orderDetails')
            ->withSum('orderDetails', 'quantity')
            ->first();

        if (!$orderDetail) {
            abort(404, 'Order not found.');
        }

        $stripe_fee = 0;
        if ($orderDetail->payment_method !== 'afterPay' && $orderDetail->payment_method !== 'free' && !empty($orderDetail->payment_id)) {
            $cacheKey = 'stripe_fee_' . $orderDetail->payment_id;
            $stripe_fee = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () use ($orderDetail) {
                try {
                    $stripe = $this->StripeService->retrivePaymentDetails($orderDetail->payment_id);
                    if (is_object($stripe) && isset($stripe->fee) && !empty($stripe->fee)) {
                        return floatval($stripe->fee) / 100;
                    }
                } catch (\Exception $e) {
                    \Log::warning('Stripe payment details retrieval failed: ' . $e->getMessage());
                }
                return 0;
            });
        }

        $OrderTotal = $this->CartService->getOrderTotal($orderNumber);

        return view('admin.orders.order_details', compact('orderDetail', 'OrderTotal', 'stripe_fee'));
    }

    public function search(Request $request)
    {
        $searchTerm = trim((string) $request->input('query', ''));
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $ordersQuery = Order::query()
            ->with(['orderBillingShippingDetails', 'shoppingCountry'])
            ->orderBy('id', 'desc');

        if ($searchTerm !== '') {
            $ordersQuery->where(function ($query) use ($searchTerm) {
                $query->where('order_number', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('orderBillingShippingDetails', function ($billingQuery) use ($searchTerm) {
                        $billingQuery->where('fname', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('lname', 'LIKE', "%{$searchTerm}%");
                    })
                    ->orWhereHas('shoppingCountry', function ($countryQuery) use ($searchTerm) {
                        $countryQuery->where('name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('code', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        if (!empty($startDate) && !empty($endDate)) {
            $ordersQuery->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        // Empty search with no date filter → restore default first page
        if ($searchTerm === '' && empty($startDate) && empty($endDate)) {
            $orders = Order::with(['orderBillingShippingDetails', 'shoppingCountry'])
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('admin.orders.order_search', [
                'orders' => $orders,
            ]);
        }

        $orders = $ordersQuery->get();

        if ($orders->isEmpty()) {
            return response('<tr><td colspan="9"><p class="text-center">No any data found!</p></td></tr>');
        }

        return view('admin.orders.order_search', [
            'orders' => $orders,
        ]);
    }

    public function downloadOrderzip($orderId)
    {
        @set_time_limit(0);
        @ini_set('max_execution_time', '0');
        @ini_set('memory_limit', '512M');

        $order = Order::with('orderDetails')->find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $zipDirectory = public_path('order_zip');
        if (!file_exists($zipDirectory)) {
            @mkdir($zipDirectory, 0777, true);
        }

        $zipFileName = "{$order->order_number}.zip";
        $zipFilePath = "{$zipDirectory}/{$zipFileName}";

        // If zip file already exists and is non-empty, immediately return for download
        if (file_exists($zipFilePath) && filesize($zipFilePath) > 0) {
            return response()->download($zipFilePath, $zipFileName, [
                'Content-Type' => 'application/zip',
            ]);
        }

        // Collect remote URLs to pre-download in parallel
        $remoteUrls = [];
        foreach ($order->orderDetails as $details) {
            $productType = $details->product_type;
            if ($productType === 'shop') {
                if (!empty($details->selected_images) && preg_match('/^https?:\/\//i', $details->selected_images)) {
                    $remoteUrls[] = $details->selected_images;
                }
            } else {
                $productDetails = $this->CartService->getProductDetailsByType($details->product_id, $productType);
                if ($productDetails && !empty($productDetails->product_image)) {
                    $imgs = explode(',', $productDetails->product_image);
                    $firstImg = trim($imgs[0] ?? '');
                    if (preg_match('/^https?:\/\//i', $firstImg)) {
                        $remoteUrls[] = $firstImg;
                    }
                }
            }
        }

        if (!empty($remoteUrls)) {
            $this->downloadRemoteFilesParallel(array_unique($remoteUrls));
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $fileCounter = [];

            foreach ($order->orderDetails as $details) {
                $productId = $details->product_id;
                $quantity = max(1, (int) ($details->quantity ?? 1));
                $productType = $details->product_type;

                $productDetails = $this->CartService->getProductDetailsByType($productId, $productType);
                $rawTitle = $productDetails->product_title ?? "product_{$productId}";
                $productTitle = preg_replace('/[\\/\\\\:*?"<>|]/', '_', trim($rawTitle));
                $quantityFolder = "{$productTitle}/qty_{$quantity}/";

                $zip->addEmptyDir($quantityFolder);

                switch ($productType) {
                    case 'shop':
                        $imagePath = $details->selected_images;
                        if (!empty($imagePath)) {
                            for ($i = 1; $i <= $quantity; $i++) {
                                $this->addFileToZip($zip, $imagePath, $quantityFolder, $fileCounter);
                            }
                        }
                        break;

                    case 'photo_for_sale':
                    case 'hand_craft':
                    case 'gift_card':
                        $imagePath = $productDetails->product_image ?? '';
                        if (!empty($imagePath)) {
                            $imageArray = explode(',', $imagePath);
                            $firstImg = trim($imageArray[0] ?? '');
                            if (!empty($firstImg)) {
                                for ($i = 1; $i <= $quantity; $i++) {
                                    $this->addFileToZip($zip, $firstImg, $quantityFolder, $fileCounter);
                                }
                            }
                        }
                        break;
                }
            }

            $zip->close();

            return file_exists($zipFilePath)
                ? response()->download($zipFilePath, $zipFileName, [
                    'Content-Type' => 'application/zip',
                ])
                : response()->json(['message' => 'Failed to create the zip file.'], 500);
        } else {
            return response()->json(['message' => 'Failed to open zip file.'], 500);
        }
    }

    private function downloadRemoteFilesParallel(array $urls)
    {
        $downloadFolder = public_path('temp_images/');
        if (!file_exists($downloadFolder)) {
            @mkdir($downloadFolder, 0777, true);
        }

        $mh = curl_multi_init();
        $curlHandles = [];
        $filePointers = [];

        foreach ($urls as $url) {
            $urlPath = parse_url($url, PHP_URL_PATH);
            $fileName = basename($urlPath);
            if (empty($fileName)) {
                $fileName = md5($url) . '.jpg';
            }
            $localFilePath = $downloadFolder . $fileName;

            if (file_exists($localFilePath) && filesize($localFilePath) > 0) {
                continue;
            }

            $fp = @fopen($localFilePath, 'w+');
            if (!$fp) {
                continue;
            }
            $filePointers[] = $fp;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 90);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            curl_multi_add_handle($mh, $ch);
            $curlHandles[] = $ch;
        }

        if (!empty($curlHandles)) {
            $running = null;
            do {
                curl_multi_exec($mh, $running);
                curl_multi_select($mh, 0.5);
            } while ($running > 0);

            foreach ($curlHandles as $ch) {
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
            }
            curl_multi_close($mh);

            foreach ($filePointers as $fp) {
                @fclose($fp);
            }
        }
    }

    private function addShopImagesToZip($zip, $orderDetails, $folder, &$fileCounter, $quantity)
    {
        foreach ($orderDetails as $image) {
            $imagePath = $image->selected_images;
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
        if (empty($imagePath)) {
            return;
        }

        // Handle remote URL (e.g. AWS S3, Cloudfront, HTTP/HTTPS)
        if (preg_match('/^https?:\/\//i', $imagePath)) {
            $downloadFolder = public_path('temp_images/');
            if (!file_exists($downloadFolder)) {
                @mkdir($downloadFolder, 0777, true);
            }

            $urlPath = parse_url($imagePath, PHP_URL_PATH);
            $fileName = basename($urlPath);
            if (empty($fileName)) {
                $fileName = md5($imagePath) . '.jpg';
            }

            $localFilePath = $downloadFolder . $fileName;

            // Only download if it does not already exist locally
            if (!file_exists($localFilePath) || filesize($localFilePath) === 0) {
                $ctx = stream_context_create([
                    'http' => [
                        'timeout' => 60,
                        'follow_location' => 1,
                    ],
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]);

                $content = @file_get_contents($imagePath, false, $ctx);
                if ($content !== false && strlen($content) > 0) {
                    file_put_contents($localFilePath, $content);
                }
            }

            if (file_exists($localFilePath) && filesize($localFilePath) > 0) {
                $imagePath = $localFilePath;
            }
        } elseif (!file_exists($imagePath)) {
            if (file_exists(public_path($imagePath))) {
                $imagePath = public_path($imagePath);
            } elseif (file_exists(storage_path('app/public/' . $imagePath))) {
                $imagePath = storage_path('app/public/' . $imagePath);
            }
        }

        if (file_exists($imagePath) && is_file($imagePath) && filesize($imagePath) > 0) {
            $baseName = pathinfo($imagePath, PATHINFO_FILENAME);
            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
            if (empty($extension)) {
                $extension = 'jpg';
            }

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
