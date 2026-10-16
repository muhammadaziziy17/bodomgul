<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Sayt kontentining boshlang'ich to'plami.
     */
    public function run(): void
    {
        $this->call([
            LocaleSeeder::class,
            AdminUserSeeder::class,
            SettingsSeeder::class,
            HeroSlideSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            NewsSeeder::class,
            BranchSeeder::class,
            PageSeeder::class,
            LocalizationSeeder::class,
        ]);
    }
}
