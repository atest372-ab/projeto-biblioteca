<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inovcorp - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen">

    <div class="navbar bg-primary text-primary-content shadow-lg px-4 md:px-8">
        <div class="flex-1 gap-4">
            <a href="{{ route('dashboard') }}" class="btn btn-ghost text-xl font-black italic tracking-tighter">INOVCORP LIB</a>
            <form action="{{ route('dashboard') }}" method="GET" class="hidden md:block relative">
                <input type="text" name="search" placeholder="Pesquisar..." class="input input-bordered w-64 h-10 text-base-content" value="{{ request('search') }}">
            </form>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1 font-semibold items-center gap-2">
                <li>
                    <a href="{{ route('cart.index') }}" class="bg-primary-focus hover:bg-secondary">
                        <div class="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <span class="badge badge-sm badge-secondary indicator-item">{{ count(session('cart', [])) }}</span>
                        </div>
                    </a>
                </li>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('orders.my-orders') }}">Meus Pedidos</a></li>

                @if(Auth::user()->role === 'admin') 
                    <li><a href="{{ route('google.search') }}" class="bg-accent text-accent-content font-bold">Google API</a></li> 
                    <li><a href="/admin/reviews" class="bg-warning text-warning-content font-bold">Moderar Reviews</a></li>
                    <li><a href="{{ route('admin.logs.index') }}" class="bg-slate-700 text-white font-bold">Logs</a></li>
                @endif
                
                <li><a href="{{ route('autores.index') }}">Autores</a></li>
                <li><a href="{{ route('editoras.index') }}">Editores</a></li>
                <li><a href="{{ route('requisicoes.index') }}">Requisições</a></li>
            </ul>
        </div>
    </div>

    <div class="p-4 md:p-8 max-w-7xl mx-auto">
        
        <div class="mb-8 animate-in fade-in duration-700">
            <h1 class="text-3xl font-black text-gray-800">Olá, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-gray-600 mt-1 italic">
                Bem-vindo à tua biblioteca. 
                @if(Auth::user()->role === 'admin')
                    Tens <span class="font-bold text-primary">{{ \App\Models\Review::where('status', 'SUSPENSO')->count() }}</span> avaliações a aguardar moderação.
                @else
                    Tens <span class="font-bold text-secondary">{{ \App\Models\Order::where('user_id', Auth::id())->count() }}</span> encomendas registadas na tua conta.
                @endif
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stats shadow bg-blue-600 text-white"><div class="stat"><div class="stat-title text-blue-100 uppercase text-xs font-bold">Requisições Ativas</div><div class="stat-value">{{ \App\Models\Requisicao::whereNull('data_rececao_real')->count() }}</div></div></div>
            <div class="stats shadow bg-purple-600 text-white"><div class="stat"><div class="stat-title text-purple-100 uppercase text-xs font-bold">Últimos 30 dias</div><div class="stat-value">{{ \App\Models\Requisicao::where('created_at', '>=', now()->subDays(30))->count() }}</div></div></div>
            <div class="stats shadow bg-emerald-600 text-white"><div class="stat"><div class="stat-title text-emerald-100 uppercase text-xs font-bold">Entregues Hoje</div><div class="stat-value">{{ \App\Models\Requisicao::whereNotNull('data_rececao_real')->whereDate('updated_at', now())->count() }}</div></div></div>
            
            <div class="stats shadow bg-orange-500 text-white">
                <div class="stat">
                    <div class="stat-title text-orange-100 uppercase text-xs font-bold">Reviews Pendentes</div>
                    <div class="stat-value">{{ \App\Models\Review::where('status', 'SUSPENSO')->count() }}</div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                @if(session('success')) <div class="alert alert-success mb-4 text-white font-bold">{{ session('success') }}</div> @endif
                @if(session('error')) <div class="alert alert-error mb-4 text-white font-bold">{{ session('error') }}</div> @endif
                @if(session('info')) <div class="alert alert-info mb-4 text-white font-bold">{{ session('info') }}</div> @endif
                
                <div class="flex justify-between items-center mb-4">
                    <h2 class="card-title text-2xl font-bold text-gray-700">Catálogo de Livros</h2>
                    <a href="{{ route('livros.export') }}" class="btn btn-success btn-sm text-white">Exportar Excel</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>ISBN</th>
                                <th>Título</th>
                                <th>Stock</th> <th>Estado</th>
                                <th class="text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($livros as $livro)
                            @php 
                                $estaRequisitadoPeloUser = \App\Models\Requisicao::where('book_id', $livro->id)
                                                            ->where('user_id', Auth::id())
                                                            ->whereNull('data_rececao_real')
                                                            ->exists(); 
                            @endphp
                            <tr>
                                <td><code class="text-xs text-blue-600">{{ $livro->isbn }}</code></td>
                                <td class="font-bold">
                                    <a href="{{ route('livros.show', $livro->id) }}" class="text-primary hover:underline">
                                        {{ $livro->title ?? $livro->name }}
                                    </a>
                                </td>
                                
                                {{-- VISUALIZAÇÃO DO STOCK --}}
                                <td>
                                    <span class="font-mono font-bold {{ $livro->stock <= 0 ? 'text-error' : 'text-success' }}">
                                        {{ $livro->stock }} un.
                                    </span>
                                </td>

                                <td>
                                    @if($livro->stock > 0)
                                        <span class="badge badge-success text-white text-xs">Disponível</span>
                                    @else
                                        <span class="badge badge-error text-white text-xs">Sem Stock</span>
                                    @endif
                                </td>

                                <td class="text-right flex justify-end gap-2 items-center">
                                    {{-- COMPRAR --}}
                                    <form action="{{ route('cart.add', $livro->id) }}" method="POST">
                                        @csrf 
                                        <button type="submit" class="btn btn-secondary btn-xs">🛒 Comprar</button>
                                    </form>

                                    {{-- REQUISITAR --}}
                                    @if($estaRequisitadoPeloUser)
                                        <span class="text-xs italic text-gray-400">Já requisitado</span>
                                    @elseif($livro->stock > 0)
                                        <a href="{{ route('livro.requisitar', $livro->id) }}" class="btn btn-primary btn-xs text-white">Requisitar</a> 
                                    @else
                                        <button class="btn btn-ghost btn-xs text-error opacity-50 cursor-not-allowed" disabled>Esgotado</button>
                                    @endif

                                    {{-- ELIMINAR (Admin) --}}
                                    @if(Auth::user()->role === 'admin')
                                        <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" onsubmit="return confirm('Apagar este livro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-error btn-xs text-white">🗑️</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>