{{-- <div class="newsletter-popup-container" style="display: none;">
    <iframe width="540" height="454" src="https://9b41dcb9.sibforms.com/serve/MUIFACxvmkzwHkfGewsdaZwbjQk9ADo7q-xiQlZiFN0-FV9Hb6M1etx9lKlwijle_Q7CEEUq7aT8nf38jhc-HmtAaBN2DLPeoUc3Cpk6Rb94lN1PIqkwCqcyFbl6cT9lXfEIQHZvHk8Gz8ad-oQsUnGDveoSqTU5oDnPskA_o4x1xGAUdxaIuP2Jc7NLXdbttxx4rjnEv6w1j5-T" frameborder="0" scrolling="auto" allowfullscreen style="display: block;margin-left: auto;margin-right: auto;max-width: 100%;"></iframe>
</div> --}}

<div class="newsletter-popup-button-container">
    <button id="newsletter-popup-button" data-bs-target="#newsletter-modal" data-bs-toggle="modal" class="newspopup">Subscribe to Newsletter</button>
</div>

<div class="ambassador-button-container">
    <a href="{{ route('shadows-pro-circle') }}" class="btn">Join the Shadows Pro Circle</a>
</div>

    <div class="modal fade newslatter-cls" id="newsletter-modal" aria-hidden="true"
        aria-labelledby="exampleModalToggleLabel1" tabindex="-1" style="z-index: 9999999 !important;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <iframe width="540" height="454" src="https://9b41dcb9.sibforms.com/serve/MUIFACxvmkzwHkfGewsdaZwbjQk9ADo7q-xiQlZiFN0-FV9Hb6M1etx9lKlwijle_Q7CEEUq7aT8nf38jhc-HmtAaBN2DLPeoUc3Cpk6Rb94lN1PIqkwCqcyFbl6cT9lXfEIQHZvHk8Gz8ad-oQsUnGDveoSqTU5oDnPskA_o4x1xGAUdxaIuP2Jc7NLXdbttxx4rjnEv6w1j5-T" frameborder="0" scrolling="auto" allowfullscreen style="display: block;margin-left: auto;margin-right: auto;max-width: 100%;"></iframe>
                </div>
            </div>
        </div>
    </div>



