<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/slick.js') }}"></script>
<script src="{{ asset('assets/js/aos.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
<script type="module" src="https://cdn.myafterpay.com/elements/embed/afterpay-elements.js" async="true"></script>
<script nomodule src="https://cdn.myafterpay.com/elements/embed/afterpay-elements.legacy.js" async="true"></script>

<script>
$("#user-register").on('click',function(){
    $(".error").addClass('d-none').html('');
    if(!$("#register-name").val())
      {
         $("#register-name-error").removeClass('d-none').html('This field is required.');
      }
      else if(!$("#register-email").val())
      {
         $("#register-email-error").removeClass('d-none').html('This field is required.');
      }
      else if($("#register-email").val() && validateEmail($("#register-email").val()) === false)
      {
        $("#register-email-error").removeClass('d-none').html('Please enter valid email.');
      }
      else if(!$("#register-password").val())
      {
         $("#register-password-error").removeClass('d-none').html('This field is required.');
      }
      else
      {
        $.post("{{ route('user-register') }}",
        {
            name: $("#register-name").val(),
            email: $("#register-email").val(),
            password: $("#register-password").val(),
            password_confirmation: $("#register-password-confirmation").val(),
            '_token': '{{ csrf_token() }}'
        })
        .done(function(res) {
            if(res.status === 'success') {
                $('#register-success').html('Registration successful. A verification email has been sent.');
                // location.reload();
            }
        })
        .fail(function(xhr) {
            if(xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $("#register-email-error").removeClass('d-none').html(errors.email[0]);
            }
        });
      }

});

$("#login").on('click',function(){
    $(".error").addClass('d-none').html('');
    if(!$("#login-name").val())
      {
         $("#login-name-error").removeClass('d-none').html('This field is required.');
      }
      else if(!$("#login-password").val())
      {
         $("#login-email-error").removeClass('d-none').html('This field is required.');
      }
      else
      {
        $.post("{{ route('user-login') }}",
        {
            email: $("#login-name").val(),
            password: $("#login-password").val(),
            '_token': '{{ csrf_token() }}'
        })
        .done(function(res) {
            if(res.status === 'success') {
                $('#register-success').html('User login successfully');
                window.location.href = "{{ url('home') }}";
            }
        })
        .fail(function(xhr) {

            if(xhr.status == 422) {
                $("#login-error").removeClass('d-none').html('Invalid email or password');
            }

            if(xhr.status == 403) {
                $("#login-error").removeClass('d-none').html('Please verify your email address before logging in');
            }
        });
      }

})

$(".toggle_menu").click(function(){
  $(".sidenavs").toggleClass("intro");
});


function validateEmail(email) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

$(document).ready(function () {
    $('.h1-missing').css('display','none');
    $(".toggle_menu").click(function () {
        $(".sidenavs").addClass("intro");
    });
    $(".closebtn").click(function () {
        $(".sidenavs").removeClass("intro");
    });

    // setTimeout(function() {
    //   $('#sailImagePopup').modal('show');
    // }, 1000);
});

</script>

<script>
    $(document).ready(function () {
        $('#newsletter-popup-button').on('click', function () {
            $('.newsletter-popup-container').toggle(); 
        });
    });
</script>

<script>
    $(".orderType").on('click',function(){
       var orderTypeValue = $(this).val();
        if(orderTypeValue == "0")
         {
            $(".shipping-section").removeClass('d-none');
         }
        else
         {
            $(".shipping-section").addClass('d-none');

         } 
    })
</script>

<script>
    $(document).ready(function () {
        $(".afterpayButton").click(function () {
            $("#afterpay-modal").modal("show");
        });

        $("#close-sail-modal").click(function () {
            $("#afterpay-modal").modal("hide");
        });



        // August Promotion Form Submission
        $('#augustPromotionForm').on('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted'); // Debug log
            
            var email = $('#promotionEmail').val();
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.html();
            
            // Show loading state
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Sending...').prop('disabled', true);
            
            $.ajax({
                url: '{{ route("august.promotion.email") }}',
                method: 'POST',
                data: {
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Success:', response); // Debug log
                    if (response.success) {
                        $('#successMessage').show();
                        $('#errorMessage').hide();
                        
                        // Reset button
                        submitBtn.html(originalText).prop('disabled', false);
                        
                        // Close popup after 3 seconds
                        setTimeout(function() {
                            $('#augustPromotionPopup').modal('hide');
                        }, 3000);
                    } else {
                        $('#errorText').text(response.message || 'An error occurred');
                        $('#errorMessage').show();
                        $('#successMessage').hide();
                        
                        // Reset button
                        submitBtn.html(originalText).prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', xhr.responseText); // Debug log
                    var message = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    $('#errorText').text(message);
                    $('#errorMessage').show();
                    $('#successMessage').hide();
                    
                    // Reset button
                    submitBtn.html(originalText).prop('disabled', false);
                }
            });
            
            return false; // Prevent form submission
        });
    });
</script>

{{-- <script>
    var userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    document.cookie = "user_timezone=" + userTimeZone + "; path=/";
</script>

<script>
  var userTimezone = {!! setAppTimezone() !!};
  console.log(userTimeZone);
</script> --}}

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        var currentCookie = document.cookie.match('(^|;)\\s*user_timezone\\s*=\\s*([^;]+)')?.pop() || '';

        if (!currentCookie || currentCookie !== userTimeZone) {
            document.cookie = "user_timezone=" + userTimeZone + "; path=/";
            location.reload(); // Reload page after setting timezone
        } else {
            console.log("User Timezone:", userTimeZone);
            var userTimezone = {!! setAppTimezone() !!}; 
            console.log("Laravel Timezone:", userTimezone);
        }
    });
</script>



