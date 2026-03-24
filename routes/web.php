<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\GoogleBooksController;
use App\Exports\BooksExport; // ADICIONADO
use Maatwebsite\Excel\Facades\Excel; // ADICIONADO

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Dashboard (Página Principal)
    Route::get('dashboard', function () {
        $livros = \App\Models\Book::all(); // Puxa todos os livros do banco
        return view('dashboard', compact('livros'));
    })->name('dashboard');
    
    // 2. Rotas de Visualização (Menus da Fase 1)
    Route::get('livros', function () {
        $livros = \App\Models\Book::all();
        return view('dashboard', compact('livros'));
    })->name('livros.index');

    Route::get('autores', function () {
        return view('autores');
    })->name('autores.index');

    Route::get('editoras', function () {
        return view('editoras');
    })->name('editoras.index');

    // Requisições com indicadores (Fase 2)
    Route::get('requisicoes', [RequisicaoController::class, 'index'])->name('requisicoes.index');

    // 3. REQUISIÇÕES - Lógica (Fase 2)
    Route::get('/livro/{id}/requisitar', function ($id) {
        return view('requisitar', ['id' => $id]);
    })->name('livro.requisitar');

    Route::post('/enviar-requisicao/{id}', [RequisicaoController::class, 'store'])->name('requisicao.enviar');

    // 4. GOOGLE BOOKS API (Fase 3)
    Route::get('/google-books', [GoogleBooksController::class, 'search'])->name('google.search');
    Route::post('/google-books/import', [GoogleBooksController::class, 'import'])->name('google.import');

    // 5. EXPORTAÇÃO EXCEL (Fase 1) - NOVA ROTA
    Route::get('/exportar-livros', function () {
        return Excel::download(new BooksExport, 'livros_biblioteca.xlsx');
    })->name('livros.export');
});