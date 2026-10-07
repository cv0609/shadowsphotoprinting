@extends('front-end.layout.main')
@section('content')
{{-- @php dd($page_content); @endphp --}}
<section class="about-printing">
    <div class="container">
        <div class="about-printing">
            <h3>About Shadows Affordable Memories</h3>
            
            <h4>Professional Photo Printing &amp; Community Art, History &amp; Creative Gallery</h4>
            <p>At <strong>Shadows Affordable Memories<strong>, we’re professional photographers passionate about supporting the photography and scrapbooking communities across <strong>Australia and New Zealand</strong>. As a small family-owned business in <strong>Glenreagh, NSW</strong>, we understand how meaningful your images are — that’s why every order is handled with care and attention to detail.</p>
            <p>Alongside our professional printing services, our <strong>Community Art, History &amp; Creative Gallery<strong> provides a welcoming place to discover local art, photography, handcrafted gifts and unique products from talented artists, creators and makers.</p>
            
            <h4>Premium Quality Prints</h4>
            <p>We use high-quality photo media for your photo prints and scrapbook pages. Your files are printed exactly as provided—we don’t apply filters or edits—preserving the original emotion and intent behind every image.</p>
            <p>Every image is checked before printing, and your finished prints are checked again before packing.</p>
            
            <h4>Scrapbook Page Printing</h4>
            <p>We print your digital scrapbook pages on high-quality <strong>Lustre photo media</strong>, helping preserve the photos, stories and details you’ve brought together. Available in <strong>6×8, 8×8, 10×10 and 12×12 inch sizes</strong>, your pages are printed exactly as provided and carefully checked before packing.</p>
            <p>From a few special pages to a full album, we proudly support scrapbookers across <strong>Australia and New Zealand</strong>.</p>
            
            <h4>Handmade Canvas Prints</h4>
            <p>Our canvas prints are handmade in-house using premium stain-resistant poly-cotton canvas with a soft, artistic finish. Each piece is carefully <strong>gallery-wrapped on a 38 mm stretcher bar<strong>, ready to hang on your wall.</p>
            <p>From printing to stretching and finishing, every canvas receives the care and attention we would give our own.</p>
            
            <h4>Shipping Across Australia &amp; New Zealand</h4>
            <p>We ship photo prints, scrapbook pages and canvas prints to customers across <strong>Australia and New Zealand</strong>.</p>
            <p>Within Australia, we offer standard and express delivery through Australia Post. For sizes exceeding Australia Post limits, we provide a flat-rate courier service.</p>
            <p>For New Zealand orders, please check the delivery options and shipping costs available when placing your order.</p>
            <p><strong>In-store pickup from our Glenreagh shop is free for all online orders.</strong></p>
            
            <h4>Quick Processing Times</h4>
            <p>Printing typically takes <strong>1–4 business days</strong>, depending on the size and complexity of your order. We aim to have most orders ready for dispatch within <strong>1–2 days</strong>, and we’ll let you know if there are any delays.</p>
            <p>Delivery time is additional and varies depending on your destination and selected shipping service.</p>
            
            <h4>Our Community Art, History &amp; Creative Gallery</h4>
            <p>Our gallery celebrates the creativity of our community, offering a place where local artists, photographers, creators and makers can display and sell their work.</p>
            <p>From original artwork and photography to handcrafted gifts and locally made creations, every piece tells its own story and helps support the talented people behind it.</p>
            <p>Whether you’re collecting your latest photo prints, choosing a thoughtful gift, looking for a piece of local art or simply browsing, we invite you to come in and discover what our community has to share.</p>
            
            <h4>A Mural That Tells Our Story</h4>
            <p>The mural on the front of our shop celebrates the history, heritage and heart of Glenreagh.</p>
            <p>Inspired and designed by the Shadows Family and beautifully brought to life by talented local artist <strong>Honi Reifler</strong>, it reflects our town’s rich timber heritage and honours the days when steam trains travelled through Glenreagh. The artwork features our local steam train, affectionately known as <strong>Betty</strong>, and the <strong>Dorrigo tunnel</strong>.</p>
            <p>Whether you’re collecting your latest photo prints, choosing a thoughtful gift, looking for a piece of local art or simply browsing, we invite you to come in and discover what our community has to share.</p>
            <p>The mural also pays tribute to <strong>Shadow</strong>, our much-loved Staffy and the inspiration behind our business. Her name proudly lives on in <strong>Shadows Affordable Memories</strong>, and her love, loyalty and gentle spirit continue to remind us why preserving life’s special moments is so important.</p>
            <p>For our family, the mural is far more than artwork. It brings together our love for Shadow, our connection to Glenreagh and the memories that connect us all.</p>
            <p>We hope you’ll take a moment to enjoy it, reflect on the stories it tells and create a few new memories of your own.</p>
            
            <h4>Are You a Local Artist or Creator?</h4>
            <p>If you’re a local artist, photographer, creator or maker interested in displaying and selling your work in our gallery, <strong>please contact us</strong>. We’d love to hear about what you do and discuss whether our space would suit your work.</p>
            
            <h4>Visit Us</h4>
            <p><strong>Shadows Affordable Memories</strong><br>
            <strong>Community Art, History &amp; Creative Gallery</strong><br>
            <strong>21 Boundary Street, Glenreagh, NSW</strong></p>
            
            <p>A welcoming community space where memories are preserved, creativity is celebrated and local history is honoured.</p>
            
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