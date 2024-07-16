@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="#">Gift Cards</a></li>
    </ol>
</nav>
  @if(Session::has('success'))
    <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
  @endif
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Gift Card</h3>
        </div>
      </div>

      <div class="clearfix"></div>

      <div class="row" style="display: block;">
        <div class="col-md-12 col-sm-6  ">
          <div class="x_panel">
            <div class="x_title">
              <h2>Gift Card List</h2>
              <a href="{{ route('gift-card-add') }}">
                <button class="btn btn-info panel_toolbox">Add Card</button>
              </a>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key => $category)
                          <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ ucfirst($category->product_title) }}</td>
                            <td><img src="{{ (isset($category['image']) && !empty($category['image'])) ? asset($category['image']) : asset('assets/admin/images/dummy-image.jpg') }}" alt="Image" height="100px" width="100px"></td>
                            <td>
                              <div class="x_content">
                              <a href="{{ route('gift-card-show', ['category_id' => $category->id]) }}"><button type="button" class="btn btn-primary">Edit</button></a>
                              <form action="{{ route('gift-card-delete', ['category_id' => $category->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" style="display:inline;">
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
@endsection
