@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('gift-card-list') }}">Gift Cards</a></li>
          <li class="breadcrumb-item"><a href="#">Add Card</a></li>
        </ol>
    </nav>   
<div class="">
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add Card</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form action="{{ route('gift-card-save') }}" method="POST" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate="">
                    @csrf

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="title">Card Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="name" name="name" required="required" class="form-control ">
                            @error('name')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="image">Card Image <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="file" id="image" name="image" required="required" class="form-control ">
                            @error('image')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="card mb-4 border" style="max-width: 100%; overflow: hidden; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                        <div class="card-header d-flex align-items-center py-3 px-4" style="background: linear-gradient(180deg, #f8f9fa 0%, #eef1f4 100%); border-bottom: 1px solid #dee2e6;">
                            <span class="mr-2" style="font-size: 1.25rem; line-height: 1;">&#9679;</span>
                            <h5 class="mb-0 font-weight-bold" style="color: #2c3e50;">Gift Card Email Image Settings</h5>
                        </div>
                        <div class="card-body py-4 px-4" style="background: #fff;">
                            <div class="item form-group" style="margin-bottom:12px;">
                                <div class="col-md-12 col-sm-12">
                                    <p class="text-muted small mb-0">Configure whether gift card emails should use a different image from the product display image.</p>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="use_different_email_image">
                                    Use Different Email Image
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="use_different_email_image" name="use_different_email_image" value="1" {{ old('use_different_email_image') == '1' ? 'checked' : '' }}>
                                            Yes, use a separate image for gift card emails
                                        </label>
                                    </div>
                                    @error('use_different_email_image')
                                     <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="item form-group mb-0" id="email_image_wrapper" style="{{ old('use_different_email_image') == '1' ? '' : 'display:none;' }}">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="email_image">
                                    Email Image <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="file" id="email_image" name="email_image" class="form-control ">
                                    @error('email_image')
                                     <p class="text-danger">{{ $message }}</p>
                                    @enderror
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
    document.addEventListener('DOMContentLoaded', function () {
        var toggle = document.getElementById('use_different_email_image');
        var wrapper = document.getElementById('email_image_wrapper');
        var emailImageInput = document.getElementById('email_image');

        if (!toggle || !wrapper || !emailImageInput) {
            return;
        }

        function updateEmailImageVisibility() {
            if (toggle.checked) {
                wrapper.style.display = '';
                emailImageInput.setAttribute('required', 'required');
            } else {
                wrapper.style.display = 'none';
                emailImageInput.removeAttribute('required');
                emailImageInput.value = '';
            }
        }

        toggle.addEventListener('change', updateEmailImageVisibility);
        updateEmailImageVisibility();
    });
</script>
@endsection
