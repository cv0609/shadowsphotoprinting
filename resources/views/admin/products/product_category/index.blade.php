@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Product Categories</h3>
        </div>

        <div class="title_right">
          <div class="col-md-5 col-sm-5   form-group pull-right top_search">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search for...">
              <span class="input-group-btn">
                <button class="btn btn-default" type="button">Go!</button>
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="clearfix"></div>

      <div class="row" style="display: block;">
        <div class="col-md-12 col-sm-6  ">
          <div class="x_panel">
            <div class="x_title">
              <h2>Product Categories List</h2>
              <a href="{{ route('product-categories-add') }}">
                <button class="btn btn-info panel_toolbox">Create Category</button>
              </a>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Category Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key => $category)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ ucfirst($category->name) }}</td>
                                <td>
                                    <div class="x_content">
                                    <a href="{{ route('product-categories-show', ['category_id' => $category->id]) }}"><button type="button" class="btn btn-primary">Edit</button></a>
                                    <form action="{{ route('product-categories-delete', ['category_id' => $category->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page?');" style="display:inline;">
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
