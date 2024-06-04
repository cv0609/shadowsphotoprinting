<h1>chirag</h1>
<!doctype html>
<html lang="en">
    <head>
        @include('layouts.head')
    </head>

<body>
    <div class="main-page">
            <!-- HEADER -->
        <header class="header">
            <!-- main header -->
            @include('layouts.header')
        </header>
    
        @yield('content')
    @include('layouts.footer')
</body>
<a id="button"></a>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/custom.js"></script>
<script src="assets/js/slick.js"></script>
<script src="assets/js/aos.js"></script>

@yield('scripts')
</body>
</html>
