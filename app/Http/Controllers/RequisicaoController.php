<?php

namespace App\Http\Controllers;

use App\Models\Requisicao;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequisicaoController extends Controller
{
    /**
     * Exibe a listagem de requisições com os indicadores (Dashboard)
     */
    public function index()
    {
        // 1. Cálculos para os indicadores do topo
        $ativas = Requisicao::whereNull('data_rececao_real')->count();
        $ultimos30 = Requisicao::where('created_at', '>=', now()->subDays(30))->count();
        $entreguesHoje = Requisicao::whereDate('updated_at', now())->whereNotNull('data_rececao_real')->count();

        // 2. Filtro de segurança: Admin vê tudo, Cidadão vê apenas as suas
        if (Auth::user()->role === 'admin') {
            $requisicoes = Requisicao::with(['user', 'book'])->latest()->get();
        } else {
            $requisicoes = Requisicao::with(['user', 'book'])
                            ->where('user_id', Auth::id())
                            ->latest()
                            ->get();
        }

        return view('requisicoes', compact('ativas', 'ultimos30', 'entreguesHoje', 'requisicoes'));
    }

    /**
     * Processa a criação de uma nova requisição
     */
    public function store(Request $request, $id) 
    {
        $user = Auth::user();
        $book = Book::findOrFail($id);

        // 1. Validação: O cidadão já tem 3 livros pendentes?
        $totalAtivasUser = Requisicao::where('user_id', $user->id)
                            ->whereNull('data_rececao_real')
                            ->count();

        if ($totalAtivasUser >= 3) {
            return back()->with('error', 'Limite atingido! Já tens 3 livros requisitados.');
        }

        // 2. Processar a Foto
        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('requisicoes', 'public');
        }

        // 3. Criar a Requisição (O Model trata do número sequencial e datas via booted)
        Requisicao::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'foto_cidadao' => $path,
        ]);

        return redirect()->route('requisicoes.index')->with('success', 'Requisição realizada com sucesso!');
    }
}