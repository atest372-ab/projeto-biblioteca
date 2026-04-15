<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Log extends Model
{
    use HasFactory;

    // Define os campos que podem ser preenchidos via Log::create()
    protected $fillable = [
        'user_id',
        'modulo',
        'objeto_id',
        'alteracao',
        'ip',
        'browser',
    ];

    /**
     * Helper centralizado para registar ações no sistema.
     * Pode ser chamado de qualquer lugar: Log::record('Livros', 1, 'Eliminou o livro');
     */
    public static function record($modulo, $objeto_id, $alteracao)
    {
        return self::create([
            'user_id'   => Auth::id(), // Fica null se o user não estiver logado
            'modulo'    => $modulo,
            'objeto_id' => $objeto_id,
            'alteracao' => $alteracao,
            'ip'        => Request::ip(),
            'browser'   => Request::header('User-Agent'),
        ]);
    }

    /**
     * Relacionamento para saber quem fez a ação (opcional, mas útil para o Admin)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}