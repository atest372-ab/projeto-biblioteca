<?php

namespace App\Http\Controllers;

use App\Models\Requisicao;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequisicaoController extends Controller
{
    public function store(Request $request, $bookId)
    {
        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        // 1. Validação: O cidadão já tem 3 livros?
        $ativas = Requisicao::where('user_id', $user->id)
                            ->whereNull('data_rececao_real')
                            ->count();

        if ($ativas >= 3) {
            return back()->with('error', 'Limite atingido! Já tens 3 livros requisitados.');
        }

        // 2. Validação: O livro está disponível?
        if (!$book->estaDisponivel()) {
            return back()->with('error', 'Este livro já se encontra requisitado.');
        }

        // 3. Processar a Foto
        $path = null;
        if ($request->hasFile('foto_cidadao')) {
            $path = $request->file('foto_cidadao')->store('requisicoes', 'public');
        }

        // 4. Criar a Requisição (A numeração REQ-2026 é gerada no Model automaticamente)
        Requisicao::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'foto_cidadao' => $path,
            'data_inicio' => now(),
            'data_fim_prevista' => now()->addDays(5),
        ]);

        return redirect()->route('dashboard')->with('success', 'Requisição realizada com sucesso!');
    }
}