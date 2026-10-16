<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kontent tarjimalari: har qanday model maydoni til bo'yicha saqlanadi.
     * Masalan: products row -> field "name", locale "ru".
     */
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('translatable_type');
            $table->unsignedBigInteger('translatable_id');
            $table->string('locale', 12);
            $table->string('field', 50);
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['translatable_type', 'translatable_id', 'locale', 'field'], 'translations_unique_field');
            $table->index(['translatable_type', 'translatable_id', 'locale'], 'translations_lookup');
            $table->index(['locale', 'field'], 'translations_locale_field');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
