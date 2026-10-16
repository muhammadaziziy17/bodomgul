<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Models\Translation;
use Closure;

trait ValidatesLocalizedSlug
{
    /**
     * Berilgan tilda slug takrorlanmasligini tekshiruvchi qoida.
     *
     * @param  class-string  $modelClass
     */
    protected function uniqueSlugRule(string $locale, string $modelClass, ?string $routeParam = null): Closure
    {
        $model = $routeParam !== null ? $this->route($routeParam) : null;

        return function (string $attribute, mixed $value, Closure $fail) use ($locale, $modelClass, $model): void {
            if (blank($value)) {
                return;
            }

            $query = Translation::query()
                ->where('translatable_type', $modelClass)
                ->where('field', 'slug')
                ->where('locale', $locale)
                ->where('value', $value);

            if ($model !== null) {
                $query->where('translatable_id', '!=', $model->getKey());
            }

            if ($query->exists()) {
                $fail('Bu URL manzil (slug) bu tilda allaqachon band.');
            }
        };
    }
}
