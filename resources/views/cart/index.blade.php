<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Inovcorp - Meu Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen">

    {{-- NAVBAR --}}
    <div class="navbar bg-primary text-primary-content shadow-lg px-8">
        <div class="flex-1">
            <a href="{{ route('dashboard') }}" class="btn btn-ghost text-xl font-bold italic">INOVCORP LIB</a>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1 font-semibold items-center gap-4">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="bg-primary-focus rounded-lg">
                    <a href="{{ route('cart.index') }}">
                        <div class="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <span class="badge badge-sm badge-secondary indicator-item">{{ count($cartItems) }}</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="p-8 max-w-5xl mx-auto">
        <div class="flex justify-between items-end mb-8">
            <h1 class="text-4xl font-black text-gray-800">Meu Carrinho</h1>
            <span class="text-gray-500">{{ count($cartItems) }} itens selecionados</span>
        </div>

        @if(count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- LISTA DE ITENS --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                    <div class="card card-side bg-base-100 shadow-sm border border-base-300">
                        <figure class="w-32 bg-gray-100">
                            <img src="{{ $item->book->cover_image ?? 'https://via.placeholder.com/150' }}" class="object-cover h-full" />
                        </figure>
                        <div class="card-body">
                            <h2 class="card-title">{{ $item->book->title ?? $item->book->name }}</h2>
                            <p class="text-sm opacity-60">ISBN: {{ $item->book->isbn }}</p>
                            <div class="card-actions justify-between items-center mt-4">
                                <span class="text-xl font-bold text-primary">15.00€</span>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-ghost btn-sm text-error">Remover</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- RESUMO DO PEDIDO --}}
                <div class="card bg-base-100 shadow-xl h-fit border-t-4 border-primary">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Resumo</h2>
                        <div class="flex justify-between mb-2">
                            <span>Subtotal</span>
                            <span>{{ count($cartItems) * 15 }}.00€</span>
                        </div>
                        <div class="flex justify-between mb-2 text-success">
                            <span>Desconto</span>
                            <span>0.00€</span>
                        </div>
                        <div class="divider"></div>
                        <div class="flex justify-between text-2xl font-black mb-6">
                            <span>Total</span>
                            <span>{{ count($cartItems) * 15 }}.00€</span>
                        </div>

                        {{-- NOVO FORMULÁRIO COM MORADA --}}
                        <form action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold text-gray-700">Morada de Entrega</span>
                                </label>
                                <input type="text" name="address" placeholder="Rua, Nº, Código Postal, Cidade" 
                                       class="input input-bordered w-full focus:border-primary" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block text-white font-bold shadow-lg">
                                Finalizar Compra
                            </button>
                        </form>
                        
                        <p class="text-[10px] text-center mt-4 opacity-50 uppercase tracking-widest">Processamento Seguro Inovcorp</p>
                    </div>
                </div>
            </div>
        @else
            <div class="alert shadow-lg bg-base-100 border-l-4 border-info">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-info shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="font-bold">O seu carrinho está vazio!</h3>
                    <div class="text-xs">Explore a nossa biblioteca e adicione alguns exemplares.</div>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary">Ver Livros</a>
            </div>
        @endif
    </div>
</body>
</html>