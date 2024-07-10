@extends('admin.layout.main')
@section('page-content')
@php
   $CartService = app(App\Services\CartService::class);
@endphp
<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('orders-list') }}">Orders</a></li>
          <li class="breadcrumb-item"><a href="#">Order Detail</a></li>
        </ol>
      </nav>
    <div class="row">
      <div class="col-md-12">
          <div class="x_panel">
      <div class="x_title">
        <h2>Shipping Details <small>basic table subtitle</small></h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="#">Settings 1</a>
              <a class="dropdown-item" href="#">Settings 2</a>
            </div>
          </li>
          <li><a class="close-link"><i class="fa fa-close"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
      <div class="table-responsive-sm order-invoice-main">
        <table class="table table-striped">
          <thead>
            <tr>
              <th class="center">#</th>
              <th>Item</th>
              <th class="right">Unit Cost</th>
              <th class="center">Qty</th>
              <th class="right">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orderDetail->orderDetails as $key => $item)
            <?php $product_detail =  $CartService->getProductDetailsByType($item->product_id,$item->product_type); dd($product_detail);?>
            <tr>
              <td class="center">{{ $key + 1}}</td>
              <td class="strong">
                @php
                    $photo_product_desc = '';
                    $giftcard_product_desc = '';

                    if($item->product_type == "photo_for_sale"){
                        $photo_product_desc = json_decode($item->product_desc);
                    }
                    if($item->product_type == "gift_card"){
                        $giftcard_product_desc = json_decode($item->product_desc);
                    }

                @endphp
                <a href="#">
                    @if($item->product_type == "gift_card")
                        {{ $product_detail->name }}
                        <p class="giftcard-message"><span class="gift-desc-heading">To: </span><span>{{$giftcard_product_desc->reciept_email ?? ''}}</span><span class="gift-desc-heading"> From: </span><span> {{$giftcard_product_desc->from ?? ''}}</span><span class="gift-desc-heading"> Message: </span><span>{{$giftcard_product_desc->giftcard_msg ?? ''}}</span></p>
                    @elseif($item->product_type == "photo_for_sale")
                        {{ $product_detail->product_title ?? '' }} - {{$photo_product_desc->photo_for_sale_size  ?? ''}},{{$photo_product_desc->photo_for_sale_type ?? ''}}
                    @else
                        {{ $item->product->product_title ?? ''}}
                    @endif
                </a>

            </td>
            <td class="right">
                <span class="">
                    <bdi>
                        <span>$</span>

                    </bdi>
                </span>
            </td>

              <td class="center">1</td>
              <td class="right">$999,00</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="row">
        <div class="col-lg-4 col-sm-5">
        </div>
        <div class="col-lg-4 col-sm-5 ml-auto">
          <table class="table table-clear">
            <tbody>
              <tr>
                <td>
                  <strong>Subtotal</strong>
                </td>
                <td class="right">$8.497,00</td>
              </tr>
              <tr>
                <td>
                  <strong>Discount (20%)</strong>
                </td>
                <td class="right">$1,699,40</td>
              </tr>
              <tr>
                <td>
                  <strong>VAT (10%)</strong>
                </td>
                <td class="right">$679,76</td>
              </tr>
              <tr>
                <td>
                  <strong>Total</strong>
                </td>
                <td class="right">
                  <strong>$7.477,36</strong>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      </div>
    </div>
      </div>
      <div class="col-md-12">
        <div class="x_panel">
          <div class="order-address">
            <div class="row">
              <div class="col-md-6">
                <div class="order-address-details">
                    <h4 class="mb-3">Billing details</h4>
                <ul class="m-0 list-unstyled">
                <li>
                  <h6>First name </h6>
                  <p>Webz </p>
                </li>
                <li>
                  <h6>Last name</h6>
                  <p>Poland</p>
                </li>
                <li>
                  <h6>Company name (optional)</h6>
                  <p>avology</p>
                </li>
                <li>
                  <h6>Country / Region</h6>
                  <p>india</p>
                </li>
                <li>
                  <h6>Street address</h6>
                  <p>Madalinskiego 871-101 Szczecin, Poland</p>
                </li>
                <li>
                  <h6>Suburb</h6>
                  <p></p>
                </li>
                <li>
                  <h6>State</h6>
                  <p>Punjab</p>
                </li>
                <li>
                  <h6>Postcode</h6>
                  <p>160796</p>
                </li>
                <li>
                  <h6>Phone (optional)</h6>
                  <p>8558879462</p>
                </li>
                <li>
                  <h6>Email address</h6>
                  <p>test123@gmail.com</p>
                </li>
                <li>
                  <h6>Account username</h6>
                  <p>tesing</p>
                </li>
                <li>
                  <h6>Create account password</h6>
                  <p>********</p>
                </li>
              </ul>
                </div>

              </div>
              <div class="col-md-6">
                <div class="order-address-details" id="Shipping-address">
                    <h4 class="mb-3">Shipping details</h4>
                <ul class="m-0 list-unstyled">
                <li>
                  <h6>First name </h6>
                  <p>Webz </p>
                </li>
                <li>
                  <h6>Last name</h6>
                  <p>Poland</p>
                </li>
                <li>
                  <h6>Company name (optional)</h6>
                  <p>avology</p>
                </li>
                <li>
                  <h6>Country / Region</h6>
                  <p>india</p>
                </li>
                <li>
                  <h6>Street address</h6>
                  <p>Madalinskiego 871-101 Szczecin, Poland</p>
                </li>
                <li>
                  <h6>Suburb</h6>
                  <p></p>
                </li>
                <li>
                  <h6>State</h6>
                  <p>Punjab</p>
                </li>
                <li>
                  <h6>Postcode</h6>
                  <p>160796</p>
                </li>
                <li>
                  <h6>Order notes (optional)</h6>
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet, ducimus.</p>
                </li>
              </ul>
                </div>
                <div class="diffrent-address">
                  <h4 class="mb-3">Shipping details</h4>
                  <p>Ship to a different address?</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
