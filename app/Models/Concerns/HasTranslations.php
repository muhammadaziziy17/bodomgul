<?php

namespace App\Models\Concerns;

use App\Models\Translation;
use App\Support\Locales;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Kontent maydonlarining til bo'yicha tarjimalarini boshqaradi.
 *
 * Har bir model maydoni (name, slug, description...) `translations`
 * jadvalida `field` + `locale` ko'rinishida saqlanadi. O'qishda joriy
 * til topilmasa, asosiy tilga qaytiladi.
 */
trait HasTranslations
{
    /**
     * Slug tarjimasi bo'yicha qidirish (admin va seederlar uchun).
     */
    #[Scope]
    protected function whereSlug(Builder $query, string $slug, ?string $locale = null): Builder
    {
        return $query->whereHas('translations', function (Builder $query) use ($slug, $locale): void {
            $query->where('field', 'slug')
                ->where('value', $slug)
                ->where('locale', $locale ?? Locales::default());
        });
    }

    /**
     * @return MorphMany<Translation, $this>
     */
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * Joriy (yoki berilgan) tildagi qiymat.
     *
     * Zanjir: joriy til -> asosiy til -> boshlang'ich kontent tili
     * (config `app.locale`, ya'ni dastlabki `uz`). Shu tufayli asosiy til
     * almashtirilganda ham eski kontent yo'qolmaydi.
     */
    public function tr(string $field, ?string $locale = null, ?string $fallback = null): ?string
    {
        $locale ??= app()->getLocale();

        $chain = array_values(array_unique(array_filter([
            $locale,
            Locales::default(),
            (string) config('bodomgul.content_locale', 'uz'),
        ])));

        foreach ($chain as $candidate) {
            $value = $this->translationValue($candidate, $field);

            if (filled($value)) {
                return $value;
            }
        }

        return $fallback;
    }

    /**
     * Aniq bir til uchun qiymat (admin formalari uchun, fallbacksiz).
     */
    public function translationFor(string $locale, string $field): ?string
    {
        return $this->translationValue($locale, $field);
    }

    /**
     * Barcha tillar bo'yicha tarjimalar: [locale => [field => value]].
     *
     * @return array<string, array<string, string|null>>
     */
    public function translationsByLocale(): array
    {
        if (! $this->exists) {
            return [];
        }

        $result = [];

        foreach ($this->translations as $translation) {
            $result[$translation->locale][$translation->field] = $translation->value;
        }

        return $result;
    }

    /**
     * Admin saqlashida tarjimalarni yozadi; bo'sh qiymatlar o'chiriladi.
     *
     * @param  array<string, array<string, mixed>>  $values
     */
    public function saveTranslations(array $values): void
    {
        foreach ($values as $locale => $fields) {
            if (! is_array($fields)) {
                continue;
            }

            foreach ($fields as $field => $value) {
                $value = is_string($value) ? trim($value) : $value;

                if ($value === null || $value === '') {
                    $this->translations()
                        ->where('locale', $locale)
                        ->where('field', $field)
                        ->delete();

                    continue;
                }

                $this->translations()->updateOrCreate(
                    ['locale' => $locale, 'field' => $field],
                    ['value' => $value]
                );
            }
        }

        $this->unsetRelation('translations');
    }

    /**
     * Marshrutda modelni topish.
     *
     * Slug bo'yicha bog'lanadigan modellarda (RouteKey('slug')) tarjima
     * zanjiri ishlatiladi: joriy til -> asosiy til -> kontent tili.
     * Boshqa kalitlar (masalan ID) uchun odatiy bog'lanish qo'llanadi.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $field ??= $this->getRouteKeyName();

        if ($field !== 'slug') {
            return parent::resolveRouteBinding($value, $field);
        }

        $locales = array_values(array_unique(array_filter([
            app()->getLocale(),
            Locales::default(),
            (string) config('bodomgul.content_locale', 'uz'),
        ])));

        foreach ($locales as $locale) {
            $model = static::query()
                ->whereHas('translations', function ($query) use ($value, $locale) {
                    $query->where('field', 'slug')
                        ->where('value', $value)
                        ->where('locale', $locale);
                })
                ->first();

            if ($model !== null) {
                return $model;
            }
        }

        return null;
    }

    private function translationValue(string $locale, string $field): ?string
    {
        if (! $this->exists) {
            return null;
        }

        return $this->translations
            ->first(fn (Translation $translation): bool => $translation->locale === $locale
                && $translation->field === $field)
            ?->value;
    }
}
