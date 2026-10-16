<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('bodomgul.news', []) as $item) {
            $model = News::query()->whereSlug($item['slug'])->first() ?? new News;

            $model->fill([
                'image' => $item['image'],
                'published_at' => $item['date'] ?? null,
                'is_published' => true,
            ])->save();

            $model->saveTranslations([
                'uz' => [
                    'slug' => $item['slug'],
                    'title' => $item['title'],
                    'tag' => $item['tag'] ?? null,
                    'excerpt' => $item['excerpt'] ?? null,
                    'body' => implode("\n\n", $item['body'] ?? []),
                ],
            ]);
        }
    }
}
