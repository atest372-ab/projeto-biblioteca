<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;

class GoogleBooksController extends Controller
{
    public function search(Request $request)
    {
        $books = [];
        $query = $request->input('search');

        if ($query) {
            $response = Http::withoutVerifying()->get("https://www.googleapis.com/books/v1/volumes", [
                'q' => $query,
                'maxResults' => 20,
                'key' => env('GOOGLE_BOOKS_API_KEY'),
            ]);

            if ($response->successful()) {
                $books = $response->json()['items'] ?? [];
            } else {
                // Em vez de dd(), apenas redirecionamos com um erro amigável
                return back()->with('error', 'Não foi possível conectar à API do Google Books.');
            }
        }

        return view('google-books.index', compact('books'));
    }

    public function import(Request $request)
    {
        try {
            \App\Models\Book::create([
                'name'          => $request->name,
                'isbn'          => $request->isbn,
                'cover_image'   => $request->cover_image,
                'bibliography'  => $request->bibliography,
                'price'         => rand(15, 49),
                'publisher_id'  => 1,
            ]);

            return redirect()->route('dashboard')->with('success', 'Livro importado com sucesso!');
        } catch (\Exception $e) {
            // Em vez de dd(), usamos o logger ou voltamos com erro
            return back()->with('error', 'Erro ao importar livro: ' . $e->getMessage());
        }
    }
}