<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use App\Models\User;
use App\Mail\AbandonedCartMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckAbandonedCarts extends Command
{
    // O nome que vais usar para correr o comando no terminal
    protected $signature = 'app:check-abandoned-carts';

    protected $description = 'Envia um email para utilizadores com itens no carrinho há mais de 1 hora';

    public function handle()
    {
        $oneHourAgo = Carbon::now()->subHour();

        $abandonedCarts = Cart::where('updated_at', '<=', $oneHourAgo)
            ->with('user')
            ->get()
            ->groupBy('user_id');

        if ($abandonedCarts->isEmpty()) {
            $this->info('Nenhum carrinho abandonado encontrado.');
            return;
        }

        foreach ($abandonedCarts as $userId => $items) {
            $user = $items->first()->user;

            if ($user && $user->email) {
                // Usar o envio direto para evitar o erro de RFC Compliance
                Mail::send('emails.abandoned-cart', ['user' => $user], function ($m) use ($user) {
                    $m->from('geral@inovcorp.pt', 'InovCorp Books');
                    $m->to($user->email);
                    $m->subject('Ainda tem livros no seu carrinho!');
                });

                $this->info("Email enviado para: {$user->email}");
            }
        }

        $this->info('Processo concluído.');
    }
}