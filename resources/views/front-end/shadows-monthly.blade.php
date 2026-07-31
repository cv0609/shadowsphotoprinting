@extends('front-end.layout.main')
@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400&family=Playfair+Display:ital,wght@0,500;0,700;1,500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/shadows-monthly.css') }}?v=23">
@endsection
@section('content')
@php
    $PageDataService = app(App\Services\PageDataService::class);
    $categories = $PageDataService->getBlogCategories();
    $latestEdition = $PageDataService->getLatestMonthlyEdition();
    $previousEditions = $PageDataService->getPreviousMonthlyEditions($latestEdition);
    $heroImage = $PageDataService->getShadowsMonthlyHeroImage();
    $libraryBlogs = $PageDataService->getLibraryBlogsExcludingEdition($latestEdition);
    $activeCategory = request('category');
    $editionWelcome = $latestEdition
        ? ($latestEdition->welcome_note ?: $latestEdition->intro)
        : null;
    $categoryIcons = [
        'photography-tips' => '📷',
        'printing-advice' => '🖨️',
        'product-guides' => '📦',
        'family-stories' => '❤️',
        'community-stories' => '🤝',
        'behind-the-scenes' => '🎬',
        'helpful-information' => '💡',
        'inspiration' => '🌿',
    ];
@endphp

