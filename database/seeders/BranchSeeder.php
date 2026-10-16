<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('bodomgul.branches', []) as $index => $branch) {
            $model = Branch::query()
                ->whereHas('translations', fn ($query) => $query
                    ->where('field', 'name')
                    ->where('locale', 'uz')
                    ->where('value', $branch['name']))
                ->first() ?? new Branch;

            $model->fill([
                'phone' => $branch['phone'] ?? null,
                'sort_order' => $index + 1,
                'is_active' => true,
            ])->save();

            $model->saveTranslations([
                'uz' => [
                    'name' => $branch['name'],
                    'district' => $branch['district'] ?? null,
                    'address' => $branch['address'],
                    'hours' => $branch['hours'] ?? null,
                ],
            ]);
        }
    }
}
