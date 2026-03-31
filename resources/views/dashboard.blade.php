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
        <div class="flex-1 gap-4">
            <a href="{{ route('dashboard') }}" class="btn btn-ghost text-xl font-black italic tracking-tighter">INOVCORP LIB</a>
            
            <div class="form-control hidden md:block">
                <form action="{{ route('dashboard') }}" method="GET" class="relative">
                    <input type="text" name="search" placeholder="Pesquisar livro..." 
                           class="input input-bordered w-64 h-10 text-base-content pr-10" 
                           value="{{ request('search') }}">
                    <button type="submit" class="absolute right-3 top-2.5 text-gray-500 hover:text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-none text-sm opacity-70 mr-4">
            Perfil: <span class="badge badge-secondary badge-outline uppercase">{{ Auth::user()->role }}</span>
        </div>

        <div class="flex-none">
            <ul class="menu menu-horizontal px-1 font-semibold items-center">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                
                @if(Auth::user()->role === 'admin')
                    <li><a href="{{ route('google.search') }}" class="bg-secondary text-white rounded-lg mx-1 hover:bg-secondary-focus">Pesquisar Google API</a></li>
                @endif
                
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
                    
                    <div class="flex flex-wrap gap-2 justify-center">
                        {{-- Botão de Exportar (Visível para todos) --}}
                        <a href="{{ route('livros.export') }}" class="btn btn-success btn-sm text-white shadow-sm">
                            Exportar Excel
                        </a>

                        {{-- Botões Exclusivos do ADMIN --}}
                        @if(Auth::user()->role === 'admin')
                            {{-- Botão de Moderação com Contador --}}
                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-warning btn-sm shadow-sm text-white">
                                Moderador de Reviews
                                @if(isset($pendentes) && $pendentes > 0)
                                    <span class="badge badge-error badge-xs ml-1 text-white border-none">+{{ $pendentes }}</span>
                                @endif
                            </a>

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
                                    <td class="font-bold">
                                        <a href="{{ route('livros.show', $livro->id) }}" class="text-primary hover:link flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ $livro->title ?? $livro->name }}
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $estaRequisitado = \App\Models\Requisicao::where('book_id', $livro->id)
                                                                                ->whereNull('data_rececao_real')
                                                                                ->exists();
                                        @endphp
                                        
                                        @if($estaRequisitado)
                                            <div class="badge badge-error text-white text-xs">Indisponível</div>
                                        @else
                                            <div class="badge badge-success text-white text-xs">Disponível</div>
                                        @endif
                                    </td>
                                    <td class="text-right flex justify-end gap-2">
                                        @if(!$estaRequisitado)
                                            <a href="{{ route('livro.requisitar', $livro->id) }}" class="btn btn-primary btn-xs text-white">Requisitar</a>
                                        @else
                                            <button class="btn btn-disabled btn-xs" disabled>Indisponível</button>
                                        @endif

                                        @if(Auth::user()->role === 'admin')
                                            <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" onsubmit="return confirm('Eliminar este livro permanentemente?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error btn-xs text-white">Eliminar</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 opacity-50">Nenhum livro cadastrado ou encontrado.</td>
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