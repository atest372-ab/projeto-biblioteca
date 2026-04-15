<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicao;
use App\Models\Book;
use App\Models\AvailabilityAlert;
use App\Models\Log; // Modelo de Logs importado
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RequisicaoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $requisicoes = Requisicao::with(['user', 'book'])->latest()->get();
        } else {
            $requisicoes = Requisicao::where('user_id', $user->id)->with('book')->latest()->get();
        }
        return view('requisicoes.index', compact('requisicoes'));
    }

    public function store(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        if ($book->stock <= 0) {
            return redirect()->route('dashboard')->with('error', 'Este livro não tem stock disponível.');
        }

        $ocupado = Requisicao::where('book_id', $id)->whereNull('data_rececao_real')->exists();
        if ($ocupado) {
            return redirect()->route('dashboard')->with('error', 'Este livro está ocupado.');
        }
        
        $requisicao = Requisicao::create([
            'user_id' => Auth::id(),
            'book_id' => $id,
            'numero_sequencial' => 'REQ-' . strtoupper(uniqid()),
            'data_inicio' => now(),
            'data_fim_prevista' => now()->addDays(15),
        ]);

        $book->decrement('stock');

        // --- GRAVAÇÃO DE LOG ---
        Log::create([
            'user_id' => Auth::id(),
            'modulo' => 'Requisições',
            'object_id' => $requisicao->id,
            'alteracao' => "Livro: {$book->title} | Stock atual: {$book->stock}",
            'ip' => $request->ip(),
            'browser' => $request->header('User-Agent'),
        ]);

        return redirect()->route('dashboard')->with('success', 'Livro requisitado com sucesso!');
    }

    public function entregar($id)
    {
        $requisicao = Requisicao::with('book')->findOrFail($id);
        
        $requisicao->update([
            'data_rececao_real' => now()
        ]);

        $requisicao->book->increment('stock');

        // --- GRAVAÇÃO DE LOG ---
        Log::create([
            'user_id' => Auth::id(),
            'modulo' => 'Devoluções',
            'object_id' => $requisicao->id,
            'alteracao' => "Livro '{$requisicao->book->title}' devolvido. Stock reposto.",
            'ip' => request()->ip(),
            'browser' => request()->header('User-Agent'),
        ]);

        // Lógica de Alertas
        $alertas = AvailabilityAlert::where('book_id', $requisicao->book_id)->get();
        foreach ($alertas as $alerta) {
            $alerta->delete();
        }

        return redirect()->back()->with('success', 'Livro recebido e sistema atualizado!');
    }
}