<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\MonthlyEdition;
use App\Models\MonthlyEditionSection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MonthlyEditionsController extends Controller
{
    public function index()
    {
        $editions = MonthlyEdition::orderByDesc('is_homepage')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate(10);

        return view('admin.monthly-editions.index', compact('editions'));
    }

    public function create()
    {
        $blogs = Blog::where('status', '1')->orderBy('title')->get(['id', 'title']);
        $selectedBlogIds = old('blog_ids', []);
        $formSections = $this->formSectionsFromOld();

        return view('admin.monthly-editions.add', compact('blogs', 'selectedBlogIds', 'formSections'));
    }

    public function store(Request $request)
    {
        $this->normalizeSectionsInput($request);
        $data = $this->validated($request);
        $blogIds = $data['blog_ids'] ?? [];
        $sections = $data['sections'] ?? [];
        unset($data['blog_ids'], $data['cover_image'], $data['sections']);

        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['cover_image'] = $this->storeImage($request->file('cover_image'));
        $data['is_homepage'] = $request->boolean('is_homepage');
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        $edition = MonthlyEdition::create($data);
        $this->ensureSingleHomepage($edition);
        $this->syncStories($edition, $blogIds);
        $this->syncSections($edition, $sections, $request);

        return redirect()
            ->route('monthly-editions.index')
            ->with('success', 'Monthly edition created successfully.');
    }

    public function show($id)
    {
        $edition = MonthlyEdition::with([
            'blogs' => function ($query) {
                $query->orderByPivot('sort_order');
            },
            'sections',
        ])->findOrFail($id);
        $blogs = Blog::where('status', '1')->orderBy('title')->get(['id', 'title']);
        $selectedBlogIds = old('blog_ids', $edition->blogs->pluck('id')->all());
        $formSections = old('sections') !== null
            ? $this->formSectionsFromOld()
            : $edition->sections->map(fn ($section) => [
                'id' => $section->id,
                'title' => $section->title,
                'icon' => $section->icon,
                'content' => $section->content,
                'image' => $section->image,
                'image_caption' => $section->image_caption,
                'image_position' => $section->image_position ?? 'full_width',
                'placement' => $section->placement,
                'sort_order' => $section->sort_order,
            ])->values()->all();

        return view('admin.monthly-editions.edit', compact('edition', 'blogs', 'selectedBlogIds', 'formSections'));
    }

    public function update(Request $request, $id)
    {
        $edition = MonthlyEdition::findOrFail($id);
        $this->normalizeSectionsInput($request);
        $data = $this->validated($request);
        $blogIds = $data['blog_ids'] ?? [];
        $sections = $data['sections'] ?? [];
        unset($data['blog_ids'], $data['cover_image'], $data['sections']);

        if ($edition->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $edition->id);
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->storeImage($request->file('cover_image'));
        }

        $data['is_homepage'] = $request->boolean('is_homepage');

        if ($data['status'] === 'published' && !$edition->published_at) {
            $data['published_at'] = now();
        }
        if ($data['status'] === 'draft') {
            $data['published_at'] = null;
        }

        $edition->update($data);
        $this->ensureSingleHomepage($edition->fresh());
        $this->syncStories($edition, $blogIds);
        $this->syncSections($edition, $sections, $request);

        return redirect()
            ->route('monthly-editions.index')
            ->with('success', 'Monthly edition updated successfully.');
    }

    public function destroy($id)
    {
        MonthlyEdition::findOrFail($id)->delete();

        return redirect()
            ->route('monthly-editions.index')
            ->with('success', 'Monthly edition deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'intro' => 'nullable|string',
            'welcome_note' => 'nullable|string',
            'editor_note' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'is_homepage' => 'nullable|boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'blog_ids' => 'nullable|array',
            'blog_ids.*' => 'integer|exists:blogs,id',
            'sections' => 'nullable|array',
            'sections.*.id' => 'nullable|integer|exists:monthly_edition_sections,id',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.icon' => 'nullable|string|max:191',
            'sections.*.content' => 'nullable|string',
            'sections.*.image_caption' => 'nullable|string|max:255',
            'sections.*.image_position' => 'nullable|in:full_width,left,right,centered',
            'sections.*.placement' => 'nullable|in:before_featured,after_featured',
            'sections.*.sort_order' => 'nullable|integer|min:0',
            'sections.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'sections.*.existing_image' => 'nullable|string|max:500',
            'sections.*.remove_image' => 'nullable|boolean',
        ]);
    }

    private function normalizeSectionsInput(Request $request): void
    {
        $sections = $request->input('sections', []);
        if (!is_array($sections)) {
            $request->merge(['sections' => []]);
            return;
        }

        $cleaned = [];
        foreach ($sections as $index => $row) {
            if (!is_array($row)) {
                continue;
            }

            $title = trim((string) ($row['title'] ?? ''));
            $contentText = trim(strip_tags((string) ($row['content'] ?? '')));
            $hasId = !empty($row['id']);
            $hasUpload = $request->hasFile('sections.' . $index . '.image');

            if (!$hasId && $title === '' && $contentText === '' && !$hasUpload) {
                continue;
            }

            $cleaned[$index] = $row;
        }

        $request->merge(['sections' => $cleaned]);
    }

    private function formSectionsFromOld(): array
    {
        $old = old('sections', []);
        if (!is_array($old)) {
            return [];
        }

        return array_values(array_map(function ($row, $index) {
            return [
                'id' => $row['id'] ?? null,
                'title' => $row['title'] ?? '',
                'icon' => $row['icon'] ?? '',
                'content' => $row['content'] ?? '',
                'image' => $row['existing_image'] ?? ($row['image'] ?? null),
                'image_caption' => $row['image_caption'] ?? '',
                'image_position' => $row['image_position'] ?? MonthlyEditionSection::IMAGE_FULL,
                'placement' => $row['placement'] ?? MonthlyEditionSection::PLACEMENT_BEFORE,
                'sort_order' => $row['sort_order'] ?? ($index + 1),
            ];
        }, $old, array_keys($old)));
    }

    private function storeImage(?UploadedFile $file, string $destinationPath = 'assets/admin/uploads/monthly-editions'): ?string
    {
        if (!$file) {
            return null;
        }

        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
            . '-' . time() . '-' . Str::lower(Str::random(4))
            . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($destinationPath), $fileName);

        return $destinationPath . '/' . $fileName;
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'edition';
        $slug = $base;
        $i = 1;

        while (
            MonthlyEdition::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function syncStories(MonthlyEdition $edition, array $blogIds): void
    {
        $sync = [];
        foreach (array_values($blogIds) as $index => $blogId) {
            $sync[(int) $blogId] = [
                'sort_order' => $index + 1,
                'is_featured' => true,
            ];
        }
        $edition->blogs()->sync($sync);
    }

    private function syncSections(MonthlyEdition $edition, array $sections, Request $request): void
    {
        $keptIds = [];
        $order = 0;

        foreach ($sections as $index => $row) {
            if (!is_array($row)) {
                continue;
            }

            $title = trim((string) ($row['title'] ?? ''));
            if ($title === '') {
                continue;
            }

            $order++;
            $sectionId = !empty($row['id']) ? (int) $row['id'] : null;
            $section = $sectionId
                ? $edition->sections()->where('id', $sectionId)->first()
                : null;

            $allowedPositions = [
                MonthlyEditionSection::IMAGE_FULL,
                MonthlyEditionSection::IMAGE_LEFT,
                MonthlyEditionSection::IMAGE_RIGHT,
                MonthlyEditionSection::IMAGE_CENTERED,
            ];
            $imagePosition = $row['image_position'] ?? MonthlyEditionSection::IMAGE_FULL;
            if (!in_array($imagePosition, $allowedPositions, true)) {
                $imagePosition = MonthlyEditionSection::IMAGE_FULL;
            }

            $payload = [
                'title' => $title,
                'icon' => $row['icon'] ?? null,
                'content' => $row['content'] ?? null,
                'image_caption' => $row['image_caption'] ?? null,
                'image_position' => $imagePosition,
                'placement' => ($row['placement'] ?? MonthlyEditionSection::PLACEMENT_BEFORE) === MonthlyEditionSection::PLACEMENT_AFTER
                    ? MonthlyEditionSection::PLACEMENT_AFTER
                    : MonthlyEditionSection::PLACEMENT_BEFORE,
                'sort_order' => (int) ($row['sort_order'] ?? $order),
            ];

            $uploaded = $request->file('sections.' . $index . '.image');
            if ($uploaded instanceof UploadedFile) {
                $payload['image'] = $this->storeImage(
                    $uploaded,
                    'assets/admin/uploads/monthly-edition-sections'
                );
            } elseif (!empty($row['remove_image'])) {
                $payload['image'] = null;
            } elseif ($section) {
                // keep existing image
            } elseif (!empty($row['existing_image'])) {
                $payload['image'] = $row['existing_image'];
            }

            if ($section) {
                $section->update($payload);
            } else {
                $section = $edition->sections()->create($payload);
            }

            $keptIds[] = $section->id;
        }

        if (count($keptIds)) {
            $edition->sections()->whereNotIn('id', $keptIds)->delete();
        } else {
            $edition->sections()->delete();
        }
    }

    private function ensureSingleHomepage(MonthlyEdition $edition): void
    {
        if (!$edition->is_homepage) {
            return;
        }

        MonthlyEdition::where('id', '!=', $edition->id)
            ->where('is_homepage', true)
            ->update(['is_homepage' => false]);
    }
}
