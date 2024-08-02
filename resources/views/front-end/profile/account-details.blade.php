@extends('front-end.layout.main')
@php
    $PageDataService = app(App\Services\PageDataService::class);
    $ProductCategories = $PageDataService->getProductCategories();
@endphp
@section('content')
   
<section class="account-page">
    <div class="container">
        <div class="account-wrapper">
            <div class="row">
                @include('front-end.profile.component.account-sidebar')
                <div class="col-md-9">
                    @if(Session::has('success'))
                      <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
                    @endif
                    <div class="pangas-can">
                        <div class="endpointtitle">
                            <h2>Account details </h2>
                            <div class="notices-wrapper">
                                <form action="{{ route('account-details-save') }}" method="POST" class="account-details input-color">
                                    @csrf
                                    <div class="fields__field-wrapper">
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="billing_first">
                                                        <label>First name * </label>
                                                        <input type="text" name="first_name" value="{{$user->first_name}}" placeholder="Enter first name">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="billing_first">
                                                        <label>Last name * </label>
                                                        <input type="text" name="last_name" value ="{{$user->last_name}}" placeholder="Enter last name">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing_first">
                                                        <label>Display name * </label>
                                                        <input type="text" name = "username" placeholder="Enter display name" value="{{ $user->username ?? '' }}">
                                                        <span><em>This will be how your name will be displayed in the account section and in reviews</em></span>
                                                        @error('username')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing_first">
                                                        <label>Email address * </label>
                                                        <input type="text"
                                                            placeholder="Enter email address" name="email" value="{{$user->email ?? ''}}">
                                                        @error('email')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset>
                                            <legend>Password change</legend>
                                            <div class="fields-inner">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="billing_first">
                                                            <label>Current password (leave blank to leave unchanged)</label>
                                                            <input type="password" name="current_password" placeholder="Enter current password">
                                                            @error('current_password')
                                                              <p class="text-danger">{{ $message }}</p>
                                                            @enderror    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fields-inner">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="billing_first">
                                                            <label>New password (leave blank to leave unchanged)
                                                            </label>
                                                            <input type="password" name="password" placeholder="Enter new password">
                                                            @error('password')
                                                              <p class="text-danger">{{ $message }}</p>
                                                            @enderror    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fields-inner">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="billing_first">
                                                            <label>Confirm new password</label>
                                                            <input type="password" name="password_confirmation" placeholder="Confirm password">
                                                            @error('password_confirmation')
                                                                <p class="text-danger">{{ $message }}</p>
                                                            @enderror  
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="quanti">
                                                        <button type="submit">Save changes</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('scripts')
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
