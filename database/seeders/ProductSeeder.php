<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('bodomgul.products', []) as $index => $product) {
            $category = Category::query()->whereSlug($product['category'])->first();

            if ($category === null) {
                continue;
            }

            $model = Product::query()->whereSlug($product['slug'])->first() ?? new Product;

            $model->fill([
                'category_id' => $category->id,
                'price' => $product['price'],
                'image' => $product['image'],
                'is_featured' => (bool) ($product['featured'] ?? false),
                'is_active' => true,
                'sort_order' => $index + 1,
            ])->save();

            $model->saveTranslations([
                'uz' => [
                    'slug' => $product['slug'],
                    'name' => $product['name'],
                    'description' => $product['description'] ?? null,
                    'composition' => $product['composition'] ?? null,
                    'weight' => $product['weight'] ?? null,
                ],
            ]);
        }
    }
}
