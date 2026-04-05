<?php

namespace App\Jobs;

use App\Mail\CartReminderMail;
use App\Models\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendCartReminder implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Buscar carrinhos abandonados (não completados e criados há mais de 1 hora)
        $abandonedCarts = Cart::with(['user', 'book.authors'])
            ->where('completed', false)
            ->where('created_at', '<', now()->subHour())
            ->get();

        // Agrupar por usuário
        $cartsByUser = $abandonedCarts->groupBy('user_id');

        foreach ($cartsByUser as $userId => $carts) {
            $user = $carts->first()->user;

            // Enviar email de lembrete
            Mail::to($user->email)->send(new CartReminderMail($user, $carts));
        }
    }
}
