<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'image',
    'primary_route',
    'primary_params',
    'secondary_route',
    'sort_order',
    'is_active',
])]
class HeroSlide extends Model
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
            'primary_params' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Faqat faol slaydlar, admin belgilagan tartibda.
     */
    #[Scope]
    protected function ordered(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function getAltAttribute(): ?string
    {
        return $this->tr('alt');
    }

    public function getEyebrowAttribute(): ?string
    {
        return $this->tr('eyebrow');
    }

    public function getTitleAttribute(): string
    {
        return (string) $this->tr('title');
    }

    public function getTextAttribute(): ?string
    {
        return $this->tr('text');
    }

    public function getPrimaryLabelAttribute(): ?string
    {
        return $this->tr('primary_label');
    }

    public function getSecondaryLabelAttribute(): ?string
    {
        return $this->tr('secondary_label');
    }
}
