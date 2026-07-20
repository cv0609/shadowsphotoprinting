<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\MonthlyEdition;
use Illuminate\Http\Request;
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

        return view('admin.monthly-editions.add', compact('blogs', 'selectedBlogIds'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $blogIds = $data['blog_ids'] ?? [];
        unset($data['blog_ids'], $data['cover_image']);

        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['cover_image'] = $this->storeImage($request, 'cover_image');
        $data['is_homepage'] = $request->boolean('is_homepage');
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        $edition = MonthlyEdition::create($data);
        $this->ensureSingleHomepage($edition);
        $this->syncStories($edition, $blogIds);

        return redirect()
            ->route('monthly-editions.index')
            ->with('success', 'Monthly edition created successfully.');
    }

    public function show($id)
    {
        $edition = MonthlyEdition::with(['blogs' => function ($query) {
            $query->orderByPivot('sort_order');
        }])->findOrFail($id);
        $blogs = Blog::where('status', '1')->orderBy('title')->get(['id', 'title']);
        $selectedBlogIds = old('blog_ids', $edition->blogs->pluck('id')->all());

        return view('admin.monthly-editions.edit', compact('edition', 'blogs', 'selectedBlogIds'));
    }

    public function update(Request $request, $id)
    {
        $edition = MonthlyEdition::findOrFail($id);
        $data = $this->validated($request);
        $blogIds = $data['blog_ids'] ?? [];
        unset($data['blog_ids'], $data['cover_image']);

        if ($edition->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $edition->id);
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->storeImage($request, 'cover_image');
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
        ]);
    }

    private function storeImage(Request $request, string $field): ?string
    {
        if (!$request->hasFile($field)) {
            return null;
        }

        $file = $request->file($field);
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
            . '-' . time() . '.' . $file->getClientOriginalExtension();
        $destinationPath = 'assets/admin/uploads/monthly-editions';
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
