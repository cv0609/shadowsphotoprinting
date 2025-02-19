@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('test-print-product-list') }}">Sale</a></li>
            <li class="breadcrumb-item"><a href="#">Edit Sale PopUp</a></li>
        </ol>
    </nav>
    @if(Session::has('success'))
    <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
    @endif
    <div class="">
        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Edit Sale PopUp</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        <form action="{{ route('edit-sale-popup-save') }}" method="POST" class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$sale_popup_details->id}}">
                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="maximum_spend">Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="name" name="name" value="{{ $sale_popup_details->name }}" required="required" class="form-control" step=".01">
                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_image">Sale Image<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="file" id="image" name="image" required="required" class="form-control ">

                                    <div class="choose-file-wrap">
                                        <div class="choose-file-single">
                                            <figure>
                                                <img src="{{ asset($sale_popup_details->image) }}" alt="img-single">
                                            </figure>
                                        </div>
                                    </div>

                                    @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="start_date">Start date <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="date" id="start_date" name="start_date" value="{{ $sale_popup_details->start_date }}" required="required" class="form-control" step=".01">
                                    @error('start_date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="end_date">End date <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="date" id="end_date" name="end_date" value="{{ $sale_popup_details->end_date }}" required="required" class="form-control" step=".01">
                                    @error('end_date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 offset-md-3">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom-script')
{{-- <script>
$(document).ready(function(){

    $('#productlist').select2({
        placeholder: 'Select products',
        allowClear: true
    });

    $('#category_id').on('change', function() {
        var categoryId = $(this).val();
        if (categoryId) {
            $.ajax({
                url: '{{ route("get-products") }}',
type: 'GET',
data: { category_id: categoryId },
success: function(response) {
if (!response.error) {
var productList = $('#productlist');
productList.empty();
$.each(response.data, function(key, product) {
productList.append('<option value="' + product.id + '">' + product.product_title + '</option>');
});
} else {
alert("No products found for this category.");
}
},
error: function() {
alert("An error occurred while fetching the products.");
}
});
} else {
$('#productlist').empty();
}
});

$('#add-btn').on('click', function(e) {
// Clear previous errors
$('.validation-error').text('');

// Validation logic
var isValid = true;

// Check Product Category
if ($('#category_id').val() === "") {
$('.category_id_error').text('Product category is required.');
isValid = false;
}

var productList = $('#productlist');
if (productList.length === 0 || productList.val() === null || productList.val().length === 0) {
$('.products_error').text('At least one product must be selected.');
isValid = false;
}

// Check Product Price
if ($('#product_price').val() === "") {
$('.product_price_error').text('Product price is required.');
isValid = false;
}

// Check Product Quantity
if ($('#product_qty').val() === "") {
$('.product_qty_error').text('Product quantity is required.');
isValid = false;
}

// If valid, submit the form
if (isValid) {
$('#add-form').submit();
}
});
});
</script> --}}
@endsection
