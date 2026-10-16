<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['image', 'published_at', 'is_published'])]
#[RouteKey('slug')]
class News extends Model
{
    use HasTranslations;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Faqat chop etilgan yangiliklar, eng yangisi birinchi.
     */
    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }

    /**
     * Maqola tanasi: bo'sh qator bilan ajratilgan paragraflar.
     *
     * @return array<int, string>
     */
    public function paragraphs(): array
    {
        return collect(preg_split('/\R{2,}/u', (string) $this->tr('body')))
            ->map(fn (string $paragraph): string => trim($paragraph))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Ko'rsatish uchun o'qishli sana (joriy tilda).
     */
    public function getDateLabelAttribute(): string
    {
        return localized_date($this->published_at);
    }

    public function getTitleAttribute(): string
    {
        return (string) $this->tr('title');
    }

    public function getSlugAttribute(): string
    {
        return (string) $this->tr('slug');
    }

    public function getTagAttribute(): ?string
    {
        return $this->tr('tag');
    }

    public function getExcerptAttribute(): ?string
    {
        return $this->tr('excerpt');
    }

    public function getBodyAttribute(): ?string
    {
        return $this->tr('body');
    }
}
