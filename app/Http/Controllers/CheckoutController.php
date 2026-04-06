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
        // 1. Validar se a morada foi preenchida
        $request->validate([
            'address' => 'required|string|max:255',
        ]);

        // 2. Ir buscar os itens do carrinho do utilizador logado
        $cartItems = Cart::where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'O seu carrinho está vazio!');
        }

        // 3. Calcular o total (15€ fixos por item)
        $total = $cartItems->count() * 15;

        // 4. Criar a encomenda (Order) incluindo a morada
        $order = Order::create([
            'user_id' => Auth::id(),
            'total'   => $total,
            'address' => $request->address, // <--- Aqui gravamos a morada vinda do formulário
            'status'  => 'pendente',
        ]);

        // 5. Limpar o carrinho da Base de Dados e da Sessão
        Cart::where('user_id', Auth::id())->delete();
        session()->forget('cart');

        // 6. Redirecionar para o histórico de pedidos
        return redirect()->route('orders.my-orders')->with('success', "Pedido #{$order->id} registado! Proceda ao pagamento.");
    }
}