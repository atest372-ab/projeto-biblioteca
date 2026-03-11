<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequisicaoController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Rotas de Visualização
    Route::view('livros', 'livros')->name('livros.index');
    Route::view('autores', 'autores')->name('autores.index');
    Route::view('requisicoes', 'requisicoes')->name('requisicoes.index');

    // REQUISIÇÕES: O formulário e a ação de salvar
    Route::get('/livro/{id}/requisitar', function ($id) {
        return view('requisitar', ['id' => $id]);
    })->name('livro.requisitar');

    Route::post('/livro/{id}/requisitar', [RequisicaoController::class, 'store'])->name('requisicao.enviar');
});