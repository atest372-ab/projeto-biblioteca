<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Inovcorp - Histórico de Requisições</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen">

    <div class="navbar bg-primary text-primary-content shadow-lg px-4 md:px-8">
        <div class="flex-1">
            <a class="btn btn-ghost text-xl font-black italic tracking-tighter">INOVCORP LIB</a>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1 font-semibold">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('autores.index') }}">Autores</a></li>
                <li><a href="{{ route('editoras.index') }}">Editoras</a></li>
                <li><a href="{{ route('requisicoes.index') }}">Requisições</a></li>
            </ul>
        </div>
    </div>

    <div class="p-4 md:p-8 max-w-7xl mx-auto">
        
        <div class="stats shadow w-full mb-6 bg-base-100">
            <div class="stat">
                <div class="stat-title font-bold text-gray-500">Requisições Ativas</div>
                <div class="stat-value text-primary">{{ $ativas }}</div>
                <div class="stat-desc">Livros em circulação</div>
            </div>
            
            <div class="stat">
                <div class="stat-title font-bold text-gray-500">Últimos 30 dias</div>
                <div class="stat-value text-secondary">{{ $ultimos30 }}</div>
                <div class="stat-desc">Volume mensal</div>
            </div>
            
            <div class="stat">
                <div class="stat-title font-bold text-gray-500">Entregues Hoje</div>
                <div class="stat-value text-success">{{ $entreguesHoje }}</div>
                <div class="stat-desc">Processadas hoje</div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="card-title text-2xl font-bold text-gray-700">Histórico de Requisições</h2>
                    @if(session('success'))
                        <div class="alert alert-success py-2 w-auto">
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif
                </div>
                
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead class="bg-base-200 text-gray-600">
                            <tr>
                                <th>Nº Sequencial</th>
                                <th>Livro</th>
                                <th>Cidadão</th>
                                <th>Data Início</th>
                                <th>Data Fim (Prevista)</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requisicoes as $req)
                            <tr>
                                <td class="font-bold text-primary">{{ $req->numero_sequencial }}</td>
                                <td>{{ $req->book->nome ?? 'Livro Indisponível' }}</td>
                                <td>{{ $req->user->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($req->data_inicio)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($req->data_fim_prevista)->format('d/m/Y') }}</td>
                                <td>
                                    @if($req->data_rececao_real)
                                        <div class="badge badge-success gap-1">Entregue</div>
                                    @else
                                        <div class="badge badge-warning gap-1">Pendente</div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">Nenhuma requisição encontrada.</td>
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