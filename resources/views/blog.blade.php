@extends('layout.main')
@section('content')
<section class="blog-new">
    <div class="container">
        <div class="blog-new-wrapper">
            <div class="blog-heading">
                <h2>Shadows Photo Printing Blog </h2>
            </div>
            <div class="kadence-posts">
                <article>
                    <a href="what-is-bulk-printing-and-how-does-it-benefit-photographers.html">
                        <div class="post-thumbnail-inner">
                            <img src="assets/images/jon-tyson.jpg" alt="jon-tyson">
                        </div>
                    </a>
                    <div class="kadence-posts-content">
                        <div class="kadence-street">
                            <div class="entry-taxonomies">
                                <span>
                                    <a href="uncategorized.html">Uncategorized</a>
                                </span>
                            </div>
                            <h2><a href="what-is-bulk-printing-and-how-does-it-benefit-photographers.html">What is Bulk Printing and How Does it Benefit Photographers?</a>
                            </h2>
                            <div class="divider-dot">
                                <span>By Terri Pangas</span>
                                <span class=""></span>
                                <span>March 28, 2023</span>
                            </div>
                        </div>
                        <div class="entry-summary">
                            <p>Bulk printing is a printing process that involves printing a large number of
                                copies of an image at once. It is a cost-effective way to produce high-quality
                                prints that photographers can use for various purposes, such as exhibitions,
                                selling to … </p>
                            <div class="read-printing">
                                <a href="what-is-bulk-printing-and-how-does-it-benefit-photographers.html"> Read More <i class="fa-solid fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                    </div>
                </article>
                <article>
                    <a href="photo-restoration-bring-your-memories-back-to-life.html">
                        <div class="post-thumbnail-inner">
                            <img src="assets/images/table.png" alt="jon-tyson">
                        </div>
                    </a>
                    <div class="kadence-posts-content">
                        <div class="kadence-street">
                            <div class="entry-taxonomies">
                                <span>
                                    <a href="uncategorized.html">Uncategorized</a>
                                </span>
                            </div>
                            <h2><a href="photo-restoration-bring-your-memories-back-to-life.html">Photo Restoration -Bring Your Memories Back to Life</a></h2>
                            <div class="divider-dot">
                                <span>By Terri Pangas</span>
                                <span class=""></span>
                                <span>February 24, 2023</span>
                            </div>
                        </div>
                        <div class="entry-summary">
                            <p>Have you ever wanted to bring your memories back to life? Photo restoration can
                                help you do just that! With the right tools, you can take old photos and restore
                                them to their former glory. Whether it’s restoring a single … </p>
                            <div class="read-printing">
                                <a href="photo-restoration-bring-your-memories-back-to-life.html"> Read More <i class="fa-solid fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                    </div>
                </article>
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