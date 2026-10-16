<?php

namespace App\Support;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Joriy sahifaning boshqa tildagi manzilini hisoblaydi
 * (til almashtirgich va hreflang uchun).
 */
class LocalizedUrl
{
    /**
     * Sahifaning berilgan tildagi to'liq URL manzili.
     */
    public static function for(Request $request, string $locale): string
    {
        $route = $request->route();
        $name = $route?->getName();

        if ($name === null) {
            return url('/'.$locale);
        }

        $params = $route->parameters();

        foreach ($params as $key => $value) {
            if ($value instanceof Model) {
                $params[$key] = static::localizedKey($value, $locale);
            }
        }

        $params['locale'] = $locale;

        return route($name, $params);
    }

    /**
     * hreflang uchun barcha faol tillardagi manzillar.
     *
     * @return array<int, array{code: string, url: string}>
     */
    public static function alternates(Request $request): array
    {
        return Locales::all()
            ->map(fn ($locale): array => [
                'code' => $locale->code,
                'url' => static::for($request, $locale->code),
            ])
            ->all();
    }

    /**
     * Model uchun tildagi slug (topilmasa asosiy tilga qaytadi).
     */
    private static function localizedKey(Model $model, string $locale): mixed
    {
        if (! in_array(HasTranslations::class, class_uses_recursive($model), true)) {
            return $model->getRouteKey();
        }

        return $model->tr('slug', $locale) ?? $model->getRouteKey();
    }
}
