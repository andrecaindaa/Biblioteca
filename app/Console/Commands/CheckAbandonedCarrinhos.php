<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Carrinho;
use App\Mail\AbandonoCarrinhoMail;
use Illuminate\Support\Facades\Mail;

class CheckAbandonedCarrinhos extends Command
{
    protected $signature = 'carrinhos:check-abandoned';
    protected $description = 'Envia e-mail para utilizadores com carrinhos abandonados há >= 1h';

    public function handle()
    {
        $carrinhos = Carrinho::whereHas('items')
            ->where('updated_at', '<=', now()->subHour())
            ->get();

        foreach ($carrinhos as $carrinho) {
            Mail::to($carrinho->user->email)->send(new AbandonoCarrinhoMail($carrinho));
            usleep(500000);
            $this->info("Notificado: {$carrinho->user->email}");
        }
    }
}