<footer class="site-footer">
    <!-- Top Footer Bar: Newsletter & Australian Made Assurance -->
    <div class="footer-top-strip">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-12 mb-3 mb-lg-0">
                    <div class="footer-newsletter-wrap">
                        <i class="fa fa-envelope-open-o footer-newsletter-icon"></i>
                        <div>
                            <h4 class="footer-strip-title">Stay Connected &amp; Save</h4>
                            <p class="footer-strip-desc">Subscribe to receive exclusive monthly discounts, photo tips, and community news.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12 text-lg-end text-center footer-strip-actions">
                    <button type="button" class="footer-subscribe-btn" data-bs-target="#newsletter-modal" data-bs-toggle="modal">
                        <i class="fa fa-paper-plane me-1"></i> Subscribe to Newsletter
                    </button>
                    <a href="{{ route('shadows-pro-circle') }}" class="footer-pro-btn ms-lg-2">
                        <i class="fa fa-star me-1"></i> Shadows Pro Circle
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer 4 Columns -->
    <div class="footer-main-content">
        <div class="container">
            <div class="row gy-4">
                <!-- Column 1: Brand, Story & Trust Signals -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-col-about">
                        <div class="footer-logo mb-3">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="Shadows Affordable Memories Logo" width="180" height="105" style="max-width: 180px; height: auto;">
                            </a>
                        </div>
                        <p class="footer-about-text">
                            Printed in-house in Glenreagh NSW with genuine care. Professional photo printing, digital scrapbook pages, handmade canvas art, and handcrafted community gifts &mdash; delivered Australia-wide.
                        </p>
                        <div class="footer-trust-badge">
                            <span class="badge-aus"><i class="fa fa-check-circle me-1" style="color: #FFC205;"></i> 100% Australian Printed In-House</span>
                        </div>
                        <div class="footer-social-icons mt-3">
                            <span class="social-label">Follow Our Community:</span>
                            <div class="social-links-wrap mt-2">
                                <a href="https://www.facebook.com/PhotoPrintingShadowsCommunityHub" target="_blank" rel="noopener" aria-label="Facebook" class="footer-social-btn"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="https://www.instagram.com/shadowsphotoprinting/" target="_blank" rel="noopener" aria-label="Instagram" class="footer-social-btn"><i class="fa-brands fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Our Products (SEO Category Links) -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-col-nav">
                        <h4 class="footer-heading">Our Products</h4>
                        <ul class="footer-links-list">
                            <li><a href="{{ url('shop') }}"><i class="fa fa-angle-right me-2"></i>Order Photo Prints</a></li>
                            <li><a href="{{ url('our-products/scrapbook-page-printing') }}"><i class="fa fa-angle-right me-2"></i>Scrapbook Page Printing</a></li>
                            <li><a href="{{ url('our-products/canvas-prints') }}"><i class="fa fa-angle-right me-2"></i>Handmade Canvas Prints</a></li>
                            <li><a href="{{ url('our-products/photo-enlargements') }}"><i class="fa fa-angle-right me-2"></i>Photo Enlargements</a></li>
                            <li><a href="{{ url('our-products/poster-prints') }}"><i class="fa fa-angle-right me-2"></i>Poster Prints</a></li>
                            <li><a href="{{ url('our-products/hand-craft') }}"><i class="fa fa-angle-right me-2"></i>Handcrafted &amp; Gifts</a></li>
                            <li><a href="{{ url('our-products/gift-card') }}"><i class="fa fa-angle-right me-2"></i>Digital Gift Cards</a></li>
                            <li><a href="{{ url('our-products') }}"><i class="fa fa-angle-right me-2"></i>All Products &amp; Bundles</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Column 3: Customer Care & Information -->
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="footer-col-nav">
                        <h4 class="footer-heading">Customer Care</h4>
                        <ul class="footer-links-list">
                            <li><a href="{{ url('more-info') }}"><i class="fa fa-angle-right me-2"></i>About Us &amp; Story</a></li>
                            <li><a href="{{ route('faqs') }}"><i class="fa fa-angle-right me-2"></i>Help &amp; FAQs</a></li>
                            <li><a href="{{ url('more-info') }}"><i class="fa fa-angle-right me-2"></i>Shipping &amp; Delivery</a></li>
                            <li><a href="{{ url('more-info') }}"><i class="fa fa-angle-right me-2"></i>Returns &amp; Quality</a></li>
                            <li><a href="{{ url('get-a-quote') }}"><i class="fa fa-angle-right me-2"></i>Get a Custom Quote</a></li>
                            <li><a href="{{ route('shadows-monthly') }}"><i class="fa fa-angle-right me-2"></i>Shadows Monthly</a></li>
                            <li><a href="{{ url('blogs') }}"><i class="fa fa-angle-right me-2"></i>Photography Blog</a></li>
                            <li><a href="{{ url('fun-facts') }}"><i class="fa fa-angle-right me-2"></i>Fun Facts &amp; Tips</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Column 4: Visit Us & Contact Details -->
                <div class="col-lg-3 col-md-6">
                    <div class="footer-col-contact">
                        <h4 class="footer-heading">Visit &amp; Contact Us</h4>
                        <ul class="footer-contact-info">
                            <li>
                                <i class="fa fa-map-marker footer-info-icon"></i>
                                <div>
                                    <strong>Shadows Affordable Memories</strong><br>
                                    21 Boundary Street, Glenreagh NSW 2450<br>
                                    <span class="text-gold-subtle">&#10003; Free In-Store Pickup Available</span>
                                </div>
                            </li>
                            <li>
                                <i class="fa fa-phone footer-info-icon"></i>
                                <div>
                                    <a href="tel:+61266029424">+61 2 6602 9424</a>
                                </div>
                            </li>
                            <li>
                                <i class="fa fa-envelope footer-info-icon"></i>
                                <div>
                                    <a href="mailto:shadowsphotoprinting@outlook.com">shadowsphotoprinting@outlook.com</a>
                                </div>
                            </li>
                            <li>
                                <i class="fa fa-clock-o footer-info-icon"></i>
                                <div>
                                    <strong>Operating Hours:</strong><br>
                                    Mon &ndash; Fri: 9:00 AM &ndash; 4:30 PM<br>
                                    Online Orders: 24/7 Available
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar: Legal Links & Accepted Payment Badges -->
    <div class="footer-bottom-strip">
        <div class="container">
            <div class="row align-items-center py-3">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <p class="footer-copyright-text mb-0">
                        &copy; {{ date('Y') }} <strong>Shadows Affordable Memories</strong>. All rights reserved. 
                        Proudly Australian Family Owned.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-payment-badges">
                        <span class="payment-title me-2">Accepted Payments:</span>
                        <span class="badge-payment" title="Visa"><i class="fa-brands fa-cc-visa"></i></span>
                        <span class="badge-payment" title="Mastercard"><i class="fa-brands fa-cc-mastercard"></i></span>
                        <span class="badge-payment" title="Stripe"><i class="fa-brands fa-stripe"></i></span>
                        <span class="badge-payment payment-text-badge" title="Afterpay">afterpay<span class="afterpay-dot">&#10022;</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<div id="ImgViewer" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="modal-close">&times;</button>
        </div>
        <div class="modal-body">
          <img src="" alt="image" id="modal-img">
        </div>
      </div>
    </div>
