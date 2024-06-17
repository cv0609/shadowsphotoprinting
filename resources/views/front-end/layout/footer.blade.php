
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

<div class="login-popup">
    <div class="modal" id="login-form">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="woocommerce-notices-wrapper">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="customer_login">
                                <div class="woocommerce-form">
                                    <h2>Login</h2>
                                    <span class="text-danger" id="login-error"></span>
                                    <form action="#" method="post">
                                        <div class="woocommerce-wrapper">
                                            <label for="username-pop">Username or email address *</label>
                                            <input type="text" name="name" id="login-name">
                                            <span class="text-danger d-none error" id="login-name-error"></span>

                                        </div>
                                        <div class="woocommerce-wrapper">
                                            <label for="username-pop">Password*</label>
                                            <input type="text" name="password" id="login-password">
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
                                            <a href="#">Lost your password?</a>
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
                                    <form action="" method="post">
                                        @csrf
                                        <div class="woocommerce-wrapper">
                                            <label for="username-pop">Username *</label>
                                            <input type="text" name="name" id="register-name">
                                            <span class="text-danger d-none error" id="register-name-error"></span>
                                        </div>
                                        <div class="woocommerce-wrapper">
                                            <label for="username-pop">Email address *</label>
                                            <input type="text" name="email" id="register-email">
                                            <span class="text-danger d-none error" id="register-email-error"></span>
                                        </div>
                                        <div class="woocommerce-wrapper">
                                            <label for="username-pop">Password *</label>
                                            <input type="password" name="password" id="register-password">
                                            <span class="text-danger d-none error" id="register-password-error"></span>
                                        </div>

                                        <div class="woocommerce-wrapper">
                                            <p>Your personal data will be used to support your experience
                                                throughout
                                                this website, to manage access to your account, and for other
                                                purposes described in our <span class="privacy-text">privacy
                                                    policy</span>.</p>
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
