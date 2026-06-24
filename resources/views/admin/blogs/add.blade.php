@extends('admin.layout.main')

@section('page-content')
<div class="right_col" role="main">
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blogs</a></li>
        <li class="breadcrumb-item active">Add Blog</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add Blog</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <form action="{{ route('blogs.store') }}"
                      method="POST"
                      class="form-horizontal form-label-left"
                      enctype="multipart/form-data">
                    @csrf

                    {{-- Title --}}
                    <div class="item form-group">
                        <label class="col-md-3 label-align">Blog Title *</label>
                        <div class="col-md-6">
                            <input type="text" name="title"
                                   class="form-control"
                                   value="{{ old('title') }}" required>
                            @error('title') <p class="text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Image --}}
                    <div class="item form-group">
                        <label class="col-md-3 label-align">Blog Image *</label>
                        <div class="col-md-6">
                            <input type="file" name="image" class="form-control" required>
                            @error('image') <p class="text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="item form-group">
                        <label class="col-md-3 label-align">Blog Description *</label>
                        <div class="col-md-6">
                            <textarea id="description" name="description"
                                      class="form-control" rows="10" required>
                                {{ old('description') }}
                            </textarea>
                            @error('description') <p class="text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <div class="form-group">
                        <div class="col-md-6 offset-md-3">
                            <button class="btn btn-success">Submit</button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
</div>
@endsection

@section('custom-script')
<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    ClassicEditor
        .create(document.querySelector('#description'), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'bulletedList', 'numberedList', '|',
                    'link', 'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ]
            },

            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                    { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' },
                ]
            },

            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            }
        })
        .catch(error => {
            console.error(error);
        });
});
</script>

@endsection
