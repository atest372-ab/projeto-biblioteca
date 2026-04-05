<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Removido o "?" e garantido o nome correto
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pendente'); 
            // Alterado de total_price para total (como está no Controller)
            $table->decimal('total', 10, 2)->default(0); 
            $table->string('address')->nullable();
            $table->string('stripe_id')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};