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
            <form id="submitForm" action="{{route('send-quote')}}" method="post">
                @csrf
                <div class="wrapper-latest">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="name">First Name </label>
                                <input type="text" id="name" name="name" autocomplete="off"
                                    placeholder="First Name">
                                <span class="validation-error name_error"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="last_name">Last Name </label>
                                <input type="text" id="last_name" name="last_name" autocomplete="off"
                                    placeholder="Last Name">
                                <span class="validation-error last_name_error"></span>
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
                                <span class="validation-error email_error"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="phone_number">Phone Number </label>
                                <input type="number" id="phone_number" name="phone_number" autocomplete="off"
                                    placeholder="Phone Number">
                                <span class="validation-error phone_number_error"></span>
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
                                <span class="validation-error requested_error"></span>
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
                                <span class="validation-error message_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-submit">
                    <button type="button" id="submitBtn">Get a Quote</button>
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


<script>

$(document).ready(function(){
    $('#submitBtn').on('click', function() {
        $(document).find('.text-danger').text('');    

        var error = false;

        if ($('#name').val() == '') {
            $('.name_error').text('Name field is required.');
            $('.name_error').addClass('text-danger');
            error = true;
        }

        if ($('#last_name').val() == '') {
            $('.last_name_error').text('Last name field is required.');
            $('.last_name_error').addClass('text-danger');
            error = true;
        }

        if ($('#email').val() == '') {
            $('.email_error').text('Email field is required.');
            $('.email_error').addClass('text-danger');
            error = true;
        }

        if ($('#phone_number').val() == '') {
            $('.phone_number_error').text('Phone number field is required.');
            $('.phone_number_error').addClass('text-danger');
            error = true;
        }

        if ($('#requested').val() == '') {
            $('.requested_error').text('Requested field is required.');
            $('.requested_error').addClass('text-danger');
            error = true;
        }

        if ($('#message').val() == '') {
            $('.message_error').text('Message field is required.');
            $('.message_error').addClass('text-danger');
            error = true;
        }

        if (error) {
            return false;
        } else {
            $('#submitForm').submit();
        }
    });
    })
    
</script>



@endsection
