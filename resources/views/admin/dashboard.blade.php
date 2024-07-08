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
          <div class="col-md-4 col-sm-6  widget_tally_box">
            <div class="x_panel">
              <div class="x_title">
                <h2>User Uptake</h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Settings 1</a>
                        <a class="dropdown-item" href="#">Settings 2</a>
                      </div>
                  </li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a>
                  </li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <div id="graph_bar" style="width:100%; height:200px;"></div>
              </div>
            </div>
          </div>
          </div>
@endsection
@section('custom-script')
<script>
    /* CHART - MORRIS  */

function init_morris_charts() {

if (typeof (Morris) === 'undefined') { return; }
console.log('init_morris_charts');

if ($('#graph_bar').length) {

    Morris.Bar({
        element: 'graph_bar',
        data: [
            { device: 'iPhone 4', geekbench: 380 },
            { device: 'iPhone 4S', geekbench: 655 },
            { device: 'iPhone 3GS', geekbench: 275 },
            { device: 'iPhone 5', geekbench: 1571 },
            { device: 'iPhone 5S', geekbench: 655 },
            { device: 'iPhone 6', geekbench: 2154 },
            { device: 'iPhone 6 Plus', geekbench: 1144 },
            { device: 'iPhone 6S', geekbench: 2371 },
            { device: 'iPhone 6S Plus', geekbench: 1471 },
            { device: 'Other', geekbench: 1371 }
        ],
        xkey: 'device',
        ykeys: ['geekbench'],
        labels: ['Geekbench'],
        barRatio: 0.4,
        barColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
        xLabelAngle: 35,
        hideHover: 'auto',
        resize: true
    });

}

if ($('#graph_bar_group').length) {

    Morris.Bar({
        element: 'graph_bar_group',
        data: [
            { "period": "2016-10-01", "licensed": 807, "sorned": 660 },
            { "period": "2016-09-30", "licensed": 1251, "sorned": 729 },
            { "period": "2016-09-29", "licensed": 1769, "sorned": 1018 },
            { "period": "2016-09-20", "licensed": 2246, "sorned": 1461 },
            { "period": "2016-09-19", "licensed": 2657, "sorned": 1967 },
            { "period": "2016-09-18", "licensed": 3148, "sorned": 2627 },
            { "period": "2016-09-17", "licensed": 3471, "sorned": 3740 },
            { "period": "2016-09-16", "licensed": 2871, "sorned": 2216 },
            { "period": "2016-09-15", "licensed": 2401, "sorned": 1656 },
            { "period": "2016-09-10", "licensed": 2115, "sorned": 1022 }
        ],
        xkey: 'period',
        barColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
        ykeys: ['licensed', 'sorned'],
        labels: ['Licensed', 'SORN'],
        hideHover: 'auto',
        xLabelAngle: 60,
        resize: true
    });

}

if ($('#graphx').length) {

    Morris.Bar({
        element: 'graphx',
        data: [
            { x: '2015 Q1', y: 2, z: 3, a: 4 },
            { x: '2015 Q2', y: 3, z: 5, a: 6 },
            { x: '2015 Q3', y: 4, z: 3, a: 2 },
            { x: '2015 Q4', y: 2, z: 4, a: 5 }
        ],
        xkey: 'x',
        ykeys: ['y', 'z', 'a'],
        barColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
        hideHover: 'auto',
        labels: ['Y', 'Z', 'A'],
        resize: true
    }).on('click', function (i, row) {
        console.log(i, row);
    });

}

if ($('#graph_area').length) {

    Morris.Area({
        element: 'graph_area',
        data: [
            { period: '2014 Q1', iphone: 2666, ipad: null, itouch: 2647 },
            { period: '2014 Q2', iphone: 2778, ipad: 2294, itouch: 2441 },
            { period: '2014 Q3', iphone: 4912, ipad: 1969, itouch: 2501 },
            { period: '2014 Q4', iphone: 3767, ipad: 3597, itouch: 5689 },
            { period: '2015 Q1', iphone: 6810, ipad: 1914, itouch: 2293 },
            { period: '2015 Q2', iphone: 5670, ipad: 4293, itouch: 1881 },
            { period: '2015 Q3', iphone: 4820, ipad: 3795, itouch: 1588 },
            { period: '2015 Q4', iphone: 15073, ipad: 5967, itouch: 5175 },
            { period: '2016 Q1', iphone: 10687, ipad: 4460, itouch: 2028 },
            { period: '2016 Q2', iphone: 8432, ipad: 5713, itouch: 1791 }
        ],
        xkey: 'period',
        ykeys: ['iphone', 'ipad', 'itouch'],
        lineColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
        labels: ['iPhone', 'iPad', 'iPod Touch'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true
    });

}

if ($('#graph_donut').length) {

    Morris.Donut({
        element: 'graph_donut',
        data: [
            { label: 'Jam', value: 25 },
            { label: 'Frosted', value: 40 },
            { label: 'Custard', value: 25 },
            { label: 'Sugar', value: 10 }
        ],
        colors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
        formatter: function (y) {
            return y + "%";
        },
        resize: true
    });

}

if ($('#graph_line').length) {

    Morris.Line({
        element: 'graph_line',
        xkey: 'year',
        ykeys: ['value'],
        labels: ['Value'],
        hideHover: 'auto',
        lineColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
        data: [
            { year: '2012', value: 20 },
            { year: '2013', value: 10 },
            { year: '2014', value: 5 },
            { year: '2015', value: 5 },
            { year: '2016', value: 20 }
        ],
        resize: true
    });

    $MENU_TOGGLE.on('click', function () {
        $(window).resize();
    });

}

};
$(document).ready(function () {
    init_morris_charts();
});

</script>
@endsection
