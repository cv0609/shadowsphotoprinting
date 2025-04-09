<!doctype html>
<html lang="en">
    <head>
        @include('front-end.layout.head')
        @yield('styles')
    </head>
<body>
    <div class="main-page">
        @include('front-end.layout.header')
        @yield('content')
        @include('front-end.layout.footer')
    </div> 
</body>
<a id="button"></a>
@include('front-end.layout.script')
@yield('scripts')
</body>
</html>
