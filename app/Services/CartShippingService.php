<?php

namespace App\Services;

use App\Models\ShippingTier;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class CartShippingService
{
    /**
     * Calculate shipping for cart items
     */
    public function calculateShipping($cartItems)
    {
        $shippingOptions = [];
        
        // Debug logging for cart items
        Log::info('Cart shipping calculation started', [
            'cart_items' => $cartItems
        ]);
        
        // Group items by category
        $scrapbookItems = [];
        $canvasItems = [];
        $photoEnlargementItems = [];
        $photoPrintsItems = [];
        $postersItems = [];
        $handCraftItems = [];
        $photosForSaleItems = [];
        $giftCardItems = [];
        $testPrintItems = [];
        $otherItems = [];
        
        foreach ($cartItems as $item) {
            // Log each cart item for debugging
            Log::info('Processing cart item:', [
                'product_id' => $item['product_id'] ?? 'not set',
                'product_type' => $item['product_type'] ?? 'not set',
                'quantity' => $item['quantity'] ?? 'not set',
                'item_data' => $item
            ]);

            // if (isset($item['is_test_print']) && $item['is_test_print'] == '1') {
            //     $testPrintItems[] = $item;
            // }
            
            // Check if this is a special product type (hand_craft, photo_for_sale, gift_card)
            if (isset($item['product_type'])) {
                switch ($item['product_type']) {
                    case 'hand_craft':
                        $handCraftItems[] = $item;
                        Log::info('Item assigned to hand_craft category');
                        break;
                    case 'photo_for_sale':
                        $photosForSaleItems[] = $item;
                        Log::info('Item assigned to photo_for_sale category');
                        break;
                    case 'gift_card':
                        $giftCardItems[] = $item;
                        Log::info('Item assigned to gift_card category');
                        break;
                    default:
                        // Try to find in products table
                        $product = Product::find($item['product_id']);
                        if ($product) {
                            switch ($product->category_id) {
                                case 1: // Scrapbook page printing
                                   
                                    if (isset($item['is_test_print']) && $item['is_test_print'] == '1') {
                                        $testPrintItems[] = $item;
                                        Log::info('Test print item found in case 4, assigned to test_print category2');
                                    } else {
                                        $scrapbookItems[] = $item;
                                    }

                                    break;
                                case 2: // Canvas prints
                                    $canvasItems[] = $item;
                                    break;
                                case 3: // Photo Enlargements
                                    $photoEnlargementItems[] = $item;
                                    break;
                                case 4: // Photo Prints
                                    // Check if this is a test print item - if so, don't categorize as Photo Prints
                                    if (isset($item['is_test_print']) && $item['is_test_print'] == '1') {
                                        $testPrintItems[] = $item;
                                        Log::info('Test print item found in case 4, assigned to test_print category2');
                                    } else {
                                        $photoPrintsItems[] = $item;
                                    }
                                    break;
                                case 5: // Photos for sale
                                    $photosForSaleItems[] = $item;
                                    break;
                                case 6: // Gift card
                                    $giftCardItems[] = $item;
                                    break;
                                case 14: // Hand Craft
                                    $handCraftItems[] = $item;
                                    break;
                                case 18: // Poster Prints
                                    $postersItems[] = $item;
                                    break;
                                default:
                                    $otherItems[] = $item;
                                    break;
                            }
                        } else {
                            $otherItems[] = $item;
                        }
                        break;
                }
            } else {
                // Fallback to products table lookup
                $product = Product::find($item['product_id']);
                if ($product) {
                    switch ($product->category_id) {
                        case 1: // Scrapbook page printing

                            if (isset($item['is_test_print']) && $item['is_test_print'] == '1') {
                                $testPrintItems[] = $item;
                                Log::info('Test print item found in case 4, assigned to test_print category3');
                            } else {
                                $scrapbookItems[] = $item;
                            }

                            break;
                        case 2: // Canvas prints
                            $canvasItems[] = $item;
                            break;
                        case 3: // Photo Enlargements
                            $photoEnlargementItems[] = $item;
                            break;
                        case 4: // Photo Prints
                            // Check if this is a test print item - if so, don't categorize as Photo Prints
                            if (isset($item['is_test_print']) && $item['is_test_print'] == '1') {
                                $testPrintItems[] = $item;
                                Log::info('Test print item found in case 4, assigned to test_print category3');
                            } else {
                                $photoPrintsItems[] = $item;
                            }
                            break;
                        case 5: // Photos for sale
                            $photosForSaleItems[] = $item;
                            break;
                        case 6: // Gift card
                            $giftCardItems[] = $item;
                            break;
                        case 14: // Hand Craft
                            $handCraftItems[] = $item;
                            break;
                        case 18: // Poster Prints
                            $postersItems[] = $item;
                            break;
                        default:
                            $otherItems[] = $item;
                            break;
                    }
                } else {
                    $otherItems[] = $item;
                }
            }
        }
        
        Log::info('Item categorization', [
            'scrapbook_items_count' => count($scrapbookItems),
            'canvas_items_count' => count($canvasItems),
            'photo_enlargement_items_count' => count($photoEnlargementItems),
            'photo_prints_items_count' => count($photoPrintsItems),
            'posters_items_count' => count($postersItems),
            'hand_craft_items_count' => count($handCraftItems),
            'photos_for_sale_items_count' => count($photosForSaleItems),
            'gift_card_items_count' => count($giftCardItems),
            'other_items_count' => count($otherItems),
            'test_print_items_count' => count($testPrintItems)
        ]);
        
        // Log detailed information about each category
        if (!empty($handCraftItems)) {
            Log::info('Hand Craft items found:', $handCraftItems);
        }
        if (!empty($photosForSaleItems)) {
            Log::info('Photos for Sale items found:', $photosForSaleItems);
        }
        if (!empty($giftCardItems)) {
            Log::info('Gift Card items found:', $giftCardItems);
        }
        
        // Calculate Australia Post mixed shipping
        $shippingOptions = $this->calculateAustraliaPostMixedShipping($scrapbookItems, $canvasItems, $photoEnlargementItems, $photoPrintsItems, $postersItems, $handCraftItems, $photosForSaleItems, $giftCardItems, $otherItems,$testPrintItems);
        
        // Remove duplicates and ensure only first snail_mail and express options per category
        $shippingOptions = $this->filterShippingOptions($shippingOptions);
        
        Log::info('Final shipping options for mixed categories', [
            'shipping_options' => $shippingOptions
        ]);
        
        return $shippingOptions;
    }
    
    /**
     * Calculate Australia Post mixed shipping
     */
    protected function calculateAustraliaPostMixedShipping($scrapbookItems, $canvasItems, $photoEnlargementItems, $photoPrintsItems, $postersItems, $handCraftItems, $photosForSaleItems, $giftCardItems, $otherItems,$testPrintItems)
    {
        $shippingOptions = [];
        
        // Calculate total quantity for tier-based pricing (Scrapbook only)
        $tierBasedQuantity = 0;
        foreach ($scrapbookItems as $item) {
            $tierBasedQuantity += $item['quantity'];
        }
        
        // Calculate fixed pricing categories total using database rules
        $fixedShippingSnailMail = 0;
        $fixedShippingExpress = 0;
        
        // Canvas fixed pricing
        if (!empty($canvasItems)) {
            $canvasShipping = $this->getFixedShippingForCategory('canvas');
            $fixedShippingSnailMail += $canvasShipping['snail_mail'] ?? 0;
            $fixedShippingExpress += $canvasShipping['express'] ?? 0;
        }
        
        // Photo Enlargements fixed pricing
        if (!empty($photoEnlargementItems)) {
            $photoEnlargementShipping = $this->getFixedShippingForCategory('photo_enlargements');
            $fixedShippingSnailMail += $photoEnlargementShipping['snail_mail'] ?? 0;
            $fixedShippingExpress += $photoEnlargementShipping['express'] ?? 0;
        }
        
        // Posters fixed pricing
        if (!empty($postersItems)) {
            $postersShipping = $this->getFixedShippingForCategory('posters');
            $fixedShippingSnailMail += $postersShipping['snail_mail'] ?? 0;
            $fixedShippingExpress += $postersShipping['express'] ?? 0;
        }
        
        // Hand Craft fixed pricing
        if (!empty($handCraftItems)) {
            Log::info('Processing Hand Craft items for shipping');
            $handCraftShipping = $this->getFixedShippingForCategory('hand_craft');
            $fixedShippingSnailMail += $handCraftShipping['snail_mail'] ?? 0;
            $fixedShippingExpress += $handCraftShipping['express'] ?? 0;
            Log::info('Hand Craft shipping added:', [
                'snail_mail' => $handCraftShipping['snail_mail'] ?? 0,
                'express' => $handCraftShipping['express'] ?? 0,
                'total_snail_mail' => $fixedShippingSnailMail,
                'total_express' => $fixedShippingExpress
            ]);
        }
        
        // Photos for Sale fixed pricing
        if (!empty($photosForSaleItems)) {
            Log::info('Processing Photos for Sale items for shipping');
            $photosForSaleShipping = $this->getFixedShippingForCategory('photos_for_sale');
            $fixedShippingSnailMail += $photosForSaleShipping['snail_mail'] ?? 0;
            $fixedShippingExpress += $photosForSaleShipping['express'] ?? 0;
            Log::info('Photos for Sale shipping added:', [
                'snail_mail' => $photosForSaleShipping['snail_mail'] ?? 0,
                'express' => $photosForSaleShipping['express'] ?? 0,
                'total_snail_mail' => $fixedShippingSnailMail,
                'total_express' => $fixedShippingExpress
            ]);
        }
        
        // Photo Prints fixed pricing (calculate tier-based pricing for Photo Prints and add as fixed)
        if (!empty($photoPrintsItems)) {
            $photoPrintsQuantity = 0;
            foreach ($photoPrintsItems as $item) {
                $photoPrintsQuantity += $item['quantity'];
            }
            
            // Calculate Photo Prints tier-based pricing
            $photoPrintsTierShipping = $this->calculatePhotoPrintsTierShipping($photoPrintsQuantity);
            
            // Add Photo Prints pricing to fixed shipping
            foreach ($photoPrintsTierShipping as $option) {
                if ($option['service'] === 'snail_mail') {
                    $fixedShippingSnailMail += $option['price'];
                } elseif ($option['service'] === 'express') {
                    $fixedShippingExpress += $option['price'];
                }
            }
        }
        
        // Test Print shipping (get from shipping table where is_test_print = 1)
        $testPrintShipping = 0;
        $hasTestPrint = !empty($testPrintItems);
        
        // Count total test print quantity
        $testPrintQuantity = 0;
        foreach ($testPrintItems as $item) {
            $testPrintQuantity += $item['quantity'];
        }
        
        if ($hasTestPrint) {
            $testPrintShippingRecord = \App\Models\Shipping::where('is_test_print', '1')->first();
            if ($testPrintShippingRecord) {
                $testPrintShipping = (float) $testPrintShippingRecord->amount * $testPrintQuantity;
                Log::info('Test print shipping calculated:', [
                    'per_item_amount' => $testPrintShippingRecord->amount,
                    'total_quantity' => $testPrintQuantity,
                    'total_amount' => $testPrintShipping,
                    'shipping_record' => $testPrintShippingRecord->toArray()
                ]);
            }
        }
        
        // Gift Cards have zero shipping (no additional cost)
        // $giftCardItems are handled separately - they don't add to shipping cost
        
        // Calculate tier-based shipping for Scrapbook only
        $tierShipping = $this->calculateTierBasedShipping($tierBasedQuantity);
        
        // Combine tier-based shipping with fixed pricing and test print shipping
        if (!empty($tierShipping)) {
            foreach ($tierShipping as $option) {
                $additionalShipping = 0;
                if ($option['service'] === 'snail_mail') {
                    $additionalShipping = $fixedShippingSnailMail;
                } elseif ($option['service'] === 'express') {
                    $additionalShipping = $fixedShippingExpress;
                }
                
                $shippingOptions[] = [
                    'carrier' => $option['carrier'],
                    'service' => $option['service'],
                    'price' => $option['price'] + $additionalShipping + $testPrintShipping,
                    'delivery_time' => $option['delivery_time'],
                    'note' => $option['note'] . ' + Fixed categories' . ($testPrintShipping > 0 ? ' + Test Print' : '')
                ];
            }
        } else {
            // If no tier-based items, show fixed pricing + test print shipping
            if ($fixedShippingSnailMail > 0 || $testPrintShipping > 0) {
                // Show snail mail option if we have any shipping
                $shippingOptions[] = [
                    'carrier' => 'auspost',
                    'service' => 'snail_mail',
                    'price' => $fixedShippingSnailMail + $testPrintShipping,
                    'delivery_time' => '5-10 business days',
                    'note' => $this->getShippingNote($fixedShippingSnailMail, $testPrintShipping)
                ];
            }
            
            if ($fixedShippingExpress > 0 || $testPrintShipping > 0) {
                // Show express option if we have any shipping
                $shippingOptions[] = [
                    'carrier' => 'auspost',
                    'service' => 'express',
                    'price' => $fixedShippingExpress + $testPrintShipping,
                    'delivery_time' => '1-2 business days',
                    'note' => $this->getShippingNote($fixedShippingExpress, $testPrintShipping)
                ];
            }
        }

        Log::info('Final shipping options created:', [
            'shipping_options' => $shippingOptions,
            'test_print_shipping' => $testPrintShipping,
            'test_print_quantity' => $testPrintQuantity,
            'has_test_print' => $hasTestPrint,
            'fixed_shipping_snail_mail' => $fixedShippingSnailMail,
            'fixed_shipping_express' => $fixedShippingExpress
        ]);
        
        return $shippingOptions;
    }
    
    /**
     * Get shipping note based on shipping types
     */
    protected function getShippingNote($fixedShipping, $testPrintShipping)
    {
        if ($fixedShipping > 0 && $testPrintShipping > 0) {
            return 'Fixed categories + Test Print';
        } elseif ($fixedShipping > 0) {
            return 'Fixed categories only';
        } elseif ($testPrintShipping > 0) {
            return 'Test Print only';
        } else {
            return 'Standard shipping';
        }
    }

    /**
     * Get shipping breakdown for admin display
     */
    public function getShippingBreakdown($cartItems, $selectedShipping)
    {
        // Categorize items
        $scrapbookItems = [];
        $canvasItems = [];
        $photoEnlargementItems = [];
        $photoPrintsItems = [];
        $postersItems = [];
        $handCraftItems = [];
        $photosForSaleItems = [];
        $giftCardItems = [];
        $testPrintItems = [];
        $otherItems = [];

        foreach ($cartItems as $item) {
            // Priority check for test print items
            if (isset($item['is_test_print']) && $item['is_test_print'] == '1') {
                $testPrintItems[] = $item;
                continue;
            }

            // Get product details
            $product = null;
            if (isset($item['product_type']) && in_array($item['product_type'], ['gift_card', 'photo_for_sale', 'hand_craft'])) {
                // Handle special product types
                switch ($item['product_type']) {
                    case 'gift_card':
                        $product = \App\Models\GiftCardCategory::find($item['product_id']);
                        break;
                    case 'photo_for_sale':
                        $product = \App\Models\PhotoForSaleProduct::find($item['product_id']);
                        break;
                    case 'hand_craft':
                        $product = \App\Models\HandCraftProduct::find($item['product_id']);
                        break;
                }
            } else {
                $product = \App\Models\Product::find($item['product_id']);
            }

            if ($product) {
                switch ($product->category_id) {
                    case 1: // Scrapbook page printing
                        $scrapbookItems[] = $item;
                        break;
                    case 2: // Canvas prints
                        $canvasItems[] = $item;
                        break;
                    case 3: // Photo Enlargements
                        $photoEnlargementItems[] = $item;
                        break;
                    case 4: // Photo Prints
                        $photoPrintsItems[] = $item;
                        break;
                    case 5: // Photos for sale
                        $photosForSaleItems[] = $item;
                        break;
                    case 6: // Gift card
                        $giftCardItems[] = $item;
                        break;
                    case 14: // Hand Craft
                        $handCraftItems[] = $item;
                        break;
                    case 18: // Poster Prints
                        $postersItems[] = $item;
                        break;
                    default:
                        $otherItems[] = $item;
                        break;
                }
            } else {
                $otherItems[] = $item;
            }
        }

        // Calculate breakdown
        $breakdown = [];

        // Scrapbook items
        if (!empty($scrapbookItems)) {
            $quantity = array_sum(array_column($scrapbookItems, 'quantity'));
            $shipping = $this->calculateTierBasedShipping($quantity, 'scrapbook_page_printing');
            if (!empty($shipping)) {
                $breakdown['scrapbook_page_printing'] = [
                    'quantity' => $quantity,
                    'shipping' => $shipping[0]['price'] ?? 0,
                    'service' => $shipping[0]['service'] ?? 'snail_mail'
                ];
            }
        }

        // Photo Prints items
        if (!empty($photoPrintsItems)) {
            $quantity = array_sum(array_column($photoPrintsItems, 'quantity'));
            $shipping = $this->calculateTierBasedShipping($quantity, 'photo_prints');
            if (!empty($shipping)) {
                $breakdown['photo_prints'] = [
                    'quantity' => $quantity,
                    'shipping' => $shipping[0]['price'] ?? 0,
                    'service' => $shipping[0]['service'] ?? 'snail_mail'
                ];
            }
        }

        // Canvas items
        if (!empty($canvasItems)) {
            $shipping = $this->getFixedShippingForCategory('canvas');
            $breakdown['canvas'] = [
                'quantity' => array_sum(array_column($canvasItems, 'quantity')),
                'shipping' => $shipping['snail_mail'] ?? 0,
                'service' => 'snail_mail'
            ];
        }

        // Photo Enlargements
        if (!empty($photoEnlargementItems)) {
            $shipping = $this->getFixedShippingForCategory('photo_enlargements');
            $breakdown['photo_enlargements'] = [
                'quantity' => array_sum(array_column($photoEnlargementItems, 'quantity')),
                'shipping' => $shipping['snail_mail'] ?? 0,
                'service' => 'snail_mail'
            ];
        }

        // Posters
        if (!empty($postersItems)) {
            $shipping = $this->getFixedShippingForCategory('posters');
            $breakdown['posters'] = [
                'quantity' => array_sum(array_column($postersItems, 'quantity')),
                'shipping' => $shipping['snail_mail'] ?? 0,
                'service' => 'snail_mail'
            ];
        }

        // Hand Craft
        if (!empty($handCraftItems)) {
            $shipping = $this->getFixedShippingForCategory('hand_craft');
            $breakdown['hand_craft'] = [
                'quantity' => array_sum(array_column($handCraftItems, 'quantity')),
                'shipping' => $shipping['snail_mail'] ?? 0,
                'service' => 'snail_mail'
            ];
        }

        // Photos for Sale
        if (!empty($photosForSaleItems)) {
            $shipping = $this->getFixedShippingForCategory('photos_for_sale');
            $breakdown['photos_for_sale'] = [
                'quantity' => array_sum(array_column($photosForSaleItems, 'quantity')),
                'shipping' => $shipping['snail_mail'] ?? 0,
                'service' => 'snail_mail'
            ];
        }

        // Test Print items
        if (!empty($testPrintItems)) {
            $quantity = array_sum(array_column($testPrintItems, 'quantity'));
            $testPrintShipping = \App\Models\Shipping::where('is_test_print', '1')->first();
            $breakdown['test_print'] = [
                'quantity' => $quantity,
                'shipping' => ($testPrintShipping ? $testPrintShipping->amount * $quantity : 0),
                'service' => 'snail_mail'
            ];
        }

        // Gift Cards (no shipping)
        if (!empty($giftCardItems)) {
            $breakdown['gift_card'] = [
                'quantity' => array_sum(array_column($giftCardItems, 'quantity')),
                'shipping' => 0,
                'service' => 'none'
            ];
        }

        return $breakdown;
    }
    
    /**
     * Calculate tier-based shipping for prints using database rules
     */
    protected function calculateTierBasedShipping($totalQuantity, $category = 'scrapbook_page_printing')
    {
        // Get shipping category from database
        $shippingCategory = \App\Models\ShippingCategory::where('name', $category)->first();
        
        if (!$shippingCategory) {
            return [];
        }
        
        // Get shipping rules from database
        $shippingRules = \App\Models\ShippingRule::where('shipping_category_id', $shippingCategory->id)
            ->orderBy('priority')
            ->get();
        
        $shippingOptions = [];
        
        foreach ($shippingRules as $rule) {
            // Parse condition to determine if this rule applies
            $applies = $this->checkQuantityCondition($totalQuantity, $rule->condition);
            
            if ($applies) {
                $shippingOptions[] = [
                    'carrier' => $rule->carrier,
                    'service' => $rule->service,
                    'price' => $rule->price,
                    'delivery_time' => $rule->delivery_time,
                    'weight' => null,
                    'note' => $rule->condition . ' prints tier'
                ];
            }
        }
        
        return $shippingOptions;
    }

    /**
     * Calculate Photo Prints tier-based shipping using database rules
     */
    protected function calculatePhotoPrintsTierShipping($totalQuantity)
    {
        return $this->calculateTierBasedShipping($totalQuantity, 'photo_prints');
    }

    /**
     * Check if quantity matches the condition
     */
    protected function checkQuantityCondition($quantity, $condition)
    {
        if ($condition === 'fixed') {
            return true; // Fixed pricing always applies
        }
        
        // Parse range conditions like "1-60", "61-100", "101+"
        if (strpos($condition, '-') !== false) {
            // Range condition like "1-60"
            list($min, $max) = explode('-', $condition);
            return $quantity >= (int)$min && $quantity <= (int)$max;
        } elseif (strpos($condition, '+') !== false) {
            // Plus condition like "101+"
            $min = (int)str_replace('+', '', $condition);
            return $quantity >= $min;
        }
        
        return false;
    }

    /**
     * Get fixed shipping pricing for a category from database
     */
    protected function getFixedShippingForCategory($categoryName)
    {
        Log::info('Getting fixed shipping for category:', ['category_name' => $categoryName]);
        
        $shippingCategory = \App\Models\ShippingCategory::where('name', $categoryName)->first();
        
        if (!$shippingCategory) {
            Log::warning('Shipping category not found:', ['category_name' => $categoryName]);
            return ['snail_mail' => 0, 'express' => 0];
        }
        
        Log::info('Shipping category found:', [
            'category_id' => $shippingCategory->id,
            'category_name' => $shippingCategory->name,
            'pricing_type' => $shippingCategory->pricing_type
        ]);
        
        $shippingRules = \App\Models\ShippingRule::where('shipping_category_id', $shippingCategory->id)
            ->where('condition', 'fixed')
            ->orderBy('priority')
            ->get();
        
        Log::info('Shipping rules found:', [
            'category_name' => $categoryName,
            'rules_count' => $shippingRules->count(),
            'rules' => $shippingRules->toArray()
        ]);
        
        $pricing = ['snail_mail' => 0, 'express' => 0];
        
        foreach ($shippingRules as $rule) {
            if ($rule->service === 'snail_mail') {
                $pricing['snail_mail'] = $rule->price;
            } elseif ($rule->service === 'express') {
                $pricing['express'] = $rule->price;
            }
        }
        
        Log::info('Final pricing for category:', [
            'category_name' => $categoryName,
            'pricing' => $pricing
        ]);
        
        return $pricing;
    }
    
    /**
     * Filter shipping options to remove duplicates and ensure only 2 options
     */
    protected function filterShippingOptions($options)
    {
        $filteredOptions = [];
        $snailMailFound = false;
        $expressFound = false;
        
        foreach ($options as $option) {
            if ($option['service'] === 'snail_mail' && !$snailMailFound) {
                $filteredOptions[] = $option;
                $snailMailFound = true;
            } elseif ($option['service'] === 'express' && !$expressFound) {
                $filteredOptions[] = $option;
                $expressFound = true;
            }
        }
        
        return $filteredOptions;
    }
    
    /**
     * Get shipping tiers for display
     */
    public function getShippingTiers($category = 'scrapbook_page_printing')
    {
        return ShippingTier::getTiersForCategory($category);
    }
    
    /**
     * Get shipping options for a specific quantity
     */
    public function getShippingOptionsForQuantity($quantity, $category = 'scrapbook_page_printing')
    {
        $tier = ShippingTier::getTierForQuantity($category, $quantity);
        
        if ($tier) {
            return [
                'snail_mail' => [
                    'price' => $tier->snail_mail_price,
                    'delivery_time' => '5-10 business days'
                ],
                'express' => [
                    'price' => $tier->express_price,
                    'delivery_time' => '1-2 business days'
                ]
            ];
        }
        
                return null;
    }
} 