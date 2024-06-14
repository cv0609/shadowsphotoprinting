@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('photos-for-sale-categories-list') }}">Product Categories</a></li>
          <li class="breadcrumb-item"><a href="#">Edit Category</a></li>
        </ol>
    </nav>
<div class="">
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit Product Category</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form action="{{ route('photos-for-sale-categories-update') }}" method="POST" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate="">
                    @csrf
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="title">Category Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="name" name="name" required="required" class="form-control" value="{{ $category->name }}">
                            @error('name')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="image">Category Image <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                        <div class="choose-file-wrap">
                            <input type="file" id="image" name="image" class="form-control">
                            @if(Session::has('error'))
                                <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                            <div class="choose-file-single">
                                <figure>
                                    <img src="{{ asset($category->image) }}" alt="img-single">
                                    {{-- <span class="closed_btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="red"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
                                    </span> --}}
                                </figure>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-6 col-sm-6 offset-md-3">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                       <input type="hidden" name="category_id" value="{{ $category->id }}">
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
