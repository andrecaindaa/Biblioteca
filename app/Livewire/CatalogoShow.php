<?php

namespace App\Livewire;

use App\Services\RelatedBooksService;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Livro;
use App\Models\Requisicao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Review;
use App\Models\AlertaLivro;

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
    protected RelatedBooksService $relatedService;

    public function boot(RelatedBooksService $relatedService)
    {
        // Livewire chama boot() automaticamente após injeção
        $this->relatedService = $relatedService;
    }
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

            // obter relacionados (top 5)
            $related = $this->relatedService->getRelated($this->livro, 5);

        // carregar histórico (últimas 10)
        $historico = $this->livro->requisicoes()
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        // carregar reviews ativas
                $reviews = Review::where('livro_id', $this->livro->id)
                    ->where('status', 'ativo')
                    ->with('user')
                    ->latest()
                    ->get();

                return view('livewire.catalogo-show', [
                    'livro' => $this->livro,
                    'historico' => $historico,
                    'reviews' => \App\Models\Review::where('livro_id', $this->livro->id)
                                ->where('status','ativo')->with('user')->latest()->get(),
                'relatedBooks' => $related,
        ]);
    }

    public function ativarAlerta()
    {
        $user = Auth::user();

        if (!$user || $user->isAdmin()) {
            session()->flash('alerta_success', 'Apenas cidadãos podem usar este alerta.');
            return;
        }

        // Verifica se já existe alerta pendente
        $existe = AlertaLivro::where('livro_id', $this->livro->id)
            ->where('user_id', $user->id)
            ->whereNull('notificado_em')
            ->exists();

        if ($existe) {
            session()->flash('alerta_success', 'Já será avisado quando o livro estiver disponível 📬');
            return;
        }

        AlertaLivro::create([
            'livro_id' => $this->livro->id,
            'user_id'   => $user->id,
        ]);

        session()->flash('alerta_success', 'Receberá um email quando o livro estiver disponível 📬');
    }
}
