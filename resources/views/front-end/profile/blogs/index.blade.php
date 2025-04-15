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

                              <h2>Blogs List</h2>
                              <a href="{{ route('ambassador.blog.create') }}">
                                <button class="btn btn-info panel_toolbox">Add Blog</button>
                              </a>
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
                                <td>{{ ucfirst($blog->title) }}</td>
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
