<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailabilityAlert extends Model
{
    protected $fillable = ['user_id', 'book_id', 'is_notified'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function book() {
        return $this->belongsTo(Book::class);
    }
}