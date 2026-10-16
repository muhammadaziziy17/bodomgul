<?php

namespace App\Support;

use App\Models\Setting;
use Throwable;

/**
 * Sayt sozlamalari: brend, ijtimoiy tarmoqlar, ilova havolalari va promo.
 *
 * Asosiy qiymatlar `settings` jadvalining `site` yozuvida (asosiy til),
 * boshqa tillar uchun matnlar esa shu yozuvga bog'langan tarjimalarda
 * saqlanadi. Tarjima bo'lmasa — asosiy til qiymati ishlatiladi.
 */
class SiteSettings
{
    /**
     * Tarjima qilinadigan sozlama maydonlari (nuqtali kalitlar).
     *
     * @var array<int, string>
     */
    public const TRANSLATABLE_FIELDS = [
        'brand.motto',
        'brand.tagline',
        'brand.about',
        'brand.announcement',
        'brand.hours',
        'brand.address',
        'brand.copyright_note',
        'promo.eyebrow',
        'promo.title',
        'promo.text',
        'promo.cta_label',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    private static array $cache = [];

    /**
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        $locale = app()->getLocale();

        if (isset(static::$cache[$locale])) {
            return static::$cache[$locale];
        }

        $defaults = [
            'brand' => config('bodomgul.brand', []),
            'promo' => config('bodomgul.promo', []),
            'social' => config('bodomgul.social', []),
            'app' => config('bodomgul.app', []),
        ];

        try {
            $setting = static::model();
        } catch (Throwable) {
            $setting = null;
        }

        $stored = $setting?->value;

        $base = is_array($stored)
            ? array_replace_recursive($defaults, $stored)
            : $defaults;

        static::$cache[$locale] = static::applyLocaleOverrides($base, $setting);

        return static::$cache[$locale];
    }

    /**
     * @return array<string, mixed>
     */
    public static function brand(): array
    {
        return static::all()['brand'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function promo(): array
    {
        return static::all()['promo'];
    }

    /**
     * @return array<string, string>
     */
    public static function social(): array
    {
        return static::all()['social'];
    }

    /**
     * @return array<string, string>
     */
    public static function app(): array
    {
        return static::all()['app'];
    }

    /**
     * Sozlamalar yozuvi (admin tahriri uchun).
     */
    public static function model(): ?Setting
    {
        return Setting::query()->where('key', 'site')->first();
    }

    /**
     * Sozlamalar saqlangach keshni tozalash uchun.
     */
    public static function flush(): void
    {
        static::$cache = [];
    }

    /**
     * Joriy til uchun tarjima qilingan maydonlarni asosiy qiymat ustiga qo'yadi.
     *
     * @param  array<string, mixed>  $base
     * @return array<string, mixed>
     */
    private static function applyLocaleOverrides(array $base, ?Setting $setting): array
    {
        if ($setting === null) {
            return $base;
        }

        $locale = app()->getLocale();
        $default = Locales::default();

        if ($locale === $default) {
            return $base;
        }

        $setting->loadMissing('translations');

        foreach ($setting->translations->where('locale', $locale) as $translation) {
            if (in_array($translation->field, self::TRANSLATABLE_FIELDS, true) && filled($translation->value)) {
                data_set($base, $translation->field, $translation->value);
            }
        }

        return $base;
    }
}
