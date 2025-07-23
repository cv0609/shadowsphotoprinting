@extends('front-end.layout.main')
@section('styles')
<style>
    /* FAQ Banner */
    section.get-a-quote {
        background: url('{{ asset('assets/images/get-a-quote.jpg') }}') no-repeat center center;
        background-size: cover;
        background-blend-mode: multiply;
        background-color: rgba(0,0,0,0.4);
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0;
    }
    .contact-bnr-text h2 {
        color: #ffc205;
        font-size: 2.5rem;
        font-weight: 700;
        text-shadow: 1px 1px 8px rgba(0,0,0,0.3);
        letter-spacing: 2px;
    }
    .faq-section-title {
        color: #16a085;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        margin-top: 2rem;
        text-align: center;
    }
    .accordion {
        --bs-accordion-bg: #fff;
        --bs-accordion-border-color: #16a085;
        --bs-accordion-btn-bg: #f8f9fa;
        --bs-accordion-btn-color: #16a085;
        --bs-accordion-active-bg: #16a085;
        --bs-accordion-active-color: #fff;
        --bs-accordion-btn-focus-box-shadow: 0 0 0 0.25rem rgba(22,162,133,0.25);
        border-radius: 8px;
        box-shadow: 0 2px 16px 0 rgba(22,162,133,0.08);
    }
    .accordion-button {
        font-weight: 600;
        font-size: 1.1rem;
        background: #f8f9fa;
        color: #16a085;
        border-radius: 8px 8px 0 0;
        transition: background 0.2s, color 0.2s;
    }
    .accordion-button:not(.collapsed) {
        background: #16a085;
        color: #fff;
    }
    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(22,162,133,0.25);
    }
    .accordion-item {
        border: 1px solid #16a085;
        border-radius: 8px;
        margin-bottom: 10px;
        overflow: hidden;
    }
    .accordion-body {
        background: #fff;
        color: #222;
        font-size: 1rem;
        line-height: 1.7;
    }
    @media (max-width: 767px) {
        section.get-a-quote { height: 180px; }
        .faq-section-title { font-size: 1.3rem; }
    }
</style>
@endsection
@section('content')
<section class="get-a-quote">
    <div class="banner-img"></div>
    <div class="container">
        <div class="contact-bnr-text">
            <h2> FAQ </h2>
        </div>
    </div>
</section>
<div class="container mt-5 mb-5">
    <div class="faq-section-title">{{ $items['main_heading'] }}</div>
    <div class="accordion" id="accordionExample">
        @foreach($items['faqs'] as $index => $item)
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

