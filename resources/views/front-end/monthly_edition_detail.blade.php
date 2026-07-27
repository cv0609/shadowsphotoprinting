@extends('front-end.layout.main')
@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400&family=Playfair+Display:ital,wght@0,500;0,700;1,500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/shadows-monthly.css') }}?v=28">
@endsection
@section('content')
@php
    $PageDataService = app(App\Services\PageDataService::class);
    $heroImage = $PageDataService->getShadowsMonthlyHeroImage();
    $topics = $edition->blogs
        ->pluck('category.name')
        ->filter()
        ->unique()
        ->take(3)
        ->values();
    if ($topics->isEmpty()) {
        $topics = collect(['Photography', 'Family Stories', 'Printing Tips']);
    }
    $hasMagazineSections = $hasMagazineSections ?? $edition->sections->isNotEmpty();
    $sectionsBeforeFeatured = $sectionsBeforeFeatured ?? collect();
    $sectionsAfterFeatured = $sectionsAfterFeatured ?? collect();
@endphp 

<div class="sm-page sm-page--magazine sm-page--edition">
    <div class="sm-progress" aria-hidden="true"><span class="sm-progress__bar" id="sm-read-progress"></span></div>

    <section class="sm-mag-hero" style="background-image: url('{{ $heroImage }}');">
        <div class="sm-mag-hero__veil"></div>
        <div class="sm-mag-hero__inner">
            <span class="sm-mag-hero__eyebrow">{{ strtoupper($edition->edition_label) }}</span>
            <h1>{{ $edition->title }}</h1>
            <p class="sm-mag-hero__topics">{{ $topics->implode(' · ') }}</p>
            <a class="sm-mag-hero__scroll" href="#edition-content">
                Continue Reading <span aria-hidden="true">↓</span>
            </a>
        </div>
    </section>

    <section class="sm-edition-detail" id="edition-content">
        <div class="sm-container sm-edition-detail__wrap">
            <ul class="sm-meta-row">
                <li><span aria-hidden="true">📅</span> {{ $edition->edition_label }}</li>
                <li><span aria-hidden="true">👤</span> Editor: Terri Pangas</li>
                @if ($editionNumber)
                    <li><span aria-hidden="true">📰</span> Edition #{{ str_pad((string) $editionNumber, 2, '0', STR_PAD_LEFT) }}</li>
                @endif
                <li><span aria-hidden="true">⏱</span> {{ $readMinutes }} min read</li>
            </ul>

            @if ($edition->cover_image)
                <div class="sm-divider" aria-hidden="true"><span>❦</span></div>
                <figure class="sm-mag-cover">
                    <img src="{{ asset($edition->cover_image) }}" alt="{{ $edition->title }} cover">
                    <figcaption>
                        <strong>Edition Cover</strong>
                        <span>{{ $edition->edition_label }}</span>
                    </figcaption>
                </figure>
            @endif

            @if ($hasMagazineSections)
                @foreach ($sectionsBeforeFeatured as $section)
                    @include('front-end.partials.monthly_edition_section', ['section' => $section, 'isFirst' => $loop->first])
                @endforeach
            @else
                @if ($edition->welcome_note)
                    <div class="sm-divider" aria-hidden="true"><span>❦</span></div>
                    <section class="sm-mag-block" id="welcome">
                        <h2 class="sm-mag-kicker">Welcome</h2>
                        <div class="sm-mag-prose sm-mag-prose--dropcap">
                            {!! $edition->welcome_note !!}
                        </div>
                    </section>
                @endif

                @if ($edition->intro)
                    <div class="sm-divider" aria-hidden="true"><span>❦</span></div>
                    <section class="sm-mag-block" id="intro">
                        <h2 class="sm-mag-kicker">Introduction</h2>
                        <div class="sm-mag-prose">
                            {!! $edition->intro !!}
                        </div>
                    </section>
                @endif

                @if ($edition->editor_note)
                    <div class="sm-divider" aria-hidden="true"><span>❦</span></div>
                    <aside class="sm-mag-letter" id="editors-letter">
                        <h2>✍ Editor’s Letter</h2>
                        <div class="sm-mag-letter__body">
                            {!! $edition->editor_note !!}
                        </div>
                        <p class="sm-mag-letter__sign">— Terri Pangas</p>
                    </aside>
                @endif

                <blockquote class="sm-mag-pullquote">
                    <span class="sm-mag-pullquote__mark" aria-hidden="true">❝</span>
                    <p>Every family has a story worth preserving.</p>
                    <span class="sm-mag-pullquote__mark sm-mag-pullquote__mark--end" aria-hidden="true">❞</span>
                </blockquote>
            @endif

            <div class="sm-divider" aria-hidden="true"><span>❦</span></div>
            <section class="sm-mag-block" id="stories">
                <div class="sm-section__head sm-section__head--tight">
                    <h2>Editor’s Picks</h2>
                    <p>Handpicked stories from this edition.</p>
                </div>

                @if ($edition->blogs->count())
                    <div class="sm-card-grid">
                        @foreach ($edition->blogs as $value)
                            <article class="sm-card sm-card--magazine">
                                <a href="{{ route('blog-detail', ['slug' => $value->slug]) }}" class="sm-card__media">
                                    <img src="{{ $PageDataService->getBlogImageUrl($value->image) }}" alt="{{ $value->title }}">
                                </a>
                                <div class="sm-card__body">
                                    <span class="sm-badge">Editor’s Pick</span>
                                    <h3>
                                        <a href="{{ route('blog-detail', ['slug' => $value->slug]) }}">{{ $value->title }}</a>
                                    </h3>
                                    <p class="sm-card__excerpt">
                                        {{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($value->description)), 120) }}
                                    </p>
                                    <a class="sm-card__link" href="{{ route('blog-detail', ['slug' => $value->slug]) }}">
                                        Read Story <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="sm-empty">Featured stories will appear here once they are linked to this edition.</div>
                @endif
            </section>

            @if ($hasMagazineSections)
                @foreach ($sectionsAfterFeatured as $section)
                    @include('front-end.partials.monthly_edition_section', ['section' => $section, 'isFirst' => false])
                @endforeach
            @endif

            <nav class="sm-edition-nav" aria-label="Edition navigation">
                <div class="sm-edition-nav__side">
                    @if ($previousEdition)
                        <a href="{{ route('shadows-monthly.edition', $previousEdition->slug) }}">
                            <span>← Previous</span>
                            <strong>{{ $previousEdition->edition_label }} Edition</strong>
                        </a>
                    @endif
                </div>
                <div class="sm-edition-nav__center">
                    <a class="sm-btn sm-btn--ghost" href="{{ route('shadows-monthly') }}">Back to Library</a>
                </div>
                <div class="sm-edition-nav__side sm-edition-nav__side--next">
                    @if ($nextEdition)
                        <a href="{{ route('shadows-monthly.edition', $nextEdition->slug) }}">
                            <span>Next →</span>
                            <strong>{{ $nextEdition->edition_label }} Edition</strong>
                        </a>
                    @endif
                </div>
            </nav>

            <section class="sm-mag-bottom-cta">
                <div class="sm-divider" aria-hidden="true"><span>❦</span></div>
                <h2>Enjoyed this edition?</h2>
                <p>Browse previous issues or return to the full Shadows Monthly library.</p>
                <a class="sm-btn sm-btn--lg" href="{{ route('shadows-monthly') }}#previous-editions">View Library</a>
                <div class="sm-divider" aria-hidden="true"><span>❦</span></div>
            </section>
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
(function () {
    var bar = document.getElementById('sm-read-progress');
    if (!bar) return;

    function updateProgress() {
        var doc = document.documentElement;
        var scrollTop = window.scrollY || doc.scrollTop;
        var height = doc.scrollHeight - doc.clientHeight;
        var pct = height > 0 ? Math.min(100, Math.max(0, (scrollTop / height) * 100)) : 0;
        bar.style.width = pct + '%';
    }

    window.addEventListener('scroll', updateProgress, { passive: true });
    window.addEventListener('resize', updateProgress);
    updateProgress();
})();
</script>
@endsection
