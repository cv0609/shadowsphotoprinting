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

            // if (isset($item['is_test_print']) && $item['is_test_print'] == '1') {
            //     $testPrintItems[] = $item;
            // }
            
            // Check if this is a special product type (hand_craft, photo_for_sale, gift_card)
            if (isset($item['product_type'])) {
                switch ($item['product_type']) {
                    case 'hand_craft':
                        $handCraftItems[] = $item;
                        break;
                    case 'photo_for_sale':
                        $photosForSaleItems[] = $item;
                        break;
                    case 'gift_card':
                        $giftCardItems[] = $item;
                        break;
                    default:
                        // Try to find in products table
                        $product = Product::find($item['product_id']);
                        if ($product) {
                            switch ($product->category_id) {
                                case 1: // Scrapbook page printing
                                   
                                    if (isset($item['is_test_print']) && $item['is_test_print'] == '1') {
                                        $testPrintItems[] = $item;
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
        

        

        
        // Calculate Australia Post mixed shipping
        $shippingOptions = $this->calculateAustraliaPostMixedShipping($scrapbookItems, $canvasItems, $photoEnlargementItems, $photoPrintsItems, $postersItems, $handCraftItems, $photosForSaleItems, $giftCardItems, $otherItems,$testPrintItems);
        
        // Remove duplicates and ensure only first snail_mail and express options per category
        $shippingOptions = $this->filterShippingOptions($shippingOptions);
        
        return $shippingOptions;
    }
    
    /**
     * Calculate shipping options per category for cart items
     */
    public function calculateShippingPerCategory($cartItems)
    {
        $categoryShippingOptions = [];
        
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
            // Check if this is a special product type
            if (isset($item['product_type'])) {
                switch ($item['product_type']) {
                    case 'hand_craft':
                        $handCraftItems[] = $item;
                        break;
                    case 'photo_for_sale':
                        $photosForSaleItems[] = $item;
                        break;
                    case 'gift_card':
                        $giftCardItems[] = $item;
                        break;
                    default:
                        // Try to find in products table
                $product = Product::find($item['product_id']);
                if ($product) {
                            switch ($product->category_id) {
                                case 1: // Scrapbook page printing
                                    if (isset($item['is_test_print']) && $item['is_test_print'] == '1') {
                                        $testPrintItems[] = $item;
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
                                    if (isset($item['is_test_print']) && $item['is_test_print'] == '1') {
                                        $testPrintItems[] = $item;
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
                                case 18: // Posters
                                    $postersItems[] = $item;
                                    break;
                                default:
                                    $otherItems[] = $item;
                                    break;
                            }
                        }
                        break;
                }
            }
        }
        
        // Calculate shipping options for each category
        if (!empty($scrapbookItems)) {
            $quantity = array_sum(array_column($scrapbookItems, 'quantity'));
            $categoryShippingOptions['scrapbook_page_printing'] = $this->calculateTierBasedShipping($quantity, 'scrapbook_page_printing');
        }
        
        if (!empty($photoPrintsItems)) {
            $quantity = array_sum(array_column($photoPrintsItems, 'quantity'));
            $categoryShippingOptions['photo_prints'] = $this->calculateTierBasedShipping($quantity, 'photo_prints');
        }
        
        if (!empty($canvasItems)) {
            $categoryShippingOptions['canvas'] = $this->getFixedShippingForCategory('canvas');
        }
        
        if (!empty($photoEnlargementItems)) {
            $categoryShippingOptions['photo_enlargements'] = $this->getFixedShippingForCategory('photo_enlargements');
        }
        
        if (!empty($postersItems)) {
            $categoryShippingOptions['posters'] = $this->getFixedShippingForCategory('posters');
        }
        
        if (!empty($handCraftItems)) {
            $categoryShippingOptions['hand_craft'] = $this->getFixedShippingForCategory('hand_craft');
        }
        
        if (!empty($photosForSaleItems)) {
            $categoryShippingOptions['photos_for_sale'] = $this->getFixedShippingForCategory('photos_for_sale');
        }
        
        if (!empty($giftCardItems)) {
            $categoryShippingOptions['gift_card'] = $this->getFixedShippingForCategory('gift_card');
        }
        
        if (!empty($testPrintItems)) {
            $quantity = array_sum(array_column($testPrintItems, 'quantity'));
            $testPrintShippingRecord = \App\Models\Shipping::where('is_test_print', '1')->first();
            if ($testPrintShippingRecord) {
                // Calculate quantity-based shipping but cap at $2.00 total
                $calculatedShipping = $testPrintShippingRecord->amount * $quantity;
                $testPrintShipping = min($calculatedShipping, 2.00); // Cap at $2.00
                
                $categoryShippingOptions['test_prints'] = [
                    [
                        'service' => 'snail_mail',
                        'price' => $testPrintShipping,
                        'delivery_time' => '5-10 business days',
                        'carrier' => 'Australia Post',
                        'note' => "Test prints: {$quantity} items × $" . number_format($testPrintShippingRecord->amount, 2) . " = $" . number_format($calculatedShipping, 2) . " (capped at $2.00)"
                    ],
                    [
                        'service' => 'express',
                        'price' => $testPrintShipping,
                        'delivery_time' => '1-2 business days',
                        'carrier' => 'Australia Post',
                        'note' => "Test prints: {$quantity} items × $" . number_format($testPrintShippingRecord->amount, 2) . " = $" . number_format($calculatedShipping, 2) . " (capped at $2.00)"
                    ]
                ];
            }
        }
        

        
        return $categoryShippingOptions;
    }

    /**
     * Calculate total shipping cost based on category-wise selections
     */
    public function calculateTotalShippingFromSelections($categorySelections)
    {
        $totalSnailMail = 0;
        $totalExpress = 0;
        
        foreach ($categorySelections as $category => $selection) {
            if (isset($selection['service']) && isset($selection['price'])) {
                if ($selection['service'] === 'snail_mail') {
                    $totalSnailMail += $selection['price'];
                } elseif ($selection['service'] === 'express') {
                    $totalExpress += $selection['price'];
                }
            }
        }
        
        return [
            'snail_mail' => $totalSnailMail,
            'express' => $totalExpress
        ];
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
            $handCraftShipping = $this->getFixedShippingForCategory('hand_craft');
            $fixedShippingSnailMail += $handCraftShipping['snail_mail'] ?? 0;
            $fixedShippingExpress += $handCraftShipping['express'] ?? 0;
        }
        
        // Photos for Sale fixed pricing
        if (!empty($photosForSaleItems)) {
            $photosForSaleShipping = $this->getFixedShippingForCategory('photos_for_sale');
            $fixedShippingSnailMail += $photosForSaleShipping['snail_mail'] ?? 0;
            $fixedShippingExpress += $photosForSaleShipping['express'] ?? 0;
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
        // Get the selected shipping service from the user's choice
        $selectedService = $selectedShipping['service'] ?? 'snail_mail';
        

        
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
                // Find the correct shipping option based on selected service
                $selectedShippingOption = collect($shipping)->first(function($option) use ($selectedService) {
                    return $option['service'] === $selectedService;
                });
                

                
                $breakdown['scrapbook_page_printing'] = [
                    'quantity' => $quantity,
                    'shipping' => $selectedShippingOption['price'] ?? 0,
                    'service' => $selectedService
                ];
            }
        }

        // Photo Prints items
        if (!empty($photoPrintsItems)) {
            $quantity = array_sum(array_column($photoPrintsItems, 'quantity'));
            $shipping = $this->calculateTierBasedShipping($quantity, 'photo_prints');
            

            
            if (!empty($shipping)) {
                // Find the correct shipping option based on selected service
                $selectedShippingOption = collect($shipping)->first(function($option) use ($selectedService) {
                    return $option['service'] === $selectedService;
                });
                

                
                $breakdown['photo_prints'] = [
                    'quantity' => $quantity,
                    'shipping' => $selectedShippingOption['price'] ?? 0,
                    'service' => $selectedService
                ];
            }
        }

        // Canvas items
        if (!empty($canvasItems)) {
            $shipping = $this->getFixedShippingForCategory('canvas');
            if (!empty($shipping)) {
                // Find the correct shipping option based on selected service
                $selectedShippingOption = collect($shipping)->first(function($option) use ($selectedService) {
                    return $option['service'] === $selectedService;
                });
                
                $breakdown['canvas'] = [
                    'quantity' => array_sum(array_column($canvasItems, 'quantity')),
                    'shipping' => $selectedShippingOption['price'] ?? 0,
                    'service' => $selectedService
                ];
            }
        }

        // Photo Enlargements
        if (!empty($photoEnlargementItems)) {
            $shipping = $this->getFixedShippingForCategory('photo_enlargements');
            if (!empty($shipping)) {
                // Find the correct shipping option based on selected service
                $selectedShippingOption = collect($shipping)->first(function($option) use ($selectedService) {
                    return $option['service'] === $selectedService;
                });
                
                $breakdown['photo_enlargements'] = [
                    'quantity' => array_sum(array_column($photoEnlargementItems, 'quantity')),
                    'shipping' => $selectedShippingOption['price'] ?? 0,
                    'service' => $selectedService
                ];
            }
        }

        // Posters
        if (!empty($postersItems)) {
            $shipping = $this->getFixedShippingForCategory('posters');
            if (!empty($shipping)) {
                // Find the correct shipping option based on selected service
                $selectedShippingOption = collect($shipping)->first(function($option) use ($selectedService) {
                    return $option['service'] === $selectedService;
                });
                
                $breakdown['posters'] = [
                    'quantity' => array_sum(array_column($postersItems, 'quantity')),
                    'shipping' => $selectedShippingOption['price'] ?? 0,
                    'service' => $selectedService
                ];
            }
        }

        // Hand Craft
        if (!empty($handCraftItems)) {
            $shipping = $this->getFixedShippingForCategory('hand_craft');
            if (!empty($shipping)) {
                // Find the correct shipping option based on selected service
                $selectedShippingOption = collect($shipping)->first(function($option) use ($selectedService) {
                    return $option['service'] === $selectedService;
                });
                
                $breakdown['hand_craft'] = [
                    'quantity' => array_sum(array_column($handCraftItems, 'quantity')),
                    'shipping' => $selectedShippingOption['price'] ?? 0,
                    'service' => $selectedService
                ];
            }
        }

        // Photos for Sale
        if (!empty($photosForSaleItems)) {
            $shipping = $this->getFixedShippingForCategory('photos_for_sale');
            if (!empty($shipping)) {
                // Find the correct shipping option based on selected service
                $selectedShippingOption = collect($shipping)->first(function($option) use ($selectedService) {
                    return $option['service'] === $selectedService;
                });
                
                $breakdown['photos_for_sale'] = [
                    'quantity' => array_sum(array_column($photosForSaleItems, 'quantity')),
                    'shipping' => $selectedShippingOption['price'] ?? 0,
                    'service' => $selectedService
                ];
            }
        }

        // Test Print items
        if (!empty($testPrintItems)) {
            $quantity = array_sum(array_column($testPrintItems, 'quantity'));
            // Test print shipping is always $2 per item
            $breakdown['test_print'] = [
                'quantity' => $quantity,
                'shipping' => 2.00,
                'service' => $selectedService
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

        
        $shippingCategory = \App\Models\ShippingCategory::where('name', $categoryName)->first();
        
        if (!$shippingCategory) {
            return [];
        }
        

        
        $shippingRules = \App\Models\ShippingRule::where('shipping_category_id', $shippingCategory->id)
            ->where('condition', 'fixed')
            ->orderBy('priority')
            ->get();
        

        
        $shippingOptions = [];
        
        foreach ($shippingRules as $rule) {
            $shippingOptions[] = [
                'carrier' => $rule->carrier,
                'service' => $rule->service,
                'price' => $rule->price,
                'delivery_time' => $rule->delivery_time,
                'weight' => null,
                'note' => 'Fixed pricing'
            ];
        }
        

        
        return $shippingOptions;
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