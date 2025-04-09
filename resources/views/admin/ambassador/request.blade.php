@extends('admin.layout.main')
@section('page-content')

<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Ambassadors</a></li>
            <li class="breadcrumb-item"><a href="#">Requests</a></li>
        </ol>
    </nav>
    @if(Session::has('success'))
    <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
    @endif
    <div class="">
        <div class="clearfix"></div>
        <div class="row" style="display: block;">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Ambassador Request List</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table" id="order_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="main-tbody">
                                @foreach ($ambassadors as $key => $ambassador)

                                  <tr>
                                    <td data-title="s_no">{{ $key + 1 }}</td>

                                    <td data-title="order-number">{{ $ambassador->name }}</td>

                                    <td>{{ $ambassador->email }}</td>
                                    <td>{{ $ambassador->location }}</td>
                                    <td>{{ \Carbon\Carbon::parse($ambassador->created_at)->format('d M Y') }}</td>
                                    <td>        
                                        @if (!$ambassador->is_approved)
                                            <form method="POST" action="{{ route('admin.ambassador.approve', $ambassador->id) }}" style="display: inline-flex;">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Approve</button>
                                            </form>
                                        @else
                                            <span class="badge bg-success">Approved</span>
                                        @endif
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#viewModal{{ $ambassador->id }}">View</button>

                                    </td>
                                </tr>

                                <!-- View Modal -->
                                    <div class="modal fade" id="viewModal{{ $ambassador->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $ambassador->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="viewModalLabel{{ $ambassador->id }}">Ambassador Application Details</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                @php
                                                    $specialty =  is_string($ambassador->specialty) ? explode(',', $ambassador->specialty) : [];
                                                    $items = [];
                                                    foreach ($specialty ?? [] as $item){
                                                        $items[] = $specialtyMap[$item]; 
                                                    }
                                                @endphp
                                                    <p><strong>Name:</strong> {{ $ambassador->name }}</p>
                                                    <p><strong>Email:</strong> {{ $ambassador->email }}</p>
                                                    <p><strong>Location:</strong> {{ $ambassador->location }}</p>
                                                    <p><strong>Business:</strong> {{ $ambassador->business_name }}</p>
                                                    <p><strong>Website:</strong> <a href="{{ $ambassador->website }}" target="_blank">{{ $ambassador->website }}</a></p>
                                                    <p><strong>Social Handle:</strong> {{ $ambassador->social_media_handle }}</p>
                                                    <p><strong>Photography Specialty:</strong>{{implode(', ',$items)}}</p>
                                                    @if($ambassador->other_specialty)
                                                        <p><strong>Other Specialty:</strong> {{ $ambassador->other_specialty }}</p>
                                                    @endif
                                                    <p><strong>Additional Comments:</strong><br>{{ $ambassador->comments }}</p>
                                                    <p><strong>Signature:</strong><br><img src="{{ $ambassador->signature }}" style="width:200px; height:auto;"/></p>
                                                    <p><strong>Date:</strong><br>{{ $ambassador->submit_date }}</p>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
