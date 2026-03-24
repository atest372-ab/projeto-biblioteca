<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequisicaoController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Dashboard (Página Principal)
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // 2. Rotas de Visualização (Menus da Fase 1)
    // Nota: Alterei 'livros' para apontar para a 'dashboard' se for lá que está a tua tabela principal
    Route::get('livros', function () {
        return view('dashboard'); 
    })->name('livros.index');

    Route::get('autores', function () {
        return view('autores');
    })->name('autores.index');

    Route::get('editoras', function () {
        return view('editoras');
    })->name('editoras.index');

    Route::get('requisicoes', function () {
        return view('requisicoes');
    })->name('requisicoes.index');

    // 3. REQUISIÇÕES (Lógica da Fase 2)
    Route::get('/livro/{id}/requisitar', function ($id) {
        return view('requisitar', ['id' => $id]);
    })->name('livro.requisitar');

    Route::post('/enviar-requisicao/{id}', [RequisicaoController::class, 'store'])->name('requisicao.enviar');
});