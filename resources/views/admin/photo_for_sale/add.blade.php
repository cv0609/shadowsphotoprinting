@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('photos-for-sale-product-list') }}">Products</a></li>
          <li class="breadcrumb-item"><a href="#">Add Product</a></li>
        </ol>
    </nav>
    @if(Session::has('success'))
      <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
    @endif
    <div class="">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Add Products</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        <form method="POST" id="demo-form2"
                            data-parsley-validate="" class="form-horizontal form-label-left"
                            enctype="multipart/form-data" novalidate="">
                            @csrf

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="meta_title">Meta
                                    Title <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="meta_title" name="meta_title" required="required"
                                        class="form-control ">
                                    <span class="validation-error meta_title_error"></span>

                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="meta_description">Meta
                                    Description <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="meta_description" name="meta_description" required="required"
                                        class="form-control ">
                                    <span class="validation-error meta_description_error"></span>

                                </div>
                            </div>


                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align"
                                    for="product_category">Product Category <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6">
                                    <select class="form-control" name="category_id" id="category_id">
                                        <option value="">Select</option>
                                        @foreach ($productCategories as $productCategory)
                                        <option value="{{ $productCategory->id }}">{{ $productCategory->product_title }}</option>
                                        @endforeach
                                    </select>
                                    <span class="validation-error category_id_error"></span>

                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_title">Product
                                    Title <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="product_title" name="product_title" required="required"
                                        class="form-control ">
                                    <span class="validation-error product_title_error"></span>

                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="min_price"> Mininum
                                    Price <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="number" id="min_price" name="min_price" required="required"
                                        class="form-control" step=".01">
                                    <span class="validation-error min_price_error"></span>

                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="max_price"> Maximum
                                    Price <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="number" id="max_price" name="max_price" required="required"
                                        class="form-control" step=".01">
                                    <span class="validation-error max_price_error"></span>

                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_image">Product
                                    Image<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="file" id="product_image" name="product_images[]" required="required"
                                        class="form-control" multiple>
                                    <span class="validation-error product_image_error"></span>

                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_description">
                                    Product Description <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <textarea id="product_description" name="product_description"
                                        class="form-control" id="product_description"></textarea>
                                    <span class="validation-error product_description_error"></span>

                                </div>
                            </div>
                            <div class="size-and-type-wrap">
                                <div class="size-and-type">
                                    <div class="size">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="size">Select size</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="size_arr[size][1][children][]" id="size_arr" multiple="multiple" class="multi_size">
                                                    <option value="">Select size</option>
                                                    @foreach($size as $val)
                                                      <option value="{{$val->id}}">{{$val->name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="validation-error size_arr_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="type">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="type">Select type</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="type_arr[type][1][children][]" id="type_arr" class="multi_type" multiple="multiple">
                                                    <option value="">Select type</option>
                                                    @foreach($size_type as $val)
                                                      <option value="{{$val->id}}">{{$val->name}}</option>
                                                    @endforeach
                                                  </select>
                                                  <span class="validation-error type_arr_error"></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="selcet-price">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="price">select price</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="number" id="price" name="price_arr[price][1][children][]">
                                                <span class="validation-error price_arr_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                      <input type="hidden" name="type_size_count[click_count][1][children][]" value="1">
                                </div>
                            </div>
                            <div class="row read-more">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <button type="button" class="read_more-btn" id="add-more-attribute">Add More</button>
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 offset-md-3">
                                    <button type="button" class="btn btn-success" id="submitBtn">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <script>
    CKEDITOR.replace('description');
</script> --}}

