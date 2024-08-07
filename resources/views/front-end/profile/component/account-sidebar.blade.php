<div class="col-md-3">
    <div class="kad-account">
        <div class="kad-max">
            <img src="{{asset('assets/images/profile-img.png')}}" alt="">
            <a href="#" class="kt-link-to-gravatar">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span class="kt-profile-photo-text">Update Profile Photo </span>
            </a>
        </div>
    </div>
    <div class="MyAccount-navigation">

        <ul class="ashboard prod">
            <li class=""> <a href="{{ route('dashboard') }}" class="active">Dashboard<i
                        class="fa-solid fa-gauge"></i></a> </li>
            <li class=""> <a href="{{ route('orders') }}">Orders<i class="fa-solid fa-bag-shopping"></i></a>
            </li>
            {{-- <li class=""> <a href="{{ route('downloads') }}">Downloads <i class="fa-solid fa-download"></i></a> </li> --}}
            <li class=""> <a href="{{ route('address') }}">Addresses <i class="fa-solid fa-house"></i></a></li>
            {{-- <li class=""> <a href="{{ route('payment-method') }}">Payment methods <i class="fa-solid fa-credit-card"></i></a> --}}
            </li>
            <li class=""> <a href="{{ route('account-details') }}">Account details <i class="fa-solid fa-user"></i></a> </li>
            {{-- <li class=""> <a href="{{ route('my-coupons') }}">My Coupons <i class="fa-solid fa-credit-card"></i></a> --}}
            </li>
            <li class=""><a href="{{ route('user-logout') }}">Log out <i class="fa-solid fa-arrow-right"></i></a>
            </li>
        </ul>
    </div>
</div>