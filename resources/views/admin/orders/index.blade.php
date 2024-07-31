@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Orders</a></li>
        </ol>
    </nav>
    @if(Session::has('success'))
    <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
    @endif
    <div class="">
        <div class="page-title order-range-list">
            <div class="title_left order-list-range">
                <h3>Orders</h3>
            </div>

            <div class="date-range-picker">
                <div class="date-range-wrapper">
                    <input type="text" name="datetimes" id="date-range-picker" />
                </div>

                <div class="serach-bar-range">
                    <input type="text" name="search" id="order-search" placeholder="search"><span><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="row" style="display: block;">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Orders List</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table" id="order_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th>Username</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Billing</th>
                                    <th>Ship to</th>
                                    <th>Total</th>
                                    <th>Download zip</th>
                                </tr>
                            </thead>
                            <tbody id="main-tbody">
                                @foreach ($orders as $key => $order)

                                  <tr>
                                    <td data-title="s_no">{{ $key + 1 }}</td>
                                    <td data-title="order-number"> <a
                                            href="{{ route('order-detail',['order_number'=>$order->order_number]) }}">{{ $order->order_number }}</a>
                                    </td>
                                    <td>{{ $order->orderBillingShippingDetails->username ?? ''}}</td>
                                    <td data-title="created-at">{{ date('M d, Y',strtotime($order->created_at)) }}</td>

                                    <td class="status_td" data-title="status"> 
                                        <p class="@if($order->order_status == 0) alert alert-primary @elseif($order->order_status == 1) alert alert-info @elseif($order->order_status == 2) alert alert-danger @elseif($order->order_status == 3) alert alert-warning @endif"> @if($order->order_status == 0) Prcessing @elseif($order->order_status == 1) Completed @elseif($order->order_status == 2) Cancelled @elseif($order->order_status == 3) Refunded @endif</p>
                                    </td>

                                    <td class="billing_address column-billing_address" data-colname="Billing" data-title="billing-add">
                                        
                                        @php
                                        $addressParts = [];
                                
                                        if (isset($order->orderBillingShippingDetails->street1)) {
                                            $addressParts[] = $order->orderBillingShippingDetails->street1;
                                        }
                                
                                        if (isset($order->orderBillingShippingDetails->street2)) {
                                            $addressParts[] = $order->orderBillingShippingDetails->street2;
                                        }
                                
                                        if (isset($order->orderBillingShippingDetails->postcode)) {
                                            $addressParts[] = $order->orderBillingShippingDetails->postcode;
                                        }
                                
                                        if (isset($order->orderBillingShippingDetails->suburb)) {
                                            $addressParts[] = $order->orderBillingShippingDetails->suburb;
                                        }
                                
                                        $addressLine1 = implode(', ', $addressParts);
                                        $addressParts = [];
                                
                                        if (isset($order->orderBillingShippingDetails->state)) {
                                            $addressParts[] = $order->orderBillingShippingDetails->state;
                                        }
                                
                                        if (isset($order->orderBillingShippingDetails->country_region)) {
                                            $addressParts[] = $order->orderBillingShippingDetails->country_region;
                                        }
                                
                                        $addressLine2 = implode(', ', $addressParts);
                                    @endphp
                                
                                    {{ $addressLine1 }}<br>
                                    {{ $addressLine2 }}

                                    </td>


                                    <td class="shipping_address column-shipping_address" data-colname="Ship to" data-title="shipp-add">
                                        <a target="_blank" href="https://maps.google.com/maps?&amp;q=1145%20Eyre%20Street%2C%20%2C%20Newington%2C%20VIC%2C%203350%2C%20AU&amp;z=16">
                                    
                                            @if($order->orderBillingShippingDetails->isShippingAddress == 1)
                                                @php
                                                    $shippingAddressParts1 = [];
                                                    $shippingAddressParts2 = [];
                                    
                                                    if (isset($order->orderBillingShippingDetails->ship_street1)) {
                                                        $shippingAddressParts1[] = $order->orderBillingShippingDetails->ship_street1;
                                                    }
                                    
                                                    if (isset($order->orderBillingShippingDetails->ship_street2)) {
                                                        $shippingAddressParts1[] = $order->orderBillingShippingDetails->ship_street2;
                                                    }
                                    
                                                    if (isset($order->orderBillingShippingDetails->ship_suburb)) {
                                                        $shippingAddressParts1[] = $order->orderBillingShippingDetails->ship_suburb;
                                                    }
                                    
                                                    if (isset($order->orderBillingShippingDetails->ship_state)) {
                                                        $shippingAddressParts2[] = $order->orderBillingShippingDetails->ship_state;
                                                    }
                                    
                                                    if (isset($order->orderBillingShippingDetails->ship_postcode)) {
                                                        $shippingAddressParts2[] = $order->orderBillingShippingDetails->ship_postcode;
                                                    }
                                    
                                                    if (isset($order->orderBillingShippingDetails->ship_country_region)) {
                                                        $shippingAddressParts2[] = $order->orderBillingShippingDetails->ship_country_region;
                                                    }
                                    
                                                    $shippingAddressLine1 = implode(', ', $shippingAddressParts1);
                                                    $shippingAddressLine2 = implode(', ', $shippingAddressParts2);
                                                @endphp
                                    
                                                {{ $shippingAddressLine1 }}<br>
                                                {{ $shippingAddressLine2 }}
                                            @else
                                                -
                                            @endif
                                    
                                        </a>
                                    </td>
                                    

                                    <td data-title="total">{{ $order->total }}</td>
                                    
                                    <td data-title="zip">
                                        <a href="{{ route('download-order-zip', ['order_id' => $order->id]) }}" data-id="{{$order->id}}" class="order-zip">order_{{$order->order_number}}.zip</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $orders->links('pagination::bootstrap-4') }}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom-script')
<script>
    $(function () {

        $('.fa-search').on('click', function() {
            $('#order-search').focus();
        });


        $('input[name="datetimes"]').daterangepicker({
            timePicker: false,
            "showDropdowns": true,
            "alwaysShowCalendars": false,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
                format: 'M/DD hh:mm A'
            }
        });

    });

    $('#order-search').on('keyup', function () {
        
        let query = $('#order-search').val();
        
        $.ajax({
            url: '{{ route("orders.search") }}',
            type: 'GET',
            data: {
                query: query,
            },
            success: function (data) {
                if (data) {
                    $('.pagination').addClass('d-none');
                    $("#main-tbody").html(data);
                    if(!query){
                        console.log('yes');
                        $('.pagination').removeClass('d-none');
                    }
                } else {
                    $("#main-tbody").html("<tr><td colspan='5'><p class='text-center'>No any data found!</p></td></tr>");
                }
            }
        });
    });


    $('#date-range-picker').on('apply.daterangepicker', function (ev, picker) {
        $('.drp-calendar .right').css('display','none');
        let dateRange = $('#date-range-picker').data('daterangepicker');
        let startDate = dateRange.startDate.format('YYYY-MM-DD');
        let endDate = dateRange.endDate.format('YYYY-MM-DD');

        $.ajax({
            url: '{{ route("orders.search") }}',
            type: 'GET',
            data: {
                start_date: startDate,
                end_date: endDate
            },
            success: function (data) {
                console.log(data);
                if (data) {
                    $('.pagination').addClass('d-none');
                    $("#main-tbody").html(data);
                } else {
                    $("#main-tbody").html("<tr><td colspan='5'><p class='text-center'>No any data found!</p></td></tr>");
                }
            }
        });
    });

</script>
@endsection
