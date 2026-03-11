<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    // --- RELAÇÕES EXISTENTES ---

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    // --- NOVIDADES FASE 2 ---

    /**
     * Relação com Requisições
     */
    public function requisicoes(): HasMany
    {
        return $this->hasMany(Requisicao::class);
    }

    /**
     * Verifica se o livro está disponível para requisição
     */
    public function estaDisponivel(): bool
    {
        // Um livro está disponível se não tiver nenhuma requisição 
        // onde a 'data_rececao_real' seja nula (ainda não foi devolvido)
        return !$this->requisicoes()
                     ->whereNull('data_rececao_real')
                     ->exists();
    }
}