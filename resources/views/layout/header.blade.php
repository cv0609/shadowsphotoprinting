<header class="header">
    <!-- main header -->
    <div class="navigation page-header">
        <div class="container">
            <div class="navigation-inner">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <a href="index.html">
                            <img src="assets/images/logo.png" alt="logo">
                        </a>
                    </div>
                    <div class="sidena mycel" id="mySidenav">
                        <div class="magnifying">
                            <ul class="desk-trt">

                                <li class="update-menu" data-bs-toggle="modal" data-bs-target="#myModal"><a
                                        class=""><span> Login/Signup</span> </a></li>
                                <li class="dropdown"><a href="our-products.html" class="signup">MY ACCOUNT </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <div class="kt-woo-account-nav">
                                                <div class="kad-account-avatar">
                                                    <div class="kad-customer-image">
                                                        <img src="images/user.png" alt="user">
                                                    </div>
                                                    <div class="kad-customer-name">
                                                        <h5> Terri Pangas </h5>
                                                    </div>
                                                </div>
                                                <div class="MyAccount-navigation">
                                                    <ul class="ashboard">
                                                        <li class=""> <a href="my-account.html"
                                                                class="active">Dashboard <i
                                                                    class="fa-solid fa-gauge"></i></a> </li>
                                                        <li class=""> <a href="orders.html">Orders<i
                                                                    class="fa-solid fa-bag-shopping"></i></a>
                                                        </li>
                                                        <li class=""> <a href="downloads.html">Downloads <i
                                                                    class="fa-solid fa-download"></i></a> </li>
                                                        <li class=""> <a href="edit-address.html">Addresses <i
                                                                    class="fa-solid fa-house"></i></a></li>
                                                        <li class=""> <a href="payment-methods.html">Payment
                                                                methods <i
                                                                    class="fa-solid fa-credit-card"></i></a>
                                                        </li>
                                                        <li class=""> <a href="edit-account.html">Account
                                                                details <i class="fa-solid fa-user"></i></a>
                                                        </li>
                                                        <li class=""> <a href="wt-smart-coupon.html">My Coupons
                                                                <i class="fa-solid fa-credit-card"></i></a>
                                                        </li>
                                                        <li class=""><a href="log-out.html">Log out <i
                                                                    class="fa-solid fa-arrow-right"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li><a>
                                        <span class="kt-extras-label">
                                            <span class="cart-extras-title">Cart</span>
                                        </span>
                                        <i class="fa fa-shopping-bag" aria-hidden="true"></i>

                                        <span class="kt-cart-total">0</span>
                                    </a>
                                </li>
                                <li class="social-media"><a><i class="fa-brands fa-facebook-f"></i> </a></li>
                                <li class="social-media"><a> <i class="fa-brands fa-instagram"></i> </a></li>


                            </ul>
                        </div>
                    </div>
                    <div class="toggle-bar">
                        <span class="toggle_menu" onclick="openNav()"><i class="fa fa-bars"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- HEADER BOTTOM -->
    <div class="outside-second">
        <div class="second-navclass">
            <div class="container">
                <div class="second-clearfix">
                    <div class="sidenavs" id="mySidenavs">
                        <span class="closebtn" onclick="closeNav()"><i class="fa-solid fa-xmark"></i></span>
                        <div class="screen-reader">
                            <div class="">
                                <form action="">
                                    <div class="search-box">
                                        <input type="search" class="search-field no-cancel-button"
                                            placeholder="Search …" value="" name="s">
                                        <span class="magnifying"><i
                                                class="fa-solid fa-magnifying-glass"></i></span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('shop') }}">Shop</a></li>
                            <li><a href="{{ route('blog') }}">Blog</a></li>
                            <li><a href="{{ route('fun-facts') }}">Fun Facts</a></li>
                            <li class="dropdown"><a href="{{ route('our-products') }}">Our Products <i
                                        class="fa-solid fa-caret-down"></i></a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('scrapbook-prints') }}">SCRAPBOOK PRINTS</a></li>
                                    <li><a href="{{ route('canvas-prints') }}">CANVAS PRINTS</a></li>
                                    <li><a href="{{ route('posters-panoramics') }}">POSTERS & PANORAMICS</a></li>
                                    <li><a href="{{ route('prints-enlargements') }}">PRINTS & ENLARGEMENTS</a></li>
                                    <li><a href="{{ route('photos') }}">Photos for Sale</a></li>
                                    <li><a href="{{ route('giftcard') }}">Gift Card</a></li>
                                </ul>
                            </li>
                            <li><a href="get-a-quote.html">Get a Quote</a></li>
                            <li><a href="contact-us.html">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- HEADER BOTTOM -->

</header>
