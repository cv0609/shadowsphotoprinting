@extends('front-end.layout.main')
@section('styles')
<!-- <style>
.pro-circle-sec {
    background: #000;
    padding: 80px 0;
}
.pro-hero-card {
    max-width: 980px;
    margin: 0 auto 48px auto;
    background: linear-gradient(135deg, #ffc205 0%, #f5b400 100%);
    border-radius: 14px;
    padding: 40px 36px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.30);
}
.pro-hero-card h1 {
    color: #111;
    font-weight: 700;
    margin-bottom: 10px;
}
.pro-hero-card .sub {
    color: #1f1f1f;
    font-size: 18px;
    margin-bottom: 0;
}
.pro-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
}
.pro-card {
    background: #0b0b0b;
    border: 1px solid rgba(255, 194, 5, 0.45);
    border-radius: 12px;
    padding: 28px;
}
.pro-card h2 {
    color: #ffc205;
    font-size: 30px;
    margin: 0 0 14px 0;
}
.pro-card h3 {
    color: #ffc205;
    font-size: 24px;
    margin: 0 0 10px 0;
}
.pro-card p,
.pro-card li {
    color: #efefef;
    font-size: 17px;
    line-height: 1.7;
}
.pro-card ul {
    margin: 0;
    padding-left: 20px;
}
.shape-divider {
    width: 80px;
    height: 4px;
    border-radius: 20px;
    background: #16a085;
    margin: 0 0 20px 0;
}
.pro-full {
    grid-column: 1 / -1;
}
.pro-image-wrap img {
    width: 100%;
    border-radius: 12px;
    border: 1px solid rgba(255, 194, 5, 0.45);
}
.pro-quote {
    font-size: 24px;
    color: #ffc205;
    font-weight: 700;
}
.pro-cta-row {
    margin-top: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
}
.pro-btn {
    display: inline-block;
    padding: 12px 18px;
    border-radius: 8px;
    text-decoration: none !important;
    font-weight: 700;
    letter-spacing: 0.2px;
}
.pro-btn-primary {
    background: #ffc205;
    color: #121212 !important;
}
.pro-btn-primary:hover {
    background: #f0b700;
}
.pro-btn-outline {
    border: 1px solid #16a085;
    color: #16a085 !important;
}
.pro-btn-outline:hover {
    background: #16a085;
    color: #fff !important;
}
.pro-note {
    color: #c9c9c9 !important;
    font-size: 15px !important;
}
@media (max-width: 991px) {
    .pro-grid {
        grid-template-columns: 1fr;
    }
    .pro-card h2 {
        font-size: 26px;
    }
}
</style> -->

<style>
      .pro-circle-sec {
        background: #000;
        padding: 80px 0;
      }
      .pro-hero-card {
        max-width: 100%;
        margin: 0 auto 48px auto;
        background: linear-gradient(135deg, #ffc205 0%, #f5b400 100%);
        border-radius: 14px;
        padding: 40px 36px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      }
      .pro-hero-card h1 {
        color: #111;
        font-weight: 700;
        margin-bottom: 10px;
      }
      .pro-hero-card .sub {
        color: #1f1f1f;
        font-size: 18px;
        margin-bottom: 0;
      }
      .pro-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px;
      }
      .pro-card {
        background: #0b0b0b;
        border: 1px solid rgba(255, 194, 5, 0.45);
        border-radius: 12px;
        padding: 28px;
      }
      .pro-card h2 {
        color: #ffc205;
        font-size: 30px;
        margin: 0 0 14px 0;
      }
      .pro-card h3 {
        color: #ffc205;
        font-size: 24px;
        margin: 0 0 10px 0;
      }
      .pro-card p,
      .pro-card li {
        color: #efefef;
        font-size: 17px;
        line-height: 1.7;
      }
      .pro-card ul {
        /* margin: 0; */
        padding-left: 20px;
        list-style-type: disc;
      }
      .shape-divider {
        width: 80px;
        height: 4px;
        border-radius: 20px;
        background: #16a085;
        margin: 0 0 20px 0;
      }
      .pro-full {
        grid-column: 1 / -1;
      }
      .pro-image-wrap img {
        width: 100%;
        border-radius: 12px;
        border: 1px solid rgba(255, 194, 5, 0.45);
      }
      .pro-quote {
        font-size: 24px;
        color: #ffc205;
        font-weight: 700;
      }
      .pro-cta-row {
        margin-top: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
      }
      .pro-btn {
        display: inline-block;
        padding: 12px 18px;
        border-radius: 8px;
        text-decoration: none !important;
        font-weight: 700;
        letter-spacing: 0.2px;
      }
      .pro-btn-primary {
        background: #ffc205;
        color: #121212 !important;
      }
      .pro-btn-primary:hover {
        background: #f0b700;
      }
      .pro-btn-outline {
        border: 1px solid #16a085;
        color: #16a085 !important;
      }
      .pro-btn-outline:hover {
        background: #16a085;
        color: #fff !important;
      }
      .pro-note {
        color: #c9c9c9 !important;
        font-size: 15px !important;
      }
      .list-bottom-margin {
        margin-bottom: 16px;
      }
      @media (max-width: 991px) {
        .pro-grid {
          grid-template-columns: 1fr;
        }
        .pro-card h2 {
          font-size: 26px;
        }
      }
      @media (max-width: 767px) {
        .pro-circle-sec {
          padding: 60px 0;
        }
      }
      @media (max-width: 575px) {
        .pro-hero-card {
          margin: 0 auto 30px auto;
        }
        .pro-circle-sec {
          padding: 40px 0;
        }
      }
      @media (max-width: 480px) {
        .pro-circle-sec {
          padding: 30px 0;
        }

        .pro-card h3 {
          font-size: 20px;
          margin: 0 0 10px 0;
        }
        .pro-card p,
        .pro-card li {
          color: #efefef;
          font-size: 16px;
          line-height: 1.5;
        }
        .pro-hero-card {
          padding: 20px 20px;
        }
        .pro-hero-card h1 {
            font-size: 25px;
        }
        .pro-hero-card .sub {
            font-size: 17px;
            line-height: normal;
        }
        .pro-card h2 {
    font-size: 21px;
}
      }
      @media (max-width: 380px) {
        .pro-hero-card h1 {
          margin-bottom: 10px;
          font-size: 23px;
        }
        .pro-hero-card {
          padding: 20px 20px;
        }
        .pro-card {
          padding: 20px;
        }
        .pro-card h2 {
          font-size: 20px;
        }
        .pro-btn-primary,
        .pro-btn-outline {
          font-size: 14px;
          line-height: normal;
        }
        .pro-hero-card .sub {
          line-height: normal;
          font-size: 16px;
        }
      }
    </style>


