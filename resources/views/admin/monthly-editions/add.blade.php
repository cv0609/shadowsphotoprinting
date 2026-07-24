@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('monthly-editions.index') }}">Monthly Editions</a></li>
            <li class="breadcrumb-item active">Add Edition</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Add Monthly Edition</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="{{ route('monthly-editions.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal form-label-left">
                        @csrf
                        @include('admin.monthly-editions._form', [
                            'edition' => null,
                            'selectedBlogIds' => old('blog_ids', $selectedBlogIds ?? []),
                            'formSections' => $formSections ?? [],
                        ])
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Create Edition</button>
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
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<script src="{{ asset('assets/admin/js/monthly-edition-form.js') }}?v=3"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    ['#intro', '#welcome_note', '#editor_note'].forEach(function (selector) {
        var el = document.querySelector(selector);
        if (el) {
            ClassicEditor.create(el).catch(console.error);
        }
    });
    if (window.MonthlyEditionForm) {
        window.MonthlyEditionForm.init(@json(array_map('intval', old('blog_ids', $selectedBlogIds ?? []))));
    }
});
</script>
@endsection
