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
@include('layouts.script')
@yield('scripts')
</body>
</html>
