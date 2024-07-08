@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Orders</a></li>
      </ol>
    </nav>
    @if(Session::has('success'))
      <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
    @endif
      <div class="">
        <div class="page-title">
          <div class="title_left">
            <h3>Orders</h3>
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
                <h2>Orders List</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                  <table class="table">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Order Number</th>
                              <th>Total</th>
                              <th>Status</th>
                              <th>Created At</th>
                          </tr>
                      </thead>
                      <tbody>
                      @foreach ($orders as $key => $order)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td> <a href="{{ route('order-detail',['order_number'=>$order->order_number]) }}">{{ $order->order_number }}</a> </td>
                            <td>{{ $order->total }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ date('d-m-Y h:i:d',strtotime($order->created_at)) }}</td>
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
