<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; 
use App\Jobs\SendCartReminder;          

Artisan::command('inspire', function () {
    $this->comment(\Illuminate\Foundation\Inspiring::quote());
})->purpose('Display an inspiring quote');

// AGENDAMENTO DA FASE 5:
// Verifica a cada hora se existem carrinhos abandonados para notificar
Schedule::job(new SendCartReminder)->hourly();