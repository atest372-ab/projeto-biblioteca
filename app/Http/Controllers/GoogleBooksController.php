<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;
use Illuminate\Support\Facades\Crypt;

class GoogleBooksController extends Controller
{
    /**
     * 1. Pesquisa na API (Fase 3)
     */
    public function search(Request $request)
    {
        $books = [];
        
        // Captura o termo (seja 'search' ou 'q' para evitar bugs de nomes)
        $searchTerm = $request->input('search');

        if ($searchTerm) {
            // Chamada à API usando array de parâmetros (mais seguro para espaços e símbolos)
            $response = Http::get("https://www.googleapis.com/books/v1/volumes", [
                'q' => $searchTerm,
                'maxResults' => 12,
                'printType' => 'books'
            ]);
            
            if ($response->successful()) {
                $books = $response->json()['items'] ?? [];
            }
        }
        
        return view('google-books.index', compact('books'));
    }

    /**
     * 2. Grava no Banco (A "Bridge" entre Fase 3 e Fase 1)
     */
    public function import(Request $request)
    {
        // Criamos o livro com os dados escondidos enviados pela View
        Book::create([
            'name'          => $request->name, 
            // Cifragem obrigatória do ISBN (Requisito 8 - Fase 1)
            'isbn'          => Crypt::encryptString($request->isbn ?? '0000000000'),
            'cover_image'   => $request->cover_image,
            'bibliography'  => $request->bibliography,
            'price'         => rand(15, 49), 
        ]);

        return redirect()->route('dashboard')->with('success', 'Livro importado e protegido com sucesso!');
    }
}