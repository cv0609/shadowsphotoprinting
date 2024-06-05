<!doctype html>
<html lang="en">
    <head>
        @include('layout.head')
    </head>
<body>
    <div class="main-page">
            <!-- HEADER -->
        <header class="header">
            <!-- main header -->
            @include('layout.header')
        </header>
    
        @yield('content')
    @include('layout.footer')
</body>
<a id="button"></a>
@include('layout.script')
@yield('scripts')
</body>
</html>
