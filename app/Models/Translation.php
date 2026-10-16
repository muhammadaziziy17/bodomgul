<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['locale', 'field', 'value'])]
class Translation extends Model
{
    /**
     * Tarjima tegishli bo'lgan model.
     *
     * @return MorphTo<Model, $this>
     */
    public function translatable(): MorphTo
    {
        return $this->morphTo();
    }
}
