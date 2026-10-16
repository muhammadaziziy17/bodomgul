<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Umumiy yangilik ma'lumotlari. Sarlavha, slug, teg, qisqa matn va
     * maqola tanasi `translations` jadvalida saqlanadi.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->date('published_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['is_published', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
