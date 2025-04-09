@extends('admin.layout.main')
@section('page-content')

<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Ambassadors</a></li>
        </ol>
    </nav>
    @if(Session::has('success'))
    <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
    @endif
    <div class="">
        <!-- <div class="page-title order-range-list">
            <div class="title_left order-list-range">
                <h3>Orders</h3>
            </div>

            <div class="date-range-picker">
                <div class="date-range-wrapper">
                    <input type="text" name="datetimes" id="date-range-picker" />
                </div>

                <div class="serach-bar-range">
                    <input type="text" name="search" id="order-search" placeholder="Search by Order number/username"><span><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div> -->

        <div class="clearfix"></div>
        <div class="row" style="display: block;">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Ambassador List</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table" id="order_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Affiliate Code</th>
                                    <th>Shutter Bucks</th>
                                    <th>Reffered Users</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="main-tbody">
                                @foreach ($ambassadors as $key => $ambassador)

                                  <tr>
                                    <td data-title="s_no">{{ $key + 1 }}</td>

                                    <td data-title="order-number">{{ $ambassador->name }}</td>

                                    <td>{{ $ambassador->email }}</td>
                                    <td>XXXX</td>
                                    <td>XXXX</td>
                                    <td>XXXX</td>

                                    <td>{{ $ambassador->status }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $ambassadors->links('pagination::bootstrap-4') }}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom-script')
<script>



</script>
@endsection
