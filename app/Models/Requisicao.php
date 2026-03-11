<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requisicao extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'book_id', 'numero_sequencial', 
        'data_inicio', 'data_fim_prevista', 'foto_cidadao'
    ];

    protected static function booted()
    {
        static::creating(function ($requisicao) {
            // Gera o número sequencial automaticamente: REQ-2026-0001
            $ultimoId = static::max('id') ?? 0;
            $requisicao->numero_sequencial = 'REQ-' . date('Y') . '-' . str_pad($ultimoId + 1, 4, '0', STR_PAD_LEFT);
            
            // Define as datas automáticas
            $requisicao->data_inicio = now();
            $requisicao->data_fim_prevista = now()->addDays(5);
        });
    }

    // Relacionamentos
    public function user() { return $this->belongsTo(User::class); }
    public function book() { return $this->belongsTo(Book::class); }
}