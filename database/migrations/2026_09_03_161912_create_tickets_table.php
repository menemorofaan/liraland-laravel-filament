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
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->string('ticket_number')->unique(); // Номер тикета (напр. TCK-84920)
        $table->string('client_name');             // Кто обратился
        $table->string('client_email');            // Контактный email
        $table->string('subject');                 // Тема проблемы
        $table->text('message');                   // Подробный текст
        $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
        $table->enum('status', ['new', 'in_progress', 'resolved', 'closed'])->default('new');
        
        // Связь с сотрудником: на кого назначен тикет (внешний ключ на таблицу users)
        $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
        $table->text('admin_comment')->nullable(); // Внутренняя заметка техподдержки
        $table->timestamps();
    });
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
