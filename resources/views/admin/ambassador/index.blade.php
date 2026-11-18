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
                                </tr>
                            </thead>
                            <tbody id="main-tbody">
                                @foreach ($ambassadors as $key => $ambassador)

                                  <tr>
                                    <td data-title="s_no">{{ $key + 1 }}</td>

                                    <td data-title="order-number">{{ $ambassador->name }}</td>

                                    <td>{{ $ambassador->email }}</td>
                                    <td>
                                        @if($ambassador->user?->affiliate?->referral_code)
                                            @php
                                                $refUrl = url('/shop?ref=' . $ambassador->user->affiliate->referral_code);
                                            @endphp
                                            <div style="font-weight: 600; color: #2A3F54; font-size: 13px;">
                                                {{ $ambassador->user->affiliate->referral_code }}
                                            </div>
                                            <div style="margin-top: 3px; font-size: 12px;">
                                                <a href="javascript:void(0)" class="copy-ref-link" data-url="{{ $refUrl }}" title="Click to copy full referral link" style="color: #337ab7; text-decoration: none; cursor: pointer;">
                                                    <i class="fa fa-copy"></i> <span class="copy-text">Copy Link</span>
                                                </a>
                                                <span style="color: #ccc; margin: 0 5px;">|</span>
                                                <a href="{{ $refUrl }}" target="_blank" title="Test referral link in new tab" style="color: #73879C; text-decoration: none;">
                                                    <i class="fa fa-external-link"></i> Visit
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{($ambassador->user?->affiliate?->commission)*100}}</td>
                                    <td>{{$ambassador->user?->affiliate?->referral_count}}</td>
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
$(document).ready(function() {
    $(document).on('click', '.copy-ref-link', function(e) {
        e.preventDefault();
        var link = $(this);
        var textSpan = link.find('.copy-text');
        var icon = link.find('i');
        var urlToCopy = link.data('url');

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(urlToCopy).then(function() {
                showSuccess();
            }).catch(function() {
                fallbackCopy(urlToCopy);
            });
        } else {
            fallbackCopy(urlToCopy);
        }

        function fallbackCopy(text) {
            var tempInput = document.createElement("input");
            tempInput.style.position = "absolute";
            tempInput.style.left = "-9999px";
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            try {
                document.execCommand("copy");
                showSuccess();
            } catch (err) {
                console.error("Copy failed", err);
            }
            document.body.removeChild(tempInput);
        }

        function showSuccess() {
            var originalText = textSpan.text();
            icon.removeClass('fa-copy').addClass('fa-check');
            textSpan.text('Copied!');
            link.css('color', '#26b99a');

            setTimeout(function() {
                icon.removeClass('fa-check').addClass('fa-copy');
                textSpan.text(originalText);
                link.css('color', '#337ab7');
            }, 2000);
        }
    });
});
</script>
@endsection
