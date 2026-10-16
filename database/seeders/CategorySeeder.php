<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('bodomgul.categories', []) as $index => $category) {
            $model = Category::query()->whereSlug($category['slug'])->first() ?? new Category;

            $model->fill([
                'image' => $category['image'],
                'sort_order' => $index + 1,
                'is_active' => true,
            ])->save();

            $model->saveTranslations([
                'uz' => [
                    'slug' => $category['slug'],
                    'name' => $category['name'],
                    'description' => $category['description'] ?? null,
                ],
            ]);
        }
    }
}
