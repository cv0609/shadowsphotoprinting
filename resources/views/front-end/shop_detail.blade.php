@extends('front-end.layout.main')
@section('content')
@php
    $CartService = app(App\Services\CartService::class);

    // function getS3Img($str, $size){
    //     $str = str_replace('raw/', '', $str);
    //     $str = preg_replace('/(.*)(\/[^\/]*?$)/', "$1/$size$2", $str);
    //     return $str;
    // }

    // function getS3ImgName($str){
    //     $str = preg_replace('/.*\/([^\/]*?$)/', "$1", $str);
    //     return $str;
    // }
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
                            <img class="main_check_img" src="{{ getS3Img2($temImages, 'medium') }}" data-src="{{ getS3Img2($temImages, 'original') }}" alt="">
                        </a>

                        <input type="checkbox" name="selected-image[]" value="0" class="d-none" data-img="{{ getS3Img2($temImages, 'original') }}" id="image-checkbox-{{ $counter }}">
                        <div id="unchecked-img-{{ $counter }}" class="common_check unchecked-img" onclick="check_img({{ $counter }})">
                            <img src="/assets/images/unactive_image_tick.png" alt="" class="img-fluid">
                        </div>
                        <div id="checked-img-{{ $counter }}" class="d-none common_check checked-img" onclick="uncheck_img({{ $counter }})">
                            <img src="assets/images/active_image_tick.png" alt="" class="img-fluid">
                        </div>
                        <p class="title_image_p">{{ getS3ImgName($temImages) }}</p>
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
                        
                        <!-- Wedding Package Dropdown (Hidden by default) -->
                        <div class="fw-products-cats wedding-package-dropdown" id="wedding-package-dropdown" style="display: none;">
                            <select name="wedding_package" id="wedding-package-select">
                                <option value="">Select Wedding Package</option>
                                <!-- Wedding packages will be loaded here via AJAX -->
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

            <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3">
                <div id="add_to_cart_toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            Item added to cart successfully!
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
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
        $("#modal-img").attr('src',$(this).children('img').attr('data-src'));
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
                let productId = $(this).data('productid'); 
                let testPrint = $(this).data('testprint');
                let testPrintPrice = $(this).data('test_print_price');
                let testPrintQty = $(this).data('test_print_qty');
                let testPrintCategory_id = $(this).data('category_id');
                let is_package = $(this).data('is_package') || 0;
                let package_price = $(this).data('package_price') || null;
                let package_product_id = $(this).data('package_product_id') || null;
                
                let totalPrice = quantity * price;
                total += totalPrice;
                cartItems.push({
                    product_id: productId,
                    quantity: parseFloat(quantity),
                    price: price,
                    testPrint: testPrint,
                    testPrintPrice: testPrintPrice,
                    testPrintQty: testPrintQty,
                    testPrintCategory_id: testPrintCategory_id,
                    is_package: is_package,
                    package_price: package_price,
                    package_product_id: package_product_id
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
                    if(response.error == true){
                       alert(response.message);
                    }else{
                        var toastElement = new bootstrap.Toast(document.getElementById('add_to_cart_toast')); 
                        toastElement.show();

                        $('.kt-cart-total').text(response.count);

                        $("input[name=quantity]").each(function() {
                        $(this).val('');
                        $(this).removeAttr('readonly');
                        });
                        $(".show-details").text("0.00");
                        $("#cart-total-itmes").children(".show-details").text("0");
                    
                        setTimeout(function() {
                        location.reload();
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
    
    // Initial update of cart totals on page load
    updateCartTotals();
});


function updateCartTotals() {
    var total = 0;
    var totalQuantity = 0;

    var testPrintTotal = 0;
    var testPrintTotalQuantity = 0;
    var is_test_print = 0;

    $("input[name=quantity]").each(function() {
        var quantity = $(this).val();
        var rowId = $(this).attr('id').split('-')[1];

        var testprint = $(this).data('testprint');
        if(testprint){
            is_test_print += testprint;
            var test_print_price = $(this).data('test_print_price');
            var test_print_qty = $(this).data('test_print_qty');
            console.log(test_print_qty,'test_print_qty');

            var testPrintTotalPrice = test_print_qty * test_print_price;
            testPrintTotal += testPrintTotalPrice;
            testPrintTotalQuantity += test_print_qty;
        }

        var price = parseFloat($(this).data('price'));
        
        var totalPrice = quantity * price;
        total += totalPrice;
        totalQuantity += +quantity;

        if(quantity == ''){
            totalPrice=0;
            testPrintTotalPrice=0;
            if(testprint){
                $("#quantity-price-" + rowId).children('.show-details').text(testPrintTotalPrice.toFixed(2));
            }else{
                $("#quantity-price-" + rowId).children('.show-details').text(totalPrice.toFixed(2));
            }
        }

        if (quantity !== '' && quantity > 0) {
            if(testprint){
                $("#quantity-price-" + rowId).children('.show-details').text(testPrintTotalPrice.toFixed(2));
            }else{
                $("#quantity-price-" + rowId).children('.show-details').text(totalPrice.toFixed(2));
            }
        }
    });

    if(is_test_print != 0){
        console.log(is_test_print,'test1');
        console.log(testPrintTotalQuantity);
        $("#cart-total-price").children('.show-details').text( testPrintTotal.toFixed(2)); 
        $("#cart-total-itmes").children('.show-details').text(totalQuantity);
    }else{
        console.log(is_test_print,'test122');
        is_test_print = 0;
        console.log(totalQuantity,'totalQuantity');
        $("#cart-total-price").children('.show-details').text( total.toFixed(2)); 
        $("#cart-total-itmes").children('.show-details').text(totalQuantity);
    }

}


 $("#category").on('change',function(){
    var selectedCategory = $(this).val();
    var total = 0;
    var totalQuantity = 0;

    // Show/hide wedding package dropdown based on category selection
    if (selectedCategory === 'wedding-package') {
        $('#wedding-package-dropdown').show();
        loadWeddingPackages();
    } else {
        $('#wedding-package-dropdown').hide();
        $('#wedding-package-select').val('');
    }

    $.post("{{ route('products-by-category') }}",
    {
        slug: selectedCategory,
        '_token': "{{ csrf_token() }}"
    },
    function(res){
        // updateCartTotals();
        $("#products-main").html(res);
        // updateCartTotals();
    });
    $("#cart-total-price").children('.show-details').text( total.toFixed(2)); 
    $("#cart-total-itmes").children('.show-details').text(totalQuantity);
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

    // Function to load wedding packages
    function loadWeddingPackages() {
        $.get("{{ route('wedding-packages-list') }}", function(data) {
            var options = '<option value="">Select Wedding Package</option>';
            data.forEach(function(package) {
                options += '<option value="' + package.slug + '">' + package.name + ' - $' + package.price + '</option>';
            });
            $('#wedding-package-select').html(options);
        });
    }

    // Wedding package dropdown change event
    $("#wedding-package-select").on('change', function() {
        var selectedPackage = $(this).val();
        if (selectedPackage) {
            // Load the specific wedding package frames
            loadWeddingPackageFrames(selectedPackage);
        } else {
            // Clear the products table
            $("#products-main").html('<tr><td colspan="4" style="text-align: center; padding: 20px;">Please select a wedding package</td></tr>');
        }
    });

    // Function to load wedding package frames
    function loadWeddingPackageFrames(packageSlug) {
        $.post("{{ route('wedding-package-frames') }}", {
            package_slug: packageSlug,
            '_token': "{{ csrf_token() }}"
        }, function(res) {
            $("#products-main").html(res);
        });
    }
</script>

<style>
/* Wedding Package Dropdown Styling */
.wedding-package-dropdown {
    margin-top: 10px;
    animation: slideDown 0.3s ease-out;
}

.wedding-package-dropdown select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #fff;
    font-size: 14px;
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease;
}

.wedding-package-dropdown select:focus {
    outline: none;
    border-color: #e74c3c;
    box-shadow: 0 0 5px rgba(231, 76, 60, 0.3);
}

.wedding-package-dropdown select:hover {
    border-color: #c0392b;
}

/* Animation for dropdown appearance */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Ensure consistent styling with main dropdown */
.fw-products-cats select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #fff;
    font-size: 14px;
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease;
}

.fw-products-cats select:focus {
    outline: none;
    border-color: #e74c3c;
    box-shadow: 0 0 5px rgba(231, 76, 60, 0.3);
}

.fw-products-cats select:hover {
    border-color: #c0392b;
}
</style>

@endsection
