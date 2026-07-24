<?php

namespace Database\Seeders;

use App\Models\MonthlyEdition;
use App\Models\MonthlyEditionSection;
use Illuminate\Database\Seeder;

class July2026ShadowsMonthlySectionImagesSeeder extends Seeder
{
    public function run(): void
    {
        $edition = MonthlyEdition::query()
            ->where(function ($query) {
                $query->where(function ($inner) {
                    $inner->where('month', 7)->where('year', 2026);
                })->orWhere('is_homepage', true);
            })
            ->orderByRaw('CASE WHEN month = 7 AND year = 2026 THEN 0 ELSE 1 END')
            ->orderByDesc('is_homepage')
            ->first();

        if (!$edition) {
            $this->command?->warn('No July 2026 edition found.');
            return;
        }

        $base = 'assets/admin/uploads/monthly-edition-sections';

        $map = [
            'Why Her Name Lives On' => [
                'image' => $base . '/behind-our-story-july-2026.png',
                'image_caption' => 'Behind Our Story — Terri, John & Shadow',
                'image_position' => MonthlyEditionSection::IMAGE_FULL,
            ],
            'Why We Print' => [
                'image' => $base . '/why-we-print-july-2026.png',
                'image_caption' => 'Why We Print',
                'image_position' => MonthlyEditionSection::IMAGE_FULL,
            ],
            "Joblin's Corner" => [
                'image' => $base . '/joblins-corner-july-2026.png',
                'image_caption' => "Joblin's Corner — Apparently… I snore.",
                'image_position' => MonthlyEditionSection::IMAGE_CENTERED,
            ],
            "A Moment I'll Never Forget" => [
                'image' => $base . '/moment-ill-never-forget-july-2026.png',
                'image_caption' => "A Moment I'll Never Forget — Kosciuszko National Park",
                'image_position' => MonthlyEditionSection::IMAGE_CENTERED,
            ],
        ];

        foreach ($map as $title => $data) {
            $section = $edition->sections()->where('title', $title)->first();
            if (!$section) {
                $this->command?->warn("Section not found: {$title}");
                continue;
            }

            $section->update($data);
            $this->command?->info("Updated image for: {$title}");
        }

        $this->embedTimberFestivalInline($edition, $base);
    }

    private function embedTimberFestivalInline(MonthlyEdition $edition, string $base): void
    {
        $section = $edition->sections()->where('title', 'Making Memories Around Australia')->first();
        if (!$section) {
            $this->command?->warn('Making Memories Around Australia section not found.');
            return;
        }

        // Keep section image empty so the graphic sits inside the Glenreagh story flow.
        $section->image = null;
        $section->image_caption = null;
        $section->image_position = MonthlyEditionSection::IMAGE_FULL;

        $content = (string) $section->content;
        $content = preg_replace(
            '/<figure class="sm-mag-inline-figure">.*?<\/figure>/s',
            '',
            $content
        ) ?? $content;

        $imgUrl = asset($base . '/glenreagh-timber-festival-july-2026.png');
        $figure = '<figure class="sm-mag-inline-figure sm-mag-section__figure sm-mag-section__figure--full">'
            . '<img src="' . e($imgUrl) . '" alt="Glenreagh Timber Festival">'
            . '<figcaption>Glenreagh Timber Festival — meet Joblin, see John’s Mack Super-Liner, and take home a printed memory.</figcaption>'
            . '</figure>';

        $anchor = 'This year, you\'ll find the whole Shadows Family there too.';
        if (str_contains($content, $anchor)) {
            // Insert after the paragraph that contains this sentence.
            $content = preg_replace(
                '/(<p>[^<]*' . preg_quote($anchor, '/') . '[^<]*<\/p>)/',
                '$1' . $figure,
                $content,
                1
            ) ?? ($content . $figure);
        } elseif (str_contains($content, 'Glenreagh Timber Festival')) {
            $content = preg_replace(
                '/(<p>[^<]*Glenreagh Timber Festival[^<]*<\/p>)/',
                '$1' . $figure,
                $content,
                1
            ) ?? ($content . $figure);
        } else {
            $content .= $figure;
        }

        $section->content = $content;
        $section->save();
        $this->command?->info('Embedded Glenreagh Timber Festival image inside Making Memories Around Australia.');
    }
}
