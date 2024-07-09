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
                                    <select class="form-control" name="category_id">
                                        <option value="">Select</option>
                                        @foreach ($productCategories as $productCategory)
                                        <option value="{{ $productCategory->id }}">{{ $productCategory->name }}</option>
                                        @endforeach
                                    </select>
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
                                        class="form-control "></textarea>
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
                                                <span class="validation-error" id="size_arr_error"></span>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="type" >
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="type">Select type</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="type[]" id="type">
                                                    <option value="">Select type</option>
                                                    @foreach($size_type as $val)
                                                      <option value="{{$val->id}}">{{$val->name}}</option>
                                                    @endforeach
                                                  </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="selcet-price">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="price">select price</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" id="price" name="price">
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

        $(".size-and-type-wrap").append(`
            <div class="size-and-type">
                <button type="button" class="close-button" onclick="removeAddMore(this)">×</button>
                <div class="size">
                    <div class="row">
                        <div class="col-md-3"><label for="size">Select size</label></div>
                        <div class="col-md-6">
                            <select name="size_arr[]" class="form-control size-select">
                                ${sizeOptions}
                            </select>
                            <span id="size_arr_error"></span>
                        </div>
                    </div>
                </div>
                <div class="type">
                    <div class="row">
                        <div class="col-md-3"><label for="type">Select type</label></div>
                        <div class="col-md-6">
                            <select name="type[]" class="form-control type-select">
                                ${typeOptions}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="select-price">
                    <div class="row">
                        <div class="col-md-3"><label for="price">Select price</label></div>
                        <div class="col-md-6">
                            <input type="text" name="price[]" class="form-control price-input">
                        </div>
                    </div>
                </div>
            </div>
        `);
    });

    $('#submitBtn').on('click',function(){
            let sizeEmpty = false;
            $('select[name="size[]"]').each(function() {
                console.log('jjjj');
                if ($(this).val() === '') {
                    sizeEmpty = true;
                    return false; 
                }
            });

            if (sizeEmpty) {
                $('#size_arr_error').text('Size field(s) are required.');
            } else {
                $('#size_arr_error').text(''); // Clear error message if all fields are filled
                // Optionally, you can submit the form here as well
                // $('#demo-form2').submit();
            }
    })



 function removeAddMore(that)
  {
     $(that).parent(".size-and-type").remove();
  }
</script>
@endsection
