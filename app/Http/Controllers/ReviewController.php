<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
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

        Review::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'comment' => $request->comment,
            'rating'  => $request->rating ?? 5,
            'status'  => 'suspenso',
        ]);

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
        return back()->with('success', 'Status da avaliação atualizado!');
    }

    // NOVO MÉTODO PARA ELIMINAR
    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Avaliação eliminada com sucesso!');
    }
}