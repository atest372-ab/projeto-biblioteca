<?php

use Illuminate\Support\Facades\Route;

// Página inicial
Route::view('/', 'welcome')->name('home');

// Rota de Teste (Para verificares o layout DaisyUI antes de mudar o oficial)
Route::view('/teste', 'teste');

// Rotas Protegidas (Dashboard)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    // Podes adicionar aqui as rotas futuras para Autores e Editoras
    // Route::view('autores', 'autores')->name('authors.index');
    // Route::view('editoras', 'editoras')->name('publishers.index');
});