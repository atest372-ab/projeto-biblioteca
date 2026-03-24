<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    // Campos que podem ser preenchidos (garante que os nomes batem com a BD)
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

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function requisicoes(): HasMany
    {
        return $this->hasMany(Requisicao::class);
    }

    public function estaDisponivel(): bool
    {
        return !$this->requisicoes()
                     ->whereNull('data_rececao_real')
                     ->exists();
    }
}