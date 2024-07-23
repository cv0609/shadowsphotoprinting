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
                    <input type="text" name="search" id="order-search" placeholder="search by order number">
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="row" style="display: block;">
            <div class="col-md-12 col-sm-6  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Orders List</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Billing</th>
                                    <th>Ship to</th>
                                    <th>Created At</th>
                                    <th>Download zip</th>
                                </tr>
                            </thead>
                            <tbody id="main-tbody">
                                @foreach ($orders as $key => $order)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td> <a
                                            href="{{ route('order-detail',['order_number'=>$order->order_number]) }}">{{ $order->order_number }}</a>
                                    </td>
                                    <td>{{ $order->total }}</td>
                                    <td class="status_td"> <p> {{ $order->status }}</p></td>
                                    <td class="billing_address column-billing_address" data-colname="Billing">Jenny Garley, 1145 Eyre Street, Newington Victoria 3350<span class="description">via Credit Card (Stripe)</span></td>
                                    <td class="shipping_address column-shipping_address" data-colname="Ship to"><a target="_blank" href="https://maps.google.com/maps?&amp;q=1145%20Eyre%20Street%2C%20%2C%20Newington%2C%20VIC%2C%203350%2C%20AU&amp;z=16">Jenny Garley, 1145 Eyre Street, Newington Victoria 3350</a><span class="description">via Flat rate</span></td>
                                    <td>{{ date('d-m-Y h:i:d',strtotime($order->created_at)) }}</td>
                                    <td>
                                        <a href="{{ route('download-order-zip', ['order_id' => $order->id]) }}" data-id="{{$order->id}}" class="order-zip">order_{{$order->id}}.zip</a>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
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
        $('input[name="datetimes"]').daterangepicker({
            timePicker: false,
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
                console.log(data);
                if (data) {
                    $("#main-tbody").html(data);
                } else {
                    $("#main-tbody").html("<tr><td colspan='5'><p class='text-center'>No any data found!</p></td></tr>");
                }
            }
        });
    });


    $('#date-range-picker').on('apply.daterangepicker', function (ev, picker) {
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
                    $("#main-tbody").html(data);
                } else {
                    $("#main-tbody").html("<tr><td colspan='5'><p class='text-center'>No any data found!</p></td></tr>");

                }
            }
        });
    });

</script>
@endsection
