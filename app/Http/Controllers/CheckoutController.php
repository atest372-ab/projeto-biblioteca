<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $request->validate(['address' => 'required|string|max:255']);
        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'O seu carrinho está vazio!');
        }

        $total = $cartItems->count() * 15;

        $order = Order::create([
            'user_id' => $userId,
            'total' => $total,
            'address' => $request->address,
            'status' => 'pendente',
        ]);

        $checkoutUrl = $this->createStripeSession($order, $total);
        
        // Limpa o carrinho após gerar o link de pagamento
        Cart::where('user_id', $userId)->delete();
        session()->forget('cart');

        return redirect($checkoutUrl);
    }

    public function cancelOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Não tem permissão para remover este pedido.');
        }

        $order->delete();
        return back()->with('success', 'Pedido removido com sucesso!');
    }

    private function createStripeSession($order, $total): string
    {
        Stripe::setApiKey(config('services.stripe.secret') ?? env('STRIPE_SECRET'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => "Encomenda #{$order->id}"],
                    'unit_amount' => (int)($total * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            // O segredo está aqui: passamos o order_id para validar no retorno
            'success_url' => route('orders.my-orders') . '?success=true&order_id=' . $order->id,
            'cancel_url' => route('dashboard') . '?cancel=true',
        ]);

        return $session->url;
    }
}