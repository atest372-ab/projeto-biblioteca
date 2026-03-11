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
        Schema::create('requisicaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('book_id')->constrained();
            $table->string('numero_sequencial')->unique();
            $table->timestamp('data_inicio');
            $table->timestamp('data_fim_prevista');
            $table->timestamp('data_rececao_real')->nullable();
            $table->string('foto_cidadao')->nullable();
            $table->integer('dias_decorridos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisicaos');
    }
};
