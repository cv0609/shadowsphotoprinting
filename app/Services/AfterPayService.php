<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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

    public function validateAfterPayOrder($token)
    {
        $auth = base64_encode("{$this->merchantId}:{$this->secretKey}");

        try {
            $response = $this->client->get("/v2/payments/token/{$token}", [
                'headers' => [
                    'Authorization' => "Basic $auth",
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Afterpay Online API',
                    'Accept' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $responseBody = json_decode($response->getBody()->getContents(), true);
                return $responseBody;
            }
            return ['error' => 'Unable to validate Afterpay order.'];
        }
    }

}