<div class="sm-page sm-page--magazine">
    <section class="sm-hero sm-hero--artwork" aria-label="Shadows Monthly">
        <img
            class="sm-hero__img"
            src="{{ $heroImage }}"
            alt="Shadows Monthly — From Our Family to Yours"
        >
        <h1 class="sm-visually-hidden">Shadows Monthly</h1>
        @if ($latestEdition)
            <a class="sm-hero__scroll" href="#latest-edition">
                Explore {{ $latestEdition->edition_label }} edition
                <span aria-hidden="true">↓</span>
            </a>
        @else
            <a class="sm-hero__scroll" href="#library">
                Explore our stories
                <span aria-hidden="true">↓</span>
            </a>
        @endif
    </section>

    <div class="sm-hero-bridge">
        <blockquote class="sm-hero-quote">
            <span class="sm-hero-quote__mark" aria-hidden="true">❝</span>
            <p>Pull up a chair,<br>make yourself a cuppa,<br>and enjoy this month’s stories.</p>
            <span class="sm-hero-quote__mark sm-hero-quote__mark--end" aria-hidden="true">❞</span>
        </blockquote>
    </div>

    <div class="sm-divider" aria-hidden="true"><span>❦</span></div>

    <section class="sm-section sm-section--cream" id="latest-edition">
        <div class="sm-container">
            <div class="sm-section__head">
                <h2>Latest Shadows Monthly Edition</h2>
                <p>Our newest magazine-style issue — cover, welcome note, and featured stories.</p>
            </div>

            @if ($latestEdition)
                <article class="sm-edition-card">
                    <figure class="sm-edition-card__media">
                        <img
                            src="{{ $latestEdition->cover_image ? asset($latestEdition->cover_image) : $heroImage }}"
                            alt="{{ $latestEdition->title }}"
                        >
                    </figure>
                    <div>
                        <span class="sm-edition-card__meta">{{ $latestEdition->edition_label }}</span>
                        <h3>{{ $latestEdition->title }}</h3>
                        @if ($editionWelcome)
                            <div class="sm-edition-card__intro">
                                {{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($editionWelcome)), 320) }}
                            </div>
                        @endif
                        <a class="sm-btn sm-btn--lg" href="{{ route('shadows-monthly.edition', $latestEdition->slug) }}">
                            View Magazine <i class="fa-solid fa-arrow-right-long"></i>
                        </a>
                    </div>
                </article>

                @if ($latestEdition->blogs->count())
                    <div class="sm-edition-featured sm-section--featured-inner">
                        <div class="sm-section__head sm-section__head--tight">
                            <h3>This Month’s Stories</h3>
                            <p>Stories curated inside this edition.</p>
                        </div>

                        <div class="sm-carousel-wrap">
                            <div class="sm-featured-carousel">
                                @foreach ($latestEdition->blogs as $value)
                                    @php
                                        $authorName = ($value->user->first_name ?? null)
                                            ? trim($value->user->first_name . ' ' . ($value->user->last_name ?? ''))
                                            : ($value->user->username ?? 'Terri Pangas');
                                    @endphp
                                    <div class="sm-featured-slide">
                                        <article class="sm-card sm-card--magazine">
                                            <a href="{{ route('blog-detail', ['slug' => $value->slug]) }}" class="sm-card__media">
                                                <img src="{{ $PageDataService->getBlogImageUrl($value->image) }}" alt="{{ $value->title }}">
                                            </a>
                                            <div class="sm-card__body">
                                                <span class="sm-badge">Editor’s Pick</span>
                                                <h3>
                                                    <a href="{{ route('blog-detail', ['slug' => $value->slug]) }}">{{ $value->title }}</a>
                                                </h3>
                                                <div class="sm-card__meta">By {{ $authorName }} · {{ $value->updated_at->format('F d, Y') }}</div>
                                                <p class="sm-card__excerpt">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($value->description)), 120) }}
                                                </p>
                                                <a class="sm-card__link" href="{{ route('blog-detail', ['slug' => $value->slug]) }}">
                                                    Read Story <i class="fa-solid fa-arrow-right-long"></i>
                                                </a>
                                            </div>
                                        </article>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="sm-empty">
                    The first Shadows Monthly edition is on its way. Meanwhile, explore our story library below.
                </div>
            @endif
        </div>
    </section>

    @if ($previousEditions->count())
        <div class="sm-divider" aria-hidden="true"><span>❦</span></div>

        <section class="sm-section sm-section--white" id="previous-editions">
            <div class="sm-container">
                <div class="sm-section__head">
                    <h2>Previous Editions</h2>
                    <p>Browse earlier Shadows Monthly issues — nothing lost, everything kept.</p>
                </div>

                <div class="sm-previous-row">
                    @foreach ($previousEditions as $pastEdition)
                        <article class="sm-previous-card sm-previous-card--cover">
                            <a href="{{ route('shadows-monthly.edition', $pastEdition->slug) }}" class="sm-previous-card__media">
                                <img
                                    src="{{ $pastEdition->cover_image ? asset($pastEdition->cover_image) : $heroImage }}"
                                    alt="{{ $pastEdition->title }}"
                                >
                            </a>
                            <div class="sm-previous-card__body">
                                <span class="sm-previous-card__meta">{{ $pastEdition->edition_label }}</span>
                                <h3>
                                    <a href="{{ route('shadows-monthly.edition', $pastEdition->slug) }}">{{ $pastEdition->title }}</a>
                                </h3>
                                @if ($pastEdition->welcome_note || $pastEdition->intro)
                                    <p class="sm-previous-card__excerpt">
                                        {{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($pastEdition->welcome_note ?: $pastEdition->intro)), 100) }}
                                    </p>
                                @endif
                                <a class="sm-card__link" href="{{ route('shadows-monthly.edition', $pastEdition->slug) }}">
                                    Read edition <i class="fa-solid fa-arrow-right-long"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <div class="sm-divider" aria-hidden="true"><span>❦</span></div>

    <section class="sm-section sm-section--library" id="library">
        <div class="sm-container">
            <div class="sm-section__head">
                <h2>Helpful Tips, Inspiration &amp; Stories</h2>
                <p>An article library of photography tips, printing advice, product guides, and community stories.</p>
            </div>

            <div class="sm-filters" role="list" id="sm-library-filters">
                <button type="button" class="sm-filter {{ !$activeCategory ? 'is-active' : '' }}" data-category="" aria-pressed="{{ !$activeCategory ? 'true' : 'false' }}">✦ All</button>
                @foreach ($categories as $category)
                    <button
                        type="button"
                        class="sm-filter {{ $activeCategory === $category->slug ? 'is-active' : '' }}"
                        data-category="{{ $category->slug }}"
                        data-category-name="{{ $category->name }}"
                        aria-pressed="{{ $activeCategory === $category->slug ? 'true' : 'false' }}"
                    >
                        <span class="sm-filter__icon">{{ $categoryIcons[$category->slug] ?? '✦' }}</span>
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <div class="sm-card-grid" id="sm-library-grid">
                @forelse ($libraryBlogs as $value)
                    @php
                        $authorName = ($value->user->first_name ?? null)
                            ? trim($value->user->first_name . ' ' . ($value->user->last_name ?? ''))
                            : ($value->user->username ?? 'Terri Pangas');
                        $catSlug = optional($value->category)->slug;
                    @endphp
                    <article class="sm-card sm-card--magazine" data-category="{{ $catSlug }}">
                        <a href="{{ route('blog-detail', ['slug' => $value->slug]) }}" class="sm-card__media">
                            <img src="{{ $PageDataService->getBlogImageUrl($value->image) }}" alt="{{ $value->title }}">
                        </a>
                        <div class="sm-card__body">
                            <span class="sm-card__cat">
                                {{ $categoryIcons[$catSlug] ?? '' }}
                                {{ optional($value->category)->name ?? 'Helpful Information' }}
                            </span>
                            <h3>
                                <a href="{{ route('blog-detail', ['slug' => $value->slug]) }}">{{ $value->title }}</a>
                            </h3>
                            <div class="sm-card__meta">By {{ $authorName }} · {{ $value->updated_at->format('F d, Y') }}</div>
                            <p class="sm-card__excerpt">
                                {{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($value->description)), 120) }}
                            </p>
                            <a class="sm-card__link" href="{{ route('blog-detail', ['slug' => $value->slug]) }}">
                                Read Story <i class="fa-solid fa-arrow-right-long"></i>
                            </a>
                        </div>
                    </article>
                @empty
                @endforelse
            </div>
            <div class="sm-empty" id="sm-library-empty" hidden>
                No articles in this category yet.
            </div>
        </div>
    </section>

    <section class="sm-section sm-section--cta">
        <div class="sm-container">
            <div class="sm-cta">
                <div class="sm-divider sm-divider--light" aria-hidden="true"><span>❦</span></div>
                <h2>Never miss an edition.</h2>
                <p>Subscribe to Shadows Monthly for warm stories, printing tips, and family memories.</p>
                <button
                    type="button"
                    class="sm-btn sm-btn--gold sm-btn--lg"
                    data-bs-toggle="modal"
                    data-bs-target="#newsletter-modal"
                >
                    Subscribe
                </button>
                <div class="sm-divider sm-divider--light" aria-hidden="true"><span>❦</span></div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    var $carousel = $('.sm-featured-carousel');
    if ($carousel.length && typeof $.fn.slick === 'function') {
        function refreshFeaturedCarousel() {
            if (!$carousel.hasClass('slick-initialized')) return;
            $carousel.find('.slick-slide').css('height', 'auto');
            $carousel.slick('setPosition');
        }

        $carousel.on('init reInit breakpoint setPosition', function () {
            $carousel.find('.slick-list').css('height', 'auto');
        });

        $carousel.slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: false,
            dots: true,
            arrows: true,
            adaptiveHeight: false,
            responsive: [
                { breakpoint: 992, settings: { slidesToShow: 2 } },
                { breakpoint: 640, settings: { slidesToShow: 1 } }
            ]
        });

        $carousel.find('img').on('load', refreshFeaturedCarousel);
        $(window).on('load', refreshFeaturedCarousel);
        setTimeout(refreshFeaturedCarousel, 50);
        setTimeout(refreshFeaturedCarousel, 300);
    }

    var $filters = $('#sm-library-filters');
    var $grid = $('#sm-library-grid');
    var $empty = $('#sm-library-empty');
    if (!$filters.length || !$grid.length) return;

    function applyLibraryFilter(category) {
        var visible = 0;
        $grid.find('.sm-card').each(function () {
            var cardCategory = ($(this).attr('data-category') || '').toString();
            var show = !category || cardCategory === category;
            $(this).toggle(show);
            if (show) visible += 1;
        });

        $filters.find('.sm-filter').each(function () {
            var isActive = ($(this).attr('data-category') || '') === (category || '');
            $(this).toggleClass('is-active', isActive).attr('aria-pressed', isActive ? 'true' : 'false');
        });

        if (visible === 0) {
            var name = $filters.find('.sm-filter.is-active').data('category-name');
            $empty.text(name
                ? 'No articles in “' + name + '” yet.'
                : 'No articles in the library yet.'
            ).prop('hidden', false);
        } else {
            $empty.prop('hidden', true);
        }

        var url = new URL(window.location.href);
        if (category) {
            url.searchParams.set('category', category);
        } else {
            url.searchParams.delete('category');
        }
        url.hash = 'library';
        window.history.replaceState({}, '', url.toString());
    }

    $filters.on('click', '.sm-filter', function (e) {
        e.preventDefault();
        applyLibraryFilter($(this).attr('data-category') || '');
    });

    var initial = @json($activeCategory ?: '');
    if (initial) {
        applyLibraryFilter(initial);
    }
});
</script>
@endsection
