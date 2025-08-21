@extends('front-end.layout.main')
@section('content')
{{-- @php dd($page_content); @endphp --}}
<section class="about-printing">
    <div class="container">
        <div class="about-printing">
            <h3>About Shadows Photo Printing & Gift Shop</h3>
            
            <p>At Shadows Photo Printing & Gift Shop, we’re professional photographers passionate about supporting the photography and scrapbooking communities across Australia. As a small, family-owned business, we understand how meaningful your images are — that’s why every order is handled with genuine care and attention to detail.</p>
            
            <h4>Premium Quality Prints</h4>
            <p>We use high-quality archival paper to ensure your photo prints and scrapbook pages are made to last. Your files are printed exactly as provided — we don’t apply filters or edits — preserving the original emotion and intent behind every shot.</p>
            
            <h4>Handmade Canvas Prints</h4>
            <p>Our canvas prints are handmade in-house, using premium stain-resistant poly-cotton canvas that features a soft, artistic finish. Each piece is gallery-wrapped with love and care, ready to hang straight on your wall.</p>
            
            <h4>Australia-Wide Shipping</h4>
            <p>We ship Australia-wide via Australia Post, offering both standard and express delivery options. For sizes exceeding Australia Post limits, we provide a flat-rate courier service. In-store pickup is free for all online orders.</p>
            
            <h4>Quick Processing Times</h4>
            <p>Printing typically takes 1–4 business days, depending on the size and complexity of your order. Most orders are dispatched within 1–2 days, and we’ll notify you immediately if there are any delays.</p>
            
        </div>
    </div>
</section>
@endsection
@section('scripts')
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