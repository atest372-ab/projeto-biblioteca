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
                'maxResults' => 20,
                'key' => env('AIzaSyCZ0sI2Er5bl2kVtB88vQ1xSyB8rifftCA')
            ]);

            // Se a API responder com sucesso, guardamos os itens
            if ($response->successful()) {
                $books = $response->json()['items'] ?? [];
            } else {
                dd(
                    "Resposta do Google:", $response->json(),
                    "Status Code:", $response->status(),
                    "Chave Lida do .env:", env('GOOGLE_BOOKS_API_KEY')
                );
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
            dd("Erro na Gravação: " . $e->getMessage());
        }
    }
}