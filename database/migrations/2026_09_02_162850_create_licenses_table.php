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
    Schema::create('licenses', function (Blueprint $table) {
        $table->id();
        $table->string('license_key')->unique(); // Уникальный ключ лицензии
        $table->string('client_name');           // Название компании или клиента
        $table->string('client_email');          // Email для связи
        $table->string('type')->default('Standard'); // Тип: Trial, Standard, Enterprise
        $table->integer('max_devices')->default(1);  // Кол-во рабочих мест
        $table->date('expires_at');             // Дата окончания
        $table->enum('status', ['active', 'expired', 'blocked'])->default('active');
        $table->timestamps();
    });
	}

    /**
     * Reverse the migrations.	
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
