@extends('admin.layout.main')
@section('page-content')
          <!-- page content -->
          <div class="right_col" role="main">
            <!-- top tiles -->
            <div class="row" style="display: inline-block;" >
            <div class="tile_count">

              <div class="col-md-4 col-sm-4 tile_stats_count">
                <span class="count_top"> Total Blogs</span>
                <div class="count">{{ $blogs }}</div>
                {{-- <span class="count_bottom"><i class="green">4% </i> From last Week</span> --}}
              </div>
              <div class="col-md-4 col-sm-4 tile_stats_count">
                <span class="count_top"> Total Products</span>
                <div class="count">{{ $products }}</div>
                {{-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span> --}}
              </div>
              <div class="col-md-4 col-sm-4 tile_stats_count">
                <span class="count_top"> Total Gift Cards</span>
                <div class="count ">{{ $cards }}</div>
                {{-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span> --}}
              </div>
              <div class="col-md-4 col-sm-4 tile_stats_count">
                <span class="count_top"> Total Orders</span>
                <div class="count ">{{ $orders }}</div>
                {{-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span> --}}
              </div>
            </div>


          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12 ">
              <div class="dashboard_graph">
                <h1>Monthly Revenue</h1>
                <canvas id="revenueChart"></canvas>
              </div>
            </div>

          </div>
          </div>


@endsection
@section('custom-script')
<script>
 document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('revenueChart').getContext('2d');
            var revenueData = @json($revenueData);

            var days = revenueData.map(data => data.day);
            var revenue = revenueData.map(data => data.total);

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Revenue',
                        data: revenue,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
</script>
@endsection
