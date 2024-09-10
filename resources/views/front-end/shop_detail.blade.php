@extends('front-end.layout.main')
@section('content')
@php
   $CartService = app(App\Services\CartService::class);
@endphp

<section class="envira-gallery">
    <div class="container">
        <div class="coupon-wrapper d-none" id="add_to_cart_msg">
            <p class="text-center">Item added to cart successfully.
            </p>
        </div>
        <div class="decoding">

            @if(Session::has('temImages'))

                @php
                    $counter = 1;
                @endphp
                @foreach($imageName as $temImages)
                    <div class="decoding-wrapper selected-images">
                        <a href="javascript:void(0)" class="product-img">
                            <img class="main_check_img" src="{{ asset('storage/temp/' . $temImages) }}" alt="">
                        </a>

                        <input type="checkbox" name="selected-image[]" value="0" class="d-none" data-img="{{ asset('storage/temp/' . $temImages) }}" id="image-checkbox-{{ $counter }}">
                        <div id="unchecked-img-{{ $counter }}" class="common_check unchecked-img" onclick="check_img({{ $counter }})">
                            <img src="/assets/images/unactive_image_tick.png" alt="" class="img-fluid">
                        </div>
                        <div id="checked-img-{{ $counter }}" class="d-none common_check checked-img" onclick="uncheck_img({{ $counter }})">
                            <img src="assets/images/active_image_tick.png" alt="" class="img-fluid">
                        </div>
                        <p class="title_image_p">{{ $temImages }}</p>
                    </div>
                    @php
                        $counter++;
                    @endphp
                @endforeach
 
            @endif

                </div>
                <div class="quanti-wrapper">
                    <div class="quanti">
                        <a class="quanti-btn selected-all" id="selectall">Select All</a>
                        <a class="quanti-btn" id="deselectall">Deselect All</a>
                    </div>
                </div>
            </div>
        </section>


        <div id="ImgViewer" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" id="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                  <img src="" alt="image" id="modal-img">
                </div>
              </div>
            </div>
        </div>

        <section class="fw-area">
            <div class="container">
                <div class="fw-area-wrap">
                    <div class="fw-head">
                        <h4>Cart contents</h4>
                        <div class="quanti">
                            <a href="{{ route('cart') }}">VIEW CART</a>
                        </div>
                    </div>
                    <div class="cart-totals">
                        <div class="cart-items">
                            <span id="cart-total-itmes"><span class="show-details">0</span>items</span>
                        </div>
                        <div class="cart-items">
                            <span id="cart-total-price">$<span class="show-details">0.00</span> </span>
                        </div>
                    </div>
                    <div class="fw-products">
                        <h4>PRODUCTS</h4>
                        <div class="fw-products-cats">
                            <select name="category" id="category">
                                <option value="all">All</option>
                                @foreach ($productCategories as $productCategory)
                                    <option value="{{ $productCategory->slug }}">{{ ucfirst($productCategory->name) }}</option>
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
                                            <span class="product-s-price">

                                                ${{ isset($product_sale_price) && !empty($product_sale_price) ? $product_sale_price : $product->product_price  }}

                                            </span>
                                        </td>
                                        <td>
                                            <span id="quantity-price-{{$key}}">$ <span class="show-details">0.00</span> </span>
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
                        <a href="{{ route('cart') }}">VIEW CART / CHECKOUT</a>
                    </div>
                </div>
            </div>
        </section>
@endsection
@section('scripts')
<script>


function check_img(counter) {
    var $input = $("#image-checkbox-" + counter);
    var $checkedImg = $("#checked-img-" + counter);
    var $uncheckedImg = $("#unchecked-img-" + counter);

    $input.val("1");
    $checkedImg.removeClass('d-none');
    $uncheckedImg.addClass('d-none');
}

function uncheck_img(counter) {
    var $input = $("#image-checkbox-" + counter);
    var $checkedImg = $("#checked-img-" + counter);
    var $uncheckedImg = $("#unchecked-img-" + counter);

    $input.val("0");
    $checkedImg.addClass('d-none');
    $uncheckedImg.removeClass('d-none');
}


$(document).ready(function() {

    $(document).on('keyup change', "input[name=quantity]", function() {
        updateCartTotals();
    });

    $(".product-img").on('click',function(){
        $("#modal-img").attr('src',$(this).children('img').attr('src'));
        $("#ImgViewer").modal('show');
    });

    $("#modal-close").on('click',function(){
        $("#ImgViewer").modal('hide');
    })

    // Event listener for "Add to Cart" button
    $("#add-to-cart").on('click', function(event) {
        event.preventDefault(); // Prevent default action

        let cartItems = [];
        let total = 0;
        let selectedImages = [];
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

        $("input[name='selected-image[]']").each(function() {
            if($(this).val() == "1")
             {
                selectedImages.push($(this).data('img'));
             }
        });

        if (selectedImages === null || selectedImages.length === 0)
            {
                alert('Please select an image');
                return false;
            }

        if (cartItems.length > 0) {
            // Send cart items to the server
            $.ajax({
                url: "{{ route('add-to-cart') }}", // Replace with your route
                method: 'POST',
                data: {
                    cart_items: cartItems,
                    total: total,
                    selectedImages:selectedImages,
                    item_type:'shop',
                    '_token': "{{ csrf_token() }}"
                },
                success: function(response) {

                    $('#add_to_cart_msg').css({'margin-top':'10px','font-weight':'bold'});
                    $('#add_to_cart_msg').removeClass('d-none');
                    $('.kt-cart-total').text(response.count);

                    $("input[name=quantity]").each(function() {
                       $(this).val('');
                    });
                    $(".show-details").text("0.00");
                    $("#cart-total-itmes").children(".show-details").text("0");

                    if ($('#add_to_cart_msg').length) {
                        setTimeout(function() {
                            // $('#add_to_cart_msg').css({'margin-top':'10px','font-weight':'bold'});
                            $('#add_to_cart_msg').addClass('d-none');
                        }, 2000);
                    }
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
            var quantity = $(this).val();
            var rowId = $(this).attr('id').split('-')[1];

            var price = parseFloat($(this).data('price'));
            console.log(price,'price');
            var totalPrice = quantity * price;
            total += totalPrice;
            totalQuantity += +quantity;

            if(quantity == ''){
                totalPrice=0;
                $("#quantity-price-" + rowId).children('.show-details').text(totalPrice.toFixed(2));
            }

            if (quantity !== '' && quantity > 0) {
                $("#quantity-price-" + rowId).children('.show-details').text(totalPrice.toFixed(2));
            }
        });

        $("#cart-total-price").children('.show-details').text( total.toFixed(2)); // Format total to 2 decimal places
        $("#cart-total-itmes").children('.show-details').text(totalQuantity);
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
<script>
    $(document).ready(function() {
        $('#selectall').click(function() {
            $('input[name="selected-image[]"]').each(function() {
                $(this).prop('checked', true);
                $(this).val("1");
                $(this).siblings('.unchecked-img').addClass('d-none');
                $(this).siblings('.checked-img').removeClass('d-none');
            });;
        });

        $('#deselectall').click(function() {
            $('.selected-images input[type="checkbox"]').each(function() {
                $(this).prop('checked', false);
                $(this).val("0");
                $(this).nextAll('.checked-img').addClass('d-none');
                $(this).nextAll('.unchecked-img').removeClass('d-none');
            });
        });
    });
</script>

@endsection
