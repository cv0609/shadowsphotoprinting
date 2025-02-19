@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Sale</a></li>
        </ol>
    </nav>
    @if(Session::has('success'))
    <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
    @endif
    <p class="alert alert-success text-center d-none coupon-status-update"></p>
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Manage sale PopUp</h3>
            </div>

        </div>

        <div class="clearfix"></div>

        <div class="row" style="display: block;">
            <div class="col-md-12 col-sm-6  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>PopUp List</h2>
                        <a href="{{ route('add-sale-popup') }}">
                            <button class="btn btn-info panel_toolbox">Add Sale PopUp</button>
                        </a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale_popups as $key => $pop)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{ $pop->name }}</td>
                                    <td><img src="{{ asset($pop->image) }}" alt="" style="height: 50px;width: 50px;"></td>
                                    <td>{{ date('d-m-Y',strtotime($pop->start_date)) }}</td>
                                    <td>{{ date('d-m-Y',strtotime($pop->end_date)) }}</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" @if($pop->status == "1") checked @endif data-id="{{$pop->id}}">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="x_content">
                                            <a href="{{ route('edit-popup-show', ['id' => $pop->id]) }}"><button type="button" class="btn btn-primary">Edit</button></a>
                                            <form action="{{ route('sale-popup-delete', ['id' => $pop->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coupon?');" style="display:inline;">
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
                        {{-- {{ $coupons->links('pagination::bootstrap-4') }} --}}
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
            var id = $(this).data('id');
            var checkedValue = $(this).is(':checked') ? "1" : "0";

            var data = {
                id: id
                , checkedValue: checkedValue
            };

            $.ajax({
                url: "{{ route('sale-popup-update-status') }}"
                , type: "GET"
                , data: data
                , success: function(res) {
                    if (res.checked == 1) {
                        $('.coupon-status-update').addClass('d-none').text('');
                        $('.coupon-status-update').removeClass('d-none').text('Sale popup activated successfully!');
                    } else {
                        $('.coupon-status-update').addClass('d-none').text('');
                        $('.coupon-status-update').removeClass('d-none').text('Sale popup deactivated successfully!');
                    }
                }
                , error: function() {
                    $('.coupon-status-update').removeClass('d-none').text('An error occurred. Please try again.');
                }
            });

        });
    });

</script>
@endsection
