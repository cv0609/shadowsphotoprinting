@extends('front-end.layout.main')
@section('content')
@php
    $PageDataService = app(App\Services\PageDataService::class);
    $blogs = $PageDataService->getWebBlogs();
@endphp
<section class="blog-new">
    <div class="container">
        <div class="blog-new-wrapper">
            <div class="blog-heading">
                <h2>{{ $page_content['photo_printing_blog_title'] ?? 'Shadows Affordable Memories Blog' }}</h2>
            </div>
            <div class="kadence-posts">
                @forelse (($blogs ?? []) as $value)
                    @php
                        $authorName = ($value->user->first_name ?? null)
                            ? trim($value->user->first_name . ' ' . ($value->user->last_name ?? ''))
                            : ($value->user->username ?? 'Terri Pangas');
                        $categoryName = optional($value->category)->name ?? 'Uncategorized';
                    @endphp
                    <article>
                        <a href="{{ route('blog-detail', ['slug' => $value->slug]) }}">
                            <div class="post-thumbnail-inner">
                                <img src="{{ $PageDataService->getBlogImageUrl($value->image) }}" alt="{{ $value->title }}">
                            </div>
                        </a>
                        <div class="kadence-posts-content">
                            <div class="kadence-street">
                                {{-- <div class="entry-taxonomies">
                                    <span>
                                        <a href="javascript:void(0)">{{ $categoryName }}</a>
                                    </span>
                                </div> --}}
                                <h2>
                                    <a href="{{ route('blog-detail', ['slug' => $value->slug]) }}">{{ $value->title }}</a>
                                </h2>
                                <div class="divider-dot">
                                    <span>By {{ $authorName }}</span>
                                    <span class=""></span>
                                    <span>{{ $value->updated_at->format('F d, Y') }}</span>
                                </div>
                            </div>
                            <div class="entry-summary">
                                {{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($value->description)), 200) }}
                                <div class="read-printing">
                                    <a href="{{ route('blog-detail', ['slug' => $value->slug]) }}">
                                        Read More <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <p style="color: #fff;">No blog posts yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script>
        AOS.init({
            duration: 1200,
        });

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
