<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    protected $fillable = ['isbn', 'name', 'bibliography', 'cover_image', 'price', 'publisher_id'];

    /**
     * REQUISITO 8: Dados cifrados na base de dados
     */
    protected function casts(): array
    {
        return [
            'isbn' => 'encrypted',
            'bibliography' => 'encrypted',
            'price' => 'decimal:2',
        ];
    }

    // Relação com a Editora
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    // Relação com Autores (muitos para muitos)
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }
}