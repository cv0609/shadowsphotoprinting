@php
   $CartService = app(App\Services\CartService::class);
@endphp

@foreach($products as $key => $product)
@php
    $product_sale_price =  $CartService->getProductSalePrice($product->id);
@endphp
<tr class="gi-prod">
    <td>
        <input type="number" name="quantity" id="quantity-{{$key}}"
        data-price="{{ isset($product_sale_price) && !empty($product_sale_price) ? $product_sale_price : $product->product_price }}"
        data-productid="{{ $product->id }}">
    </td>
    <td>
        {{ $product->product_title }}
    </td>
    <td>

    <span>
        ${{ isset($product_sale_price) && !empty($product_sale_price) ? $product_sale_price : $product->product_price  }}
    </span>

    </td>
    <td>
        <span id="quantity-price-{{$key}}">$ <span class="show-details">0.00</span> </span>
    </td>
</tr>
@endforeach
