<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'category_id',
    'price',
    'image',
    'is_featured',
    'is_active',
    'sort_order',
])]
#[RouteKey('slug')]
class Product extends Model
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
            'price' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Faqat faol mahsulotlar.
     */
    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Faqat faol kategoriyaga tegishli mahsulotlar.
     *
     * Nofaol qilingan kategoriya mahsulotlari sayt ro'yxatlarida
     * (katalog, sevimlilar, savatcha, bosh sahifa) ko'rinmaydi.
     */
    #[Scope]
    protected function inActiveCategory(Builder $query): Builder
    {
        return $query->whereHas('category', fn (Builder $query): Builder => $query->where('is_active', true));
    }

    /**
     * Katalog va ro'yxatlar uchun umumiy tartib.
     */
    #[Scope]
    protected function listing(Builder $query): Builder
    {
        return $query->orderBy('sort_order')
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

    public function getCompositionAttribute(): ?string
    {
        return $this->tr('composition');
    }

    public function getWeightAttribute(): ?string
    {
        return $this->tr('weight');
    }
}
