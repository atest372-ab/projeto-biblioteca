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
                return back()->with('error', 'Não foi possível conectar à API do Google Books.');
            }
        }

        return view('google-books.index', compact('books'));
    }

    // DESAFIO 2: Detalhe do Livro com "Inteligência" de Relacionados
    public function show(Book $book)
    {
        // Lógica: Pegar a primeira palavra com mais de 3 letras da descrição para buscar similares
        $words = explode(' ', $book->bibliography);
        $searchTerm = collect($words)->first(fn($word) => strlen($word) > 3) ?? $book->name;

        $relatedBooks = Book::where('id', '!=', $book->id)
            ->where(function($q) use ($searchTerm) {
                $q->where('bibliography', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('title', 'LIKE', "%{$searchTerm}%");
            })
            ->limit(4)
            ->get();

        return view('livros.show', [
            'book' => $book,
            'relatedBooks' => $relatedBooks,
            'reviews' => $book->reviews()->where('status', 'APROVADO')->get()
        ]);
    }

    public function import(Request $request)
    {
        try {
            \App\Models\Publisher::firstOrCreate(['id' => 1], ['name' => 'Editora Geral']);
            $autor = \App\Models\Author::firstOrCreate(['id' => 1], ['name' => 'Autor Desconhecido']);

            $book = Book::create([
                'title'          => $request->name,
                'isbn'          => $request->isbn,
                'cover_image'   => $request->cover_image,
                'bibliography'  => $request->bibliography,
                'price'         => rand(15, 49),
                'publisher_id'  => 1,
            ]);

            // Associar ao autor padrão (ID 1) para não dar erro na Dashboard
            $book->authors()->attach($autor->id);

            return redirect()->route('dashboard')->with('success', 'Livro importado com sucesso!');
        
        } catch (\Exception $e) {
            // Em vez de dd, redirecionamentos com o erro para a interface
            return back()->with('error', 'Erro na gravação: ' . $e->getMessage());
        }
    }
}