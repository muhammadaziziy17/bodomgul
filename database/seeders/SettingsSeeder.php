<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Sayt sozlamalarining boshlang'ich qiymatlari config'dan olinadi.
     */
    public function run(): void
    {
        Setting::query()->updateOrCreate(
            ['key' => 'site'],
            ['value' => [
                'brand' => config('bodomgul.brand', []),
                'promo' => config('bodomgul.promo', []),
                'social' => config('bodomgul.social', []),
                'app' => config('bodomgul.app', []),
            ]]
        );
    }
}
