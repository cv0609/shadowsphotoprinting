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

@php
    $globalIndex = 0; // Global index to track first item overall
@endphp

<div class="container mt-5 mb-5">
    <div class="accordion" id="mainAccordion"> <!-- Unified parent accordion -->
        @foreach($items as $groupIndex => $group)
            <div class="faq-section-title mt-4">{{ $group['main_heading'] }}</div>

            @foreach($group['faqs'] as $index => $item)
                @php
                    $isFirst = $globalIndex === 0;
                @endphp
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $globalIndex }}">
                        <button class="accordion-button {{ !$isFirst ? 'collapsed' : '' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $globalIndex }}"
                                aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $globalIndex }}">
                            {{ $item['title'] }}
                        </button>
                    </h2>
                    <div id="collapse{{ $globalIndex }}"
                         class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}"
                         aria-labelledby="heading{{ $globalIndex }}"
                         data-bs-parent="#mainAccordion">
                        <div class="accordion-body">
                            {{ $item['content'] }}
                        </div>
                    </div>
                </div>
                @php
                    $globalIndex++;
                @endphp
            @endforeach
        @endforeach
    </div>
</div>


@endsection
