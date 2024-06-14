@extends('layout.main')
@section('content')
<div class="kt-bc-nomargin" id="recipi">
    <div class="adbreadcrumbs">
        <div class="container">
            <div class="breadcrumbs-wrapper">
                <span><a href="home">Home</a></span>
                <span class="bc-delimiter">»</span>
                <span><a href="">uncategorized</a></span>
                <span class="bc-delimiter">»</span>
                <span> WHAT IS BULK PRINTING AND HOW DOES IT BENEFIT PHOTOGRAPHERS?</span>
            </div>
        </div>
    </div>
</div>


<section class="single-article">
    <div class="container">
        <div class="single-arti">
            <div class="category-kt">
                <a href="uncategorized.html">Uncategorized</a>
            </div>
            <div class="benefit">
                <h3>{{ $blog_details['title'] }}</h3>
                <div class="kt_color_gray">
                    <span>{{ date("F d,Y",$blog_details['update_at']) }}</span>
                    <span>by</span>
                    <span> <a href="shadtpang.html">Terri Pangas</a> </span>
               
                    <div class="shadtpang">
                        <img src="{{ asset($blog_details['image']) }}" alt="Image">
                        {!! html_entity_decode($blog_details['description']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="previous-link">
    <div class="container">
        <div class="previous-box">
            <a href="photo-restoration-bring-your-memories-back-to-life.html">
                <span class="kt_color_gray">Previous Post</span>
                <span class="kt_postlink_title">Photo Restoration -Bring Your Memories Back to Life</span>
            </a>
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