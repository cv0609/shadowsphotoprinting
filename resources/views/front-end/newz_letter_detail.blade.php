@extends('front-end.layout.main')
@section('content')
<div class="kt-bc-nomargin" id="recipi">
    <div class="adbreadcrumbs">
        <div class="container">
            <div class="breadcrumbs-wrapper">
                <span><a href="{{ url('/') }}">Home</a></span>
                <span class="bc-delimiter">»</span>
                {{-- <span><a href="#">uncategorized</a></span>
                <span class="bc-delimiter">»</span> --}}
                <span> {{ strtoupper($promotionDetail['title']) }}</span>
            </div>
        </div>
    </div>
</div>

<section class="single-article">
    <div class="container">
        <div class="single-arti">
            <div class="category-kt">
                <a href="javascript:void(0)">Uncategorized</a>
            </div>
            <div class="benefit">
                <h3>{{ $promotionDetail['title'] }}</h3>
                <div class="kt_color_gray">
                    <span>{{ date("F d,Y",$promotionDetail['update_at']) }}</span>
                    <span>by</span>
                    <span> <a href="shadtpang.html">Terri Pangas</a> </span>

                    <div class="shadtpang">
                        <img src="{{ asset($promotionDetail['image']) }}" alt="Image">
                        {!! html_entity_decode($promotionDetail['description']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="previous-link">
    @if($previousPromotion != null)
    <div class="container">
        <div class="previous-box">
            <a href="{{ route('promotion-detail',['slug'=>$previousPromotion->slug]) }}">
                <span class="kt_color_gray">Previous Post</span>
                <span class="kt_postlink_title">{{ $previousPromotion->title }}</span>
            </a>
        </div>
    </div>
    @endif

    @if($nextPromotion != null)
    <div class="container">
        <div class="next-link">
            <a href="{{ route('promotion-detail',['slug'=>$nextPromotion->slug]) }}">
                <span class="kt_color_gray">NEXT POST</span>
                <span class="kt_postlink_title">{{ $nextPromotion->title }}</span>
            </a>
        </div>
    </div>
    @endif

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

        $(document).ready(function(){
            $('.restoration-cls').attr('href', "{{ url('home') }}");
        });
    </script>

@endsection
