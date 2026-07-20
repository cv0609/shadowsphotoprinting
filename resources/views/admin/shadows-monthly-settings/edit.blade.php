@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Shadows Monthly — Page Settings</li>
        </ol>
    </nav>

    @if (Session::has('success'))
        <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Page Settings</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p style="margin-bottom:20px;color:#666;">
                        These settings control the Shadows Monthly landing page hero.
                        They are separate from Monthly Editions — change the hero once here, not on every edition.
                    </p>

                    <form action="{{ route('shadows-monthly-settings.update') }}" method="POST" enctype="multipart/form-data" class="form-horizontal form-label-left">
                        @csrf
                        @method('PUT')

                        <div class="item form-group">
                            <label class="col-md-3 label-align">Default Hero Image</label>
                            <div class="col-md-6">
                                <input type="file" name="hero_image" class="form-control" accept="image/*">
                                @if (!empty($settings->hero_image))
                                    <img src="{{ asset($settings->hero_image) }}" alt="Hero" style="max-width:320px;margin-top:12px;border-radius:8px;">
                                @endif
                                <small>Used on `/shadows-monthly`. Leave empty to keep the current image.</small>
                                @error('hero_image') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-md-3 label-align">Overlay Opacity</label>
                            <div class="col-md-6">
                                <input type="number" name="overlay_opacity" class="form-control" min="0" max="80"
                                       value="{{ old('overlay_opacity', $settings->overlay_opacity ?? 20) }}">
                                <small>0 = no dark wash, 80 = strong overlay. Only applies if optional hero text is shown.</small>
                                @error('overlay_opacity') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-md-3 label-align">Hero Heading (optional)</label>
                            <div class="col-md-6">
                                <input type="text" name="hero_heading" class="form-control"
                                       value="{{ old('hero_heading', $settings->hero_heading) }}"
                                       placeholder="Leave blank to rely on artwork text">
                                @error('hero_heading') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-md-3 label-align">Hero Subtitle (optional)</label>
                            <div class="col-md-6">
                                <textarea name="hero_subtitle" class="form-control" rows="3"
                                          placeholder="Leave blank to rely on artwork text">{{ old('hero_subtitle', $settings->hero_subtitle) }}</textarea>
                                @error('hero_subtitle') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-md-3 label-align">Hero CTA Text (optional)</label>
                            <div class="col-md-6">
                                <input type="text" name="hero_cta_text" class="form-control"
                                       value="{{ old('hero_cta_text', $settings->hero_cta_text) }}"
                                       placeholder="e.g. Browse this month’s edition">
                                @error('hero_cta_text') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-md-3 label-align">Hero CTA URL (optional)</label>
                            <div class="col-md-6">
                                <input type="text" name="hero_cta_url" class="form-control"
                                       value="{{ old('hero_cta_url', $settings->hero_cta_url) }}"
                                       placeholder="#library or full URL">
                                @error('hero_cta_url') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Save Page Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
