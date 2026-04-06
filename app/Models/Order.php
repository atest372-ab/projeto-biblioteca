<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // Atualizado para incluir a morada e o ID do Stripe
    protected $fillable = ['user_id', 'total', 'status', 'address', 'stripe_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}