@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="#">Size Types</a></li>
        </ol>
      </nav>
      @if(Session::has('success'))
        <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
      @endif
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Size Type</h3>
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
              <h2>Size Type List</h2>
              <a href="{{ route('size-type-add') }}">
                <button class="btn btn-info panel_toolbox">Add Size Type</button>
              </a>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Size Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach ($SizeTypes as $key => $SizeType)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ ucfirst($SizeType->name) }}</td>
                            <td>
                              <div class="x_content">
                                <a href="{{ route('shipping-show', ['id' => $SizeType->id]) }}"><button type="button" class="btn btn-primary">Edit</button></a>
                                <form action="{{ route('size-type-delete', ['id' => $SizeType->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coupon?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                               </div>

                            </td>
                        </tr>
                      @endforeach

                    </tbody>

                </table>
                {{ $SizeTypes->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
