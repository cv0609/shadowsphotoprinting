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
                <a href="{{ route('pages.create') }}">
                  <button class="btn btn-info panel_toolbox">Create Page</button>
                </a>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                  <table class="table">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Pages Name</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      </tbody>
                  </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
