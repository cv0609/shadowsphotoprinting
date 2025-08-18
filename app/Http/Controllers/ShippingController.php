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
     * Updated to use new combined vs separate order logic
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
            
            // Check if this is a combined order or separate order
            $isCombinedOrder = $this->isCombinedOrder($cartItems);
            
            if ($isCombinedOrder) {
                // Combined order - use fixed pricing for all categories
                $categoryShippingOptions = $this->getCombinedOrderCategoryShipping();
            } else {
                // Separate order - check if it's Photo Print/Scrapbook only
                $hasPhotoPrints = $this->hasCategory($cartItems, 4); // Photo Prints
                $hasScrapbook = $this->hasCategory($cartItems, 1);  // Scrapbook
                $hasOtherCategories = $this->hasOtherCategories($cartItems);
                
                if (($hasPhotoPrints || $hasScrapbook) && !$hasOtherCategories) {
                    // Photo Print/Scrapbook only order - use tier-based pricing
                    $categoryShippingOptions = $this->getTierBasedCategoryShipping($cartItems);
                } else {
                    // Other category orders - use existing logic
                    $categoryShippingOptions = $shippingService->calculateShippingPerCategory($cartItems);
                }
            }
            
            Log::info('Sending category shipping options to frontend:', [
                'category_shipping_options' => $categoryShippingOptions,
                'is_combined_order' => $isCombinedOrder
            ]);
            
            return response()->json([
                'success' => true,
                'category_shipping_options' => $categoryShippingOptions,
                'is_combined_order' => $isCombinedOrder
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
     * Check if this is a combined order (has items from multiple categories)
     */
    private function isCombinedOrder($cartItems)
    {
        $hasPhotoPrints = $this->hasCategory($cartItems, 4); // Photo Prints
        $hasScrapbook = $this->hasCategory($cartItems, 1);   // Scrapbook
        $hasOtherCategories = $this->hasOtherCategories($cartItems);
        
        // If we have Photo Print and/or Scrapbook AND any other categories, it's a combined order
        if (($hasPhotoPrints || $hasScrapbook) && $hasOtherCategories) {
            return true;
        }
        
        // If we have Photo Print and/or Scrapbook ONLY (no other categories), it's NOT a combined order
        // It will use tier-based pricing instead
        if (($hasPhotoPrints || $hasScrapbook) && !$hasOtherCategories) {
            return false;
        }
        
        // For all other cases, count categories normally
        $categories = [];
        foreach ($cartItems as $item) {
            $categoryId = $this->getItemCategoryId($item);
            if ($categoryId && !in_array($categoryId, $categories)) {
                $categories[] = $categoryId;
            }
        }
        
        return count($categories) > 1;
    }
    
    /**
     * Check if cart has items from a specific category
     */
    private function hasCategory($cartItems, $categoryId)
    {
        foreach ($cartItems as $item) {
            if ($this->getItemCategoryId($item) == $categoryId) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Check if cart has items from other categories (not Photo Print or Scrapbook)
     */
    private function hasOtherCategories($cartItems)
    {
        foreach ($cartItems as $item) {
            $categoryId = $this->getItemCategoryId($item);
            if ($categoryId && $categoryId != 1 && $categoryId != 4) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Get category ID for a cart item
     */
    private function getItemCategoryId($item)
    {
        if (isset($item['product_type']) && in_array($item['product_type'], ['gift_card', 'photo_for_sale', 'hand_craft'])) {
            // Handle special product types
            switch ($item['product_type']) {
                case 'gift_card':
                    $product = \App\Models\GiftCardCategory::find($item['product_id']);
                    return $product ? 8 : null; // Gift Card category
                case 'photo_for_sale':
                    $product = \App\Models\PhotoForSaleProduct::find($item['product_id']);
                    return $product ? 7 : null; // Photos for Sale category
                case 'hand_craft':
                    $product = \App\Models\HandCraftProduct::find($item['product_id']);
                    return $product ? 6 : null; // Hand Craft category
            }
        } else {
            $product = \App\Models\Product::find($item['product_id']);
            return $product ? $product->category_id : null;
        }
        
        return null;
    }
    
    /**
     * Get combined order category shipping (fixed pricing for all categories)
     */
    private function getCombinedOrderCategoryShipping()
    {
        // Get fixed pricing from database for combined orders
        $fixedPricingCategory = \App\Models\ShippingCategory::where('pricing_type', 'fixed')->first();
        
        if ($fixedPricingCategory) {
            $fixedRules = $fixedPricingCategory->rules()->where('is_active', true)->get();
            
            $combinedOrderShipping = [
                'combined_order' => []
            ];
            
            foreach ($fixedRules as $rule) {
                $combinedOrderShipping['combined_order'][$rule->service] = [
                    'price' => $rule->price,
                    'delivery_time' => $rule->delivery_time,
                    'note' => 'Combined order - Fixed pricing'
                ];
            }
            
            return $combinedOrderShipping;
        }
        
        // Fallback if no database records found
        $anyFixedCategory = \App\Models\ShippingCategory::where('pricing_type', 'fixed')->first();
        
        if ($anyFixedCategory) {
            $fixedRules = $anyFixedCategory->rules()->where('is_active', true)->get();
            
            $combinedOrderShipping = [
                'combined_order' => []
            ];
            
            foreach ($fixedRules as $rule) {
                $combinedOrderShipping['combined_order'][$rule->service] = [
                    'price' => $rule->price,
                    'delivery_time' => $rule->delivery_time,
                    'note' => 'Combined order - Fixed pricing'
                ];
            }
            
            return $combinedOrderShipping;
        }
        
        // Ultimate fallback if no database records exist at all
        return [
            'combined_order' => [
                'snail_mail' => [
                    'price' => 22.60,
                    'delivery_time' => '5-10 business days',
                    'note' => 'Combined order - Fixed pricing (fallback)'
                ],
                'express' => [
                    'price' => 31.21,
                    'delivery_time' => '1-2 business days',
                    'note' => 'Combined order - Fixed pricing (fallback)'
                ]
            ]
        ];
    }
    
    /**
     * Get tier-based category shipping for Photo Print/Scrapbook only orders
     */
    private function getTierBasedCategoryShipping($cartItems)
    {
        // Calculate total quantity for Photo Print and Scrapbook
        $totalQuantity = 0;
        
        foreach ($cartItems as $item) {
            $categoryId = $this->getItemCategoryId($item);
            if ($categoryId == 1 || $categoryId == 4) { // Scrapbook or Photo Prints
                $totalQuantity += $item['quantity'];
            }
        }
        
        // Get tier pricing from database for Photo Print and Scrapbook categories
        $photoPrintCategory = \App\Models\ShippingCategory::where('name', 'photo_prints')->first();
        $scrapbookCategory = \App\Models\ShippingCategory::where('name', 'scrapbook_page_printing')->first();
        
        if ($photoPrintCategory && $scrapbookCategory) {
            // Get rules from either category (they should have same tier pricing)
            $tierRules = $photoPrintCategory->rules()
                ->where('is_active', true)
                ->where('rule_type', 'quantity_based')
                ->orderBy('priority')
                ->get();
            
            // Determine which tier to use based on quantity
            $selectedRule = null;
            $tierNote = '';
            
            foreach ($tierRules as $rule) {
                $condition = $rule->condition;
                
                if (strpos($condition, '-') !== false) {
                    // Range condition like "1-60"
                    [$min, $max] = explode('-', $condition);
                    if ($totalQuantity >= (int)$min && $totalQuantity <= (int)$max) {
                        $selectedRule = $rule;
                        break;
                    }
                } elseif (strpos($condition, '+') !== false) {
                    // Open-ended condition like "101+"
                    $min = (int)str_replace('+', '', $condition);
                    if ($totalQuantity >= $min) {
                        $selectedRule = $rule;
                        break;
                    }
                }
            }
            
            if ($selectedRule) {
                $tierNote = $selectedRule->condition . ' prints tier';
                
                // Get prices for the selected tier
                $tierRulesForCondition = $tierRules->where('condition', $selectedRule->condition);
                
                $snailRule = $tierRulesForCondition->where('service', 'snail_mail')->first();
                $expressRule = $tierRulesForCondition->where('service', 'express')->first();
                
                $snailPrice = $snailRule ? $snailRule->price : 0;
                $expressPrice = $expressRule ? $expressRule->price : 0;
            } else {
                // Fallback values - try to get from any available tier-based category
                $anyTierCategory = \App\Models\ShippingCategory::where('pricing_type', 'tier')->first();
                if ($anyTierCategory) {
                    $fallbackRules = $anyTierCategory->rules()
                        ->where('is_active', true)
                        ->where('rule_type', 'quantity_based')
                        ->where('condition', '1-60')
                        ->get();
                    
                    $fallbackSnail = $fallbackRules->where('service', 'snail_mail')->first();
                    $fallbackExpress = $fallbackRules->where('service', 'express')->first();
                    
                    $tierNote = '1-60 prints tier (fallback)';
                    $snailPrice = $fallbackSnail ? $fallbackSnail->price : 15.00;
                    $expressPrice = $fallbackExpress ? $fallbackExpress->price : 20.00;
                } else {
                    $tierNote = '1-60 prints tier (fallback)';
                    $snailPrice = 15.00;
                    $expressPrice = 20.00;
                }
            }
        } else {
            // Fallback values if no database records found - try to get from any available tier-based category
            $anyTierCategory = \App\Models\ShippingCategory::where('pricing_type', 'tier')->first();
            if ($anyTierCategory) {
                $fallbackRules = $anyTierCategory->rules()
                    ->where('is_active', true)
                    ->where('rule_type', 'quantity_based')
                    ->where('condition', '1-60')
                    ->get();
                
                $fallbackSnail = $fallbackRules->where('service', 'snail_mail')->first();
                $fallbackExpress = $fallbackRules->where('service', 'express')->first();
                
                $tierNote = '1-60 prints tier (fallback)';
                $snailPrice = $fallbackSnail ? $fallbackSnail->price : 15.00;
                $expressPrice = $fallbackExpress ? $fallbackExpress->price : 20.00;
            } else {
                $tierNote = '1-60 prints tier (fallback)';
                $snailPrice = 15.00;
                $expressPrice = 20.00;
            }
        }
        
        // Return as a single combined category instead of separate categories
        return [
            'photo_print_scrapbook_combined' => [
                [
                    'service' => 'snail_mail',
                    'price' => $snailPrice,
                    'delivery_time' => '5-10 business days',
                    'note' => $tierNote . ' (Combined Photo Print + Scrapbook: ' . $totalQuantity . ' items)'
                ],
                [
                    'service' => 'express',
                    'price' => $expressPrice,
                    'delivery_time' => '1-2 business days',
                    'note' => $tierNote . ' (Combined Photo Print + Scrapbook: ' . $totalQuantity . ' items)'
                ]
            ]
        ];
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