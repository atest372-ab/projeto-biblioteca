<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbandonedCartMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    // Usamos o método antigo build() mas com uma sintaxe super rigorosa
    public function build()
    {
        return $this->view('emails.abandoned-cart')
                    ->from('geral@inovcorp.pt', 'InovCorp Books')
                    ->subject('Ainda tem livros no seu carrinho!');
    }
}