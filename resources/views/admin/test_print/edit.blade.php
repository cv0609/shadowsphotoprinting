@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('test-print-product-list') }}">Products</a></li>
          <li class="breadcrumb-item"><a href="#">Edit Product</a></li>
        </ol>
      </nav>     
<div class="">
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit Products</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form action="{{ route('test-print-product-update') }}" method="POST" id="add-form" class="form-horizontal form-label-left" novalidate="">
                    @csrf
                    <input type="hidden" name="test_print_id" value="{{$testPrint->id}}">
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_category">Product Category <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <select class="form-control" name="category_id" id="category_id" required>
                                <option value="">Select</option>
                                @foreach ($productCategories as $productCategory)
                                <option value="{{ $productCategory->id }}" @if($cat_id == $productCategory->id) selected @endif>{{ $productCategory->name }}</option>
                                @endforeach
                            </select>
                            <span class="validation-error category_id_error text-danger"></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="products">Products</label>
                        <div class="col-md-6 col-sm-6">
                            <select class="form-control" id="productlist" name="products[]" multiple="multiple" required>
                                @foreach($products as $value)
                        
                                    <option value="{{ $value->id }}" @if(in_array($value->id, $products_ids)) selected @endif>
                                        {{ $value->product_title }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="validation-error products_error text-danger"></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_price">Product Price <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" id="product_price" name="product_price" required="required" class="form-control" step=".01" value="{{$testPrint->product_price}}">
                            <span class="validation-error product_price_error text-danger"></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_qty">Product Quantity <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" id="product_qty" name="product_qty" required="required" class="form-control" step=".01" value="{{$testPrint->qty}}">
                            <span class="validation-error product_qty_error text-danger"></span>
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <button type="submit" id="add-btn" class="btn btn-success">Submit</button>
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
<script>
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
</script>
@endsection
