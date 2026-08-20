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
                            {{-- <img class="main_check_img" src="{{ getS3Img2($temImages, 'medium') }}" data-src="{{ getS3Img2($temImages, 'original') }}" alt=""> --}}
                             <img class="main_check_img" src="{{ getS3Img2($temImages, 'raw') }}" data-src="{{ getS3Img2($temImages, 'raw') }}" alt="">
                        </a>

                        {{-- <input type="checkbox" name="selected-image[]" value="0" class="d-none" data-img="{{ getS3Img2($temImages, 'original') }}" id="image-checkbox-{{ $counter }}"> --}}

                         <input type="checkbox" name="selected-image[]" value="0" class="d-none" data-img="{{ getS3Img2($temImages, 'raw') }}" id="image-checkbox-{{ $counter }}">

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

        <!-- Package Restriction Modal -->
        <div class="modal fade" id="packageRestrictionModal" tabindex="-1" role="dialog" aria-labelledby="packageRestrictionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="border-radius: 0;">
                    <div class="modal-header" style="background-color: #f8f9fa; border-radius: 0;">
                        <h5 class="modal-title text-dark" id="packageRestrictionModalLabel">
                            <i class="fas fa-exclamation-triangle text-danger"></i> Package Restriction Violation
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #dc3545; font-size: 1.5rem;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #ffffff; padding: 20px;">
                        <div class="alert alert-info" style="border-radius: 0; border-left: 4px solid #69794E;">
                            <i class="fas fa-info-circle"></i> You cannot add these items to your cart because they exceed the package restrictions.
                        </div>
                        <div id="restriction-message"></div>
                    </div>
                    <div class="modal-footer" style="background-color: #f8f9fa; border-radius: 0;">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="border-radius: 0; padding: 8px 20px;">
                            <i class="fas fa-times"></i> Close
                        </button>
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
                    <div style="display: flex; align-items: center; gap: 10px; margin: 12px 0; padding: 10px 12px; background: #f5f7fa; border: 1px solid #dbe3ea; border-radius: 6px;">
                        <span style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; flex: 0 0 32px; color: #69794E; background: #fff; border-radius: 50%;">
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                        </span>
                        <span style="line-height: 1.3;">
                            <small style="display: block; color: #6c757d;">Shopping Country</small>
                            <strong id="shopping-country-name" style="display: block; color: #222;">
                                {{ $shippingCountry === 'NZ' ? 'New Zealand' : 'Australia' }}
                            </strong>
                        </span>
                    </div>
                    <div class="fw-products">
                        <h4>PRODUCTS</h4>
                        <div class="shop-filter-row" style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                            <div class="fw-products-cats" style="flex: 0 0 42%; margin-bottom: 0;">
                                <select name="shop_shipping_country" id="shop-shipping-country">
                                    @foreach ($shippingCountries as $country)
                                        <option value="{{ $country->code }}" {{ $shippingCountry === $country->code ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fw-products-cats" style="flex: 1; min-width: 0; margin-bottom: 0;">
                                <select name="category" id="category">
                                    <option value="all">All</option>
                                    @foreach ($productCategories as $productCategory)
                                        <option value="{{ $productCategory->slug }}">{{ ucfirst($productCategory->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Wedding Package Dropdown (Hidden by default) -->
                        <div class="fw-products-cats wedding-package-dropdown" id="wedding-package-dropdown" style="display: none;">
                            <select name="wedding_package" id="wedding-package-select">
                                <option value="">Select Wedding Package</option>
                                <!-- Wedding packages will be loaded here via AJAX -->
                            </select>
                        </div>
                        
                        <!-- Package Restrictions Display (Hidden by default) -->
                        <div class="package-restrictions-info" id="package-restrictions-info" style="display: none; border: 1px solid rgb(230 228 223); border-radius: 4px; padding: 6px 10px; margin: 8px 0;background:#f6f6f6;">
                            <div class="restrictions-inline" style="background-color: transparent; text-align: center;">
                                <span style="color: #f12626; font-size: 10px; font-weight: 500;">
                                    <i class="fas fa-info-circle"></i> 
                                    <span id="restrictions-content">Package Restrictions</span>
                                </span>
                            </div>
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

            <div id="countryConflictModal" role="dialog" aria-modal="true" aria-labelledby="countryConflictModalLabel"
                style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(0, 0, 0, 0.65);">
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 92%; max-width: 540px; background: #fff; color: #222; border-radius: 6px; overflow: hidden; box-shadow: 0 12px 35px rgba(0, 0, 0, 0.35);">
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: #f8f9fa; border-bottom: 1px solid #ddd;">
                        <h5 id="countryConflictModalLabel" style="margin: 0; color: #222;">
                            <i class="fas fa-exclamation-triangle" style="color: #d39e00;"></i> Different Shipping Country
                        </h5>
                        <button type="button" class="country-conflict-cancel" aria-label="Close"
                            style="border: 0; background: transparent; color: #333; font-size: 28px; line-height: 1; cursor: pointer;">&times;</button>
                    </div>
                    <div style="padding: 24px; background: #fff;">
                        <p id="country-conflict-message" style="color: #222; font-size: 15px; line-height: 1.6; margin: 0;"></p>
                    </div>
                    <div style="display: flex; justify-content: flex-end; flex-wrap: wrap; gap: 10px; padding: 15px 20px; background: #f8f9fa; border-top: 1px solid #ddd;">
                        <button type="button" class="btn btn-secondary country-conflict-cancel">Cancel</button>
                        <button type="button" class="btn btn-danger" id="clear-country-conflict-cart">Replace Cart</button>
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

        // Check package validation first
        checkPackageValidation(function(isValid) {
            if (!isValid) {
                return false; // Stop if validation fails
            }
            
            // Proceed with adding to cart

            console.log('cart added');
            addItemsToCart();
        });
    });
    
    function addItemsToCart() {
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
                let category_id = $(this).data('category_id') || null;
                
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
                    package_product_id: package_product_id,
                    category_id: category_id
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
                        if (response.country_conflict) {
                            $('#country-conflict-message').text(response.message);
                            $('#countryConflictModal').stop(true, true).fadeIn(150);

                            $('.country-conflict-cancel').off('click').on('click', function() {
                                $('#countryConflictModal').stop(true, true).fadeOut(150);
                            });

                            $('#countryConflictModal').off('click.countryConflict').on('click.countryConflict', function(event) {
                                if (event.target === this) {
                                    $(this).stop(true, true).fadeOut(150);
                                }
                            });

                            $('#clear-country-conflict-cart').off('click').on('click', function() {
                                var $button = $(this);
                                $button.prop('disabled', true).text('Replacing Cart...');

                                $.post("{{ route('clear-cart') }}", {
                                    '_token': "{{ csrf_token() }}"
                                })
                                .done(function() {
                                    $('#countryConflictModal').stop(true, true).fadeOut(150);
                                    $("#add-to-cart").trigger('click');
                                })
                                .fail(function() {
                                    $('#country-conflict-message').text('Unable to clear the existing cart. Please try again.');
                                })
                                .always(function() {
                                    $button.prop('disabled', false).text('Replace Cart');
                                });
                            });
                        } else if (!response.country_conflict) {
                            alert(response.message);
                        }
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
    }

    // Function to update the cart totals
    
    // Initial update of cart totals on page load
    updateCartTotals();
});


function checkPackageValidation(callback){
    // Use cart data that's already available on the page
    @php
        $cartItemsData = [];
        if($cart && $cart->items) {
            foreach($cart->items as $item) {
                $cartItemsData[] = [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'is_package' => $item->is_package ?? 0,
                    'package_product_id' => $item->package_product_id ?? null,
                    'category_id' => $item->product->category_id ?? null,
                    'product_type' => $item->product_type ?? null
                ];
            }
        }
    @endphp
    var currentCartItems = @json($cartItemsData);
    
    // Load wedding packages JSON
    $.get("{{ route('wedding-packages-json') }}", function(packages) {
        // Get items being added to cart
        let newCartItems = [];
        let hasPackageItems = false;
        let packageSlug = '';
        
        // Get selected images count
        let selectedImagesCount = 0;
        $("input[name='selected-image[]']").each(function() {
            if ($(this).val() == "1") {
                selectedImagesCount++;
            }
        });
        
        $("input[name=quantity]").each(function() {
            let quantity = $(this).val();
            if (quantity > 0) {
                let is_package = $(this).data('is_package') || 0;
                let package_product_id = $(this).data('package_product_id') || null;
                let category_id = $(this).data('category_id') || null;
                let package_slug = $(this).data('package_slug') || '';
                
                if (is_package == 1) {
                    hasPackageItems = true;
                    packageSlug = package_slug;
                    
                    // Calculate total quantity: product quantity × selected images count
                    let totalQuantity = parseFloat(quantity) * selectedImagesCount;
                    
                    newCartItems.push({
                        product_id: $(this).data('productid'),
                        quantity: totalQuantity, // This is now the total quantity for all images
                        is_package: is_package,
                        package_product_id: package_product_id,
                        category_id: category_id
                    });
                } else {
                    // For non-package items, use original quantity
                    newCartItems.push({
                        product_id: $(this).data('productid'),
                        quantity: parseFloat(quantity),
                        is_package: is_package,
                        package_product_id: package_product_id,
                        category_id: category_id
                    });
                }
            }
        });
        
        if (hasPackageItems && packageSlug) {
            // Find the package in JSON
            var package = packages.packages.find(p => p.slug === packageSlug);
            
            if (package && package.restrictions) {
                var restrictions = package.restrictions;
                
                // Dynamic type counting
                var existingTypeCounts = {};
                var newTypeCounts = {};
                var packageProductId = null;
                
                // Get package product ID from new items
                if (newCartItems.length > 0 && newCartItems[0].package_product_id) {
                    packageProductId = newCartItems[0].package_product_id;
                }
                
                // Count existing cart items for this package
                if (currentCartItems && currentCartItems.length > 0 && packageProductId) {
                    currentCartItems.forEach(function(item) {
                        if (item.is_package == 1 && item.package_product_id == packageProductId) {
                            // Find the frame type for this category_id
                            var frame = package.frames.find(f => f.category_id == item.category_id);
                            if (frame && frame.type) {
                                var typeKey = frame.type.toLowerCase().replace(/\s+/g, '_');
                                if (!existingTypeCounts[typeKey]) {
                                    existingTypeCounts[typeKey] = 0;
                                }
                                existingTypeCounts[typeKey] += parseInt(item.quantity);
                            }
                        }
                    });
                }
                
                // Count new items being added
                newCartItems.forEach(function(item) {
                    if (item.is_package == 1) {
                        // Find the frame type for this category_id
                        var frame = package.frames.find(f => f.category_id == item.category_id);
                        if (frame && frame.type) {
                            var typeKey = frame.type.toLowerCase().replace(/\s+/g, '_');
                            if (!newTypeCounts[typeKey]) {
                                newTypeCounts[typeKey] = 0;
                            }
                            newTypeCounts[typeKey] += parseInt(item.quantity);
                        }
                    }
                });
                
                // Check restrictions dynamically
                var violations = [];
                
                for (var restrictionType in restrictions) {
                    var restriction = restrictions[restrictionType];
                    var typeKey = restrictionType; // e.g., "photo_prints", "canvas_prints"
                    var existingCount = existingTypeCounts[typeKey] || 0;
                    var newCount = newTypeCounts[typeKey] || 0;
                    var totalCount = existingCount + newCount;
                    var limit = restriction.total_limit;
                    
                    if (totalCount > limit) {
                        var typeDisplayName = typeKey.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        var violation = `${typeDisplayName} limit exceeded. You have ${existingCount} in cart and trying to add ${newCount} more, but the limit is ${limit}.`;
                        violations.push(violation);
                    }
                }
                
                if (violations.length > 0) {
                    var message = `
                        <div class="package-violation" style="background-color: #f8f9fa; padding: 15px; border-radius: 0;">
                            <h6 class="text-danger mb-3" style="font-weight: bold;">
                                <i class="fas fa-gift"></i> ${package.name}
                            </h6>
                            <div class="violations-list">
                                <ul class="list-unstyled mb-0">
                    `;
                    
                    violations.forEach(function(violation) {
                        message += `<li class="mb-2" style="color: #6c757d; font-size: 14px;"><i class="fas fa-times-circle text-danger"></i> ${violation}</li>`;
                    });
                    
                    message += `
                                </ul>
                            </div>
                        </div>
                    `;
                    
                    // Show modal with violation message
                    $('#restriction-message').html(message);
                    $('#packageRestrictionModal').modal('show');
                    
                    // Ensure close button works
                    $('#packageRestrictionModal .close, #packageRestrictionModal [data-dismiss="modal"]').off('click').on('click', function() {
                        $('#packageRestrictionModal').modal('hide');
                    });
                    
                    callback(false); // Prevent adding to cart
                    return;
                } else {
                    callback(true); // Allow adding to cart
                    return;
                }
            }
        }
        
        callback(true); // No package items or no restrictions, allow
    }).fail(function() {
        callback(true); // Allow if JSON fails to load
    });
}


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


 var shopShippingPreviousCountry = $("#shop-shipping-country").val();
 var pendingShopShippingCountry = null;

 function applyShopShippingCountryResponse(response) {
    var categoryOptions = '<option value="all">All</option>';
    $('#shopping-country-name').text($('#shop-shipping-country option:selected').text().trim());

    (response.categories || []).forEach(function(category) {
        categoryOptions += '<option value="' + category.slug + '">' + category.name + '</option>';
    });

    $('#category').html(categoryOptions).val('all').trigger('change');
    shopShippingPreviousCountry = response.country_code;
    pendingShopShippingCountry = null;
 }

 function bindCountryConflictModal(message, onReplace) {
    $('#country-conflict-message').text(message);
    $('#countryConflictModal').stop(true, true).fadeIn(150);

    $('.country-conflict-cancel').off('click').on('click', function() {
        $('#countryConflictModal').stop(true, true).fadeOut(150);
        if (shopShippingPreviousCountry) {
            $('#shop-shipping-country').val(shopShippingPreviousCountry);
        }
        pendingShopShippingCountry = null;
    });

    $('#countryConflictModal').off('click.countryConflict').on('click.countryConflict', function(event) {
        if (event.target === this) {
            $(this).stop(true, true).fadeOut(150);
            if (shopShippingPreviousCountry) {
                $('#shop-shipping-country').val(shopShippingPreviousCountry);
            }
            pendingShopShippingCountry = null;
        }
    });

    $('#clear-country-conflict-cart').off('click').on('click', function() {
        var $button = $(this);
        $button.prop('disabled', true).text('Replacing Cart...');
        onReplace($button);
    });
 }

 $("#shop-shipping-country").on('change', function() {
    var $select = $(this);
    var countryCode = $select.val();
    pendingShopShippingCountry = countryCode;

    $.post("{{ route('shop-shipping-country') }}", {
        country_code: countryCode,
        force_clear: 0,
        '_token': "{{ csrf_token() }}"
    })
    .done(function(response) {
        if (response.country_conflict) {
            $select.val(shopShippingPreviousCountry);
            bindCountryConflictModal(response.message, function($button) {
                $.post("{{ route('shop-shipping-country') }}", {
                    country_code: pendingShopShippingCountry || countryCode,
                    force_clear: 1,
                    '_token': "{{ csrf_token() }}"
                })
                .done(function(clearResponse) {
                    $('#countryConflictModal').stop(true, true).fadeOut(150);
                    $('#shop-shipping-country').val(clearResponse.country_code);
                    applyShopShippingCountryResponse(clearResponse);
                    $('.kt-cart-total').text('0');
                })
                .fail(function() {
                    $('#country-conflict-message').text('Unable to replace the cart. Please try again.');
                })
                .always(function() {
                    $button.prop('disabled', false).text('Replace Cart');
                });
            });
            return;
        }

        applyShopShippingCountryResponse(response);
    })
    .fail(function() {
        alert('Unable to change the country. Please try again.');
        $select.val(shopShippingPreviousCountry);
        window.location.reload();
    });
 });

 $("#category").on('change',function(){
    var selectedCategory = $(this).val();
    var total = 0;
    var totalQuantity = 0;

    // Show/hide wedding package dropdown based on category selection
    if (selectedCategory === 'wedding-package') {
        $('#wedding-package-dropdown').show();
        loadWeddingPackages();
        // Hide cart totals when wedding package is selected
        $('.cart-totals').hide();
    } else {
        $('#wedding-package-dropdown').hide();
        $('#wedding-package-select').val('');
        // Show cart totals for other categories
        $('.cart-totals').show();
        // Show price and total columns for non-wedding package categories
        $('th:nth-child(3), th:nth-child(4)').show();
        $('td:nth-child(3), td:nth-child(4)').show();
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
            // Load and display package restrictions
            loadPackageRestrictions(selectedPackage);
            // Hide price and total columns for wedding package items
            $('th:nth-child(3), th:nth-child(4)').hide();
            $('td:nth-child(3), td:nth-child(4)').hide();
        } else {
            // Clear the products table
            $("#products-main").html('<tr><td colspan="4" style="text-align: center; padding: 20px;">Please select a wedding package</td></tr>');
            // Hide package restrictions
            $('#package-restrictions-info').hide();
            // Show price and total columns when no package is selected
            $('th:nth-child(3), th:nth-child(4)').show();
            $('td:nth-child(3), td:nth-child(4)').show();
        }
    });

    // Function to load wedding package frames
    function loadWeddingPackageFrames(packageSlug) {
        $.post("{{ route('wedding-package-frames') }}", {
            package_slug: packageSlug,
            '_token': "{{ csrf_token() }}"
        }, function(res) {
            $("#products-main").html(res);
            // Hide price and total columns after loading wedding package frames
            $('th:nth-child(3), th:nth-child(4)').hide();
            $('td:nth-child(3), td:nth-child(4)').hide();
        });
    }

    // Function to load package restrictions
    function loadPackageRestrictions(packageSlug) {
        $.get("{{ route('wedding-packages-json') }}", function(packages) {
            var package = packages.packages.find(p => p.slug === packageSlug);
            if (package && package.restrictions) {
                var restrictions = [];
                
                for (var restrictionType in package.restrictions) {
                    var restriction = package.restrictions[restrictionType];
                    var typeDisplayName = restrictionType.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    var quantity = restriction.total_limit;
                    // var itemText = quantity === 1 ? typeDisplayName : typeDisplayName + 's';
                    restrictions.push(quantity + ' ' + typeDisplayName);
                }
                
                var restrictionsText = 'Package Includes: ' + restrictions.join(' & ');
                $('#restrictions-content').html(restrictionsText);
                $('#package-restrictions-info').show();
            } else {
                $('#package-restrictions-info').hide();
            }
        }).fail(function() {
            $('#package-restrictions-info').hide();
        });
    }
</script>


@endsection
