<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['slug'])]
#[RouteKey('slug')]
class Page extends Model
{
    use HasTranslations;

    public const ABOUT = 'about';

    public const CAREERS = 'careers';

    public const CONTACTS = 'contacts';

    public function getTitleAttribute(): string
    {
        return (string) $this->tr('title');
    }

    /**
     * Sahifalar bazadagi `slug` ustuni bo'yicha topiladi (about, careers, contacts).
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return static::query()->where('slug', $value)->first();
    }

    /**
     * Sahifa kontenti (JSON) joriy yoki berilgan til uchun.
     *
     * @return array<string, mixed>
     */
    public function contentFor(?string $locale = null): array
    {
        $raw = $this->tr('content', $locale);
        $decoded = $raw ? json_decode($raw, true) : null;

        return is_array($decoded) ? $decoded : [];
    }
}
