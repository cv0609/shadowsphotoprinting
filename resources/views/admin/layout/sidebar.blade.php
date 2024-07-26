<body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title admin-logo" style="border: 0;">
              <a href="{{ route('admin.dashboard') }}" class="site_title"><img src="{{asset('assets/images/logo.png')}}" alt=""></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            {{-- <div class="profile clearfix">
              <div class="profile_pic">
                <img src="{{ asset('assets/admin/images/img.jpg') }}" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2>Admin</h2>
              </div>
            </div> --}}
            <!-- /menu profile quick info -->
            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a href="{{route('pages.index')}}"><i class="fa fa-file-text-o"></i> Pages</a>
                  </li>
                  <li><a href="#"><i class="fa fa-product-hunt"></i> Products</a>
                    <ul class="nav child_menu">
                      <li><a href="{{ route('product-categories-list') }}">Product Categories</a></li>
                      <li><a href="{{ route('product-list') }}">Products</a></li>
                    </ul>
                  </li>
                  <li><a href="#"><i class="fa fa-picture-o"></i> Photos For Sale</a>
                    <ul class="nav child_menu">
                      <li><a href="{{ route('photos-for-sale-categories-list') }}">Categories</a></li>
                      <li><a href="{{ route('photos-for-sale-product-list') }}">Products</a></li>
                    </ul>
                  </li>


                  {{-- <li><a href="#"><i class="fa fa-picture-o"></i>Hand Craft</a>
                    <ul class="nav child_menu">
                      <li><a href="{{ route('hand-craft-categories-list') }}">Categories</a></li>
                      <li><a href="{{route('hand-craft-list')}}">Products</a></li>
                    </ul>
                  </li> --}}

                  <li><a href="{{route('gift-card-list')}}"><i class="fa fa-duotone fa-gift"></i>Gift Cards</a></li>
                  <li><a href="{{route('coupons-list')}}"><i class="fa fa-solid fa-tag"></i>Coupons</a>
                  <li><a href="{{route('blogs.index')}}"><i class="fa fa-tasks"></i>Blogs</a></li>
                  <li><a href="{{route('shipping-list')}}"><i class="fa fa-truck"></i>Shipping</a></li>
                  <li><a href="{{route('orders-list')}}"><i class="fa fa-first-order"></i>Orders</a></li>
                  <li><a href="#"><i class="fa fa-list-alt"></i> Variations</a>
                    <ul class="nav child_menu">
                      <li><a href="{{ route('sizes-list') }}">Sizes</a></li>
                      <li><a href="{{ route('size-types-list') }}">Sizes Type</a></li>
                    </ul>
                  </li>
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->


          </div>
        </div>
