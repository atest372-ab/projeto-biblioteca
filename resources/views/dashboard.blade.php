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
                <li><a href="{{ route('google.search') }}" class="bg-secondary text-white rounded-lg mx-1">Pesquisar Google API</a></li> 
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
                    <div class="alert alert-success mb-4 text-white font-bold shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="C9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
                    <h2 class="card-title text-2xl font-bold text-gray-700">Catálogo de Livros</h2>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('livros.export') }}" class="btn btn-success btn-sm text-white shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/css" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Exportar Excel
                        </a>

                        @if(Auth::user()->role === 'admin')
                            <button class="btn btn-primary btn-sm shadow-sm">+ Novo Livro</button>
                        @endif
                    </div>
                </div>
                
                <div class="divider"></div>
                
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead class="bg-base-200">
                            <tr>
                                <th>ISBN (Cifrado)</th>
                                <th>Título</th>
                                <th>Estado</th>
                                <th class="text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($livros as $livro)
                                <tr>
                                    <td>
                                        <code class="text-xs text-blue-600 italic">
                                            {{ Str::limit($livro->isbn, 15) }}...
                                        </code>
                                    </td>
                                    <td class="font-bold">{{ $livro->name }}</td>
                                    <td>
                                        @php
                                            $estaRequisitado = \App\Models\Requisicao::where('book_id', $livro->id)
                                                                ->whereNull('data_rececao_real')
                                                                ->exists();
                                        @endphp
                                        
                                        @if($estaRequisitado)
                                            <div class="badge badge-error text-white">Indisponível</div>
                                        @else
                                            <div class="badge badge-success text-white">Disponível</div>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if(!$estaRequisitado)
                                            <a href="{{ route('livro.requisitar', $livro->id) }}" class="btn btn-primary btn-xs">Requisitar</a>
                                        @else
                                            <button class="btn btn-disabled btn-xs" disabled>Indisponível</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 opacity-50">Nenhum livro cadastrado no sistema.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>