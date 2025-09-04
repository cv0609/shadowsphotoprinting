@php
   $CartService = app(App\Services\CartService::class);
@endphp

@foreach($products as $key => $product)
@php
    $product_sale_price =  $CartService->getProductSalePrice($product->id);
    $is_package_product = null;
    $package_price = null;
    $package_product_id = null;
    $package_slug = null;

    if(isset($product->is_package) && !empty($product->is_package) && ($product->is_package == 1)){
        $is_package_product = 1;
        $package_price = $product->package_price;
        $package_product_id = $product->package_product_id;
        $package_slug = $product->package_slug;
        
        // Debug: Log package data
        \Log::info('Package product data:', [
            'product_id' => $product->id,
            'is_package' => $product->is_package,
            'package_price' => $package_price,
            'package_product_id' => $package_product_id,
            'package_slug' => $package_slug
        ]);
    }
@endphp
<tr class="gi-prod">
    <td>
        <input 
        type="number" 
        name="quantity" 
        id="quantity-{{ $key }}" 
    
        data-price="{{ isset($product_sale_price) && !empty($product_sale_price) ? $product_sale_price : (isset($product->test_print[0]->product_price) ? $product->test_print[0]->product_price : $product->product_price) }}"
    
        data-productid="{{ $product->id }}" 
        data-testprint="{{ isset($product->test_print[0]->qty) ? '1' : '' }}" 
        data-category_id="{{ isset($product->test_print[0]->category_id) ? $product->test_print[0]->category_id : $product->category_id }}"
        data-test_print_price="{{ isset($product->test_print[0]->product_price) ? $product->test_print[0]->product_price : '' }}"
        data-is_package="{{ $is_package_product ?? 0 }}" 
        data-package_price="{{ $package_price ?? '' }}" 
        data-package_product_id="{{ $package_product_id ?? '' }}" 
        data-package_slug="{{ $package_slug ?? ''}}"
        data-test_print_qty="{{ isset($product->test_print[0]->qty) ? $product->test_print[0]->qty : '' }}" 
        value="0"
    >
    </td>
    <td>
        {{ $product->product_title }}
    </td>
    <td>

    <span>

        $ {{ 
            isset($product->test_print[0]->product_price) 
                ? $product->test_print[0]->product_price 
                : (isset($product_sale_price) && !empty($product_sale_price) 
                    ? $product_sale_price 
                    : $product->product_price)
        }}
        

    </span>

    </td>
    <td>
        <span id="quantity-price-{{$key}}">$ <span class="show-details">0.00</span> </span>
    </td>
</tr>
@endforeach
