@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="#">Products</a></li>
        </ol>
      </nav>

@if(Session::has('success'))
  <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
@endif    

    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Products</h3>
        </div>

        {{-- <div class="title_right">
          <div class="col-md-5 col-sm-5   form-group pull-right top_search">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search for...">
              <span class="input-group-btn">
                <button class="btn btn-default" type="button">Go!</button>
              </span>
            </div>
          </div>
        </div> --}}
      </div>

      <div class="clearfix"></div>

      <div class="row" style="display: block;">
        <div class="col-md-12 col-sm-6  ">
          <div class="x_panel">
            <div class="x_title">
              <h2>Product List</h2>
              <a href="{{ route('test-print-product-add') }}">
                <button class="btn btn-info panel_toolbox">Add Product</button>
              </a>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Products Ids</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ ucfirst($product->product_category->name) }}</td>
                                <td>{{ ucfirst($product->product_id) }}</td>
                                <td>{{ ucfirst($product->product_price) }}</td>
                                <td>{{ ucfirst($product->qty) }}</td>
                                <td>
                                    <div class="x_content">
                                    <a href="{{ route('test-print-product-show', ['cat_id' => $product->id]) }}"><button type="button" class="btn btn-primary">Edit</button></a>
                                    <form action="{{ route('test-print-product-delete', ['product_id' => $product->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
