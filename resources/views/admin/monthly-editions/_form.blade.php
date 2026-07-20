@php
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
    ];
    $selectedBlogIds = array_map('intval', $selectedBlogIds ?? []);
@endphp

<div class="item form-group">
    <label class="col-md-3 label-align">Title *</label>
    <div class="col-md-6">
        <input type="text" name="title" class="form-control" required
               value="{{ old('title', $edition->title ?? '') }}">
        @error('title') <p class="text-danger">{{ $message }}</p> @enderror
    </div>
</div>

<div class="item form-group">
    <label class="col-md-3 label-align">Month *</label>
    <div class="col-md-6">
        <select name="month" class="form-control" required>
            @foreach ($months as $num => $label)
                <option value="{{ $num }}" @selected((int) old('month', $edition->month ?? date('n')) === $num)>{{ $label }}</option>
            @endforeach
        </select>
        @error('month') <p class="text-danger">{{ $message }}</p> @enderror
    </div>
</div>

<div class="item form-group">
    <label class="col-md-3 label-align">Year *</label>
    <div class="col-md-6">
        <input type="number" name="year" class="form-control" min="2020" max="2100" required
               value="{{ old('year', $edition->year ?? date('Y')) }}">
        @error('year') <p class="text-danger">{{ $message }}</p> @enderror
    </div>
</div>

<div class="item form-group">
    <label class="col-md-3 label-align">Status *</label>
    <div class="col-md-6">
        <select name="status" class="form-control" required>
            <option value="draft" @selected(old('status', $edition->status ?? 'draft') === 'draft')>Draft</option>
            <option value="published" @selected(old('status', $edition->status ?? '') === 'published')>Published</option>
        </select>
        @error('status') <p class="text-danger">{{ $message }}</p> @enderror
    </div>
</div>

<div class="item form-group">
    <label class="col-md-3 label-align">Feature as Latest Edition</label>
    <div class="col-md-6">
        <label style="font-weight:normal;margin-top:8px;">
            <input type="checkbox" name="is_homepage" value="1"
                @checked(old('is_homepage', $edition->is_homepage ?? false))>
            Show this edition as “Latest Shadows Monthly Edition” on the frontend
        </label>
        <br>
        <small>Only one edition can be featured. If none is checked, the newest published edition is used.</small>
        @error('is_homepage') <p class="text-danger">{{ $message }}</p> @enderror
    </div>
</div>

<div class="item form-group">
    <label class="col-md-3 label-align">Cover Image</label>
    <div class="col-md-6">
        <input type="file" name="cover_image" class="form-control" accept="image/*">
        @if (!empty($edition?->cover_image))
            <img src="{{ asset($edition->cover_image) }}" alt="Cover" style="max-width:180px;margin-top:10px;">
        @endif
        @error('cover_image') <p class="text-danger">{{ $message }}</p> @enderror
    </div>
</div>

<div class="item form-group">
    <label class="col-md-3 label-align">Welcome Note</label>
    <div class="col-md-6">
        <textarea id="welcome_note" name="welcome_note" class="form-control" rows="5">{{ old('welcome_note', $edition->welcome_note ?? '') }}</textarea>
        @error('welcome_note') <p class="text-danger">{{ $message }}</p> @enderror
    </div>
</div>

<div class="item form-group">
    <label class="col-md-3 label-align">Intro</label>
    <div class="col-md-6">
        <textarea id="intro" name="intro" class="form-control" rows="6">{{ old('intro', $edition->intro ?? '') }}</textarea>
        @error('intro') <p class="text-danger">{{ $message }}</p> @enderror
    </div>
</div>

<div class="item form-group">
    <label class="col-md-3 label-align">Editor Note</label>
    <div class="col-md-6">
        <textarea id="editor_note" name="editor_note" class="form-control" rows="4">{{ old('editor_note', $edition->editor_note ?? '') }}</textarea>
        @error('editor_note') <p class="text-danger">{{ $message }}</p> @enderror
    </div>
</div>

<div class="item form-group">
    <label class="col-md-3 label-align">Featured Articles</label>
    <div class="col-md-6">
        <p style="margin-bottom:8px;color:#666;">
            Tick blogs to feature in this edition. Drag the selected list (or use ↑ ↓) to set sort order.
        </p>

        <div class="me-blog-picker">
            <div class="me-blog-picker__available">
                @foreach ($blogs as $blog)
                    <label class="me-blog-option" data-blog-id="{{ $blog->id }}" data-blog-title="{{ e($blog->title) }}">
                        <input type="checkbox" class="me-blog-check" value="{{ $blog->id }}"
                            @checked(in_array((int) $blog->id, $selectedBlogIds, true))>
                        <span>{{ $blog->title }}</span>
                    </label>
                @endforeach
            </div>

            <div class="me-blog-picker__ordered-wrap">
                <strong>Featured order (frontend display)</strong>
                <ul id="me-ordered-blogs" class="me-ordered-blogs"></ul>
                <div id="me-ordered-empty" class="me-ordered-empty">No articles selected yet.</div>
            </div>
        </div>

        <div id="me-blog-ids-inputs"></div>
        @error('blog_ids') <p class="text-danger">{{ $message }}</p> @enderror
        @error('blog_ids.*') <p class="text-danger">{{ $message }}</p> @enderror
    </div>
</div>

<style>
.me-blog-picker {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.me-blog-picker__available {
    max-height: 320px;
    overflow: auto;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 8px;
    background: #fff;
}
.me-blog-option {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 6px 4px;
    margin: 0;
    font-weight: normal;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}
.me-blog-option:last-child { border-bottom: 0; }
.me-blog-option input { margin-top: 3px; }
.me-blog-picker__ordered-wrap strong {
    display: block;
    margin-bottom: 8px;
}
.me-ordered-blogs {
    list-style: none;
    margin: 0;
    padding: 0;
    min-height: 48px;
    border: 1px dashed #ccc;
    border-radius: 6px;
    background: #fafafa;
}
.me-ordered-blogs li {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 10px;
    border-bottom: 1px solid #eee;
    background: #fff;
    cursor: grab;
}
.me-ordered-blogs li:last-child { border-bottom: 0; }
.me-ordered-blogs li.dragging { opacity: 0.55; }
.me-order-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #69794E;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
}
.me-order-title { flex: 1; font-size: 13px; }
.me-order-actions { display: flex; gap: 4px; }
.me-order-actions button {
    border: 1px solid #ccc;
    background: #fff;
    border-radius: 4px;
    width: 28px;
    height: 28px;
    line-height: 1;
    padding: 0;
}
.me-ordered-empty {
    margin-top: 8px;
    color: #888;
    font-size: 13px;
}
@media (max-width: 992px) {
    .me-blog-picker { grid-template-columns: 1fr; }
}
</style>
