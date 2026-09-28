<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
	{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');               // Заголовок новости
        $table->string('slug')->unique();       // ЧПУ-ссылка (например: update-lira-sapr-2026)
        $table->text('summary')->nullable();    // Краткий анонс для главной
        $table->longText('content');            // Полный текст (HTML из редактора)
        $table->string('cover_image')->nullable(); // Обложка новости
        $table->date('published_at')->default(now()); // Дата публикации
        $table->boolean('is_published')->default(true);
        $table->timestamps();
    });
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
