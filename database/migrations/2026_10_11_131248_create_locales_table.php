<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sayt tillari. Admin paneldan qo'shiladi va boshqariladi.
     */
    public function up(): void
    {
        Schema::create('locales', function (Blueprint $table) {
            $table->id();
            $table->string('code', 12)->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locales');
    }
};
