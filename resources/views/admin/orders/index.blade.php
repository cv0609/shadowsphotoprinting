@extends('admin.layout.main')
@section('page-content')
<style>
  .order-country-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-top: 6px;
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    line-height: 1.2;
    white-space: nowrap;
    border: 1px solid #dbe3ea;
    background: #f5f7fa;
    color: #334155;
  }

  .order-country-badge.is-nz {
    border-color: #b7ddd5;
    background: #f0faf7;
    color: #0f766e;
  }

  .order-country-badge.is-au {
    border-color: #c9d8e8;
    background: #f3f7fb;
    color: #1d4f7c;
  }

  .order-country-badge .flag {
    font-size: 13px;
    line-height: 1;
  }

  .order-range-list .serach-bar-range {
    position: relative;
    max-width: 340px;
    width: 100%;
  }

  .order-range-list .serach-bar-range input#order-search {
    width: 100%;
    max-width: 340px;
    padding-right: 36px;
    text-transform: none;
  }

  .order-range-list .serach-bar-range .fa-search {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    margin: 0;
    padding: 0;
    z-index: 2;
    color: #73879c;
    pointer-events: auto;
  }

  .pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    margin-top: 18px;
    padding-top: 12px;
    border-top: 1px solid #e6e9ed;
    gap: 12px;
  }

  .pagination-container .pagination {
    margin: 0;
  }

  .per-page-select-wrapper {
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .per-page-select-wrapper.x-title-per-page {
    float: right;
    margin-top: 4px;
  }

  .per-page-select-wrapper select {
    display: inline-block;
    width: auto;
    min-width: 75px;
    height: 30px;
    padding: 2px 8px;
    font-size: 13px;
    font-weight: 600;
    color: #2a3f54;
    background-color: #fff;
    border: 1px solid #ccd0d7;
    border-radius: 4px;
    cursor: pointer;
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
  }

  .per-page-select-wrapper select:focus {
    border-color: #1abb9c;
    outline: 0;
  }

  .per-page-select-wrapper label {
    margin-bottom: 0;
    font-size: 13px;
    font-weight: 600;
    color: #73879c;
  }

  .per-page-select-wrapper .per-page-label {
    font-size: 13px;
    color: #73879c;
  }

  .per-page-select-wrapper .pagination-entry-count {
    margin-left: 12px;
    font-size: 13px;
    color: #555;
    font-weight: 500;
    padding-left: 12px;
    border-left: 1px solid #d9dee4;
  }

  @media (max-width: 768px) {
    .pagination-container {
      flex-direction: column;
      align-items: flex-start;
    }
    .per-page-select-wrapper.x-title-per-page {
      float: none;
      margin-top: 10px;
    }
  }
</style>

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
                    <input type="text" name="search" id="order-search" placeholder="Search order, username, country"><span><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="row" style="display: block;">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Orders List</h2>
                        <div class="per-page-select-wrapper x-title-per-page">
                            <label for="per_page_top">Show:</label>
                            <select id="per_page_top" class="per-page-dropdown">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                <option value="150" {{ $perPage == 150 ? 'selected' : '' }}>150</option>
                                <option value="200" {{ $perPage == 200 ? 'selected' : '' }}>200</option>
                                <option value="250" {{ $perPage == 250 ? 'selected' : '' }}>250</option>
                                <option value="300" {{ $perPage == 300 ? 'selected' : '' }}>300</option>
                                <option value="400" {{ $perPage == 400 ? 'selected' : '' }}>400</option>
                                <option value="500" {{ $perPage == 500 ? 'selected' : '' }}>500</option>
                            </select>
                            <span class="per-page-label">orders per page</span>
                        </div>
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
                                    <td data-title="s_no">{{ method_exists($orders, 'firstItem') && $orders->firstItem() ? ($orders->firstItem() + $key) : ($key + 1) }}</td>
                                    <td data-title="order-number">
                                        <a href="{{ route('order-detail',['order_number'=>$order->order_number]) }}">{{ $order->order_number }}</a>
                                        @php
                                            $countryName = $order->shoppingCountry->name ?? null;
                                            $countryCode = strtoupper((string) ($order->shoppingCountry->code ?? ''));
                                        @endphp
                                        @if($countryName)
                                            <div>
                                                <span class="order-country-badge {{ $countryCode === 'NZ' ? 'is-nz' : ($countryCode === 'AU' ? 'is-au' : '') }}">
                                                    <span class="flag" aria-hidden="true">
                                                        @if($countryCode === 'NZ') 🇳🇿
                                                        @elseif($countryCode === 'AU') 🇦🇺
                                                        @else 🌍
                                                        @endif
                                                    </span>
                                                    {{ $countryName }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $order->orderBillingShippingDetails->fname ?? ''}}</td>
                                    <td data-title="created-at">{{ date('M d, Y',strtotime($order->created_at)) }}</td>

                                    <td class="status_td" data-title="status"> 
                                        <p class="@if($order->order_status == 0) alert alert-primary @elseif($order->order_status == 1) alert alert-info @elseif($order->order_status == 2) alert alert-danger @elseif($order->order_status == 3) alert alert-warning @endif"> @if($order->order_status == 0) Processing @elseif($order->order_status == 1) Completed @elseif($order->order_status == 2) Cancelled @elseif($order->order_status == 3) Refunded @endif</p>
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
                                    
                                            @if(isset($order->orderBillingShippingDetails?->isShippingAddress) &&$order->orderBillingShippingDetails->isShippingAddress == 1)
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

                        <div class="pagination-container" id="pagination-wrapper">
                            <div class="per-page-select-wrapper">
                                <label for="per_page_bottom">Show:</label>
                                <select id="per_page_bottom" class="per-page-dropdown">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                    <option value="150" {{ $perPage == 150 ? 'selected' : '' }}>150</option>
                                    <option value="200" {{ $perPage == 200 ? 'selected' : '' }}>200</option>
                                    <option value="250" {{ $perPage == 250 ? 'selected' : '' }}>250</option>
                                    <option value="300" {{ $perPage == 300 ? 'selected' : '' }}>300</option>
                                    <option value="400" {{ $perPage == 400 ? 'selected' : '' }}>400</option>
                                    <option value="500" {{ $perPage == 500 ? 'selected' : '' }}>500</option>
                                </select>
                                <span class="per-page-label">orders per page</span>
                                @if(method_exists($orders, 'total') && $orders->total() > 0)
                                <span class="pagination-entry-count">
                                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                                </span>
                                @endif
                            </div>
                            <div class="pagination-links">
                                {{ $orders->links('pagination::bootstrap-4') }}
                            </div>
                        </div>

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

    let orderSearchTimer = null;

    function getActiveDateRange() {
        const picker = $('#date-range-picker').data('daterangepicker');
        if (!picker || !picker.startDate || !picker.endDate) {
            return {};
        }

        // Only send dates when user has applied a custom range via apply event
        if (!$('#date-range-picker').data('range-applied')) {
            return {};
        }

        return {
            start_date: picker.startDate.format('YYYY-MM-DD'),
            end_date: picker.endDate.format('YYYY-MM-DD')
        };
    }

    $(document).on('change', '.per-page-dropdown', function () {
        const perPage = $(this).val();
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    });

    function runOrderSearch(query) {
        query = (query || '').trim();

        // Restore original paginated list when search is cleared
        if (query === '' && !$('#date-range-picker').data('range-applied')) {
            window.location.reload();
            return;
        }

        const currentPerPage = $('.per-page-dropdown').first().val() || 50;

        $.ajax({
            url: '{{ route("orders.search") }}',
            type: 'GET',
            data: Object.assign({ query: query, per_page: currentPerPage }, getActiveDateRange()),
            success: function (data) {
                $('#pagination-wrapper').addClass('d-none');
                $('.pagination').addClass('d-none');
                if (data && String(data).trim() !== '') {
                    $("#main-tbody").html(data);
                } else {
                    $("#main-tbody").html("<tr><td colspan='9'><p class='text-center'>No any data found!</p></td></tr>");
                }
            },
            error: function () {
                $("#main-tbody").html("<tr><td colspan='9'><p class='text-center'>Something went wrong. Please try again.</p></td></tr>");
            }
        });
    }

    $('#order-search').on('keyup', function () {
        clearTimeout(orderSearchTimer);
        const query = $(this).val();
        orderSearchTimer = setTimeout(function () {
            runOrderSearch(query);
        }, 300);
    });

    $('#order-search').on('search', function () {
        runOrderSearch($(this).val());
    });


    $('#date-range-picker').on('apply.daterangepicker', function (ev, picker) {
        $('#date-range-picker').data('range-applied', true);
        $('.drp-calendar .right').css('display','none');
        let startDate = picker.startDate.format('YYYY-MM-DD');
        let endDate = picker.endDate.format('YYYY-MM-DD');
        let query = ($('#order-search').val() || '').trim();
        const currentPerPage = $('.per-page-dropdown').first().val() || 50;

        $.ajax({
            url: '{{ route("orders.search") }}',
            type: 'GET',
            data: {
                query: query,
                start_date: startDate,
                end_date: endDate,
                per_page: currentPerPage
            },
            success: function (data) {
                $('#pagination-wrapper').addClass('d-none');
                $('.pagination').addClass('d-none');
                if (data && String(data).trim() !== '') {
                    $("#main-tbody").html(data);
                } else {
                    $("#main-tbody").html("<tr><td colspan='9'><p class='text-center'>No any data found!</p></td></tr>");
                }
            }
        });
    });

</script>
@endsection
