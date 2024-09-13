
<footer class="footer">
    <div class="container">
        <div class="footerbase">
            <div class="footercredits">
                <p> © 2024 Shadows Photo Printing - WordPress Theme by <a href="#">Kadence WP</a>

                </p>
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

                                        <div class="woocommerce-wrapper">
                                            <p class="wrapper-personal-data-p">Your personal data will be used to support your experience
                                                throughout
                                                this website, to manage access to your account, and for other
                                                purposes described in our privacy
                                                policy.</p>
                                                {{-- <span class="privacy-text">privacy
                                                    policy</span>. --}}
                                        </div>
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
