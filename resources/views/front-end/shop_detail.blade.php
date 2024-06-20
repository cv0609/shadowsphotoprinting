@extends('front-end.layout.main')
@section('content')
<section class="envira-gallery">
            <div class="container">
                <div class="decoding">
                    @if(Session::has('temImages'))
                    @foreach($imageName as $temImages)
                    <div class="decoding-wrapper">
                        <img src="{{ asset('storage/temp/' . $temImages) }}" alt="">
                        <input type="checkbox" name="" class="d-none">
                         <div id="unchecked-img" class="common_check"> <img src="/assets/images/unactive_image_tick.png" alt="" class="img-fluid"></div>
                         <div id="checked-img" class="d-none common_check"><img src="assets/images/active_image_tick.png" alt="" class="img-fluid"></div>
                    </div>
                    @endforeach
                    @endif
                   
                </div>
                <div class="quanti-wrapper">
                    <div class="quanti">
                        <a class="quanti-btn" id="selectall">Select All</a>
                        <a class="quanti-btn" id="deselectall">Deselect All</a>
                    </div>
                </div>
            </div>
        </section>


        <section class="fw-area">
            <div class="container">
                <div class="fw-area-wrap">
                    <div class="fw-head">
                        <h4>Cart contents</h4>
                        <div class="quanti">
                            <a href="#">VIEW CART</a>
                        </div>
                    </div>
                    <div class="cart-totals">
                        <div class="cart-items">
                            <span id="cart-total-itmes">0 items</span>
                        </div>
                        <div class="cart-items">
                            <span id="cart-total-price">$0.00</span>
                        </div>
                    </div>
                    <div class="fw-products">
                        <h4>PRODUCTS</h4>
                        <div class="fw-products-cats">
                            <select name="category" id="category">
                                <option value="all">ALL</option>
                                @foreach ($productCategories as $productCategory)
                                    <option value="{{ $productCategory->slug }}">{{ $productCategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fw-products-box">
                            <table>
                                <thead>
                                    <tr>
                                        <th>QTY</th>
                                        <th>DESCRIPTION</th>
                                        <th>PRICE</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                  <tbody id="products-main">
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

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="fw-buttons">
                    <div class="quanti">
                        <a href="#" id="add-to-cart">ADD TO CART</a>
                    </div>
                    <div class="quanti">
                        <a href="#">VIEW CART / CHECKOUT</a>
                    </div>
                </div>
            </div>
        </section>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    // Event delegation for dynamically added elements
    $(document).on('keyup change', "input[name=quantity]", function() {
        updateCartTotals();
    });

    // Event listener for "Add to Cart" button
    $("#add-to-cart").on('click', function(event) {
        event.preventDefault(); // Prevent default action

        let cartItems = [];
        let total = 0;

        $("input[name=quantity]").each(function() {
            let quantity = $(this).val();
            if (quantity !== '' && quantity > 0) {
                let price = parseFloat($(this).data('price'));
                let productId = $(this).data('productid'); // Assuming the ID is in the format "quantity-{productId}"
                let totalPrice = quantity * price;
                total += totalPrice;
                cartItems.push({
                    product_id: productId,
                    quantity: parseFloat(quantity),
                    price: price
                });
            }
        });

        if (cartItems.length > 0) {
            // Send cart items to the server
            $.ajax({
                url: "{{ route('add-to-cart') }}", // Replace with your route
                method: 'POST',
                data: {
                    cart_items: cartItems,
                    total: total,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(response) {
                    // Update the cart totals and item count
                    $("#cart-total-itmes").text(response.total_items + ' items');
                    $("#cart-total-price").text('$' + response.total_price.toFixed(2));
                    alert('Items added to cart successfully!');
                },
                error: function(xhr, status, error) {
                    console.error('Error adding items to cart:', error);
                }
            });
        } else {
            alert('No items to add to cart!');
        }
    });

    // Function to update the cart totals
    function updateCartTotals() {
        let total = 0;
        let totalQuantity = 0;

        $("input[name=quantity]").each(function() {
            let quantity = $(this).val();
            if (quantity !== '' && quantity > 0) {
                quantity = parseFloat(quantity);
                let price = parseFloat($(this).data('price'));
                let totalPrice = quantity * price;
                total += totalPrice;
                totalQuantity += quantity;

                // Update the total price for the individual product
                let rowId = $(this).attr('id').split('-')[1];
                $("#quantity-price-" + rowId).text('$' + totalPrice.toFixed(2));
            }
        });

        $("#cart-total-price").text('$' + total.toFixed(2)); // Format total to 2 decimal places
        $("#cart-total-itmes").text(totalQuantity + ' items');
    }

    // Initial update of cart totals on page load
    updateCartTotals();
});

 $("#category").on('change',function(){

    $.post("{{ route('products-by-category') }}",
    {
        slug: $(this).val(),
        '_token': "{{ csrf_token() }}"
    },
    function(res){
         $("#products-main").html(res);
    });
 })
</script>
@endsection
