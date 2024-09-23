@extends('admin.layout.main')
@section('page-content')

<div class="right_col" role="main" style="min-height: 3755px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('pages.index') }}">Pages</a></li>
          <li class="breadcrumb-item"><a href="#">Edit Page</a></li>
        </ol>
      </nav>
    <div class="">
        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Edit {{ ucfirst($page_fields->name) }} Page</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        <form action="{{ route('pages.update',['page'=>$detail->id]) }}" method="POST" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @foreach($page_fields->sections as $section)
                            <div class="form-felids-wrap">
                                <h4 class="sec-tittle">{{ ucfirst(str_replace('_',' ',$section->title)) }}</h4>
                                @foreach ($section->fields as $field)
                                  {{-- text --}}
                                  @if($field->type == 'text')
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">
                                                {{ ucfirst(str_replace('_',' ',$field->title)) }} <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" id="{{ ucfirst(str_replace('_',' ',$field->title)) }}" required="required" class="form-control" name="{{ $field->name }}" value="{{ (isset($content[$field->name])) ? $content[$field->name] : "" }}">
                                            </div>
                                        </div>
                                  @endif
                                    {{-- textarea --}}
                                  @if($field->type == 'textarea')
                                  <div class="item form-group">
									<label class="col-form-label col-md-3 col-sm-3 label-align ">{{ ucfirst(str_replace('_',' ',$field->title)) }}</label>
									<div class="col-md-6 col-sm-6">
										<textarea class="resizable_textarea form-control" name="{{ $field->name }}">{{ (isset($content[$field->name])) ? $content[$field->name] : "" }}</textarea>
									</div>
								</div>
                                  @endif
                                 {{-- image --}}
                                @if($field->type == 'image')
                                    <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">
                                        {{ ucfirst(str_replace('_',' ',$field->title)) }} <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="file" id="{{ ucfirst(str_replace('_',' ',$field->title)) }}" required="required" class="form-control" name="{{ $field->name }}">
                                        @if(isset($content[$field->name]))
                                        <div class="choose-file-wrap">
                                            <div class="choose-file-single">
                                                <figure>
                                                    <img src="{{ (isset($content[$field->name]) && !empty($content[$field->name]) && !is_array($content[$field->name])) ?  asset($content[$field->name]) : "" }}" alt="img-single">

                                                    {{-- <span class="closed_btn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="red"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
                                                    </span> --}}
                                                </figure>
                                            </div>

                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                {{-- images --}}
                                @if($field->type == 'images')
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">
                                        {{ ucfirst(str_replace('_',' ',$field->title)) }} <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="file" id="{{ ucfirst(str_replace('_',' ',$field->title)) }}" required="required" class="form-control" name="{{ $field->name.'[]' }}" multiple>
                                        @if(isset($content[$field->name]))

                                            <div class="choose-file-wrap">
                                                <div class="choose-file-multiple">
                                                @foreach ($content[$field->name] as $images)
                                                <figure>
                                                <img src="{{ (isset($images) && !empty($images) && !is_array($images)) ?  asset($images) : "" }}" alt="img-multiple">
                                                {{-- <span class="closed_btn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="red"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
                                                    </span> --}}
                                                </figure>
                                                @endforeach
                                              </div>

                                            </div>
                                        @endif

                                    </div>
                                </div>
                                @endif

                                  {{-- text editor --}}
                                  @if($field->type == 'text-editor')
                                  <div class="item form-group">
                                      <label class="col-form-label col-md-3 col-sm-3 label-align">
                                          {{ ucfirst(str_replace('_',' ',$field->title)) }} <span class="required">*</span>
                                      </label>
                                      <div class="col-md-6 col-sm-6 ">
                                        <textarea id="description" name="{{ $field->name }}" required="required" class="form-control ">{{ (isset($content[$field->name])) ? $content[$field->name] : "" }}</textarea>
                                        @if(Session::has('error'))
                                          <p class="text-danger">{{ Session::get('error') }}</p>
                                        @endif
                                        @error('description')
                                         <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                  </div>
                                  @endif

                                @endforeach
                            </div>
                            @endforeach
                            <button type="submit" class="btn btn-success">Submit</button>
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ensure CKEditor script is fully loaded before replacing the textarea
        CKEDITOR.replace('about_description', {
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
