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
             
            </div>
          </div>
          
          </div>
        
@endsection