@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Newsletter</a></li>
            <li class="breadcrumb-item"><a href="#">Edit Newsletter</a></li>
        </ol>
    </nav>
    <div class="">
        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Edit Newsletter</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        <form action="{{ route('news-letter-save') }}" method="POST" id="demo-form2" data-parsley-validate=""
                            class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate="">
                            @csrf

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="title">Newsletter Title <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="title" name="title" required="required"
                                        class="form-control " value="{{ $detail->title }}">
                                    @if(Session::has('error'))
                                    <p class="text-danger">{{ Session::get('error') }}</p>
                                    @endif
                                    @error('title')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="image">Newsletter Image <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="file" id="image" name="image" required="required" class="form-control">
                                    @if(Session::has('error'))
                                    <p class="text-danger">{{ Session::get('error') }}</p>
                                    @endif
                                    @error('image')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                    <div class="choose-file-single">
                                        <figure>
                                            <img src="{{ asset($detail->product_image) }}" alt="img-single">                                   
                                        </figure>
                                    </div>
                                </div>
                            </div>


                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="description">Newsletter
                                content <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <textarea id="description" name="content"
                                        class="form-control ">{{ $detail->content }}</textarea>
                                    @if(Session::has('error'))
                                    <p class="text-danger">{{ Session::get('error') }}</p>
                                    @endif
                                    @error('content')
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
@section('custom-script')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Ensure CKEditor script is fully loaded before replacing the textarea
        CKEDITOR.replace('description', {
            toolbar: [{
                    name: 'document',
                    items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates']
                },
                {
                    name: 'clipboard',
                    items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo',
                        'Redo'
                    ]
                },
                {
                    name: 'editing',
                    items: ['Find', 'Replace', '-', 'SelectAll']
                },
                {
                    name: 'forms',
                    items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select',
                        'Button', 'ImageButton', 'HiddenField'
                    ]
                },
                '/',
                {
                    name: 'basicstyles',
                    items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript',
                        '-', 'CopyFormatting', 'RemoveFormat'
                    ]
                },
                {
                    name: 'paragraph',
                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-',
                        'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter',
                        'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language'
                    ]
                },
                {
                    name: 'links',
                    items: ['Link', 'Unlink', 'Anchor']
                },
                {
                    name: 'insert',
                    items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar',
                        'PageBreak', 'Iframe'
                    ]
                },
                '/',
                {
                    name: 'styles',
                    items: ['Styles', 'Format', 'Font', 'FontSize']
                },
                {
                    name: 'colors',
                    items: ['TextColor', 'BGColor']
                }, // Add TextColor and BGColor buttons
                {
                    name: 'tools',
                    items: ['Maximize', 'ShowBlocks']
                },
                {
                    name: 'about',
                    items: ['About']
                }
            ]
        });
    });

</script>
@endsection