@endsection
@section('content')
<section class="pro-circle-sec">
    <div class="container">
        <div class="pro-hero-card">
            <h1>Join the Shadows Pro Circle</h1>
            <p class="sub">Supporting Photographers Across Australia</p>
        </div>

        <div class="pro-grid">
            <div class="pro-card pro-full">
                <div class="shape-divider"></div>
                <h2>Why Join the Shadows Pro Circle?</h2>
                <p>Join a supportive community of photographers and enjoy:</p>
                <ul class="list-bottom-margin">
                    <li>Reliable, professional printing you can trust</li>
                    <li>Your work featured on our website with links to your portfolio</li>
                    <li>Exclusive print discounts and Shutter Bucks rewards</li>
                    <li>Opportunities to be shared across our blog and social media</li>
                    <li>A simple way to grow your photography presence</li>
                </ul>
            </div>

            <div class="pro-card pro-image-wrap">
                <img src="{{ asset('assets/images/SPBAs2.jpg') }}" alt="Shadows Pro Circle photographers">
            </div>

            <div class="pro-card">
                <div class="shape-divider"></div>
                <h3>A Community Built for Photographers</h3>
                <p>The Shadows Pro Circle is a community for photographers across Australia who believe their images deserve to be printed.</p>
                <p>Whether you're an experienced photographer, a camera club member, or just starting to build your photography portfolio, you are welcome in the Shadows Pro Circle.</p>
                <p>This is for you if you're looking for reliable printing, consistent quality, and a supportive community that values your work.</p>
            </div>

            <div class="pro-card pro-full">
                <div class="shape-divider"></div>
                <h3>About Us</h3>
                <p>At Shadows Affordable Memories, we love helping photographers see their work printed and enjoyed beyond the screen.</p>
                <p>We are a small Australian family-run print lab, and we understand how important your images are.</p>
                <p>Every print and ready-to-hang gallery-wrapped canvas is produced in-house and carefully checked by photographers.</p>
                <p>We also understand how important reliable turnaround times are when you're working with clients or preparing for competitions.</p>
                <p>We never alter or automatically adjust your images — they are printed exactly as you created them.</p>
                <p>And with our simple promise:</p>
                <p class="pro-quote">If we stuff up - we pay for it!</p>
            </div>

            <div class="pro-card pro-full">
                <div class="shape-divider"></div>
                <h3>Supporting Photographers Through the Shadows Pro Circle</h3>
                <p>We're proud to work with talented photographers from across Australia, and many of their images are featured on our website and social media.</p>
                <p>Photographers in the Shadows Pro Circle may choose to share their work with the community. This may include:</p>
                <ul class="list-bottom-margin">
                    <li>Image galleries with download access and usage rights</li>
                    <li>Photo prints or canvas images featuring their photography</li>
                    <li>Guest blog posts about their photography journey</li>
                    <li>Product images we can showcase across our website and social media</li>
                </ul>
                <p>We keep things flexible - with a simple expectation of contributing at least twice per year.</p>
            </div>

            <div class="pro-card pro-full">
                <div class="shape-divider"></div>
                <h3>As Part of the Shadows Pro Circle</h3>
                <p>Photographers in the Shadows Pro Circle receive Shutter Bucks to use toward print and canvas orders, along with early access to promotions and seasonal offers.</p>
                <p>They also receive their own affiliate link, allowing them to earn commissions on print orders they refer, as well as a 20% discount code they can share with their clients.</p>
                <p>As part of the community, your work will be featured on our website on the Meet Our Shadows Pro Circle Photographers page, with a link to your website or portfolio to help people discover your photography.</p>
                <p>From time to time, their work may also be featured on our website, blog, or social media.</p>
                <p>The Shadows Pro Circle is more than just a program - it's a growing community of photographers who believe in the power of print.</p>
            </div>

            <div class="pro-card pro-full">
                <div class="shape-divider"></div>
                <h2>Program Details</h2>
                <h3>Shutter Bucks & Rewards</h3>
                <p>Shutter Bucks are our way of saying thank you.</p>
                <p>They are issued as store credit and can be used towards your orders with Shadows Affordable Memories.</p>
                <p>Shadows Affordable Memories (formerly known as Shadows Photo Printing) continues to operate through the same website: <a href="https://www.shadowsphotoprinting.com.au" target="_blank" rel="noopener">www.shadowsphotoprinting.com.au</a></p>
                <p>Our name has gently changed after the passing of Shadow 🐾, who will always be part of our story.</p>
                <p>We now carry that story forward with Joblin by our side.</p>

                <h3>Please Note</h3>
                <ul class="list-bottom-margin">
                    <li>Shutter Bucks are only available to approved Shadows Pro Circle members</li>
                    <li>Shutter Bucks are not redeemable for cash</li>
                    <li>Shutter Bucks are not transferable</li>
                    <li>Shutter Bucks can only be used towards eligible orders</li>
                </ul>

                <h3>Affiliate Link Requirement</h3>
                <p>To receive Shutter Bucks rewards, orders must be placed through your unique affiliate link.</p>
                <p>If an order is not placed using your affiliate link, Shutter Bucks rewards will not be issued.</p>
                <p>If you experience any issues, please contact us - we're always happy to help.</p>

                <h3>Contribution Requirements</h3>
                <p>To continue receiving Shutter Bucks rewards, members are expected to contribute at least twice per year.</p>
                <p>This may include:</p>
                <ul>
                    <li>Image galleries (with download access and usage rights)</li>
                    <li>Prints or canvas images featuring your work</li>
                    <li>Guest blog posts (your story, tips, or behind the scenes)</li>
                    <li>Product images featuring Shadows Affordable Memories products</li>
                </ul>
                <p>We'll always work with you and communicate along the way.</p>
            </div>

            <div class="pro-card">
                <div class="shape-divider"></div>
                <h3>Welcoming Camera Club Photographers</h3>
                <p>Many photographers in the Shadows Pro Circle are active in camera clubs across Australia and regularly print their work for competitions, exhibitions, and personal projects.</p>
                <p>We understand how important presentation and print quality are when preparing images for judging or display.</p>
            </div>

            <div class="pro-card">
                <div class="shape-divider"></div>
                <h3>Respecting Your Work</h3>
                <p>Your images always remain your property, and full copyright stays with you.</p>
                <p>With your permission, we may feature your images on our website, blog, or social media to showcase your photography and our printed products.</p>
                <p>Wherever possible, we will credit you with a link to your website or social media.</p>
                <p>Your images will never be sold, licensed, or provided to any third party.</p>
            </div>

            <div class="pro-card pro-full">
                <div class="shape-divider"></div>
                <h3>Who Can Apply?</h3>
                <p>The Shadows Pro Circle is open to photographers across Australia who value quality and community.</p>
                <ul>
                    <li>You must be 18 years or older</li>
                    <li>You must live in Australia</li>
                    <li>You have an active photography business, portfolio, or online presence</li>
                </ul>
                <p>We're proud to work alongside photographers across Australia and help their images come to life in print.</p>
                <p>If the Shadows Pro Circle sounds like something you would enjoy being part of, we would love to welcome you into the community.</p>
                <h3 style="margin-top:18px;">Apply to Join the Shadows Pro Circle</h3>
                <p>Use the application form to submit your details and tell us about your photography.</p>
                <div class="pro-cta-row">
                    <a class="pro-btn pro-btn-primary" href="{{ route('apply-form') }}">Apply to Join the Shadows Pro Circle</a>
                </div>
                <h3 style="margin-top:20px;">Meet Our Shadows Pro Circle Photographers</h3>
                <p>Discover the talented photographers who are part of our Shadows Pro Circle community.</p>
                <div class="pro-cta-row" style="margin-top:10px;">
                    <a class="pro-btn pro-btn-outline" href="{{ route('shadows-pro-circle-photographers') }}">Meet Our Shadows Pro Circle Photographers</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

