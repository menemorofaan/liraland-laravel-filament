<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Добавляем уровень доступа к файлам
        Schema::table('distributives', function (Blueprint $table) {
            $table->enum('access_level', ['public', 'registered', 'licensed'])
                ->default('public')
                ->after('os');
        });

        // 2. Расширяем лицензии: тип (подписка / USB-донгл), аппаратный ID и связь с юзером
        Schema::table('licenses', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->enum('license_model', ['subscription', 'perpetual'])->default('subscription')->after('type');
            $table->string('dongle_id')->nullable()->after('license_model'); // ID флешки CodeMeter
            $table->date('expires_at')->nullable()->change(); // Для бессрочных дата может быть пустой
        });
    }

    public function down(): void
    {
        Schema::table('distributives', function (Blueprint $table) {
            $table->dropColumn('access_level');
        });
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'license_model', 'dongle_id']);
        });
    }
};