</div>

<div class="login-popup">
    <div class="modal" id="login-form" style="z-index: 9999999 !important;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">

                    <button type="button" class="btn-close mfp-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="woocommerce-notices-wrapper">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="customer_login">
                                <div class="woocommerce-form">
                                    <h2>Login</h2>
                                    <span class="text-danger" id="login-error"></span>
                                    <form action="#" method="post" class="input-color">
                                        <div class="woocommerce-wrapper">
                                            <label for="username-pop">Username or email address *</label>
                                            <input type="text" name="name" id="login-name" placeholder="Enter username">
                                            <span class="text-danger d-none error" id="login-name-error"></span>
                                        </div>
                                        <div class="woocommerce-wrapper">
                                            <label for="username-pop">Password*</label>
                                            <input type="password" name="password" id="login-password" placeholder="Enter password">
                                            <span class="text-danger d-none error" id="login-email-error"></span>
                                        </div>
                                        <div class="woocommerce-wrapper">
                                            <button type="button" id="login" class="login-btn">Login</button>
                                        </div>
                                        <div class="woocommerce-wrapper">
                                            <input type="checkbox">
                                            <span>Remember me</span>
                                        </div>
                                        <p class="woocommerce-LostPassword lost_password">
                                            <a href="{{route('forgot-password')}}">Lost your password?</a>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="customer_login">
                                <div class="woocommerce-form">
                                    <h2>Register</h2>
                                    <span class="text-success" id="register-success"></span>
                                    <form action="" method="post" class="input-color">
                                        @csrf
                                        <div class="woocommerce-wrapper">
                                            <label for="username-pop">Username *</label>
                                            <input type="text" name="name" id="register-name" placeholder="Enter usename">
                                            <span class="text-danger d-none error" id="register-name-error"></span>
                                        </div>
                                        <div class="woocommerce-wrapper">
                                            <label for="username-pop">Email address *</label>
                                            <input type="text" name="email" id="register-email" placeholder="Enter email address">
                                            <span class="text-danger d-none error" id="register-email-error"></span>
                                        </div>
                                        <div class="woocommerce-wrapper">
                                            <label for="username-pop">Password *</label>
                                            <input type="password" name="password" id="register-password" placeholder="Enter password">
                                            <span class="text-danger d-none error" id="register-password-error"></span>
                                        </div>

                                        {{-- <div class="woocommerce-wrapper">
                                            <p class="wrapper-personal-data-p">Your personal data will be used to support your experience
                                                throughout
                                                this website, to manage access to your account, and for other
                                                purposes described in our privacy
                                                policy.</p>

                                        </div>
                                        <span class="privacy-text">privacy
                                                    policy</span> --}}
                                        <div class="woocommerce-wrapper">
                                            <button type="button" id="user-register" class="login-btn">Register</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

</div>
