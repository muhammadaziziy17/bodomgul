<?php

use App\Support\Ui;
use Illuminate\Support\Carbon;

if (! function_exists('format_price')) {
    /**
     * Narxni joriy tildagi valyuta yozuvi bilan chiqaradi.
     *
     * Misol: 185000 -> "185 000 so‘m" (razryadlar ajratuvchisi — uzilmaydigan bo‘shliq).
     */
    function format_price(int $amount): string
    {
        $currency = app()->bound('config') ? t('common.currency') : 'so‘m';

        return number_format($amount, 0, ',', "\u{00A0}")."\u{00A0}".$currency;
    }
}

if (! function_exists('t')) {
    /**
     * Interfeys matnini joriy tilda qaytaradi.
     *
     * @param  array<string, string|int>  $replace  `:kalit` o'rinbosarlari
     */
    function t(string $key, array $replace = []): string
    {
        $value = Ui::get($key);

        foreach ($replace as $search => $replacement) {
            $value = str_replace(':'.$search, (string) $replacement, $value);
        }

        return $value;
    }
}

if (! function_exists('carbon_locale')) {
    /**
     * Til kodini Carbon uchun mos lokaga aylantiradi.
     */
    function carbon_locale(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'uz' ? 'uz_Latn' : $locale;
    }
}

if (! function_exists('localized_date')) {
    /**
     * Sanani joriy tilda o'qishli ko'rinishda chiqaradi.
     */
    function localized_date(?Carbon $date): string
    {
        return $date
            ? Carbon::parse($date)->locale(carbon_locale())->translatedFormat('j F, Y')
            : '';
    }
}
