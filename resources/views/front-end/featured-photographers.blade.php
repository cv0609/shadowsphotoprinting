@extends('front-end.layout.main')
@section('styles')
<!-- <style>
.featured-pro {
    background: #000;
    padding: 80px 0;
}
.featured-hero {
    background: linear-gradient(135deg, #ffc205 0%, #f5b400 100%);
    border-radius: 14px;
    padding: 34px;
    margin-bottom: 30px;
}
.featured-hero h1 {
    color: #111;
    font-weight: 700;
    margin-bottom: 6px;
}
.featured-hero p {
    color: #1f1f1f;
    margin: 0;
}
.featured-intro {
    background: #0b0b0b;
    border: 1px solid rgba(255, 194, 5, 0.45);
    border-radius: 12px;
    padding: 28px;
    margin-bottom: 24px;
}
.shape-divider {
    width: 80px;
    height: 4px;
    border-radius: 20px;
    background: #16a085;
    margin-bottom: 16px;
}
.featured-intro p,
.featured-card p {
    color: #efefef;
    font-size: 17px;
    line-height: 1.75;
}
.featured-image img {
    width: 100%;
    border-radius: 12px;
    border: 1px solid rgba(255, 194, 5, 0.45);
}
.featured-card {
    background: #0b0b0b;
    border: 1px solid rgba(255, 194, 5, 0.45);
    border-radius: 12px;
    padding: 28px;
    margin-top: 24px;
}
.featured-card h2 {
    color: #ffc205;
    margin-bottom: 14px;
}
.pro-table-wrap {
    overflow-x: auto;
}
.pro-table {
    width: 100%;
    border-collapse: collapse;
}
.pro-table th,
.pro-table td {
    border-bottom: 1px solid rgba(255, 194, 5, 0.18);
    padding: 14px 12px;
    color: #efefef;
    vertical-align: top;
}
.pro-table th {
    color: #ffc205;
    font-weight: 700;
    background: #111;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
}
.pro-table td a {
    color: #16a085;
    text-decoration: underline;
}
.pro-table td a:hover {
    color: #1cc2a0;
}
.cta-btn {
    display: inline-block;
    margin-top: 12px;
    background: #ffc205;
    color: #121212 !important;
    border-radius: 8px;
    padding: 12px 18px;
    font-weight: 700;
    text-decoration: none !important;
}
.cta-btn:hover {
    background: #f0b700;
}
@media (max-width: 991px) {
    .featured-hero {
        padding: 24px;
    }
}
</style> -->
<style>
.featured-pro {
    background: #000;
    padding: 80px 0;
}
.featured-hero {
    background: linear-gradient(135deg, #ffc205 0%, #f5b400 100%);
    border-radius: 14px;
    padding: 34px;
    margin-bottom: 30px;
}
.featured-hero h1 {
    color: #111;
    font-weight: 700;
    margin-bottom: 6px;
}
.featured-hero p {
    color: #1f1f1f;
    margin: 0;
}
.featured-intro {
    background: #0b0b0b;
    border: 1px solid rgba(255, 194, 5, 0.45);
    border-radius: 12px;
    padding: 28px;
    /* margin-bottom: 24px; */
}
.shape-divider {
    width: 80px;
    height: 4px;
    border-radius: 20px;
    background: #16a085;
    margin-bottom: 16px;
}
.featured-intro p,
.featured-card p {
    color: #efefef;
    font-size: 17px;
    line-height: 1.75;
}
.featured-image img {
    width: 100%;
    border-radius: 12px;
    border: 1px solid rgba(255, 194, 5, 0.45);
    height: 100%;
    object-fit: cover;
}
.featured-card {
    background: #0b0b0b;
    border: 1px solid rgba(255, 194, 5, 0.45);
    border-radius: 12px;
    padding: 28px;
    margin-top: 24px;
}
.featured-card h2 {
    color: #ffc205;
    margin-bottom: 14px;
}
.pro-table-wrap {
    overflow-x: auto;
}
.pro-table {
    width: 100%;
    border-collapse: collapse;
}
.pro-table th,
.pro-table td {
    border-bottom: 1px solid rgba(255, 194, 5, 0.18);
    padding: 14px 12px;
    color: #efefef;
    vertical-align: top;
}
.pro-table th {
    color: #ffc205;
    font-weight: 700;
    background: #111;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
    text-wrap: nowrap;
}
.pro-table td a {
    color: #16a085;
    text-decoration: underline;
}
.pro-table td a:hover {
    color: #1cc2a0;
}
.cta-btn {
    display: inline-block;
    margin-top: 12px;
    background: #ffc205;
    color: #121212 !important;
    border-radius: 8px;
    padding: 12px 18px;
    font-weight: 700;
    text-decoration: none !important;
}
.cta-btn:hover {
    background: #f0b700;
}
.featured-image {
    height: 100%;
}

@media (max-width: 991px) {
    .featured-hero {
        padding: 24px;
    }
    .featured-intro {
  
    margin-bottom: 24px;
}
.featured-image {
  
    max-height: 750px;
}
}
@media (max-width: 767px){
    .featured-pro {
    background: #000;
    padding: 60px 0;
}
.featured-hero h1 {
    
    font-size: 30px;
}
}
@media (max-width: 575px){
    .featured-pro {
    background: #000;
    padding: 40px 0;
}
.featured-hero h1 {
    font-size: 26px;
}
.pro-hero-card h1 {
    margin-bottom: 0px;
}
}
@media (max-width: 380px) {
    .featured-card h2 {
    
    font-size: 21px;
}
.featured-intro {
   
    padding: 20px;
    /* margin-bottom: 24px; */
}
.featured-card {
  
    padding: 20px;
    
}
.featured-hero h1 {
    font-size: 21px;
}
.featured-hero p {
   
    font-size: 16px;
    line-height: normal;
}
}
</style>

@endsection
@section('content')
<section class="featured-pro">
    <div class="container">
        <div class="featured-hero">
            <h1>Meet Our Shadows Pro Circle Photographers</h1>
            <p>Supporting Photographers Across Australia</p>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="featured-intro">
                    <div class="shape-divider"></div>
                    <p>At Shadows Affordable Memories, we’re proud to work alongside a community of photographers from across Australia.</p>
                    <p>These are the photographers behind the images you see across our website, social media, and printed products.</p>
                    <p>Their work is captured with care, creativity, and passion — and we have the privilege of helping bring those moments to life through print.</p>
                    <p>Every photo print and ready-to-hang canvas is produced right here in Australia, by photographers who understand how much your images matter.</p>
                    <p>If you’ve ever found yourself drawn to the images on our website or products, there’s a good chance you’re seeing the work of one of our Shadows Pro Circle photographers.</p>
                    <p>Each image tells a story... and through print, those stories become something you can hold onto and enjoy every day.</p>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="featured-image">
                    <img src="{{ asset('assets/images/onemeet.jpg') }}" alt="Shadows Pro Circle Photographers">
                </div>
            </div>
              <div class="featured-card">
            <div class="shape-divider"></div>
            <h2>Our Shadows Pro Circle Photographers</h2>
            <div class="pro-table-wrap">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Photography Category</th>
                            <th>Website</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ambassadors as $ambassador)
                        @php
                            $specialties = is_string($ambassador->specialty) ? explode(',', $ambassador->specialty) : [];
                            $specialtyLabels = [];
                            foreach ($specialties as $s) {
                                $s = trim($s);
                                if (isset($specialtyMap[$s])) {
                                    $specialtyLabels[] = $specialtyMap[$s];
                                }
                            }
                            if ($ambassador->other_specialty) {
                                $specialtyLabels[] = $ambassador->other_specialty;
                            }
                        @endphp
                        <tr>
                            <td>{{ $ambassador->name }}</td>
                            <td>{{ $ambassador->location }}</td>
                            <td>{{ implode(', ', $specialtyLabels) }}</td>
                            <td>
                                @if($ambassador->website)
                                    <a href="{{ $ambassador->website }}" target="_blank" rel="noopener">{{ $ambassador->website }}</a>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:#aaa;">No photographers listed yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $ambassadors->links('pagination::bootstrap-4') }}
        </div>
    </div>

        <div class="featured-card">
            <div class="shape-divider"></div>
            <h2>Interested in Joining the Shadows Pro Circle?</h2>
            <p>If you're a photographer who believes your images deserve to be printed, we would love to hear from you.</p>
            <p>The Shadows Pro Circle is a supportive community for photographers across Australia who care about how their work is printed and presented.</p>
            <p>You can learn more or apply here:</p>
            <a class="cta-btn" href="{{ route('apply-form') }}">Apply to Join the Shadows Pro Circle</a>
        </div>

        <div class="featured-card">
            <div class="shape-divider"></div>
            <h2>Thank You to Our Photography Community</h2>
            <p>Every photograph tells a story.</p>
            <p>We’re proud to work alongside photographers who trust us to print their work and help bring those stories to life through printed images.</p>
        </div>
    </div>
</section>
@endsection

