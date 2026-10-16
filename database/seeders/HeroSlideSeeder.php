<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('bodomgul.hero_slides', []) as $index => $slide) {
            $model = HeroSlide::query()
                ->whereHas('translations', fn ($query) => $query
                    ->where('field', 'title')
                    ->where('locale', 'uz')
                    ->where('value', $slide['title']))
                ->first() ?? new HeroSlide;

            $model->fill([
                'image' => $slide['image'],
                'primary_route' => $slide['primary_route'] ?? null,
                'primary_params' => $slide['primary_params'] ?? null,
                'secondary_route' => $slide['secondary_route'] ?? null,
                'sort_order' => $index + 1,
                'is_active' => true,
            ])->save();

            $model->saveTranslations([
                'uz' => [
                    'alt' => $slide['alt'] ?? null,
                    'eyebrow' => $slide['eyebrow'] ?? null,
                    'title' => $slide['title'],
                    'text' => $slide['text'] ?? null,
                    'primary_label' => $slide['primary_label'] ?? null,
                    'secondary_label' => $slide['secondary_label'] ?? null,
                ],
            ]);
        }
    }
}
