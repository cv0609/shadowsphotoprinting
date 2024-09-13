@extends('front-end.layout.main')
@section('content')

<section class="lost-your-password">
  <div class="container">
      <div class="your-password">
          <div class="row">
              <div class="col-lg-12">
                  <div class="nosidebar">
                      <form action="{{route('reset-password-save')}}" method="post" class="lost_reset_password">
                        @csrf
                          <div class="receive_content">
                            <p>

                              @if(Session::has('success'))
                              <div class="coupon-wrapper">
                                  <p class="text-center">{{Session::get('success')}}</p>
                              </div>
                              @endif

                            </p>
                              <p>Reset your password? </p>
                              {{-- <p>Please enter your username or email address. You will --}}
                                  {{-- receive a link to create a new password via email.</p> --}}
                          </div>
                          <div class="receive_content">
                            <label>Enter New Password</label>

                            <input type="password" name="password" placeholder="Enter new password">
                             @error('password')
                                <p class="text-danger">{{ $message }}</p>
                             @enderror

                            <input type="hidden" name="email" value="{{$email}}">

                            <label>Comfirm Password</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm password">
                             @error('password_confirmation')
                                <p class="text-danger">{{ $message }}</p>
                             @enderror  
                          </div>
                          <div class="receive_content">
                              <button type="submit" class="reset_password" value="Reset password">Reset
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
