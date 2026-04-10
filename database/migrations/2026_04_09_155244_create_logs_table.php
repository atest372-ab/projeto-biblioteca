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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('modulo'); // Ex: Livros, Requisições, Carrinho
            $table->unsignedBigInteger('objeto_id')->nullable(); // ID do livro ou requisição
            $table->text('alteracao'); // Descrição do que mudou
            $table->string('ip');
            $table->string('browser');
            $table->timestamps(); // Já inclui Data e Hora automaticamente (create_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
