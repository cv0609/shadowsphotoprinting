<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/slick.js') }}"></script>
<script src="{{ asset('assets/js/aos.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
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
    $(".toggle_menu").click(function () {
        $(".sidenavs").addClass("intro");
    });
    $(".closebtn").click(function () {
        $(".sidenavs").removeClass("intro");
    });

    // setTimeout(function() {
    //   $('#sailImagePopup').modal('show');
    // }, 1000);

    function setCookie(name, value, days) {
      var expires = "";
      if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); // Adds 1 day in milliseconds
        expires = "; expires=" + date.toUTCString(); // Set expiration date
      }
      document.cookie = name + "=" + (value || "") + expires + "; path=/"; // Cookie applies to entire site
    }

    // Function to get a cookie by name
    function getCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
    }

    // Check if the cookie exists
    if (!getCookie("sailpopupcokies")) {
      // If the cookie does not exist, show the modal after 3 minutes
      setTimeout(function() {
        $('#sailImagePopup').modal('show');
      }, 2000); // 2 second delay
    }

    // When the close button is clicked, hide the modal and set the cookie
    $('#close-sail-modal').on('click', function() {
      $('#sailImagePopup').modal('hide');
      setCookie("sailpopupcokies", "closed", 1); // Cookie valid for 1 day (24 hours)
    });
});

</script>
