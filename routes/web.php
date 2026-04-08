<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\GoogleBooksController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

// Outros
use App\Models\Book;
use App\Models\Order;
use App\Models\Cart;
use App\Exports\BooksExport;
use Maatwebsite\Excel\Facades\Excel;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. DASHBOARD
    Route::get('dashboard', function (Request $request) {
        $search = $request->input('search');
        $livros = Book::when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%")->orWhere('isbn', 'like', "%{$search}%");
        })->get();
        $pendentes = \App\Models\Review::where('status', 'suspenso')->count();
        return view('dashboard', compact('livros', 'pendentes'));
    })->name('dashboard');

    // ROTAS LIVROS
    Route::get('/autores', function () { return view('autores'); })->name('autores.index');
    Route::get('/editoras', function () { return view('editoras'); })->name('editoras.index');
    Route::get('livros', function () { return redirect()->route('dashboard'); })->name('livros.index')->name('books.index');
    Route::get('/livros/{book}', [GoogleBooksController::class, 'show'])->name('livros.show');
    Route::delete('/livros/{book}', function (Book $book) {
        $book->delete();
        return redirect()->route('dashboard')->with('success', 'Livro removido!');
    })->name('livros.destroy');

    // 2. REQUISIÇÕES
    Route::get('requisicoes', [RequisicaoController::class, 'index'])->name('requisicoes.index');
    Route::get('/livro/{id}/requisitar', function ($id) { return view('requisitar', ['id' => $id]); })->name('livro.requisitar');
    Route::post('/enviar-requisicao/{id}', [RequisicaoController::class, 'store'])->name('requisicao.enviar');
    Route::post('/livros/{id}/alert', [AlertController::class, 'store'])->name('livros.alert');

    // 3. GOOGLE BOOKS & EXPORT
    Route::get('/google-books', [GoogleBooksController::class, 'search'])->name('google.search');
    Route::post('/google-books/import', [GoogleBooksController::class, 'import'])->name('google.import');
    Route::get('/exportar-livros', function () { return Excel::download(new BooksExport, 'livros_biblioteca.xlsx'); })->name('livros.export');

    // 4. REVIEWS
    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // 5. CARRINHO, CHECKOUT & PEDIDOS
    Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrinho/add/{id}', [CartController::class, 'store'])->name('cart.add');
    Route::delete('/carrinho/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove');
    
    Route::post('/checkout/processar', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::delete('/pedidos/{order}', [CheckoutController::class, 'cancelOrder'])->name('orders.cancel');

    // ATUALIZADO: Muda para Pago ao voltar do Stripe
    Route::get('/meus-pedidos', function (Request $request) {
        if ($request->has('success') && $request->has('order_id')) {
            $order = Order::where('id', $request->order_id)
                          ->where('user_id', Auth::id())
                          ->first();
            if ($order) {
                $order->update(['status' => 'pago']);
            }
        }
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        return view('orders.index', compact('orders'));
    })->name('orders.my-orders');
});

// 6. ADMIN
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/reviews', [ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::patch('/admin/reviews/{review}/status', [ReviewController::class, 'updateStatus'])->name('admin.reviews.update');
    Route::patch('/requisicoes/{id}/entregar', [RequisicaoController::class, 'entregar'])->name('requisicoes.entregar');
    Route::get('/admin/orders', function() {
        $orders = Order::with('user')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    })->name('admin.orders.index');
});