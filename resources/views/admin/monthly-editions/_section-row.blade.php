@php
    $index = $index ?? 0;
    $section = $section ?? [];
    $sectionId = $section['id'] ?? null;
    $title = $section['title'] ?? '';
    $icon = $section['icon'] ?? '';
    $content = $section['content'] ?? '';
    $image = $section['image'] ?? null;
    $caption = $section['image_caption'] ?? '';
    $imagePosition = $section['image_position'] ?? 'full_width';
    $placement = $section['placement'] ?? 'before_featured';
    $sortOrder = $section['sort_order'] ?? 1;
@endphp

<div class="me-section-row" data-section-row draggable="true">
    <div class="me-section-row__head">
        <span class="me-section-row__handle" title="Drag to reorder">☰</span>
        <span class="me-section-row__num"></span>
        <strong class="me-section-row__label">{{ $title !== '' ? $title : 'New section' }}</strong>
        <div class="me-section-row__actions">
            <button type="button" class="btn btn-xs btn-default" data-section-move="up" title="Move up">↑</button>
            <button type="button" class="btn btn-xs btn-default" data-section-move="down" title="Move down">↓</button>
            <button type="button" class="btn btn-xs btn-danger" data-section-remove title="Remove">Remove</button>
        </div>
    </div>

    <div class="me-section-row__body">
        @if ($sectionId)
            <input type="hidden" name="sections[{{ $index }}][id]" value="{{ $sectionId }}">
        @endif
        <input type="hidden" name="sections[{{ $index }}][sort_order]" value="{{ $sortOrder }}" data-section-sort>
        @if ($image)
            <input type="hidden" name="sections[{{ $index }}][existing_image]" value="{{ $image }}">
        @endif

        <div class="row" style="margin-bottom:10px;">
            <div class="col-md-7">
                <label>Section Title *</label>
                <input type="text" class="form-control" name="sections[{{ $index }}][title]"
                       value="{{ $title }}" data-section-title placeholder="e.g. Pull Up a Chair...">
            </div>
            <div class="col-md-2">
                <label>Icon / Emoji</label>
                <input type="text" class="form-control" name="sections[{{ $index }}][icon]"
                       value="{{ $icon }}" maxlength="50" placeholder="☕">
            </div>
            <div class="col-md-3">
                <label>Placement</label>
                <select class="form-control" name="sections[{{ $index }}][placement]">
                    <option value="before_featured" @selected($placement === 'before_featured')>Before Featured Stories</option>
                    <option value="after_featured" @selected($placement === 'after_featured')>After Featured Stories</option>
                </select>
            </div>
        </div>

        <div class="row" style="margin-bottom:10px;">
            <div class="col-md-12">
                <label>Content</label>
                <textarea class="form-control me-section-editor" name="sections[{{ $index }}][content]" rows="8"
                          data-section-editor>{{ $content }}</textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label>Optional Image</label>
                <input type="file" class="form-control" name="sections[{{ $index }}][image]" accept="image/*">
                @if ($image)
                    <div class="me-section-current-image" style="margin-top:8px;">
                        <img src="{{ asset($image) }}" alt="" style="max-width:140px;display:block;margin-bottom:6px;">
                        <label style="font-weight:normal;">
                            <input type="checkbox" name="sections[{{ $index }}][remove_image]" value="1">
                            Remove current image
                        </label>
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                <label>Image Position</label>
                <select class="form-control" name="sections[{{ $index }}][image_position]">
                    <option value="full_width" @selected($imagePosition === 'full_width')>Full Width</option>
                    <option value="left" @selected($imagePosition === 'left')>Left</option>
                    <option value="right" @selected($imagePosition === 'right')>Right</option>
                    <option value="centered" @selected($imagePosition === 'centered')>Centered</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Image Caption</label>
                <input type="text" class="form-control" name="sections[{{ $index }}][image_caption]"
                       value="{{ $caption }}" maxlength="255">
            </div>
        </div>
    </div>
</div>
