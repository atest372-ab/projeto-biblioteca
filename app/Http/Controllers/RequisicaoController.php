<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicao;
use App\Models\Book;
use App\Models\BookNotification;
use App\Models\AvailabilityAlert;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RequisicaoController extends Controller
{
    // Listagem de requisições
    public function index()
    {
        $user = Auth::user();

        // Se for admin, vê tudo. Se for cidadão, vê apenas as suas.
        if ($user->role === 'admin') {
            $requisicoes = Requisicao::with(['user', 'book'])->latest()->get();
        } else {
            $requisicoes = Requisicao::where('user_id', $user->id)->with('book')->latest()->get();
        }

        return view('requisicoes.index', compact('requisicoes'));
    }

    // Gravar a nova requisição
    public function store(Request $request, $id)
    {
        // Verificar se o livro já está ocupado (Double check de segurança)
        $ocupado = Requisicao::where('book_id', $id)->whereNull('data_rececao_real')->exists();

        if ($ocupado) {
            return redirect()->route('dashboard')->with('error', 'Este livro acabou de ser requisitado por outro utilizador.');
        }

        Requisicao::create([
            'user_id' => Auth::id(),
            'book_id' => $id,
            'data_requisicao' => now(),
            'data_entrega_prevista' => now()->addDays(15), // Prazo padrão de 15 dias
        ]);

        return redirect()->route('dashboard')->with('success', 'Livro requisitado com sucesso! Prazo: 15 dias.');
    }

    // Processar a devolução (Ação do Admin)
    public function entregar($id)
    {
        $requisicao = Requisicao::findOrFail($id);
        
        $requisicao->update([
            'data_rececao_real' => now()
        ]);

        // --- LÓGICA DE ALERTA (Desafio Extra) ---
        // Verificar se alguém pediu alerta para este livro
        $alertas = AvailabilityAlert::where('book_id', $requisicao->book_id)->get();
        
        foreach ($alertas as $alerta) {
            /** @var \App\Models\AvailabilityAlert $alerta */
            // Aqui enviaria um email ou notificação. 
            // Por agora, apenas deletamos o alerta pois o livro ficou livre.
            $alerta->delete();
        }

        return redirect()->back()->with('success', 'Livro recebido e sistema atualizado!');
    }
}