<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inovcorp - Autores</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen">

    {{-- NAVBAR PADRONIZADA COM CARRINHO --}}
    <div class="navbar bg-primary text-primary-content shadow-lg px-4 md:px-8">
        <div class="flex-1">
            <a href="{{ route('dashboard') }}" class="btn btn-ghost text-xl font-black italic tracking-tighter">INOVCORP LIB</a>
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
                <li><a href="{{ route('autores.index') }}" class="active">Autores</a></li>
                <li><a href="{{ route('editoras.index') }}">Editores</a></li>
                <li><a href="{{ route('requisicoes.index') }}">Requisições</a></li>
            </ul>
        </div>
    </div>

    <div class="p-4 md:p-8 max-w-7xl mx-auto">
        {{-- CARDS DE ESTATÍSTICAS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stats shadow bg-blue-600 text-white"><div class="stat"><div class="stat-title text-blue-100 uppercase text-xs font-bold">Requisições Ativas</div><div class="stat-value">{{ \App\Models\Requisicao::whereNull('data_rececao_real')->count() }}</div></div></div>
            <div class="stats shadow bg-purple-600 text-white"><div class="stat"><div class="stat-title text-purple-100 uppercase text-xs font-bold">Últimos 30 dias</div><div class="stat-value">{{ \App\Models\Requisicao::where('created_at', '>=', now()->subDays(30))->count() }}</div></div></div>
            <div class="stats shadow bg-emerald-600 text-white"><div class="stat"><div class="stat-title text-emerald-100 uppercase text-xs font-bold">Entregues Hoje</div><div class="stat-value">{{ \App\Models\Requisicao::whereDate('data_rececao_real', now())->count() }}</div></div></div>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="card-title text-2xl font-bold text-gray-700">Gestão de Autores</h2>
                    @if(Auth::user()->role === 'admin')
                        <button class="btn btn-primary btn-sm">+ Novo Autor</button>
                    @endif
                </div>
                <div class="divider"></div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead class="bg-base-200">
                            <tr><th>Nome do Autor</th><th>Nacionalidade</th><th class="text-right">Ação</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-bold">J.R.R. Tolkien</td>
                                <td>Reino Unido</td>
                                <td class="text-right"><button class="btn btn-ghost btn-xs">Ver Obras</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>