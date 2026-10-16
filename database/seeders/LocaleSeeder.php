<?php

namespace Database\Seeders;

use App\Models\Locale;
use Illuminate\Database\Seeder;

class LocaleSeeder extends Seeder
{
    /**
     * Boshlang'ich til: o'zbekcha (asosiy).
     * Boshqa tillarni admin panel orqali qo'shish mumkin.
     */
    public function run(): void
    {
        Locale::query()->updateOrCreate(
            ['code' => 'uz'],
            [
                'name' => 'O‘zbekcha',
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1,
            ]
        );
    }
}
