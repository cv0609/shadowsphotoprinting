# New Shipping Logic Implementation

## Overview

The shipping system has been updated to implement a new pricing structure that differentiates between combined orders and separate orders, with special handling for Photo Print and Scrapbook Print categories.

## Key Changes

### 1. Combined Orders (Multiple Categories)
- **Always use fixed pricing regardless of quantities**
- **Snail Mail**: $22.60
- **Express**: $31.21

### 2. Separate Photo Print/Scrapbook Print Orders
- **Use tier-based pricing based on COMBINED quantity**
- **1-60 prints**: $15.00 snail mail, $20.00 express
- **61-100 prints**: $18.40 snail mail, $22.65 express  
- **101+ prints**: $22.60 snail mail, $30.21 express

### 3. Other Categories
- **Use their individual fixed pricing** as defined in the database

## How It Works

### Order Type Detection
The system automatically detects the order type by counting how many different product categories are present:

```php
protected function isCombinedOrder($scrapbookItems, $canvasItems, $photoEnlargementItems, $photoPrintsItems, $postersItems, $handCraftItems, $photosForSaleItems, $giftCardItems, $otherItems)
{
    $categoriesWithItems = 0;
    
    if (!empty($scrapbookItems)) $categoriesWithItems++;
    if (!empty($canvasItems)) $categoriesWithItems++;
    if (!empty($photoEnlargementItems)) $categoriesWithItems++;
    if (!empty($photoPrintsItems)) $categoriesWithItems++;
    if (!empty($postersItems)) $categoriesWithItems++;
    if (!empty($handCraftItems)) $categoriesWithItems++;
    if (!empty($photosForSaleItems)) $categoriesWithItems++;
    if (!empty($giftCardItems)) $categoriesWithItems++;
    if (!empty($otherItems)) $categoriesWithItems++;
    
    // If more than 1 category has items, it's a combined order
    return $categoriesWithItems > 1;
}
```

### Shipping Calculation Logic

#### Combined Orders
```php
protected function calculateCombinedOrderShipping($scrapbookItems, $canvasItems, $photoEnlargementItems, $photoPrintsItems, $postersItems, $handCraftItems, $photosForSaleItems, $giftCardItems, $otherItems, $testPrintItems)
{
    // Combined orders always use fixed pricing
    $fixedShippingSnailMail = 22.60;
    $fixedShippingExpress = 31.21;
    
    // Add test print shipping if applicable
    // Return fixed pricing options
}
```

#### Separate Orders
```php
protected function calculateSeparateOrderShipping($scrapbookItems, $canvasItems, $photoEnlargementItems, $photoPrintsItems, $postersItems, $handCraftItems, $photosForSaleItems, $giftCardItems, $otherItems, $testPrintItems)
{
    // Check if this is a Photo Print or Scrapbook Print only order
    $hasPhotoPrints = !empty($photoPrintsItems);
    $hasScrapbook = !empty($scrapbookItems);
    $hasOtherCategories = !empty($canvasItems) || !empty($photoEnlargementItems) || !empty($postersItems) || !empty($handCraftItems) || !empty($photosForSaleItems) || !empty($giftCardItems) || !empty($otherItems);
    
    if (($hasPhotoPrints || $hasScrapbook) && !$hasOtherCategories) {
        // Use tier-based pricing based on combined quantity
        $totalQuantity = 0;
        if ($hasPhotoPrints) $totalQuantity += array_sum(array_column($photoPrintsItems, 'quantity'));
        if ($hasScrapbook) $totalQuantity += array_sum(array_column($scrapbookItems, 'quantity'));
        
        return $this->calculateCombinedTierShipping($totalQuantity);
    } else {
        // Use existing logic for other categories
        return $this->calculateAustraliaPostMixedShipping(...);
    }
}
```

## Examples

### Example 1: Combined Order
- **Items**: 30 Photo Prints + 1 Canvas
- **Result**: Fixed pricing - $22.60 snail mail, $31.21 express
- **Reason**: Multiple categories (Photo Prints + Canvas)

### Example 2: Separate Photo Print Order
- **Items**: 40 Photo Prints only
- **Result**: Tier-based pricing - $15.00 snail mail, $20.00 express
- **Reason**: Single category, 1-60 tier

### Example 3: Combined Photo Print + Scrapbook
- **Items**: 40 Photo Prints + 25 Scrapbook
- **Total**: 65 items
- **Result**: Tier-based pricing - $18.40 snail mail, $22.65 express
- **Reason**: Both are Photo Print/Scrapbook categories, combined quantity = 65 (61-100 tier)

### Example 4: Large Combined Order
- **Items**: 120 Photo Prints + 1 Canvas + 2 Posters
- **Result**: Fixed pricing - $22.60 snail mail, $31.21 express
- **Reason**: Multiple categories (Photo Prints + Canvas + Posters)

## Database Structure

The shipping rules are stored in the database with the following structure:

### Shipping Categories
- `scrapbook_page_printing` - Tier-based pricing
- `photo_prints` - Tier-based pricing
- `canvas` - Fixed pricing
- `photo_enlargements` - Fixed pricing
- `posters` - Fixed pricing
- `hand_craft` - Fixed pricing
- `photos_for_sale` - Fixed pricing
- `gift_card` - Zero shipping

### Shipping Rules
Each category has rules with:
- `condition`: Quantity range (e.g., "1-60", "61-100", "101+") or "fixed"
- `service`: "snail_mail" or "express"
- `price`: Shipping cost
- `priority`: Rule ordering

## Implementation Files

### Core Service
- `app/Services/CartShippingService.php` - Main shipping calculation logic

### Database
- `database/seeders/ShippingDataSeeder.php` - Shipping rules and pricing data

### Models
- `app/Models/ShippingCategory.php` - Shipping category definitions
- `app/Models/ShippingRule.php` - Individual shipping rules
- `app/Models/ProductShippingMapping.php` - Product to shipping category mapping

## Testing

Run the test script to see examples:
```bash
php test_shipping_logic.php
```

## Benefits

1. **Simplified combined order pricing** - No complex calculations for mixed categories
2. **Fair tier-based pricing** - Photo Print and Scrapbook Print customers get quantity-based discounts
3. **Consistent pricing** - All other categories use predictable fixed pricing
4. **Easy to maintain** - Clear logic separation between order types

## Future Enhancements

- Add support for international shipping
- Implement weight-based pricing for heavy items
- Add seasonal pricing adjustments
- Support for multiple carriers beyond Australia Post
