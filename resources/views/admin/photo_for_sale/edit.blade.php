@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('photos-for-sale-product-list') }}">Products</a></li>
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
                <form action="{{ route('photos-for-sale-product-update') }}" method="POST" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate="">
                    @csrf
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_category" >Product Category <span class="required">*</span>
                        </label>.
                        <div class="col-md-6 col-sm-6">
                           <select class="form-control" name="category_id">
                               <option value="">Select</option>
                                @foreach ($productCategories as $productCategory)
                                <option value="{{ $productCategory->id }}" <?= ($product->category_id == $productCategory->id) ?'selected' : '' ?>>{{ $productCategory->name }}</option>
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
                            <input type="text" id="product_title" name="product_title" required="required" class="form-control" value="{{$product->product_title}}">
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
                                class="form-control" step=".01" value="{{$product->min_price}}">
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
                                class="form-control" step=".01" value="{{$product->max_price}}">
                            @error('max_price')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_image">Product Image<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="file" id="product_image" name="product_images[]" required="required" class="form-control" multiple>
                            <div class="choose-file-wrap">
                                <div class="choose-file-multiple">
                                  @foreach (explode(',',$product->product_images) as $images)
                                    <figure>
                                        <img src="{{ asset($images) }}" alt="img-single">

                                        {{-- <span class="closed_btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="red"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
                                        </span> --}}
                                    </figure>
                                    @endforeach

                            </div>

                        </div>
                            @error('product_image')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="product_description"> Product Description <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea id="product_description" name="product_description" required="required" class="form-control ">{{$product->product_description}}</textarea>
                            @error('product_description')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                      <input type="hidden" name="product_id" value="{{ $product->id }}">
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

<script>
    CKEDITOR.replace('description');
</script>

@endsection
