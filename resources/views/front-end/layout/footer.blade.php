{{-- <div class="newsletter-popup-container" style="display: none;">
    <iframe width="540" height="454" src="https://9b41dcb9.sibforms.com/serve/MUIFACxvmkzwHkfGewsdaZwbjQk9ADo7q-xiQlZiFN0-FV9Hb6M1etx9lKlwijle_Q7CEEUq7aT8nf38jhc-HmtAaBN2DLPeoUc3Cpk6Rb94lN1PIqkwCqcyFbl6cT9lXfEIQHZvHk8Gz8ad-oQsUnGDveoSqTU5oDnPskA_o4x1xGAUdxaIuP2Jc7NLXdbttxx4rjnEv6w1j5-T" frameborder="0" scrolling="auto" allowfullscreen style="display: block;margin-left: auto;margin-right: auto;max-width: 100%;"></iframe>
</div> --}}

<div class="newsletter-popup-button-container">
    <button id="newsletter-popup-button" data-bs-target="#newsletter-modal" data-bs-toggle="modal" class="newspopup">Subscribe to Newsletter</button>
</div>

<div class="ambassador-button-container">
    <a href="{{route('photographer-brandAmbassador')}}" class="btn">Become an Ambassador</a>
</div>

    <div class="modal fade newslatter-cls" id="newsletter-modal" aria-hidden="true"
        aria-labelledby="exampleModalToggleLabel1" tabindex="-1">
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



<footer class="footer">
    <div class="container">
        <div class="footerbase">
            <div class="footercredits">
                <p>© 2025 Shadows Photo Printing</p>
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
    <div class="modal" id="login-form">
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
