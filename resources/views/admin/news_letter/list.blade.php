@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="#">Promotions</a></li>
    </ol>
  </nav>
  @if(Session::has('success'))
  <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
@endif
<p class="alert alert-success text-center d-none coupon-status-update"></p>
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Promotions</h3>
        </div>

      </div>

      <div class="clearfix"></div>

      <div class="row" style="display: block;">
        <div class="col-md-12 col-sm-6  ">
          <div class="x_panel">
            <div class="x_title">
              <h2>Promotions List</h2>
              <a href="{{ route('news-letter-add') }}">
                <button class="btn btn-info panel_toolbox">Add Promotion</button>
              </a>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Content</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($newzletters as $key => $newzletter)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $newzletter->title }}</td>
                                <td><img src="{{ (isset($newzletter['image']) && !empty($newzletter['image'])) ? asset($newzletter['image']) : asset('assets/admin/images/dummy-image.jpg') }}" alt="" height="100px" width="100px"></td>
                                <td>{{ substr(strip_tags($newzletter->content), 0, 10) }}</td>
                                <td>
                                  <label class="switch">
                                    <input type="checkbox" @if($newzletter->is_active == 1) checked @endif data-id="{{$newzletter->id}}">
                                    <span class="slider round"></span>
                                  </label>
                                </td>
                                <td>
                                    <div class="x_content">
                                      <a href="{{ route('news-letter-edit', ['id' => $newzletter->id]) }}"><button type="button" class="btn btn-primary">Edit</button></a>
                                      <form action="{{ route('news-letter-delete', ['id' => $newzletter->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coupon?');" style="display:inline;">
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
                {{ $newzletters->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection


@section('custom-script')
<script>
   $(document).ready(function() {
      $('input[type=checkbox]').change(function() {
          var newzletter_id = $(this).data('id');
          var checkedValue = $(this).is(':checked') ? 1 : 0;

          var data = {
            newzletter_id: newzletter_id,
            checkedValue: checkedValue
          };

          $.ajax({
              url: "{{ route('news-letter-update-status') }}",
              type: "GET",
              data: data,
              success: function(res) {
                  if (res.checked == 1) {
                      $('.coupon-status-update').addClass('d-none').text('');
                      $('.coupon-status-update').removeClass('d-none').text('Coupon activated successfully!');
                  } else {
                      $('.coupon-status-update').addClass('d-none').text('');
                      $('.coupon-status-update').removeClass('d-none').text('Coupon deactivated successfully!');
                  }
              },
              error: function() {
                  $('.coupon-status-update').removeClass('d-none').text('An error occurred. Please try again.');
              }
          });

      });
  });
</script>
@endsection

