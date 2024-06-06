@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
<div class="">
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add Blog</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form action="{{ route('blogs.store') }}" method="POST" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
                    @csrf

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Blog Title <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="blog-title" name="blog_title" required="required" class="form-control ">
                            @if(Session::has('error'))
                              <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                            @error('page_title')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Blog Description <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea id="blog-description" name="blog_description" required="required" class="form-control "></textarea>
                            @if(Session::has('error'))
                              <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                            @error('blog_description')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Blog Image <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="file" id="blog-image" name="blog_image" required="required" class="form-control">
                            @if(Session::has('error'))
                              <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                            @error('blog_description')
                             <p class="text-danger">{{ $message }}</p>
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
