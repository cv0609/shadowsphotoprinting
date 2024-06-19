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
                            <span>8 items</span>
                        </div>
                        <div class="cart-items">
                            <span>$4.70</span>
                        </div>
                    </div>
                    <div class="fw-products">
                        <h4>PRODUCTS</h4>
                        <div class="fw-products-cats">
                            <select name="pc_subcategory">
                                <option value="">ALL</option>
                                <option value="">Canvas Prints</option>
                                <option value="">Posters Panoramics</option>
                                <option value="">Prints Enlargements</option>
                                <option value="">Scrapbook Prints</option>
                            </select>
                        </div>
                        <div class="fw-products-box">
                            <table>
                                <tbody>
                                    <tr>
                                        <th>QTY</th>
                                        <th>DESCRIPTION</th>
                                        <th>PRICE</th>
                                        <th>TOTAL</th>
                                    </tr>
                                    @foreach($products as $key => $product)
                                    <tr class="gi-prod">
                                        <td>
                                            <input type="number" name="quantity" id="quantity-{{$key}}">
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
 $("input[name=quantity]").on('keyup',function(){
    console.log("OK");
 })
</script>
@endsection        
