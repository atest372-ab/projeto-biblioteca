<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Book; // Alterado de Livro para Book

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Requisicao>
 */
class RequisicaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), 
            'book_id' => Book::factory(), // Alterado de livro_id para book_id e Livro para Book
            'data_inicio' => now(),       // Usei os nomes que vi no teu modelo Requisicao
            'data_fim_prevista' => now()->addDays(15),
        ];
    }
}