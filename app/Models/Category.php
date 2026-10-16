<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['image', 'sort_order', 'is_active'])]
#[RouteKey('slug')]
class Category extends Model
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
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Faqat faol kategoriyalar, admin belgilagan tartibda.
     */
    #[Scope]
    protected function ordered(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function getNameAttribute(): string
    {
        return (string) $this->tr('name');
    }

    public function getSlugAttribute(): string
    {
        return (string) $this->tr('slug');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->tr('description');
    }
}
