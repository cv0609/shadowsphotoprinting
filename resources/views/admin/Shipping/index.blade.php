@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="#">Shipping</a></li>
    </ol>
  </nav>
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Shipping</h3>
        </div>

      </div>

      <div class="clearfix"></div>

      <div class="row" style="display: block;">
        <div class="col-md-12 col-sm-6  ">
          <div class="x_panel">
            <div class="x_title">
              <h2>Shipping List</h2>
              <a href="{{ route('shipping-add') }}">
                {{-- <button class="btn btn-info panel_toolbox">Add Shipping</button> --}}
              </a>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Country</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($shippings as $key => $shipping)
                      <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $shipping->country }}</td>
                        <td>{{ $shipping->amount }}</td>
                        <td>                          
                          <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                          </label>
                       </td>
                        <td>
                          <div class="x_content">
                            <a href="{{ route('shipping-show', ['id' => $shipping->id]) }}"><button type="button" class="btn btn-primary">Edit</button></a>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>

                </table>
                {{-- {{ $coupons->links('pagination::bootstrap-4') }} --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
