@php
    $sectionAnchor = 'section-' . ($section->id ?? \Illuminate\Support\Str::slug($section->title));
    $imagePosition = $section->image_position ?: 'full_width';
    $hasImage = !empty($section->image);
    $isSideImage = $hasImage && in_array($imagePosition, ['left', 'right'], true);
@endphp

<div class="sm-divider" aria-hidden="true"><span>❦</span></div>
<section class="sm-mag-section sm-mag-block" id="{{ $sectionAnchor }}">
    <h2 class="sm-mag-section__title">
        @if ($section->icon)
            <span class="sm-mag-section__icon" aria-hidden="true">{{ $section->icon }}</span>
        @endif
        <span>{{ $section->title }}</span>
    </h2>

    @if ($hasImage && $imagePosition === 'full_width')
        <figure class="sm-mag-section__figure sm-mag-section__figure--full">
            <img src="{{ asset($section->image) }}" alt="{{ $section->image_caption ?: $section->title }}">
            @if ($section->image_caption)
                <figcaption>{{ $section->image_caption }}</figcaption>
            @endif
        </figure>
    @endif

    @if ($hasImage && $imagePosition === 'centered')
        <figure class="sm-mag-section__figure sm-mag-section__figure--centered">
            <img src="{{ asset($section->image) }}" alt="{{ $section->image_caption ?: $section->title }}">
            @if ($section->image_caption)
                <figcaption>{{ $section->image_caption }}</figcaption>
            @endif
        </figure>
    @endif

    @if ($isSideImage)
        <div class="sm-mag-section__split sm-mag-section__split--{{ $imagePosition }}">
            <figure class="sm-mag-section__figure sm-mag-section__figure--side">
                <img src="{{ asset($section->image) }}" alt="{{ $section->image_caption ?: $section->title }}">
                @if ($section->image_caption)
                    <figcaption>{{ $section->image_caption }}</figcaption>
                @endif
            </figure>
            @if ($section->content)
                <div class="sm-mag-prose {{ !empty($isFirst) ? 'sm-mag-prose--dropcap' : '' }}">
                    {!! $section->content !!}
                </div>
            @endif
        </div>
    @elseif ($section->content)
        <div class="sm-mag-prose {{ !empty($isFirst) ? 'sm-mag-prose--dropcap' : '' }}">
            {!! $section->content !!}
        </div>
    @endif
</section>
