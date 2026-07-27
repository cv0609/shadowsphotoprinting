<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ShadowsMonthlySetting;
use Illuminate\Http\Request;

class ShadowsMonthlySettingsController extends Controller
{
    public function edit()
    {
        $settings = ShadowsMonthlySetting::current();

        return view('admin.shadows-monthly-settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'overlay_opacity' => 'nullable|integer|min:0|max:80',
            'hero_heading' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:500',
            'hero_cta_text' => 'nullable|string|max:120',
            'hero_cta_url' => 'nullable|string|max:500',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
        ]);

        $settings = ShadowsMonthlySetting::current();

        unset($data['hero_image']);
        $data['overlay_opacity'] = (int) ($data['overlay_opacity'] ?? 20);
        $data['hero_heading'] = $data['hero_heading'] ?: null;
        $data['hero_subtitle'] = $data['hero_subtitle'] ?: null;
        $data['hero_cta_text'] = $data['hero_cta_text'] ?: null;
        $data['hero_cta_url'] = $data['hero_cta_url'] ?: null;

        if ($request->hasFile('hero_image')) {
            $file = $request->file('hero_image');
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                . '-' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = 'assets/admin/uploads/shadows-monthly';
            $file->move(public_path($destinationPath), $fileName);
            $data['hero_image'] = $destinationPath . '/' . $fileName;
        }

        $settings->update($data);

        return redirect()
            ->route('shadows-monthly-settings.edit')
            ->with('success', 'Shadows Monthly page settings saved.');
    }
}
