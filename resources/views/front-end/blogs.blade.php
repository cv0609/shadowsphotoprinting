@extends('front-end.layout.main')
@section('content')
@php
    $PageDataService = app(App\Services\PageDataService::class);
    $blogs = $PageDataService->getWebBlogs();
@endphp
{{-- @php dd($page_content); @endphp --}}
<section class="blog-new">
    <div class="container">
        <div class="blog-new-wrapper">
            <div class="blog-heading">
                <h2>{{ $page_content['photo_printing_blog_title'] }}</h2>
                {{-- <h2>{{ $details['photo_printing_blog_title'] }} </h2> --}}

            </div>
            <div class="kadence-posts">

               @foreach ($blogs as $value)
               <article>
                <a href="{{ route('blog-detail',['slug'=>$value->slug]) }}">
                    <div class="post-thumbnail-inner">
                        <img src="{{ $value['image'] }}" alt="Image">
                        {{-- <img src="assets/images/table.png" alt="jon-tyson"> --}}
                    </div>
                </a>
                <div class="kadence-posts-content">
                    <div class="kadence-street">
                        <div class="entry-taxonomies">
                            <span>
                                <a href="javascript:void(0)">Uncategorized</a>
                            </span>
                        </div>
                        <h2><a href="{{ route('blog-detail',['slug'=>$value->slug]) }}">{{ $value['title'] }}</a></h2>
                        <div class="divider-dot">
                            <span>By Terri Pangas</span>
                            <span class=""></span>
                            <span>{{ date("F d,Y",$value['update_at']) }}</span>
                        </div>
                    </div>
                    <div class="entry-summary">
                        {!! html_entity_decode(substr($value['description'],0,200)) !!}
                        {{-- <p>Have you ever wanted to bring your memories back to life? Photo restoration can
                            help you do just that! With the right tools, you can take old photos and restore
                            them to their former glory. Whether it’s restoring a single … </p> --}}
                        <div class="read-printing">
                            <a href="{{ route('blog-detail',['slug'=>$value->slug]) }}"> Read More <i class="fa-solid fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>
            </article>
               @endforeach

            </div>
        </div>
    </div>
</section>


@endsection
@section('scripts')
    <script>
        AOS.init({
            duration: 1200,
        })

    </script>
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
