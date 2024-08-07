@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('gift-card-list') }}">Gift Cards</a></li>
          <li class="breadcrumb-item"><a href="#">Edit Card</a></li>
        </ol>
    </nav>  
<div class="">
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit Gift Card </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form action="{{ route('gift-card-update') }}" method="POST" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate="">
                    @csrf
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="title">Card Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="name" name="name" required="required" class="form-control" value="{{ $category->product_title }}">
                            @error('name')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="image">Card    Image <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                        <div class="choose-file-wrap">
                            <input type="file" id="image" name="image" class="form-control">
                            @if(Session::has('error'))
                                <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                            <div class="choose-file-single">
                                <figure>
                                    <img src="{{ asset($category->product_image) }}" alt="img-single">                                   
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
