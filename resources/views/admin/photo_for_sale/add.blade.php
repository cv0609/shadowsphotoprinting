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
                        <form action="{{ route('photos-for-sale-product-save') }}" method="POST" id="demo-form2"
                            data-parsley-validate="" class="form-horizontal form-label-left"
                            enctype="multipart/form-data" novalidate="">
                            @csrf
                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align"
                                    for="product_category">Product Category <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6">
                                    <select class="form-control" name="category_id" id="category_id">
                                        <option value="">Select</option>
                                        @foreach ($productCategories as $productCategory)
                                        <option value="{{ $productCategory->id }}">{{ $productCategory->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="validation-error category_id_error"></span>
                                    @error('category_id')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
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
                                    @error('product_title')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
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
                                    @error('min_price')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
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
                                    @error('max_price')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
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
                                    @error('product_images')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
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
                                    @error('product_description')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
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
                                                <select name="size_arr[]" id="size_arr">
                                                    <option value="">Select size</option>
                                                    @foreach($size as $val)
                                                      <option value="{{$val->id}}">{{$val->name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="validation-error size_arr_error"></span>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="type" >
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="type">Select type</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="type_arr[]" id="type">
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
                                                <input type="number" id="price" name="price_arr[]">
                                                <span class="validation-error price_arr_error"></span>
                                            </div>
                                        </div>
                                    </div>
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

<script>
    CKEDITOR.replace('description');
</script>

@endsection
@section('custom-script')
<script>
    var sizes = @json($size);
    var sizeTypes = @json($size_type);

    $("#add-more-attribute").on('click', function() {
        let sizeOptions = '<option value="">Select size</option>';
        sizes.forEach(size => {
            sizeOptions += `<option value="${size.id}">${size.name}</option>`;
        });

        let typeOptions = '<option value="">Select type</option>';
        sizeTypes.forEach(type => {
            typeOptions += `<option value="${type.id}">${type.name}</option>`;
        });

        $(".size-and-type-wrap").append('<div class=size-and-type><button class=close-button onclick=removeAddMore(this) type=button>×</button><div class=size><div class=row><div class=col-md-3><label for=size>Select size</label></div><div class=col-md-6><select class="form-control size-select"name=size_arr[]>'+sizeOptions+'</select> <span class=size_arr_error></span></div></div></div><div class=type><div class=row><div class=col-md-3><label for=type>Select type</label></div><div class=col-md-6><select class="form-control type-select"name=type_arr[]>'+typeOptions+'</select> <span class=type_arr_error></span></div></div></div><div class=select-price><div class=row><div class=col-md-3><label for=price>Select price</label></div><div class=col-md-6><input class="form-control price-input"name=price_arr[] type=number> <span class=price_arr_error></span></div></div></div></div>');
    });

    $('#submitBtn').on('click',function(){

            var error = false;

            $('select[name="size_arr[]"]').each(function(i,v) {
                if (!$(this).val()) {
                    $('.size_arr_error').text('Size field is required.');
                    error = true;
                }
            });

            $('select[name="type_arr[]"]').each(function(i,v) {
                if (!$(this).val()) {
                    $('.type_arr_error').text('Type field is required.');
                    error = true;
                }
            });

            $('input[name="price_arr[]"]').each(function(i,v) {
                if (!$(this).val()) {
                    $('.price_arr_error').text('Price field is required.');
                    error = true;
                }
            });

            if ($('#category_id').val() == '') {
                $('.category_id_error').text('Category field is required.');
                error = true;
            }

            if ($('#product_title').val() == '') {
                $('.product_title_error').text('Product title field is required.');
                error = true;
            }

            if ($('#min_price').val() == '') {
                $('.min_price_error').text('Min price field is required.');
                error = true;
            }

            if ($('#max_price').val() == '') {
                $('.max_price_error').text('Max price field is required.');
                error = true;
            }

            if ($('#product_image').val() == '') {
                $('.product_image_error').text('Product image field is required.');
                error = true;
            }

            if ($('#product_description').val() == '') {
                $('.product_description_error').text('Product descriptiion field is required.');
                error = true;
            }

           if(error){
              return false;
           }else{
             $('#demo-form2').submit();
           }

    })



 function removeAddMore(that)
  {
     $(that).parent(".size-and-type").remove();
  }
</script>
@endsection
