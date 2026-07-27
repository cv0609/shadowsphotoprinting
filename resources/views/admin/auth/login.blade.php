<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin Login | Shadows Photo Printing</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.jpg') }}" type="image/x-icon">
    <link href="{{ asset('assets/admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/build/css/custom.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/admin-login.css') }}?v=1" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <div class="logo">
            <a href="{{ url('/') }}">
              <img src="{{ asset('assets/images/logo.png') }}" alt="Shadows Photo Printing">
            </a>
          </div>

          <section class="login_content">
            <form action="{{ route('admin.login.post') }}" method="POST" class="input-color" autocomplete="on">
              @csrf
              <h1 class="login-title">Admin Login</h1>
              <p class="login-subtitle">Sign in to manage orders, products, and site content.</p>

              @if(Session::has('error'))
                <p class="text-danger alert-error">{{ Session::get('error') }}</p>
              @endif

              <div class="form-group">
                <label for="email">Email</label>
                <input
                  type="text"
                  class="form-control"
                  id="email"
                  name="email"
                  placeholder="Enter your email"
                  value="{{ old('email') }}"
                  autocomplete="username"
                >
                @error('email')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="password">Password</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  name="password"
                  placeholder="Enter your password"
                  autocomplete="current-password"
                >
                @error('password')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <div>
                <button type="submit" class="btn btn-default submit">Log in</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="login-footer">
                  Shadows Photo Printing &middot;
                  <a href="{{ url('/') }}">Back to website</a>
                </p>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>
