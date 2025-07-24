@extends('front-end.layout.main')
@section('content')
<section class="faq-banner">
    <div class="banner-img"></div>
    <div class="container">
        <div class="contact-bnr-text">
            <h2> FAQ </h2>
        </div>
    </div>
</section>
@php
    $globalIndex = 0; // Global index to track first item overall
@endphp

@foreach($items as $groupIndex => $group)
<div class="container mt-5 mb-5">
    <div class="faq-section-title">{{ $group['main_heading'] }}</div>
    <div class="accordion" id="accordionGroup{{ $groupIndex }}">
        @foreach($group['faqs'] as $index => $item)
        @php
            $isFirst = $globalIndex === 0;
        @endphp
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ $groupIndex }}-{{ $index }}">
                <button class="accordion-button {{ !$isFirst ? 'collapsed' : '' }}"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $groupIndex }}-{{ $index }}"
                        aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                        aria-controls="collapse{{ $groupIndex }}-{{ $index }}">
                    {{ $item['title'] }}
                </button>
            </h2>
            <div id="collapse{{ $groupIndex }}-{{ $index }}"
                 class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}"
                 aria-labelledby="heading{{ $groupIndex }}-{{ $index }}"
                 data-bs-parent="#accordionGroup{{ $groupIndex }}">
                <div class="accordion-body">
                    {{ $item['content'] }}
                </div>
            </div>
        </div>
        @php
            $globalIndex++;
        @endphp
        @endforeach
    </div>
</div>
@endforeach

@endsection
