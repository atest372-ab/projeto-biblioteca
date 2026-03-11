<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Rota de Teste (Podes manter ou apagar depois)
Route::view('/teste', 'teste');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard Principal
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    // Rotas de Visualização
    Route::view('livros', 'livros')->name('livros.index');
    Route::view('autores', 'autores')->name('autores.index');
    Route::view('requisicoes', 'requisicoes')->name('requisicoes.index');

    // NOVA ROTA: Formulário de Requisição (Passando um ID de exemplo por enquanto)
    Route::get('/livro/{id}/requisitar', function ($id) {
        return view('requisitar', ['id' => $id]);
    })->name('livro.requisitar');
    
    // Futuros Controllers
    // Route::resource('requisicoes', App\Http\Controllers\RequisicaoController::class);
});