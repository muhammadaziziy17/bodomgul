<?php

namespace App\Support;

use App\Models\UiTranslation;
use Throwable;

/**
 * Interfeys matnlari tarjimoni.
 *
 * Matnlar config/ui.php faylida boshlang'ich (o'zbekcha) ko'rinishda
 * turadi; admin ularni har bir til uchun `ui_translations` jadvalida
 * o'zgartiradi. Topilmasa: joriy til -> asosiy til -> boshlang'ich matn.
 */
class Ui
{
    /**
     * @var array<string, array<string, string>>|null
     */
    private static ?array $overrides = null;

    /**
     * Berilgan kalit uchun tarjima.
     */
    public static function get(string $key, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        $override = static::overrides()[$locale][$key] ?? null;

        if (filled($override)) {
            return $override;
        }

        $default = Locales::default();

        if ($locale !== $default) {
            $fallbackOverride = static::overrides()[$default][$key] ?? null;

            if (filled($fallbackOverride)) {
                return $fallbackOverride;
            }
        }

        return static::dictionary()[$key]
            ?? static::overrides()[$default][$key]
            ?? $key;
    }

    /**
     * Kalitning boshlang'ich (config'dagi) matni.
     */
    public static function initial(string $key): ?string
    {
        return static::dictionary()[$key] ?? null;
    }

    /**
     * @return array<string, string>
     */
    public static function dictionary(): array
    {
        return config('ui.strings', []);
    }

    /**
     * @return array<string, string>
     */
    public static function groups(): array
    {
        return config('ui.groups', []);
    }

    /**
     * Guruh nomi: kalitning birinchi bo'lagi bo'yicha.
     */
    public static function groupOf(string $key): string
    {
        return explode('.', $key)[0];
    }

    /**
     * Barcha tillar bo'yicha o'zgartirilgan matnlar.
     *
     * @return array<string, array<string, string>>
     */
    public static function overrides(): array
    {
        if (static::$overrides !== null) {
            return static::$overrides;
        }

        try {
            static::$overrides = UiTranslation::query()
                ->get(['locale', 'key', 'value'])
                ->groupBy('locale')
                ->map(fn ($rows): array => $rows->pluck('value', 'key')->all())
                ->all();
        } catch (Throwable) {
            static::$overrides = [];
        }

        return static::$overrides;
    }

    public static function flush(): void
    {
        static::$overrides = null;
    }
}
