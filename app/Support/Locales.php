<?php

namespace App\Support;

use App\Models\Locale;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Sayt tillari registri: faol tillar, asosiy til va joriy til.
 */
class Locales
{
    /**
     * @var Collection<int, Locale>|null
     */
    private static ?Collection $active = null;

    private static ?string $default = null;

    /**
     * Faol tillar, tartibi bilan.
     *
     * @return Collection<int, Locale>
     */
    public static function all(): Collection
    {
        if (static::$active !== null) {
            return static::$active;
        }

        try {
            static::$active = Locale::query()->active()->ordered()->get();
        } catch (Throwable) {
            static::$active = collect();
        }

        if (static::$active->isEmpty()) {
            static::$active = collect([static::fallbackLocale()]);
        }

        return static::$active;
    }

    /**
     * @return array<int, string>
     */
    public static function codes(): array
    {
        return static::all()->pluck('code')->all();
    }

    /**
     * Asosiy til kodi.
     */
    public static function default(): string
    {
        if (static::$default !== null) {
            return static::$default;
        }

        return static::$default = static::all()
            ->firstWhere('is_default', true)?->code
            ?? static::all()->first()->code
            ?? (string) config('app.locale');
    }

    /**
     * Joriy til (faol bo'lmasa — asosiy til).
     */
    public static function current(): string
    {
        $current = app()->getLocale();

        return static::exists($current) ? $current : static::default();
    }

    public static function exists(string $code): bool
    {
        return static::all()->contains(fn (Locale $locale): bool => $locale->code === $code);
    }

    public static function find(string $code): ?Locale
    {
        return static::all()->firstWhere('code', $code);
    }

    /**
     * Tillar o'zgargach keshlarni tozalash.
     */
    public static function flush(): void
    {
        static::$active = null;
        static::$default = null;
    }

    /**
     * Baza mavjud bo'lmaganda ishlatiladigan zaxira til.
     */
    private static function fallbackLocale(): Locale
    {
        $locale = new Locale([
            'code' => (string) config('app.locale', 'uz'),
            'name' => 'O‘zbekcha',
            'is_active' => true,
            'is_default' => true,
            'sort_order' => 1,
        ]);

        $locale->exists = false;

        return $locale;
    }
}
