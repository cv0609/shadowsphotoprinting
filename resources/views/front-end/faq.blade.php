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
 @foreach($items as $group)
<div class="container mt-5 mb-5">
    <div class="faq-section-title">{{ $group['main_heading'] }}</div>
    <div class="accordion" id="accordionExample">
        @foreach($group['faqs'] as $index => $item)
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ $index }}">
                <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                    {{ $item['title'] }}
                </button>
            </h2>
            <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    {{ $item['content'] }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
 @endforeach
@endsection