@endsection
@section('custom-script')
<script>
    var sizes = @json($size);
    var sizeTypes = @json($size_type);
    var clickCount = 2;
    $(document).ready(function() {

        $('.multi_type, .multi_size').select2({
            placeholder: 'Select type',
            allowClear: true
        });

        var count = 1;

        $("#add-more-attribute").on('click', function() {
            let sizeOptions = '<option value="">Select size</option>';
            sizes.forEach(size => {
                sizeOptions += `<option value="${size.id}">${size.name}</option>`;
            });

            let typeOptions = '<option value="">Select type</option>';
            sizeTypes.forEach(type => {
                typeOptions += `<option value="${type.id}">${type.name}</option>`;
            });

            count++;

            let html = '<div class="added-section size-and-type">' +
                '<button class="close-button" onclick="removeAddMore(this)" type="button">×</button>' +
                '<div class="size">' +
                    '<div class="row">' +
                        '<div class="col-md-3"><label for="size">Select size</label></div>' +
                        '<div class="col-md-6 last-row">' +
                            '<select class="form-control append-size multi_size size-select" multiple name="size_arr[size]['+count+'][children][]">' + sizeOptions + '</select>' +
                            ' <span class="size_arr_error"></span>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="type">' +
                    '<div class="row">' +
                        '<div class="col-md-3"><label for="type">Select type</label></div>' +
                        '<div class="col-md-6">' +
                            '<select class="form-control append-type multi_type type-select" multiple name="type_arr[type]['+count+'][children][]">' + typeOptions + '</select>' +
                            ' <span class="type_arr_error"></span>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="select-price">' +
                    '<div class="row">' +
                        '<div class="col-md-3"><label for="price">Select price</label></div>' +
                        '<div class="col-md-6">' +
                            '<input class="form-control price-input" name="price_arr[price]['+count+'][children][]" type="number">' +
                            ' <span class="price_arr_error"></span>' +
                        '</div>' +
                    '</div>' +
                '</div><input type="hidden" name="type_size_count[click_count]['+count+'][children][]" value="'+clickCount+'">' +
            '</div>';

            $(".size-and-type-wrap").append(html);

            $('.multi_type, .multi_size').select2({
                placeholder: 'Select type',
                allowClear: true
            });

            clickCount++;
        });

        $('#submitBtn').on('click', function() {

            $(document).find('.text-danger').text('');

            var error = false;

            $('select[name^="size_arr"]').each(function() {
                if (!$(this).val()) {
                    $(this).siblings('.size_arr_error').text('Size field is required.');
                    $(this).siblings('.size_arr_error').addClass('text-danger');
                    error = true;
                }
            });

            $('select[name^="type_arr"]').each(function() {
                if (!$(this).val()) {
                    $(this).siblings('.type_arr_error').text('Type field is required.');
                    $(this).siblings('.type_arr_error').addClass('text-danger');
                    error = true;
                }
            });

            $('input[name^="price_arr"]').each(function() {
                if (!$(this).val()) {
                    $(this).siblings('.price_arr_error').text('Price field is required.');
                    $(this).siblings('.price_arr_error').addClass('text-danger');
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

            if ($('#meta_title').val() == '') {
                $('.meta_title_error').text('Meta title field is required.');
                $('.meta_title_error').addClass('text-danger');
                error = true;
            }

            if ($('#meta_description').val() == '') {
                $('.meta_description_error').text('Meta description field is required.');
                $('.meta_description_error').addClass('text-danger');
                error = true;
            }

            if ($('#min_price').val() == '') {
                $('.min_price_error').text('Min price field is required.');
                $('.min_price_error').addClass('text-danger');
                error = true;
            }

            if ($('#max_price').val() == '') {
                $('.max_price_error').text('Max price field is required.');
                $('.max_price_error').addClass('text-danger');
                error = true;
            }

            if ($('#product_image').val() == '') {
                $('.product_image_error').text('Product image field is required.');
                $('.product_image_error').addClass('text-danger');
                error = true;
            }

            let files = $('#demo-form2 input[type=file]').get(0).files;
            let allowedExtensions = ['jpeg', 'png', 'jpg', 'gif', 'svg'];

            if (files.length > 2 || files.length < 2) {
                $('.product_image_error').text('Product must have 2 images.');
                $('.product_image_error').addClass('text-danger');
                error = true;
            }

            if(files.length == 2){
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

                var formData = new FormData($('#demo-form2')[0]);

                $.ajax({
                    url: "{{ route('photos-for-sale-product-save') }}",
                    type: "POST",
                    data: formData,
                    contentType: false, // Required for FormData
                    processData: false, // Required for FormData
                    success: function(res) {
                        if (res.error == true) {
                            $('.last-row:last').prepend('<span class="new-span text-danger">You can not add duplicate entry for size and type</span>');
                        } else {
                            location.reload();
                        }
                    }
                });
            }
        });
    });

    function hideSelectOption(){
        var selectedValues = $("select[name='size_arr[]']").val();
            if (selectedValues !== null) {
                selectedValues.forEach(function(value) {
                    $(".append-size option[value='" + value + "']").remove();
                });
            }

            var sizeValues = $("select[name='type_arr[]']").val();
            if (sizeValues !== null) {
                sizeValues.forEach(function(value) {
                    $(".append-size option[value='" + value + "']").remove();
                });
            }
    }

    function removeAddMore(that) {
        $(that).closest(".added-section.size-and-type").remove();
        clickCount = clickCount - 1;
    }


</script>
@endsection

