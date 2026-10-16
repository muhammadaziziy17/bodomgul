<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Interfeys matnlari tarjimalari (menyu, tugmalar, xabarlar).
     */
    public function up(): void
    {
        Schema::create('ui_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 12);
            $table->string('key');
            $table->text('value');
            $table->timestamps();

            $table->unique(['locale', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ui_translations');
    }
};
