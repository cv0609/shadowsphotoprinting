<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;

class AfterPayService
{
    protected $apiUrl;
    protected $merchantId;
    protected $secretKey;
    protected $client;

    public function __construct()
    {
        // Initialize class properties with environment variables
        $this->apiUrl = env('AFTERPAY_API_URL'); // Example: 'https://api.afterpay.com'
        $this->merchantId = env('AFTERPAY_MERCHANT_ID');
        $this->secretKey = env('AFTERPAY_SECRET_KEY');

        // Initialize Guzzle client
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'timeout' => 10.0,
            'verify' => false,
        ]);
    }

    /**
     * Create an Afterpay order
     *
     * @param array $orderDetails
     * @return array|null
     */
    public function charge(array $orderDetails)
    {
        $auth = base64_encode("{$this->merchantId}:{$this->secretKey}");

        try {
            $response = $this->client->post('/v2/checkouts', [
                'json' => $orderDetails,
                'headers' => [
                    'Authorization' => "Basic $auth",
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Afterpay Online API',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $responseBody = json_decode($response->getBody()->getContents(), true);
                return $responseBody;
            }
            return ['error' => 'Unable to process Afterpay order.'];
        }
    }

    // public function validateAfterPayOrder($token)
    // {
    //     $auth = base64_encode("{$this->merchantId}:{$this->secretKey}");

    //     try {
    //         $response = $this->client->get("/v2/payments/token/{$token}", [
    //             'headers' => [
    //                 'Authorization' => "Basic $auth",
    //                 'Content-Type' => 'application/json',
    //                 'User-Agent' => 'Afterpay Online API',
    //                 'Accept' => 'application/json',
    //             ],
    //         ]);

    //         return json_decode($response->getBody()->getContents(), true);
    //     } catch (RequestException $e) {
    //         if ($e->hasResponse()) {
    //             $response = $e->getResponse();
    //             $responseBody = json_decode($response->getBody()->getContents(), true);
    //             return $responseBody;
    //         }
    //         return ['error' => 'Unable to validate Afterpay order.'];
    //     }
    // }

    public function refundPayment($payment_id, $amount, $currency = 'AUD', $merchantReference = null)
    {
        $auth = base64_encode("{$this->merchantId}:{$this->secretKey}");
        $merchantReference = $merchantReference ?? "merchantRefundId-" . Str::random(8);
        $requestId = Str::uuid(); // Generate a unique request ID

        $payload = [
            'requestId' => $requestId,
            'amount' => [
                'amount' => number_format($amount, 2, '.', ''), // Ensure proper format
                'currency' => $currency
            ],
            'merchantReference' => $merchantReference,
            'refundMerchantReference' => $merchantReference
        ];

        try {
            $response = $this->client->post("/v2/payments/{$payment_id}/refund", [
                'headers' => [
                    'Authorization' => "Basic $auth",
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Afterpay Online API',
                    'Accept' => 'application/json',
                ],
                'json' => $payload
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return json_decode($response->getBody()->getContents(), true);
            }
            return ['error' => 'Unable to process the refund.'];
        }
    }

    public function getCheckoutDetails($orderToken)
    {
        $auth = base64_encode("{$this->merchantId}:{$this->secretKey}");

        try {
            $response = $this->client->get("/v2/checkouts/{$orderToken}", [
                'headers' => [
                    'Authorization' => "Basic $auth",
                    'Accept' => 'application/json',
                    'User-Agent' => 'Afterpay API Client',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            \Log::info('Afterpay Checkout Details:', $data);

            return $data;
        } catch (RequestException $e) {
            $errorResponse = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'No response received';
            \Log::error('Afterpay Checkout Details Error:', ['error' => $errorResponse]);

            return [
                'error' => 'Unable to retrieve Afterpay checkout details.',
                'details' => json_decode($errorResponse, true) ?? $errorResponse
            ];
        }
    }

    // public function getOrderDetails($orderToken)
    // {
    //     $auth = base64_encode("{$this->merchantId}:{$this->secretKey}");

    //     try {
    //         $response = $this->client->get("/v2/orders/{$orderToken}", [
    //             'headers' => [
    //                 'Authorization' => "Basic $auth",
    //                 'Accept' => 'application/json',
    //                 'User-Agent' => 'Afterpay API Client',
    //             ],
    //         ]);

    //         $data = json_decode($response->getBody()->getContents(), true);
    //         \Log::info('Afterpay Order Details:', $data);

    //         return $data;
    //     } catch (RequestException $e) {
    //         $errorResponse = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'No response received';
    //         \Log::error('Afterpay Order Details Error:', ['error' => $errorResponse]);

    //         return [
    //             'error' => 'Unable to retrieve Afterpay order details.',
    //             'details' => json_decode($errorResponse, true) ?? $errorResponse
    //         ];
    //     }
    // }

    public function capturePayment($orderToken)
    {
        $auth = base64_encode("{$this->merchantId}:{$this->secretKey}");

        try {
            $response = $this->client->post('/v2/payments/capture', [
                'headers' => [
                    'Authorization' => "Basic $auth",
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Afterpay API Client',
                ],
                'json' => [
                    'token' => $orderToken,
                    'merchantReference' => 'order_reference_001',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            \Log::info('Afterpay Payment Captured:', $data);

            return $data;
        } catch (RequestException $e) {
            $errorResponse = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'No response received';
            \Log::error('Afterpay Capture Payment Error:', ['error' => $errorResponse]);

            return [
                'error' => 'Unable to capture Afterpay payment.',
                'details' => json_decode($errorResponse, true) ?? $errorResponse
            ];
        }
    }
}
