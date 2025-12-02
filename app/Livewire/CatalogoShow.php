<?php

namespace App\Livewire;


use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Livro;
use App\Models\Requisicao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Review;

use App\Mail\RequisicaoCriada;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class CatalogoShow extends Component
{
    use WithFileUploads;

    public Livro $livro;
    public $foto_cidadao;
    public $confirmMessage = null;

    public $livroId;


    protected $rules = [
        'foto_cidadao' => 'nullable|image|max:2048',
    ];

    public function mount(Livro $livro)
    {
        // $this->livroId = is_numeric($livro) ? $livro : $livro->id;
        $this->livro = $livro->load(['autores', 'editora', 'requisicoes']);
    }

    public function requisitar()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // disponibilidade
        if (!$this->livro->isDisponivel()) {
            session()->flash('error', 'Este livro não está disponível para requisição.');
            return;
        }

        if ($user->isAdmin()) {
    session()->flash('error', 'Administradores não podem fazer requisições.');
    return;
}

        // limite 3
        $count = Requisicao::where('user_id', $user->id)
            ->where('status', 'ativo')
            ->count();

        if ($count >= 3) {
            session()->flash('error', 'Já tens 3 requisições ativas. Devolve algum antes de requisitar outro.');
            return;
        }

        $this->validate();

        $fotoPath = null;
        if ($this->foto_cidadao) {
            $fotoPath = $this->foto_cidadao->store('requisicoes', 'public');
        }

        $requisicao = Requisicao::create([
            'user_id' => $user->id,
            'livro_id' => $this->livro->id,
            'numero' => Requisicao::gerarNumeroSequencial(),
            'data_requisicao' => today(),
            'data_prevista_entrega' => today()->addDays(5),
            'foto_cidadao' => $fotoPath,
            'status' => 'ativo',
        ]);

        // reload relations and livro state
        $this->livro->load('requisicoes');

        session()->flash('success', "Requisição criada com sucesso! Nº {$requisicao->numero}");
        $this->foto_cidadao = null;

            // Enviar email para o cidadão
        Mail::to($user->email)->send(new RequisicaoCriada($requisicao));

        // Enviar email para todos os admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new RequisicaoCriada($requisicao));
}
    }

    public function render()
    {
        // carregar histórico (últimas 10)
        $historico = $this->livro->requisicoes()->with('user')->latest()->limit(10)->get();



        return view('livewire.catalogo-show', [
            'livro' => $this->livro,
            'historico' => $historico,
        ]);
    }
}
