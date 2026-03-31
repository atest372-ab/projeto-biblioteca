<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; // ADICIONADO: Importante para a pesquisa
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\GoogleBooksController;
use App\Http\Controllers\ReviewController;
use App\Exports\BooksExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Book;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. DASHBOARD E LISTAGEM (ATUALIZADO COM LÓGICA DE PESQUISA)
    Route::get('dashboard', function (Request $request) {
        $search = $request->input('search');

        // Se houver pesquisa, filtra. Se não, mostra todos.
        $livros = Book::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('isbn', 'like', "%{$search}%");
        })->get();

        // Contagem de reviews para o badge do Admin
        $pendentes = \App\Models\Review::where('status', 'suspenso')->count();

        return view('dashboard', compact('livros', 'pendentes'));
    })->name('dashboard');
    
    Route::get('livros', function (Request $request) {
        $search = $request->input('search');
        
        $livros = Book::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })->get();

        return view('dashboard', compact('livros'));
    })->name('livros.index');

    // DETALHES DO LIVRO
    Route::get('/livros/{book}', function (Book $book) {
        // Carrega as reviews ativas para o público ver
        $reviews = $book->reviews()->where('status', 'ativo')->with('user')->get();
        return view('livros.show', compact('book', 'reviews'));
    })->name('livros.show');

    // 2. MENUS ESTÁTICOS / VISTAS
    Route::view('autores', 'autores')->name('autores.index');
    Route::view('editoras', 'editoras')->name('editoras.index');

    // 3. REQUISIÇÕES
    Route::get('requisicoes', [RequisicaoController::class, 'index'])->name('requisicoes.index');
    Route::get('/livro/{id}/requisitar', function ($id) {
        return view('requisitar', ['id' => $id]);
    })->name('livro.requisitar');
    Route::post('/enviar-requisicao/{id}', [RequisicaoController::class, 'store'])->name('requisicao.enviar');

    // 4. GOOGLE BOOKS API
    Route::get('/google-books', [GoogleBooksController::class, 'search'])->name('google.search');
    Route::post('/google-books/import', [GoogleBooksController::class, 'import'])->name('google.import');

    // 5. EXPORTAÇÃO EXCEL
    Route::get('/exportar-livros', function () {
        return Excel::download(new BooksExport, 'livros_biblioteca.xlsx');
    })->name('livros.export');

    // 6. REVIEWS - LADO DO CIDADÃO
    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // 7. GESTÃO DE LIVROS - ELIMINAR
    Route::delete('/livros/{book}', function (Book $book) {
        $book->delete();
        return redirect()->route('dashboard')->with('success', 'Livro removido com sucesso!');
    })->name('livros.destroy');
});

// 8. ADMIN MODERAÇÃO
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/reviews', [ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::patch('/admin/reviews/{review}/status', [ReviewController::class, 'updateStatus'])->name('admin.reviews.update');

    // ROTA PARA ELIMINAR REVIEWS
    Route::delete('/admin/reviews/{review}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
});