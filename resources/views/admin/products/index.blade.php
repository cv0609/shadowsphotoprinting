@extends('admin.layout.main')

@section('page-content')
<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="#">Products</a></li>
        </ol>
    </nav>

@if(Session::has('success'))
    <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
@endif    

    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Products</h3>
            </div>

            <div class="clearfix"></div>
        </div>

        <div class="row" style="display: block;">
            <div class="col-md-12 col-sm-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Product List</h2>
                        <a href="{{ route('product-add') }}">
                            <button class="btn btn-info panel_toolbox">Add Product</button>
                        </a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table" id="sortable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Products Name</th>
                                    <th>Products Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr data-id="{{ $product->id }}">
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>{{ ucfirst($product->product_title) }}</td>
                                        <td>{{ ucfirst($product->product_category['name']) }}</td>
                                        <td>
                                            <a href="{{ route('product-show', ['slug' => $product->slug]) }}">
                                                <button type="button" class="btn btn-primary">Edit</button>
                                            </a>
                                            <form action="{{ route('product-delete', ['product_id' => $product->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- {{ $products->links('pagination::bootstrap-4') }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-script')
<script>
    $(document).ready(function () {
        $("#sortable tbody").sortable({
            update: function (event, ui) {
                var order = [];
                $('tbody tr').each(function (index, element) {
                    order.push({
                        id: $(this).data('id'),
                        position: index + 1
                    });
                });

                $.ajax({
                    url: '{{ route("product-order-update") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order: order
                    },
                    success: function (response) {
                        if(response.success){
                            alert('Order updated successfully');
                        } else {
                            alert('Something went wrong');
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
