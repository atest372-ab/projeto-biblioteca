<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inovcorp - Sistema Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen">

    <div class="navbar bg-primary text-primary-content shadow-lg px-4 md:px-8">
        <div class="flex-1">
            <a class="btn btn-ghost text-xl font-black italic tracking-tighter">INOVCORP LIB</a>
        </div>
        <div class="flex-none text-sm opacity-70 mr-4">
            Perfil: <span class="badge badge-secondary badge-outline uppercase">{{ Auth::user()->role }}</span>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1 font-semibold">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('livros.index') }}">Livros</a></li>
                <li><a href="{{ route('autores.index') }}">Autores</a></li>
                <li><a href="{{ route('editoras.index') }}">Editores</a></li>
                <li><a href="{{ route('requisicoes.index') }}">Requisições</a></li>
            </ul>
        </div>
    </div>

    <div class="p-4 md:p-8 max-w-7xl mx-auto">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stats shadow bg-blue-600 text-white">
                <div class="stat">
                    <div class="stat-title text-blue-100 uppercase text-xs font-bold">Requisições Ativas</div>
                    <div class="stat-value">{{ \App\Models\Requisicao::whereNull('data_rececao_real')->count() }}</div>
                </div>
            </div>
            
            <div class="stats shadow bg-purple-600 text-white">
                <div class="stat">
                    <div class="stat-title text-purple-100 uppercase text-xs font-bold">Últimos 30 dias</div>
                    <div class="stat-value">{{ \App\Models\Requisicao::where('created_at', '>=', now()->subDays(30))->count() }}</div>
                </div>
            </div>

            <div class="stats shadow bg-emerald-600 text-white">
                <div class="stat">
                    <div class="stat-title text-emerald-100 uppercase text-xs font-bold">Entregues Hoje</div>
                    <div class="stat-value">{{ \App\Models\Requisicao::whereDate('data_rececao_real', now())->count() }}</div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success mb-4 text-white font-bold">{{ session('success') }}</div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <h2 class="card-title text-2xl font-bold text-gray-700">Gestão de Editoras</h2>
                    @if(Auth::user()->role === 'admin')
                        <button class="btn btn-primary btn-sm">+ Nova Editora</button>
                    @endif
                </div>
                
                <div class="divider"></div>
                
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead class="bg-base-200">
                            <tr>
                                <th>Nome da Editora</th>
                                <th>Localização</th>
                                <th class="text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-bold">Publicações Europa-América</td>
                                <td>Portugal</td>
                                <td class="text-right">
                                    <button class="btn btn-ghost btn-xs">Contactar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>