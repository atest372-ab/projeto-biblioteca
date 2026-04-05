<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookNotification;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    public function store(Request $request, $bookId)
    {
        BookNotification::create([
            'user_id' => Auth::id(),
            'book_id' => $bookId,
            'is_notified' => false
        ]);

        return back()->with('success', 'Alerta registado! Enviaremos um e-mail assim que o livro estiver disponível.');
    }
}