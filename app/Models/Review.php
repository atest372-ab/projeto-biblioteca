<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'book_id', 'comment', 'rating', 'status', 'rejection_reason'];

    // Uma Review pertence a um Utilizador (Cidadão)
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Uma Review pertence a um Livro
    public function book() {
        return $this->belongsTo(Book::class);
    }
}