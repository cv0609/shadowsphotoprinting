@extends('front-end.layout.main')
@php
    $PageDataService = app(App\Services\PageDataService::class);
    $ProductCategories = $PageDataService->getProductCategories();
@endphp
@section('styles')
<style>

.form-inner button[type="submit"] {
    box-shadow: inset 0 0 0 0 transparent;
    background-color: #16a085;
    border: 0;
    border-radius: 0;
    display: block;
    cursor: pointer;
    color: #fff;
    font-weight: 700;
    padding: 8px 16px;
    line-height: 24px;
    text-decoration: none;
    text-shadow: 0-1px 0 rgba(0, 0, 0, .1);
    transition: box-shadow .2s ease-in-out;
    width: 100%;
    max-width: 184px;
    margin: 40px auto 0;
}

.form-inner button[type="submit"]:hover {
    box-shadow: inset 0 -4px 0 0 rgba(0, 0, 0, .2);
}   
</style>
@endsection 
@section('content')
   
<section class="account-page">
    <div class="container">
        <div class="account-wrapper">
            <div class="row">
                @include('front-end.profile.component.account-sidebar')
                <div class="col-md-9">
                    @if(Session::has('success'))
                      <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
                    @endif
                    <div class="pangas-can">
                        <div class="endpointtitle">
                          
                              <h2>Add Blog</h2>
                              <div class="clearfix"></div>

                            <div class="notices-wrapper">

                    <form action="{{ route('ambassador.blog.save') }}" method="POST" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate="">
                    @csrf

                    <div class="item form-inner">
                        <label class="col-form-label col-md-12 label-align" for="title">Blog Title <span class="required">*</span>
                        </label>
                        <div class="col-md-12 ">
                            <input type="text" id="title" name="title" required="required" class="form-control ">
                            @if(Session::has('error'))
                              <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                            @error('title')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-inner">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="image">Blog Image <span class="required">*</span>
                        </label>
                        <div class="col-md-12">
                            <input type="file" id="image" name="image" required="required" class="form-control">
                            @if(Session::has('error'))
                              <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                            @error('image')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                    <div class="item form-inner">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="description">Blog Description <span class="required">*</span>
                        </label>
                        <div class="col-md-12">
                            <textarea id="description" name="description" required="required" class="form-control "></textarea>
                            @if(Session::has('error'))
                              <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                            @error('description')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                                      
                    <div class="ln_solid"></div>
                        <div class="item form-inner">
                            <div class="col-md-12">
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
    </div>
</section>

@endsection
@section('scripts')
<script src="https://cdn.ckeditor.com/4.21.0/full/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ensure CKEditor script is fully loaded before replacing the textarea
        CKEDITOR.replace('description', {
            toolbar: [
                { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
                { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll'] },
                { name: 'forms', items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'] },
                '/',
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language'] },
                { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
                { name: 'insert', items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
                '/',
                { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] }, // Add TextColor and BGColor buttons
                { name: 'tools', items: ['Maximize', 'ShowBlocks'] },
                { name: 'about', items: ['About'] }
            ]
        });
    });
</script>
@endsection
