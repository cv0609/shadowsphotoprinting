@extends('front-end.layout.main')
@php
    $PageDataService = app(App\Services\PageDataService::class);
    $ProductCategories = $PageDataService->getProductCategories();
@endphp
@section('content')
   
<section class="account-page">
    <div class="container">
        <div class="account-wrapper">
            <div class="row">
                <div class="col-lg-3">
                    <div class="kad-account">
                        <div class="kad-max">
                            <img src="images/max.png" alt="">
                            <a href="#" class="kt-link-to-gravatar">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <span class="kt-profile-photo-text">Update Profile Photo </span>
                            </a>
                        </div>
                    </div>
                    <div class="MyAccount-navigation">
                        @include('front-end.component.account-sidebar')
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="pangas-can">
                        <div class="endpointtitle">
                            <h2>Orders </h2>
                            <div class="orders-wrapper">
                                <table>
                                    <thead>
                                        @if(!$orders->isEmpty())
                                            <tr>
                                                <th>Order</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th> Actions</th>
                                            </tr>
                                        @endif
                                    </thead>
                                    <tbody>
                                        @if($orders->isEmpty())
                                            <div class="col-md-9">
                                                <div class="pangas-can">
                                                    <div class="endpointtitle">
                                                        <div class="woocommerce-info">
                                                            <p>No orders available yet.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @foreach($orders as $value)
                                                @php
                                                    $status = '';
                                                    if($value->order_status == "0") {
                                                        $status = "Processing";
                                                    } elseif($value->order_status == "1") {
                                                        $status = "Completed";
                                                    } elseif($value->order_status == "2") {
                                                        $status = "Cancelled";
                                                    } elseif($value->order_status == "3") {
                                                        $status = "Refunded";
                                                    }
                                                @endphp
                                        
                                                <tr>
                                                    <td><a href="view-order-13873.html" class="orders">#{{$value->order_number ?? ''}}</a></td>
                                                    <td>{{ date('F j, Y', strtotime($value->created_at ?? '')) }}</td>
                                                    <td>{{ $status }}</td>
                                                    <td><span>${{ $value->total ?? '' }} for {{ $value->order_details_count ?? '' }} item</span></td>
                                                    <td>
                                                        <div class="quanti">
                                                            <a href="{{ route('view-order',['order_id' => $value->id]) }}">View<i class="fa-solid fa-eye"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    
                                    </tbody>
                                </table>
                            </div>
                            <div class="next-btn">
                                <div class="quanti">
                                    {{-- <a href="orders-2.html">Next </a> --}}
                                    {{ $orders->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('scripts')
    <script>
        $('.fade-slider').slick({
            autoplay: true,
            dots: true,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear'
        });
    </script>
@endsection
