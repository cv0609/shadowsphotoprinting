<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Services\CartShippingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;

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
     * Calculate shipping options per category for cart
     */
    public function calculateShippingPerCategory(Request $request)
    {
        try {
            $cartItems = $request->input('cart_items', []);
            
            Log::info('Received cart items for category shipping:', [
                'cart_items' => $cartItems
            ]);
            
            if (empty($cartItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No cart items provided'
                ]);
            }
            
            $shippingService = new CartShippingService();
            $categoryShippingOptions = $shippingService->calculateShippingPerCategory($cartItems);
            
            Log::info('Sending category shipping options to frontend:', [
                'category_shipping_options' => $categoryShippingOptions
            ]);
            
            return response()->json([
                'success' => true,
                'category_shipping_options' => $categoryShippingOptions
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error calculating category shipping: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error calculating shipping options'
            ]);
        }
    }

    /**
     * Calculate total shipping from category selections
     */
    public function calculateTotalShipping(Request $request)
    {
        try {
            $categorySelections = $request->input('category_selections', []);
            
            $shippingService = new CartShippingService();
            $totalShipping = $shippingService->calculateTotalShippingFromSelections($categorySelections);
            
            return response()->json([
                'success' => true,
                'total_shipping' => $totalShipping
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error calculating total shipping: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error calculating total shipping'
            ]);
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
     * Clear shipping session (for pickup)
     */
    public function clearShippingSession(Request $request)
    {
        try {
            // Remove shipping from session
            session()->forget('selected_shipping');
            
            Log::info('Shipping session cleared for pickup', [
                'session_id' => Session::getId(),
                'user_authenticated' => Auth::check()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shipping session cleared successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error clearing shipping session: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error clearing shipping session'
            ], 500);
        }
    }

    /**
     * Save shipping session data
     */
    public function saveShippingSession(Request $request)
    {
        try {
            $shippingData = $request->input('shipping_data');
            
            Log::info('Saving shipping session data', [
                'shipping_data' => $shippingData,
                'session_id' => Session::getId(),
                'has_category_selections' => $shippingData && isset($shippingData['category_selections']),
                'category_selections' => $shippingData['category_selections'] ?? null
            ]);
            
            if (!$shippingData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No shipping data provided'
                ]);
            }
            
            // Save to session
            session(['selected_shipping' => $shippingData]);
            
            Log::info('Shipping session saved successfully', [
                'saved_data' => session('selected_shipping')
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Shipping session saved'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error saving shipping session: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving shipping session'
            ]);
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
            
            Log::info('Getting session shipping data', [
                'selected_shipping' => $selectedShipping,
                'session_id' => Session::getId(),
                'has_category_selections' => $selectedShipping && isset($selectedShipping['category_selections']),
                'category_selections' => $selectedShipping['category_selections'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'shipping' => $selectedShipping
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting session shipping: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting session shipping: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cart items from server
     */
    public function getCartItems(Request $request)
    {
        try {
            $cart = null;
            
            // Get cart from database (same logic as CartController)
            if (Auth::check() && !empty(Auth::user())) {
                $auth_id = Auth::user()->id;
                $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
            } else {
                $session_id = Session::getId();
                $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
            }
            
            $cartItems = [];
            
            Log::info('Getting cart items from server', [
                'cart_exists' => !empty($cart),
                'cart_items_count' => $cart ? $cart->items->count() : 0,
                'user_authenticated' => Auth::check()
            ]);
            
            if ($cart && !$cart->items->isEmpty()) {
                foreach ($cart->items as $item) {
                    $cartItems[] = [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'product_type' => $item->product_type,
                        'is_test_print' => $item->is_test_print ?? '0'
                    ];
                }
                
                Log::info('Cart items found', [
                    'items_count' => count($cartItems),
                    'items' => $cartItems
                ]);
            } else {
                Log::info('No cart items found in database');
            }
            
            return response()->json([
                'success' => true,
                'cart_items' => $cartItems
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting cart items: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting cart items'
            ]);
        }
    }
} 