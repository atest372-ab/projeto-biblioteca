<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Log; // Importação necessária
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('book')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    public function store($id)
    {
        $book = Book::findOrFail($id);

        $exists = Cart::where('user_id', Auth::id())->where('book_id', $id)->exists();
        if ($exists) {
            return redirect()->back()->with('info', 'Este livro já está no seu carrinho!');
        }

        // 1. Atualiza a Sessão
        $cart = session()->get('cart', []);
        $cart[$id] = [
            "name" => $book->title,
            "quantity" => 1,
            "price" => 15.00
        ];
        session()->put('cart', $cart);

        // 2. Persistência na Base de Dados
        Cart::create([
            'user_id' => Auth::id(),
            'book_id' => $id
        ]);

        // --- FASE 6: REGISTO DE LOG ---
        Log::record('Carrinho', $id, "Utilizador adicionou o livro '{$book->title}' ao carrinho.");

        return redirect()->route('cart.index')->with('success', 'Livro adicionado!');
    }

    public function destroy($id)
    {
        // Encontra o item pelo ID da tabela 'carts'
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if ($cartItem) {
            // Carregamos o livro antes de eliminar para ter o título no Log
            $tituloLivro = $cartItem->book->title ?? 'ID: ' . $cartItem->book_id;

            // Remove da Sessão também usando o book_id
            $cart = session()->get('cart', []);
            if(isset($cart[$cartItem->book_id])) {
                unset($cart[$cartItem->book_id]);
                session()->put('cart', $cart);
            }

            // --- FASE 6: REGISTO DE LOG (Antes de eliminar) ---
            Log::record('Carrinho', $cartItem->book_id, "Utilizador removeu o livro '{$tituloLivro}' do carrinho.");

            // Remove da Base de Dados
            $cartItem->delete();
            return redirect()->back()->with('success', 'Item removido do carrinho.');
        }

        return redirect()->back()->with('error', 'Item não encontrado.');
    }
}