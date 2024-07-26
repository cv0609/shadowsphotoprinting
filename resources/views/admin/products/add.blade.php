@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('product-list') }}">Products</a></li>
          <li class="breadcrumb-item"><a href="#">Add Product</a></li>
        </ol>
      </nav>     
<div class="">
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add Products</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form action="{{ route('product-save') }}" method="POST" id="add-form" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate="">
                    @csrf
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_category" >Product Category <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                           <select class="form-control" name="category_id">
                               <option value="">Select</option>
                                @foreach ($productCategories as $productCategory)
                                <option value="{{ $productCategory->id }}">{{ $productCategory->name }}</option>
                                @endforeach
                        </select>
                        <span class="validation-error category_id_error"></span>
                    </div>

                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_title">Product Title <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="product_title" name="product_title" required="required" class="form-control ">
                            <span class="validation-error product_title_error"></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_price"> Product Price <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="product_price" name="product_price" required="required" class="form-control" step=".01">
                            <span class="validation-error product_price_error"></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="type_of_paper_use">Type of paper use<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="type_of_paper_use" name="type_of_paper_use" required="required" class="form-control ">
                            <span class="validation-error type_of_paper_use_error"></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_image">Product Image<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="file" id="product_image" name="product_image" required="required" class="form-control ">
                            <span class="validation-error product_image_error"></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_description"> Product Description <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea id="product_description" name="product_description" required="required" class="form-control "></textarea>
                            <span class="validation-error product_description_error"></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_description"> Manage Sale
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <label class="switch">
                                <input type="checkbox" name="manage_sale" id="manage_sale" value="1"  {{ old('manage_sale') ? 'checked' : '' }}>
                                <span class="slider round"></span>
                              </label>
                            @error('manage_sale')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_description"></label>
                        <div class="col-md-6 col-sm-6 ">
                            <span class="validation-error" id="date_overlap_error"></span>
                        </div>
                    </div>


                    <div class="{{ old('manage_sale') ? '' : 'd-none' }}" id="sale-div">
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="type_of_paper_use">Sale Price<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="number" id="sale_price" name="sale_price[]" required="required" class="form-control ">
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
                    <div class="main_add_more d-none">
                      <div class="add-more d-none">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <button type="button" class="read_more-btn" id="add-more-attribute">Add More</button>
                        </div>
                     </div>
                    </div>
                    <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-6 col-sm-6 offset-md-3">
                                <button type="button" id="add-btn" class="btn btn-success">Submit</button>
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

$("#manage_sale").on('change', function() {
    if($(this).prop('checked') == true){
        $("#sale-div").removeClass('d-none');
        $(".main_add_more").removeClass('d-none');
    }else{
        $("#sale-div").addClass('d-none');
        $(".main_add_more").addClass('d-none');
    }
    hidePreviousDate();
});

function removeAddMore(that) {
    $(that).closest(".added-section.size-and-type").remove();
}

function areDatesOverlapping(newStartDate, newEndDate, existingRanges) {
    for (let range of existingRanges) {
        if ((newStartDate >= range.start && newStartDate <= range.end) || 
            (newEndDate >= range.start && newEndDate <= range.end) ||
            (newStartDate <= range.start && newEndDate >= range.end)) {
            return true;
        }
    }
    return false;
}

function hidePreviousDate(){
    var dtToday = new Date();
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    var maxDate = year + '-' + month + '-' + day;
    $('input[type=date]').attr('min', maxDate);
}

$(document).ready(function(){
    hidePreviousDate();

    $(document).on('change', 'input[name^="sale_start_date"]', function() {
        var startDate = new Date($(this).val());
        if (startDate) {
            var month = startDate.getMonth() + 1;
            var day = startDate.getDate();
            var year = startDate.getFullYear();

            if (month < 10) month = '0' + month.toString();
            if (day < 10) day = '0' + day.toString();

            var minEndDate = year + '-' + month + '-' + day;
            var index = $('input[name^="sale_start_date"]').index(this);
            $('input[name^="sale_end_date"]').eq(index).attr('min', minEndDate);
        }
    });

    $('#add-btn').on('click', function() {

        $(document).find('.text-danger').text('');
        var error = false;

        if($('#manage_sale').prop('checked') == true){

            $('input[name^="sale_price"]').each(function() {
                if (!$(this).val()) {
                    $(this).siblings('.sale_price_error').text('Sale price field is required.');
                    $(this).siblings('.sale_price_error').addClass('text-danger');
                    error = true;
                }
            });
    
            $('input[name^="sale_start_date"]').each(function() {
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
    
            var existingRanges = [];
            $('input[name^="sale_start_date"]').each(function(index) {
                let startDate = new Date($(this).val());
                let endDate = new Date($('input[name^="sale_end_date"]').eq(index).val());
                existingRanges.push({ start: startDate, end: endDate });
            });
    
            // Validate overlapping dates
            $('input[name^="sale_start_date"]').each(function(index) {
                let newStartDate = new Date($(this).val());
                let newEndDate = new Date($('input[name^="sale_end_date"]').eq(index).val());
                let filteredRanges = existingRanges.filter((_, i) => i !== index);
    
                if (areDatesOverlapping(newStartDate, newEndDate, filteredRanges)) {
                    $(this).css('border', '1px solid red');
                    $('input[name^="sale_end_date"]').eq(index).css('border', '1px solid red');
                    $('#date_overlap_error').text('The selected date range overlaps with an existing date range.');
                    $('#date_overlap_error').addClass('text-danger');
                    error = true;
                } else {
                    $(this).css('border', '');
                    $('input[name^="sale_end_date"]').eq(index).css('border', '');
                }
            });
        }

        if ($('select[name=category_id]').val() == '') {
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

        let files = $('#add-form input[type=file]').get(0).files;
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
            $('#add-form').submit();
        }
    });


    $("#add-more-attribute").on('click', function () {

        $("#sale-div").append(`
            <div class="added-section size-and-type">
                <button class="close-button" onclick="removeAddMore(this)" type="button">×</button>
                <div class="form-group item">
                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="sale_price">Sale Price<span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" id="sale_price" name="sale_price[]" required type="number" />
                        <span class="validation-error sale_price_error"></span>
                    </div>
                </div>
                <div class="form-group item">
                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="sale_start_date">Sale Start From<span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control inputDate" id="sale_start_date" name="sale_start_date[]" required type="date" />
                        <span class="validation-error sale_start_date_error"></span>
                    </div>
                </div>
                <div class="form-group item">
                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="sale_end_date">Sale End To<span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control inputDate" id="sale_end_date" name="sale_end_date[]" required type="date" />
                        <span class="validation-error sale_end_date_error"></span>
                    </div>
                </div>
            </div>
        `);
        hidePreviousDate();
    });

})



</script>
@endsection
