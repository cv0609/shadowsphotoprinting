<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/slick.js') }}"></script>
<script src="{{ asset('assets/js/aos.js') }}"></script>

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
                $('#register-success').html('User registered successfully');
                location.reload();
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
                location.reload();
            }
        })
        .fail(function(xhr) {
            if(xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $("#login-error").removeClass('d-none').html('Login details not matched');
            }
        });
      }

})


function validateEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
</script>
