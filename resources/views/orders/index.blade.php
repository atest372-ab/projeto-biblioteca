<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos - Inovcorp</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen">
    {{-- Navbar Simples --}}
    <div class="navbar bg-primary text-primary-content shadow-lg px-8">
        <div class="flex-1">
            <a href="{{ route('dashboard') }}" class="btn btn-ghost text-xl font-bold italic">INOVCORP LIB</a>
        </div>
    </div>

    <div class="p-8 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-black text-gray-800">Histórico de Pedidos</h1>
            <a href="{{ route('dashboard') }}" class="btn btn-ghost btn-sm">Voltar à Loja</a>
        </div>

        @if($orders->count() > 0)
            <div class="overflow-x-auto bg-base-100 rounded-xl shadow-sm border border-base-300">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th>ID do Pedido</th>
                            <th>Data</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="hover">
                            <td class="font-mono font-bold text-primary">#ORD-{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="font-bold">{{ number_format($order->total, 2) }}€</td>
                            <td>
                                <div class="badge {{ $order->status == 'pendente' ? 'badge-warning' : 'badge-success' }} gap-2">
                                    {{ ucfirst($order->status) }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card bg-base-100 shadow-xl border-2 border-dashed border-base-300">
                <div class="card-body items-center text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 opacity-20 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    <h2 class="card-title opacity-60">Ainda não tem encomendas.</h2>
                    <p class="opacity-50">Explore a biblioteca e adicione livros ao seu carrinho!</p>
                    <div class="card-actions mt-6">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Ver Catálogo</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>