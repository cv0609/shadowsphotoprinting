@extends('front-end.layout.main')
@section('content')

<section class="lost-your-password">
  <div class="container">
      <div class="your-password">
          <div class="row">
              <div class="col-lg-12">
                  <div class="nosidebar">
                
                    <div class="receive_content">

                          <p>

                            @if(Session::has('success'))
                            <div class="coupon-wrapper">
                                <p class="text-center">{{Session::get('success')}}</p>
                            </div>
                            @endif

                          </p>
                     @if($user->is_email_verified == '0')
                        <p>Please verify your email address.</p>
                        <p>Once verified, you can log in to your account.</p>
                     @endif   
                    </div>
                    
                     
                      <div class="receive_content">
                        
                        @if(isset($user) && !empty($user))
                            @if($user->is_email_verified == '0')
                                <form action="{{ route('email-verification') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="email" value="{{$user->email}}">
                                    <button type="submit" class="reset_password">
                                        Verify Email
                                    </button>
                                </form>
                            @endif    
                            {{-- @elseif($user->is_email_verified == '1')
                               <p class="already-verified text-center">Your email is already verified.</p>
                            @endif --}}
                        @else
                            <p class="already-verified text-center">Some thing went wrong</p>
                        @endif
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</section>

@endsection
@section('scripts')
  
@endsection
