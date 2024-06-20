@foreach($products as $key => $product)
<tr class="gi-prod">
    <td>
        <input type="number" name="quantity" id="quantity-{{$key}}" data-price="{{ $product->product_price }}" data-productid="{{ $product->id }}">
    </td>
    <td>
        {{ $product->product_title }}
    </td>
    <td>
        <span>${{ $product->product_price }}</span>
    </td>
    <td>
        <span id="quantity-price-{{$key}}">$0.00</span>
    </td>
</tr>
@endforeach
