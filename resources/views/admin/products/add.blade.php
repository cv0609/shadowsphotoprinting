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
                <form action="{{ route('product-save') }}" method="POST" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate="">
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
                        @error('category_id')
                        <p class="text-danger">{{ $message }}</p>
                       @enderror
                    </div>

                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_title">Product Title <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="product_title" name="product_title" required="required" class="form-control ">
                            @error('product_title')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_price"> Product Price <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="product_price" name="product_price" required="required" class="form-control" step=".01">
                            @error('product_price')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="type_of_paper_use">Type of paper use<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="type_of_paper_use" name="type_of_paper_use" required="required" class="form-control ">
                            @error('type_of_paper_use')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_image">Product Image<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="file" id="product_image" name="product_image" required="required" class="form-control ">
                            @error('product_image')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_description"> Product Description <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea id="product_description" name="product_description" required="required" class="form-control "></textarea>
                            @error('product_description')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
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
                    <div class="{{ old('manage_sale') ? '' : 'd-none' }}" id="sale-div">
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="type_of_paper_use">Sale Price<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="number" id="sale_price" name="sale_price" required="required" class="form-control ">
                                @error('sale_price')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="type_of_paper_use">Sale Start From<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="date" id="sale_start_date" name="sale_start_date" required="required" class="form-control inputDate">
                                @error('sale_start_date')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="type_of_paper_use">Sale End To<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="date" id="sale_end_date" name="sale_end_date" required="required" class="form-control inputDate">
                                @error('sale_end_date')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row read-more d-none">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <button type="button" class="read_more-btn" id="add-more-attribute">Add More</button>
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

  $("#add-more-attribute").on('click',function(){
      console.log("Ok");
      $("#sale-div").append('<div class="form-group item"><label class="col-form-label col-md-3 col-sm-3 label-align"for=type_of_paper_use>Sale Price<span class=required>*</span></label><div class="col-md-6 col-sm-6"><input class=form-control id=sale_price name=sale_price required type=number> @error('sale_price')<p class=text-danger>{{ $message }}</p>@enderror</div></div><div class="form-group item"><label class="col-form-label col-md-3 col-sm-3 label-align"for=type_of_paper_use>Sale Start From<span class=required>*</span></label><div class="col-md-6 col-sm-6"><input class="form-control inputDate"id=sale_start_date name=sale_start_date required type=date> @error('sale_start_date')<p class=text-danger>{{ $message }}</p>@enderror</div></div><div class="form-group item"><label class="col-form-label col-md-3 col-sm-3 label-align"for=type_of_paper_use>Sale End To<span class=required>*</span></label><div class="col-md-6 col-sm-6"><input class="form-control inputDate"id=sale_end_date name=sale_end_date required type=date> @error('sale_end_date')<p class=text-danger>{{ $message }}</p>@enderror</div>');
  })
  </script>
@endsection
