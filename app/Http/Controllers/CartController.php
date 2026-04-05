<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Cart;
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

        // --- MELHORIA: Verificar se já existe no carrinho ---
        $exists = Cart::where('user_id', Auth::id())->where('book_id', $id)->exists();
        if ($exists) {
            return redirect()->back()->with('info', 'Este livro já está no seu carrinho!');
        }

        // 1. Atualiza a Sessão
        $cart = session()->get('cart', []);
        $cart[$id] = [
            "name" => $book->title ?? $book->name,
            "quantity" => 1,
            "price" => 15.00,
            "image" => $book->image_url ?? $book->image_link ?? ''
        ];
        session()->put('cart', $cart);

        // 2. Persistência na Base de Dados
        Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'book_id' => $id],
            ['updated_at' => now()]
        );

        return redirect()->route('cart.index')->with('success', 'Livro adicionado ao carrinho!');
    }

    public function destroy($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        Cart::where('user_id', Auth::id())->where('book_id', $id)->delete();

        return redirect()->back()->with('success', 'Item removido do carrinho.');
    }
}