<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use App\Models\Log; // Importação do modelo Log
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'comment' => 'required|min:5',
            'rating'  => 'required|integer|between:1,5',
        ]);

        $review = Review::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'comment' => $request->comment,
            'rating'  => $request->rating ?? 5,
            'status'  => 'suspenso',
        ]);

        // Opcional: Logar quando um utilizador deixa uma review
        Log::record('Reviews', $review->id, "Utilizador enviou uma avaliação para o livro '{$book->title}'.");

        return back()->with('success', 'A sua avaliação foi enviada e aguarda aprovação!');
    }

    public function index()
    {
        $reviews = Review::with(['user', 'book'])->where('status', 'suspenso')->get();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:ativo,recusado'
        ]);

        $review->update(['status' => $request->status]);

        // --- FASE 6: REGISTO DE LOG ---
        $acao = $request->status == 'ativo' ? 'aprovou' : 'recusou';
        Log::record('Reviews', $review->id, "Admin {$acao} a avaliação do utilizador {$review->user->name}.");

        return back()->with('success', 'Status da avaliação atualizado!');
    }

    public function destroy(Review $review)
    {
        // Guarda-se os dados antes de apagar
        $autorReview = $review->user->name ?? 'Desconhecido';
        $reviewId = $review->id;

        // --- FASE 6: REGISTO DE LOG ---
        Log::record('Reviews', $reviewId, "Admin eliminou permanentemente a avaliação de {$autorReview}.");

        $review->delete();
        return back()->with('success', 'Avaliação eliminada com sucesso!');
    }
}