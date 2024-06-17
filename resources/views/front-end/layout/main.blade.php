<!doctype html>
<html lang="en">
    <head>
        @include('front-end.layout.head')
    </head>
<body>
    <div class="main-page">
            <!-- HEADER -->

            <!-- main header -->
            @include('front-end.layout.header')


        @yield('content')
    @include('front-end.layout.footer')
</body>
<a id="button"></a>
@include('front-end.layout.script')
@yield('scripts')
</body>
</html>
