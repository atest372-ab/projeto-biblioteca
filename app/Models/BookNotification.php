<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookNotification extends Model
{
    // Se a sua tabela no banco se chamar 'book_notifications', o Laravel já identifica.
    // Caso contrário, defina: protected $table = 'nome_da_tabela';

    protected $fillable = ['user_id', 'book_id', 'is_notified'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}