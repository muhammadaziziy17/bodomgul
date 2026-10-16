<?php

namespace App\Support;

use Throwable;

/**
 * Admin formalarida tanlanadigan sahifa yo'nalishlari.
 *
 * Slayd va promo tugmalari `route` nomi + parametrlar ko'rinishida
 * saqlanadi; bu yerdagi ro'yxat faqat ruxsat etilgan yo'nalishlarni
 * taklif qiladi.
 */
class RouteOptions
{
    /**
     * @return array<string, string>
     */
    public static function all(): array
    {
        return [
            'catalog.index' => 'Katalog',
            'catalog.category' => 'Kategoriya sahifasi',
            'branches.index' => 'Lokatsiyalar',
            'about' => 'Biz haqimizda',
            'news.index' => 'Yangiliklar va aksiyalar',
            'careers' => 'Karyera',
            'contacts' => 'Kontaktlar / Keytering',
            'home' => 'Bosh sahifa',
        ];
    }

    public static function label(?string $routeName): string
    {
        if ($routeName === null) {
            return 'Yo‘nalish tanlanmagan';
        }

        return static::all()[$routeName] ?? $routeName;
    }

    /**
     * Kategoriya parametri bilan birga xavfsiz URL qaytaradi.
     *
     * @param  array<string, mixed>  $params
     */
    public static function url(?string $routeName, array $params = []): string
    {
        if ($routeName === null || ! isset(static::all()[$routeName])) {
            return '#';
        }

        try {
            return route($routeName, $params);
        } catch (Throwable) {
            return '#';
        }
    }
}
