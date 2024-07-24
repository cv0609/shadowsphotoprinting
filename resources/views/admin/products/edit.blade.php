@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('product-list') }}">Products</a></li>
          <li class="breadcrumb-item"><a href="#">Edit Product</a></li>
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
                <h2>Edit Products</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form action="{{ route('product-update') }}" method="POST" id="product_save_form" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate="">
                    @csrf
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_category" >Product Category <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                           <select class="form-control" name="category_id" id="category_id">
                               <option value="">Select</option>
                                @foreach ($productCategories as $productCategory)
                                <option value="{{ $productCategory->id }}" <?= ($product->category_id == $productCategory->id) ? 'selected' : '' ?>>{{ $productCategory->name }}</option>
                                @endforeach
                           </select>
                        <span class="validation-error category_id_error"></span>
                    </div>

                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_title">Product Title <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="product_title" name="product_title" required="required" class="form-control" value="{{$product->product_title}}">
                            <span class="validation-error product_title_error"></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_price"> Product Price <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="product_price" name="product_price" required="required" class="form-control" step=".01" value="{{$product->product_price}}">
                            <span class="validation-error product_price_error"></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="type_of_paper_use">Type of paper use<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="type_of_paper_use" name="type_of_paper_use" required="required" class="form-control" value="{{$product->type_of_paper_use}}">
                            <span class="validation-error type_of_paper_use_error"></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_image">Product Image<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="file" id="product_image" name="product_image" required="required" class="form-control">
                            <div class="choose-file-wrap">
                            <div class="choose-file-single">
                                <figure>
                                    <img src="{{ asset($product->product_image) }}" alt="img-single">

                                    {{-- <span class="closed_btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="red"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
                                    </span> --}}
                                </figure>
                            </div>

                        </div>
                        <span class="validation-error product_image_error"></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_description"> Product Description <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea id="product_description" name="product_description" required="required" class="form-control ">{{$product->product_description}}</textarea>
                            <span class="validation-error product_description_error"></span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_description"> Manage Sale
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <label class="switch">
                                <input type="checkbox" name="manage_sale" id="manage_sale" value="1"  {{ $product->manage_sale == 1 ? 'checked' : '' }}  {{ old('manage_sale') ? 'checked' : '' }}>
                                <span class="slider round"></span>
                              </label>
                              <span class="validation-error manage_sale_error"></span>
                        </div>
                    </div>
                    <div class="{{ ($product->manage_sale == 1 ||  old('manage_sale')) ? '' : 'd-none' }}" id="sale-div">
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="type_of_paper_use">Sale Price<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="number" id="sale_price" name="sale_price[]" required="required" class="form-control">
                                <span class="validation-error sale_price_error"></span>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="type_of_paper_use">Sale Start From<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="date" id="sale_start_date" name="sale_start_date[]" required="required" class="form-control inputDate">
                                <span class="validation-error sale_start_date_error"></span>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="type_of_paper_use">Sale End To<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="date" id="sale_end_date" name="sale_end_date[]" required="required" class="form-control inputDate">
                                <span class="validation-error sale_end_date_error"></span>
                            </div>
                        </div>
                   </div>
                      <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-6 col-sm-6 offset-md-3">
                                <button type="button" id="editBtn" class="btn btn-success">Submit</button>
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
    CKEDITOR.replace('product_description');
</script> --}}
<script>
   $(function(){
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;

    $('.inputDate').attr('min', maxDate);
});
  $("#manage_sale").on('click change', function() {
       if($(this).prop('checked') == true)
         {
             $("#sale-div").removeClass('d-none');
         }
        else
         {
            $("#sale-div").addClass('d-none');
         }
  });


$(document).ready(function(){
    $('#editBtn').on('click', function() {

    $(document).find('.text-danger').text('');

    var error = false;

    $('select[name^="sale_price"]').each(function() {
        if (!$(this).val()) {
            $(this).siblings('.sale_price_error').text('Sale price field is required.');
            $(this).siblings('.sale_price_error').addClass('text-danger');
            error = true;
        }
    });

    $('select[name^="sale_start_date"]').each(function() {
        console.log('dddd');
        if (!$(this).val()) {
            $(this).siblings('.sale_start_date_error').text('Sale start date field is required.');
            $(this).siblings('.sale_start_date_error').addClass('text-danger');
            error = true;
        }
    });

    $('input[name^="sale_end_date"]').each(function() {
        if (!$(this).val()) {
            $(this).siblings('.sale_end_date_error').text('Sale end date field is required.');
            $(this).siblings('.sale_end_date_error').addClass('text-danger');
            error = true;
        }
    });


    if ($('#category_id').val() == '') {
        $('.category_id_error').text('Category field is required.');
        $('.category_id_error').addClass('text-danger');
        error = true;
    }

    if ($('#product_title').val() == '') {
        $('.product_title_error').text('Product title field is required.');
        $('.product_title_error').addClass('text-danger');
        error = true;
    }

    if ($('#product_price').val() == '') {
        $('.product_price_error').text('Product price field is required.');
        $('.product_price_error').addClass('text-danger');
        error = true;
    }

    if ($('#type_of_paper_use').val() == '') {
        $('.type_of_paper_use_error').text('Type of paper use field is required.');
        $('.type_of_paper_use_error').addClass('text-danger');
        error = true;
    }

    if ($('#product_image').val() == '') {
        $('.product_image_error').text('Product image field is required.');
        $('.product_image_error').addClass('text-danger');
        error = true;
    }

    let files = $('#product_save_form input[type=file]').get(0).files;
    let allowedExtensions = ['jpeg', 'png', 'jpg', 'gif', 'svg'];

    if(files.length == 1){
        for (let i = 0; i < files.length; i++) {
            let fileExtension = files[i].name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExtension)) {
                $('.product_image_error').text('Only JPEG, PNG, JPG, GIF, and SVG formats are allowed.');
                $('.product_image_error').addClass('text-danger');
                error = true;
                break;
            }
        }
    }

    if ($('#product_description').val() == '') {
        $('.product_description_error').text('Product description field is required.');
        $('.product_description_error').addClass('text-danger');
        error = true;
    }
    
    if (error) {
        return false;
    } else {
      $('.product_save_form').submit();
    }
     });
})

</script>

@endsection
