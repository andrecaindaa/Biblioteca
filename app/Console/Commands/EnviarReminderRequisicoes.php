<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Requisicao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequisicaoReminder;

class EnviarReminderRequisicoes extends Command
{
    protected $signature = 'requisicoes:reminder';

    protected $description = 'Enviar email de reminder para cidadãos com livros a entregar no dia seguinte';

    public function handle()
    {
        $amanha = Carbon::tomorrow()->startOfDay();

        $requisicoes = Requisicao::where('status', 'ativo')
            ->whereDate('data_prevista_entrega', $amanha)
            ->with('livro', 'user')
            ->get();

        if ($requisicoes->isEmpty()) {
            $this->info('Nenhuma requisição para reminder amanhã.');
            return 0;
        }

        foreach ($requisicoes as $requisicao) {
            Mail::to($requisicao->user->email)
                ->send(new RequisicaoReminder($requisicao));

            $this->info("Reminder enviado para: {$requisicao->user->email}");
        }

        return 0;
    }
}
