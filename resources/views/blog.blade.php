@extends('layout.main')
@section('content')
<section class="blog-new">
    <div class="container">
        <div class="blog-new-wrapper">
            <div class="blog-heading">
                <h2>Shadows Photo Printing Blog </h2>
            </div>
            <div class="kadence-posts">
               @foreach ($detail as $value)
               <article>
                <a href="photo-restoration-bring-your-memories-back-to-life.html">
                    <div class="post-thumbnail-inner">
                        <img src="{{ $value['image'] }}" alt="Image">
                        {{-- <img src="assets/images/table.png" alt="jon-tyson"> --}}
                    </div>
                </a>
                <div class="kadence-posts-content">
                    <div class="kadence-street">
                        <div class="entry-taxonomies">
                            <span>
                                <a href="uncategorized.html">Uncategorized</a>
                            </span>
                        </div>
                        <h2><a href="photo-restoration-bring-your-memories-back-to-life.html">{{ $value['title'] }}</a></h2>
                        <div class="divider-dot">
                            <span>By Terri Pangas</span>
                            <span class=""></span>
                            <span>February 24, 2023</span>
                        </div>
                    </div>
                    <div class="entry-summary">
                        {!! html_entity_decode($value['description']) !!}
                        {{-- <p>Have you ever wanted to bring your memories back to life? Photo restoration can
                            help you do just that! With the right tools, you can take old photos and restore
                            them to their former glory. Whether it’s restoring a single … </p> --}}
                        <div class="read-printing">
                            <a href="photo-restoration-bring-your-memories-back-to-life.html"> Read More <i class="fa-solid fa-arrow-right-long"></i></a>
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