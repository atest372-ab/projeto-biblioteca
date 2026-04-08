<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Minhas Requisições - INOVCORP</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen">

    <div class="navbar bg-primary text-primary-content shadow-lg px-8">
        <a href="{{ route('dashboard') }}" class="btn btn-ghost text-xl font-black italic">INOVCORP LIB</a>
    </div>

    <div class="p-8 max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-black text-gray-800">Histórico de Requisições</h1>
            <a href="{{ route('dashboard') }}" class="btn btn-ghost">Voltar ao Catálogo</a>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="text-gray-700">
                                <th>Livro</th>
                                <th>Data Requisição</th>
                                <th>Data Entrega Prevista</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requisicoes as $req)
                                <tr>
                                    <td class="font-bold text-primary">
                                        {{ $req->book->title ?? $req->book->name ?? 'Livro Indisponível' }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($req->data_requisicao)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($req->data_entrega_prevista)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($req->data_rececao_real)
                                            <span class="badge badge-success text-white">Entregue</span>
                                        @else
                                            <span class="badge badge-warning text-white">Em Leitura</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-10 opacity-50 italic">
                                        Ainda não realizou nenhuma requisição.
                                    </td>
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