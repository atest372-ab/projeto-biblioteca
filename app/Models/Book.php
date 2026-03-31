<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = ['name', 'isbn', 'bibliography', 'cover_image', 'price', 'publisher_id'];

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

    // RELAÇÃO CORRIGIDA: Apenas a ligação, sem filtros fixos
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(AvailabilityAlert::class);
    }
}