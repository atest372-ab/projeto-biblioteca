<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        // 1. Ir buscar os itens do carrinho do utilizador logado
        $cartItems = Cart::where('user_id', Auth::id())->with('book')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'O seu carrinho está vazio!');
        }

        // 2. Calcular o total (usando os 15€ fixos ou o preço do livro se tiveres)
        $total = $cartItems->count() * 15;

        // 3. Criar a encomenda (Order)
        $order = Order::create([
            'user_id' => Auth::id(),
            'total'   => $total,
            'status'  => 'pendente', // Status inicial
        ]);

        // 4. Limpar o carrinho (Importante para a persistência)
        Cart::where('user_id', Auth::id())->delete();

        // 5. Redirecionar com mensagem de sucesso
        return redirect()->route('dashboard')->with('success', "Compra finalizada! Pedido #{$order->id} registado com sucesso.");
    }
}