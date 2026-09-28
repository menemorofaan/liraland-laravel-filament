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
    Schema::create('distributives', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Название программы
        $table->string('version'); // Версия (напр. 2.1.0)
        $table->string('os')->default('Windows'); // ОС (Windows, Linux, macOS)
        $table->string('file_path')->nullable(); // Путь к загруженному файлу
        $table->boolean('is_active')->default(true); // Доступен ли для скачивания
        $table->timestamps();
    });
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributives');
    }
};
