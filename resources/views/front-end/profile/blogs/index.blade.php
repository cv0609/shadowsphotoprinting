@extends('front-end.layout.main')
@php
    $PageDataService = app(App\Services\PageDataService::class);
    $ProductCategories = $PageDataService->getProductCategories();
@endphp
@section('styles')
<style>
.eddpoint-header-title {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
    align-items: center;
}

.eddpoint-header-title a {
    box-shadow: inset 0 0 0 0 transparent;
    background-color: #16a085;
    border: 0;
    border-radius: 0;
    display: block;
    cursor: pointer;
    color: #fff;
    font-weight: 700;
    padding: 8px 26px;
    line-height: 24px;
    text-decoration: none;
    text-shadow: 0-1px 0 rgba(0, 0, 0, .1);
    transition: box-shadow .2s ease-in-out;
}
.eddpoint-header-title a:hover{
    box-shadow: inset 0 -4px 0 0 rgba(0, 0, 0, .2);
}
</style>
@endsection 
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
                            <div class="eddpoint-header-title">
                                <h2>Blogs List</h2>
                                <a href="{{ route('ambassador.blog.create') }}">
                                  Add Blog
                                </a>
                            </div>
                              <div class="clearfix"></div>

                            <div class="notices-wrapper">
                            <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Blogs Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                            @php
                            $status = ['0'=>'Draft','1'=>'publish','2'=>'In Review','3'=>'Modify'];
                            @endphp
                        @foreach ($blogs as $key => $blog)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td style="width: 40%;">{{ ucfirst($blog->title) }}</td>
                                <td>{{ $status[$blog->status] }}</td>
                                <td>
                                    <div class="x_content">
                                    <a href="{{ route('ambassador.blog.view', ['id' => $blog->id]) }}"><button type="button" class="btn btn-primary">Edit</button></a>
                                    <form action="{{ route('ambassador.blog.destroy', ['blog' => $blog->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this blog?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
