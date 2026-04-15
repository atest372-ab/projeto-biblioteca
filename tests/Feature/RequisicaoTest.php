use App\Models\User;
use App\Models\Livro;
use App\Models\Requisicao;

// 1. Teste de Criação de Requisição de Livro
it('permite que um utilizador crie uma requisição de livro', function () {
    // Criar um utilizador e um livro (Setup)
    $user = User::factory()->create();
    $livro = livro::factory()->create(['stock' => 5]);

    // Simular o login e a submissão
    $response = $this->actingAs($user)
        ->post('/requisicoes', [
            'livro_id' => $livro->id,
            'data_requisicao' => now(),
        ]);

    // Garantir que foi redirecionado e os dados estão na DB
    $response->assertStatus(302);
    $this->assertDatabaseHas('requisicoes', [
        'user_id' => $user->id,
        'livro_id' => $livro->id,
    ]);
});

// 5. Teste de Stock (Aproveitando o balanço)
it('impede a requisição de um livro sem stock', function () {
    $user = User::factory()->create();
    $livro = Livro::factory()->create(['stock' => 0]);

    $response = $this->actingAs($user)
        ->post('/requisicoes', [
            'livro_id' => $livro->id,
        ]);

    // Verifica se houve erro de validação ou mensagem de erro
    $response->assertSessionHasErrors('stock');
});