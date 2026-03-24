<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BooksExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Puxamos os livros da base de dados
        return Book::all();
    }

    // Define o cabeçalho do Excel
    public function headings(): array
    {
        return [
            'ISBN',
            'Título do Livro',
            'Preço',
        ];
    }

    // Mapeia os dados para as colunas certas
    public function map($book): array
    {
        return [
            $book->isbn,
            $book->name,
            $book->price . '€',
        ];
    }
}