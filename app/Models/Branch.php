<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['phone', 'map_url', 'sort_order', 'is_active'])]
class Branch extends Model
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
     * Faqat faol filiallar, admin belgilagan tartibda.
     */
    #[Scope]
    protected function ordered(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    /**
     * Xarita havolasi: admin kiritmagan bo'lsa, manzil bo'yicha qidiruv.
     */
    public function getMapUrlAttribute(): string
    {
        $custom = $this->attributes['map_url'] ?? null;

        if (is_string($custom) && trim($custom) !== '') {
            return $custom;
        }

        return 'https://www.google.com/maps/search/?api=1&query='.
            rawurlencode(trim($this->address.', '.$this->district.', Toshkent', ', '));
    }

    /**
     * Mijoz tomonidagi qidiruv uchun tayyor matn.
     */
    public function getSearchAttribute(): string
    {
        return Str::lower(implode(' ', array_filter([
            $this->name,
            $this->district,
            $this->address,
        ])));
    }

    public function getNameAttribute(): string
    {
        return (string) $this->tr('name');
    }

    public function getDistrictAttribute(): ?string
    {
        return $this->tr('district');
    }

    public function getAddressAttribute(): string
    {
        return (string) $this->tr('address');
    }

    public function getHoursAttribute(): ?string
    {
        return $this->tr('hours');
    }
}
