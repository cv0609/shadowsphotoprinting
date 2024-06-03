@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Pages</h3>
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
              <h2>Pages List</h2>
              <a href="{{route('admin.page.add')}}">
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
                            <th>Pages Slug </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Home</td>
                            <td>Home</td>
                            <td>
                                <div class="x_content">
                                    <button type="button" class="btn btn-primary">Edit</button>
                                    <button type="button" class="btn btn-danger">Danger</button>
                                </div>
                            </td>
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
