<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;
use Illuminate\Support\Facades\Crypt;

class GoogleBooksController extends Controller
{
    public function search(Request $request)
    {
        $books = [];
        // Capturamos 'search' que é o que está na tua URL
        $query = $request->input('search');

        if ($query) {
            // .withoutVerifying() ignora erros de certificado SSL comuns no Windows/Herd
            $response = Http::withoutVerifying()->get("https://www.googleapis.com/books/v1/volumes", [
                'q' => $query,
                'maxResults' => 20
            ]);

            // Se a API responder com sucesso, guardamos os itens
            if ($response->successful()) {
                $books = $response->json()['items'] ?? [];
            } else {
                // Se der erro (ex: internet ou bloqueio), podes ver aqui
                logger("Erro na API: " . $response->status());
            }
        }

        return view('google-books.index', compact('books'));
    }

    public function import(Request $request)
    {
        Book::create([
            'title'         => $request->name ?? $request->title ?? 'Sem Título', 
            'isbn'          => Crypt::encryptString($request->isbn ?? '0000000000'),
            'cover_image'   => $request->cover_image,
            'bibliography'  => $request->bibliography,
            'price'         => rand(15, 49), 
        ]);

        return redirect()->route('dashboard')->with('success', 'Livro importado com sucesso!');
    }
}