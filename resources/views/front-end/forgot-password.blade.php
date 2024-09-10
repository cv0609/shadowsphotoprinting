@extends('front-end.layout.main')
@section('content')
{{-- @php dd($page_content); @endphp --}}
{{-- <section class="contact-banner">
    <div class="container">
        <div class="contact-bnr-text">
            <h2></h2>
        </div>
    </div>
</section> --}}

{{-- @if(Session::has('success'))
 {{Session::get('success')}}
@endif --}}

{{-- <section class="shipping-policy">
    <div class="container">
       <h3>Forgot Password</h3>
       <form action="{{route('forgot-save')}}" method="post" id="forgot-form">
          @csrf
          <label for="">Enter email</label>
          <input type="text" name="email" id="email">

          @if($errors->has('email'))
            <div class="error-message">{{ $errors->first('email') }}</div>
          @endif

          @if(Session::has('error'))
            {{Session::get('error')}}
          @endif
          <button type="submit">Submit</button>
       </form>
    </div>
</section> --}}

<section class="lost-your-password">
  <div class="container">
      <div class="your-password">
          <div class="row">
              <div class="col-lg-12">
                  <div class="nosidebar">
                      <form action="{{route('forgot-save')}}" method="post" id="forgot-form" class="lost_reset_password">
                        @csrf
                          <div class="receive_content">
                            <p>

                              @if(Session::has('success'))
                              <div class="coupon-wrapper">
                                  <p class="text-center">{{Session::get('success')}}</p>
                              </div>
                              @endif

                            </p>
                              <p>Lost your password? Please enter your username or email address. You will
                                  receive a link to create a new password via email.</p>
                          </div>
                          <div class="receive_content">
                              <label>Username or email</label>
                              <input type="text" name="email" id="email" autocomplete="off">

                              @if($errors->has('email'))
                                <p class="error-message" style="color: red;">{{ $errors->first('email') }}</p>
                              @endif
                  
                              @if(Session::has('error'))
                                {{Session::get('error')}}
                              @endif

                          </div>
                          <div class="receive_content">
                              <button type="submit" class="reset_password" value="Reset password">Forgot
                                  password</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</section>

@endsection
@section('scripts')
  
@endsection
