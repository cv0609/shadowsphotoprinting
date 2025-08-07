<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Services\CartShippingService;

class ShippingController extends Controller
{
    protected $shippingService;

    public function __construct()
    {
        $this->shippingService = new CartShippingService();
    }

    /**
     * Calculate shipping for cart items
     */
    public function calculateShipping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_items' => 'required|array',
            'cart_items.*.product_id' => 'required|integer',
            'cart_items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $shippingOptions = $this->shippingService->calculateShipping($request->cart_items);
            
            // Auto-select and save shipping option to session
            if (!empty($shippingOptions)) {
                $selectedShipping = $this->autoSelectShippingOption($shippingOptions);
                
                if ($selectedShipping) {
                    // Save to session
                    session([
                        'selected_shipping' => [
                            'carrier' => $selectedShipping['carrier'],
                            'service' => $selectedShipping['service'],
                            'price' => (float) $selectedShipping['price']
                        ]
                    ]);
                }
            } else {
                // Clear session shipping if no options are available
                session()->forget('selected_shipping');
                Log::info('No shipping options available, cleared session shipping');
            }

            return response()->json([
                'success' => true,
                'shipping_options' => $shippingOptions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating shipping: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get shipping options for specific quantity
     */
    public function getShippingForQuantity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'category' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = $request->category ?? 'scrapbook_page_printing';
            $shippingOptions = $this->shippingService->getShippingOptionsForQuantity(
                $request->quantity, 
                $category
            );

            if ($shippingOptions) {
                return response()->json([
                    'success' => true,
                    'shipping_options' => $shippingOptions,
                    'quantity' => $request->quantity
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No shipping options found for this quantity'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting shipping options: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all shipping tiers
     */
    public function getShippingTiers(Request $request)
    {
        try {
            $category = $request->category ?? 'scrapbook_page_printing';
            $tiers = $this->shippingService->getShippingTiers($category);

            return response()->json([
                'success' => true,
                'tiers' => $tiers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting shipping tiers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update selected shipping option
     */
    public function updateShippingSelection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carrier' => 'required|string',
            'service' => 'required|string',
            'price' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Store selected shipping in session
            session([
                'selected_shipping' => [
                    'carrier' => $request->carrier,
                    'service' => $request->service,
                    'price' => (float) $request->price
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shipping option updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating shipping: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear shipping selection (for pickup)
     */
    public function clearShippingSelection(Request $request)
    {
        try {
            // Remove shipping from session
            session()->forget('selected_shipping');

            return response()->json([
                'success' => true,
                'message' => 'Shipping selection cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing shipping: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Auto-select the best shipping option
     */
    private function autoSelectShippingOption($shippingOptions)
    {
        // Check if there's already a session shipping selection
        $existingShipping = session('selected_shipping');
        
        if ($existingShipping) {
            // Try to find the existing selection in current options
            foreach ($shippingOptions as $option) {
                if ($option['carrier'] === $existingShipping['carrier'] && 
                    $option['service'] === $existingShipping['service']) {
                    return $option;
                }
            }
        }
        
        // Auto-selection logic: prioritize Australia Post snail_mail
        foreach ($shippingOptions as $option) {
            if ($option['carrier'] === 'auspost' && $option['service'] === 'snail_mail') {
                return $option;
            }
        }
        
        // Fallback to first option
        return $shippingOptions[0] ?? null;
    }

    /**
     * Get current session shipping selection
     */
    public function getSessionShipping(Request $request)
    {
        try {
            $selectedShipping = session('selected_shipping');

            return response()->json([
                'success' => true,
                'shipping' => $selectedShipping
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting session shipping: ' . $e->getMessage()
            ], 500);
        }
    }
} 