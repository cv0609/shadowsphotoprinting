@extends('front-end.layout.main')
@section('content')
<div class="kt-bc-nomargin" id="recipi">
    <div class="adbreadcrumbs">
        <div class="container">
            <div class="breadcrumbs-wrapper">
                <span><a href="{{url('/')}}">Home</a></span>
                <span class="bc-delimiter">»</span>
                {{-- <span><a href="#">uncategorized</a></span>
                <span class="bc-delimiter">»</span> --}}
                <span> {{strtoupper($blog_details->title)}}</span>
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
                <div class="kt_color_gray">
                    
                    <div class="shadtpang">
                        <img src="{{asset($blog_details->image)}}" alt="Image">
                        {!!html_entity_decode($blog_details->description)!!}
                    </div>
                    <br>
                    <span>{{ $blog_details->updated_at->format('F d, Y') }}</span>
                    <span>by</span>
                    @php
                        $author = $blog_details->user;
                        $authorName = $author?->first_name
                            ? trim($author->first_name . ' ' . ($author->last_name ?? ''))
                            : ($author->username ?? 'Terri Pangas');
                    @endphp
                
                    <span> <a href="#">{{ $authorName }}</a> </span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="previous-link">
    @if($previousBlog != null)
    <div class="container">
        <div class="previous-box">
            <a href="{{ route('blog-detail',['slug'=>$previousBlog->slug]) }}">
                <span class="kt_color_gray">Previous Post</span>
                <span class="kt_postlink_title">{{ $previousBlog->title }}</span>
            </a>
        </div>
    </div>
    @endif

    @if($nextBlog != null)
    <div class="container">
        <div class="next-link">
            <a href="{{ route('blog-detail',['slug'=>$nextBlog->slug]) }}">
                <span class="kt_color_gray">NEXT POST</span>
                <span class="kt_postlink_title">{{ $nextBlog->title }}</span>
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
