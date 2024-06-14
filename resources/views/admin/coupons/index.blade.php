@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Products</h3>
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
              <h2>Product List</h2>
              <a href="{{ route('product-add') }}">
                <button class="btn btn-info panel_toolbox">Add Product</button>
              </a>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Products Name</th>
                            <th>Products Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coupons as $key => $product)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ ucfirst($product->product_title) }}</td>
                                <td>{{ ucfirst($product->product_category['name']) }}</td>
                                <td>
                                    <div class="x_content">
                                    <a href="{{ route('product-show', ['slug' => $product->slug]) }}"><button type="button" class="btn btn-primary">Edit</button></a>
                                    <form action="{{ route('product-delete', ['product_id' => $product->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
                {{ $coupons->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
