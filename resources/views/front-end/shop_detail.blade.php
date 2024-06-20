@extends('front-end.layout.main')
@section('content')
<section class="envira-gallery">
            <div class="container">
                <div class="decoding">
                    @if(Session::has('temImages'))
                    @foreach($imageName as $temImages)
                    <div class="decoding-wrapper">
                        <img src="{{ asset('storage/temp/' . $temImages) }}" alt="">
                    </div>
                    @endforeach
                    @endif
                    <div class="quanti-wrapper">
                        <div class="quanti">
                            <a class="quanti-btn" id="selectall">Select All</a>
                            <a class="quanti-btn" id="deselectall">Deselect All</a>
                        </div>
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
                                            <input type="number" name="quantity" id="quantity-{{$key}}" data-price="{{ $product->product_price }}">
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
                        <a href="#">ADD TO CART</a>
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
 $(document).on('keyup', "input[name=quantity]", function(){
    let total = 0;
    let totalQuantity = 0;
    $("input[name=quantity]").each(function() {
        if ($(this).val() !== '') {
            let quantity = parseFloat($(this).val());
            let price = parseFloat($(this).data('price'));
            let totalPrice = quantity * price;
            total += totalPrice;
            totalQuantity += quantity;
        }
    });
    $("#cart-total-price").html("$"+total.toFixed(2));
    $("#cart-total-itmes").html(totalQuantity+"items");
   })

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
