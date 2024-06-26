@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="#">Coupons</a></li>
    </ol>
  </nav>
  @if(Session::has('success'))
  <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
@endif
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Coupons</h3>
        </div>

      </div>

      <div class="clearfix"></div>

      <div class="row" style="display: block;">
        <div class="col-md-12 col-sm-6  ">
          <div class="x_panel">
            <div class="x_title">
              <h2>Coupons List</h2>
              <a href="{{ route('coupon-add') }}">
                <button class="btn btn-info panel_toolbox">Add Coupons</button>
              </a>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Coupon Code</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coupons as $key => $coupon)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $coupon->code }}</td>
                                <td>{{ date('d-m-Y',strtotime($coupon->start_date)) }}</td>
                                <td>{{ date('d-m-Y',strtotime($coupon->end_date)) }}</td>
                                <td>
                                    <div class="x_content">
                                      <a href="{{ route('coupon-show', ['id' => $coupon->id]) }}"><button type="button" class="btn btn-primary">Edit</button></a>
                                      <form action="{{ route('coupon-delete', ['id' => $coupon->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coupon?');" style="display:inline;">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" class="btn btn-danger">Delete</button>
                                      </form>

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
