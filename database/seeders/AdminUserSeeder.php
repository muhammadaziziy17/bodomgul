<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Admin panelga kirish uchun foydalanuvchi.
     * Login/parol config/bodomgul.php orqali `.env`dan olinadi.
     */
    public function run(): void
    {
        $admin = config('bodomgul.admin');

        User::query()->updateOrCreate(
            ['email' => $admin['email']],
            [
                'name' => $admin['name'],
                'password' => $admin['password'],
            ]
        );
    }
}
