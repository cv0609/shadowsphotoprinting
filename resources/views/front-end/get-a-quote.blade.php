@extends('front-end.layout.main')
@section('content')
<section class="get-a-quote">
    <div class="banner-img">
        @foreach ($page_content['get_a_quote_banner'] as $quote_image)
          <img src="{{ asset($quote_image) }}" alt="{{ pathinfo($quote_image, PATHINFO_FILENAME) }}">
        @endforeach
    </div>
    <div class="container">
        <div class="contact-bnr-text">
            <h2>{{ $page_content['get_a_quote_banner_title'] }} </h2>
        </div>
    </div>
</section>

<section class="get-quote">
    <div class="container">
        <div class="get-quote-inner">
            <form id="myForm" action="/submit" method="post">
                <div class="wrapper-latest">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="name">First Name </label>
                                <input type="text" id="name" name="name" autocomplete="off"
                                    placeholder="First Name">
                                <div id="nameError" class="error"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="last_name">Last Name </label>
                                <input type="text" id="last_name" name="last_name" autocomplete="off"
                                    placeholder="Last Name">
                                <div id="last_nameError" class="error"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrapper-latest">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="email">Email </label>
                                <input type="text" id="email" name="email" autocomplete="off"
                                    placeholder="Email">
                                <div id="emailError" class="error"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="phone_number">Phone Number </label>
                                <input type="text" id="phone_number" name="phone_number" autocomplete="off"
                                    placeholder="Phone Number">
                                <div id="numrError" class="error"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrapper-latest">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="requested">Requested Size to be printed </label>
                                <input type="text" id="requested" name="requested" autocomplete="off"
                                    placeholder="For Example:- 10&quot;X10&quot;">
                                <div id="requestedError" class="error"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrapper-latest">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="message">Message*</label>
                                <textarea id="message" name="message" rows="10" cols="40"
                                    autocomplete="off"></textarea>
                                <div id="messageError" class="error"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-submit">
                    <button type="button" onclick="validateForm()">Get a Quote</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
@section('scripts')
    <script>
        AOS.init({
            duration: 1200,
        })

    </script>
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
