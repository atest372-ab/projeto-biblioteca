@extends('layouts.app') {{-- Ou copie a estrutura do seu dashboard --}}

<div class="p-8 max-w-7xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Gestão de Requisições</h2>

    <div class="overflow-x-auto bg-base-100 shadow-xl rounded-box">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    @if(Auth::user()->role === 'admin') <th>Utilizador</th> @endif
                    <th>Livro</th>
                    <th>Data Pedido</th>
                    <th>Prazo Limite</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requisicoes as $req)
                <tr>
                    @if(Auth::user()->role === 'admin') <td>{{ $req->user->name }}</td> @endif
                    <td>{{ $req->book->title ?? $req->book->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($req->data_requisicao)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($req->data_entrega_prevista)->format('d/m/Y') }}</td>
                    <td>
                        @if($req->data_rececao_real)
                            <span class="badge badge-success text-white">Entregue</span>
                        @elseif(\Carbon\Carbon::parse($req->data_entrega_prevista)->isPast())
                            <span class="badge badge-error text-white">Atrasado</span>
                        @else
                            <span class="badge badge-info text-white">Em posse</span>
                        @endif
                    </td>
                    <td>
                        @if(!$req->data_rececao_real && Auth::user()->role === 'admin')
                            <form action="{{ route('requisicoes.entregar', $req->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-xs btn-outline btn-primary">Confirmar Receção</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>