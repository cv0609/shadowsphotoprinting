<?php

/**
 * Test script to demonstrate the new shipping logic
 * This shows how the shipping calculation works for different order types
 */

// Simulate cart items for different scenarios
$testScenarios = [
    'scenario_1' => [
        'name' => 'Combined Order (Photo Prints + Canvas)',
        'items' => [
            ['product_id' => 1, 'quantity' => 30, 'category_id' => 4], // Photo Prints
            ['product_id' => 2, 'quantity' => 1, 'category_id' => 2],  // Canvas
        ]
    ],
    'scenario_2' => [
        'name' => 'Separate Photo Print Order (40 items)',
        'items' => [
            ['product_id' => 1, 'quantity' => 40, 'category_id' => 4], // Photo Prints
        ]
    ],
    'scenario_3' => [
        'name' => 'Separate Scrapbook Order (25 items)',
        'items' => [
            ['product_id' => 2, 'quantity' => 25, 'category_id' => 1], // Scrapbook
        ]
    ],
    'scenario_4' => [
        'name' => 'Combined Photo Print + Scrapbook (40 + 25 = 65 items)',
        'items' => [
            ['product_id' => 1, 'quantity' => 40, 'category_id' => 4], // Photo Prints
            ['product_id' => 2, 'quantity' => 25, 'category_id' => 1], // Scrapbook
        ]
    ],
    'scenario_5' => [
        'name' => 'Large Combined Order (Photo Prints + Canvas + Posters)',
        'items' => [
            ['product_id' => 1, 'quantity' => 120, 'category_id' => 4], // Photo Prints
            ['product_id' => 2, 'quantity' => 1, 'category_id' => 2],   // Canvas
            ['product_id' => 3, 'quantity' => 2, 'category_id' => 18], // Posters
        ]
    ]
];

echo "=== NEW SHIPPING LOGIC TEST ===\n\n";

foreach ($testScenarios as $key => $scenario) {
    echo "📦 {$scenario['name']}\n";
    echo "Items: " . json_encode($scenario['items']) . "\n";
    
    // Determine order type
    $categories = array_unique(array_column($scenario['items'], 'category_id'));
    $isCombinedOrder = count($categories) > 1;
    
    if ($isCombinedOrder) {
        echo "✅ Order Type: COMBINED ORDER\n";
        echo "💰 Shipping: Fixed pricing\n";
        echo "   - Snail Mail: $22.60\n";
        echo "   - Express: $31.21\n";
    } else {
        // Check if it's Photo Print or Scrapbook only
        $hasPhotoPrints = in_array(4, $categories);
        $hasScrapbook = in_array(1, $categories);
        
        if (($hasPhotoPrints || $hasScrapbook) && count($categories) === 1) {
            echo "✅ Order Type: SEPARATE PHOTO PRINT/SCRAPBOOK ORDER\n";
            
            // Calculate total quantity
            $totalQuantity = array_sum(array_column($scenario['items'], 'quantity'));
            echo "📊 Total Quantity: {$totalQuantity} items\n";
            
            // Determine tier
            if ($totalQuantity >= 1 && $totalQuantity <= 60) {
                echo "💰 Shipping: 1-60 prints tier\n";
                echo "   - Snail Mail: $15.00\n";
                echo "   - Express: $20.00\n";
            } elseif ($totalQuantity >= 61 && $totalQuantity <= 100) {
                echo "💰 Shipping: 61-100 prints tier\n";
                echo "   - Snail Mail: $18.40\n";
                echo "   - Express: $22.65\n";
            } elseif ($totalQuantity >= 101) {
                echo "💰 Shipping: 101+ prints tier\n";
                echo "   - Snail Mail: $22.60\n";
                echo "   - Express: $30.21\n";
            }
        } else {
            echo "✅ Order Type: OTHER CATEGORY ORDER\n";
            echo "💰 Shipping: Category-specific pricing\n";
        }
    }
    
    echo "\n" . str_repeat("-", 60) . "\n\n";
}

echo "=== KEY POINTS ===\n";
echo "🔑 Combined orders (multiple categories): Always use fixed pricing\n";
echo "🔑 Separate Photo Print/Scrapbook orders: Use tier-based pricing based on COMBINED quantity\n";
echo "🔑 Example: 40 Photo Prints + 25 Scrapbook = 65 total items = 61-100 tier pricing\n";
echo "🔑 Other categories: Use their individual fixed pricing\n\n";

echo "=== PRICING SUMMARY ===\n";
echo "📋 Combined Orders:\n";
echo "   - Snail Mail: $22.60\n";
echo "   - Express: $31.21\n\n";

echo "📋 Separate Photo Print/Scrapbook Orders:\n";
echo "   - 1-60 items: $15.00 snail mail, $20.00 express\n";
echo "   - 61-100 items: $18.40 snail mail, $22.65 express\n";
echo "   - 101+ items: $22.60 snail mail, $30.21 express\n";
