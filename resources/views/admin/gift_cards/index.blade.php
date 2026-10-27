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
                <table class="table table-striped jambo_table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Card Name</th>
                            <th>Web Display Image</th>
                            <th>Email Recipient Image</th>
                            <th style="width: 160px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key => $category)
                          <tr>
                            <td scope="row" style="vertical-align: middle;">{{ $key + 1 }}</td>
                            <td style="vertical-align: middle;"><strong>{{ ucfirst($category->product_title) }}</strong></td>
                            <td style="vertical-align: middle;">
                              @if(!empty($category['product_image']))
                                <a href="{{ asset($category['product_image']) }}" target="_blank">
                                  <img src="{{ asset($category['product_image']) }}" alt="Web Image" style="max-height: 80px; max-width: 120px; object-fit: contain; border-radius: 6px; border: 1px solid #e0e0e0; background: #fafafa; padding: 2px;">
                                </a>
                              @else
                                <span class="badge badge-light border">No Image</span>
                              @endif
                            </td>
                            <td style="vertical-align: middle;">
                              @if($category->use_different_email_image == '1' && !empty($category->email_product_image))
                                <a href="{{ asset($category->email_product_image) }}" target="_blank">
                                  <img src="{{ asset($category->email_product_image) }}" alt="Email Image" style="max-height: 80px; max-width: 120px; object-fit: contain; border-radius: 6px; border: 1px solid #c3e6cb; background: #f4faf6; padding: 2px;">
                                </a>
                                <br><span class="badge badge-success mt-1" style="font-size: 10px;">Custom Email Image</span>
                              @else
                                <span class="badge badge-secondary" style="font-size: 11px; padding: 4px 8px;">Same as Web Image</span>
                              @endif
                            </td>
                            <td style="vertical-align: middle;">
                              <a href="{{ route('gift-card-show', ['category_id' => $category->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                              <form action="{{ route('gift-card-delete', ['category_id' => $category->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
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
