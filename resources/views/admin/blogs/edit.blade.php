@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
<div class="">
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit Blog</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form action="{{ route('blogs.update',['blog'=>$detail->id]) }}" method="POST" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate="">
                    @csrf
                    @method('PUT')
                    @csrf

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="title">Blog Title <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="title" name="title" required="required" class="form-control" value="{{ $detail->title }}">
                            @if(Session::has('error'))
                              <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                            @error('title')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="image">Blog Image <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">

                            <div class="choose-file-wrap">
                                <input type="file" id="image" name="image" class="form-control">
                                @if(Session::has('error'))
                                <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                                <div class="choose-file-single">
                                    <figure>
                                        <img src="{{ asset($detail->image) }}" alt="img-single">
                                        <span class="closed_btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="red"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
                                        </span>
                                    </figure>
                                </div>
                            </div>
            
                        </div>
                    </div>


                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="description">Blog Description <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea id="description" name="description" required="required" class="form-control ">{{ $detail->description }}</textarea>
                            @if(Session::has('error'))
                              <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                            @error('description')
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

<script>
    CKEDITOR.replace('description');
</script>

@endsection
