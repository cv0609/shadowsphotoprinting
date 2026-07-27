@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Monthly Editions</li>
        </ol>
    </nav>

    @if (Session::has('success'))
        <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
    @endif

    <div class="row" style="display:block;">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Shadows Monthly Editions</h2>
                    <a href="{{ route('monthly-editions.create') }}">
                        <button class="btn btn-info panel_toolbox">Add Edition</button>
                    </a>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Month / Year</th>
                                <th>Status</th>
                                <th>Latest</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($editions as $key => $edition)
                                <tr>
                                    <th>{{ $editions->firstItem() + $key }}</th>
                                    <td>{{ $edition->title }}</td>
                                    <td>{{ $edition->edition_label }}</td>
                                    <td>{{ ucfirst($edition->status) }}</td>
                                    <td>
                                        @if ($edition->is_homepage)
                                            <span class="label label-success">Homepage</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('monthly-editions.show', $edition->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('monthly-editions.destroy', $edition->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this edition?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No editions yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">{{ $editions->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
