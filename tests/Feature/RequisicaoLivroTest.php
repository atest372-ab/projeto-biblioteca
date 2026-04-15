<?php

use App\Models\User;
use App\Models\Book;
use App\Models\Requisicao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpParser\Node\Expr\FuncCall;

uses(RefreshDatabase::class);

// 1. TESTE DE CRIAÇÃO
it('permite que um utilizador crie uma requisicao de livro', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();

    $response = $this->actingAs($user)
        ->post("/enviar-requisicao/{$book->id}");

    $response->assertStatus(302);
    $response->assertRedirect(route('dashboard'));
    
    $this->assertDatabaseHas('requisicaos', [
        'user_id' => $user->id,
        'book_id' => $book->id,
    ]);
});

// 2. TESTE DE ENTREGA (CORRIGIDO)
it('permite ao admin registar a entrega de um livro', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $book = Book::factory()->create();

    $requisicao = Requisicao::create([
        'user_id' => $admin->id,
        'book_id' => $book->id,
        'data_inicio' => now(),
        'data_fim_prevista' => now()->addDays(15),
        'numero_sequencial' => 'REQ-' . uniqid(),
    ]);

    $response = $this->actingAs($admin)
        ->patch("/requisicoes/{$requisicao->id}/entregar");

    $response->assertStatus(302);

    // fresh() recarrega os dados da base de dados para garantir que a mudança consiga ser visualizada
    $this->assertNotNull($requisicao->fresh()->data_rececao_real);
});

// 3. TESTE DE SEGURANÇA
it('impede que visitantes não autenticados acedam às requisições', function () {
    $response = $this->get(route('requisicoes.index'));
    $response->assertRedirect('/login');
});

// TESTE 4: LISTAGEM POR UTILIZADOR (Garantir privacidade)
it('garante que um utilizador apenas veja as suas próprias requisições', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $book = Book::factory()->create();

    // Criação da requisição para o User A
    Requisicao::create([
        'user_id' => $userA->id,
        'book_id' => $book->id,
        'numero_sequencial' => 'REQ-USER-A',
        'data_inicio' => now(),
        'data_fim_prevista' => now()->addDays(15),
    ]);

    // Simulação do login do User B e o acesso à listagem
    $response = $this->actingAs($userB)->get('/requisicoes');

    // O User B não deve visualizar a requisição do User A
    $response->assertStatus(200);
    $response->assertDontSee('REQ-USER-A');
});

// TESTE 2: VALIDAÇÃO DE REQUISIÇÃO (Livro inválido)
it('impede a criação de uma requisição com um livro inexistente', function () {
    $user = User::factory()->create();

    // Tentar post para um ID de livro que não existe (ex: 999)
    $response = $this->actingAs($user)
        ->post("/enviar-requisicao/999");

    // Deve retornar um erro 404 (Not Found) porque o Controller usa findOrFail
    $response->assertStatus(404);
});

// TESTE 5: STOCK
it('impede a requisição de um livro sem stock disponível', function () {
    $user = User::factory()->create();
    // Criação de um livro com stock ZERO
    $book = Book::factory()->create(['stock' => 0]);

    $response = $this->actingAs($user)
        ->post("/enviar-requisicao/{$book->id}");
        
    // Verificação de redirecionamento com erro de stock na sessão
    $response->assertSessionHas('error', 'Este livro não tem stock disponível.');

    // Garante que a requisição NÃO foi criada
    $this->assertDatabaseMissing('requisicaos', [
        'book_id' => $book->id,
        'user_id' => $user->id,
    ]);
});