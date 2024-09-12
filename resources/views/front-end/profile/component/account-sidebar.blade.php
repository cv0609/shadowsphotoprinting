<div class="col-md-3">
    <div class="kad-account">
        <div class="kad-max">
            
            {{-- <img src="{{asset('assets/images/profile-img.png')}}" alt=""> --}}

            <img src="{{(!empty(Auth::user()->image)) ? asset(Auth::user()->image) : asset('assets/images/profile-img.png') }}" alt="user_img">

            <form id="profile-pic-form" action="{{ route('profile.update-pic') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="profile-pic-input" name="profile_picture" style="display: none;" accept="image/*" onchange="document.getElementById('profile-pic-form').submit()">
                
                <!-- Clicking the icon will trigger the file input click event -->
                <a href="javascript:void(0);" class="kt-link-to-gravatar" onclick="document.getElementById('profile-pic-input').click();">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span class="kt-profile-photo-text">Update Profile Photo</span>
                </a>
            </form>
            
            


        </div>
    </div>
    <div class="MyAccount-navigation">

        <ul class="ashboard prod">
            <li class=""> <a href="{{ route('dashboard') }}" class="{{ Request::url() == route('dashboard') ? 'active' : '' }}">Dashboard<i
                        class="fa-solid fa-gauge"></i></a> </li>
            <li class=""> <a href="{{ route('orders') }}" class="{{ Request::url() == route('orders') ? 'active' : '' }}">Orders<i class="fa-solid fa-bag-shopping"></i></a>
            </li>
            {{-- <li class=""> <a href="{{ route('downloads') }}">Downloads <i class="fa-solid fa-download"></i></a> </li> --}}
            <li class=""> <a href="{{ route('address') }}" class="{{ Request::url() == route('address') ? 'active' : '' }}">Addresses <i class="fa-solid fa-house"></i></a></li>
            {{-- <li class=""> <a href="{{ route('payment-method') }}">Payment methods <i class="fa-solid fa-credit-card"></i></a> --}}
            </li>
            <li class=""> <a href="{{ route('account-details') }}" class="{{ Request::url() == route('account-details') ? 'active' : '' }}">Account details <i class="fa-solid fa-user"></i></a> </li>
            {{-- <li class=""> <a href="{{ route('my-coupons') }}">My Coupons <i class="fa-solid fa-credit-card"></i></a> --}}
            </li>
            <li class=""><a href="{{ route('user-logout') }}">Log out <i class="fa-solid fa-arrow-right"></i></a>
            </li>
        </ul>
    </div>
</div>