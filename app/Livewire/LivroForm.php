<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Livro;
use App\Models\Editora;
use App\Models\Autor;
use Illuminate\Support\Facades\Auth;

#[Title('Livros')]
class LivroForm extends Component
{
    use WithFileUploads;

    public $livroId;
    public $isbn;
    public $nome;
    public $editora_id;
    public $autoresSelecionados = [];
    public $bibliografia;
    public $imagem_capa;
    public $preco;
    public $isEditing = false;

    protected $rules = [
        'isbn' => 'required|string|max:20',
        'nome' => 'required|string|max:255',
        'editora_id' => 'required|exists:editoras,id',
        'autoresSelecionados' => 'required|array|min:1',
        'bibliografia' => 'required|string',
        'imagem_capa' => 'nullable|image|max:2048',
        'preco' => 'required|numeric|min:0',
    ];

    public function mount($livro = null)
    {
        // VERIFICAÇÃO DE ADMIN
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso reservado a administradores.');
        }

        // ... resto do código original
        if ($livro) {
            if (is_numeric($livro)) {
                $livroModel = Livro::with('autores')->find($livro);
            } else {
                $livroModel = $livro;
            }

            if ($livroModel) {
                $this->livroId = $livroModel->id;
                $this->isbn = $livroModel->isbn;
                $this->nome = $livroModel->nome;
                $this->editora_id = $livroModel->editora_id;
                $this->bibliografia = $livroModel->bibliografia;
                $this->preco = $livroModel->preco;
                $this->autoresSelecionados = $livroModel->autores->pluck('id')->toArray();
                $this->isEditing = true;
                $this->rules['isbn'] = 'required|string|max:20|unique:livros,isbn,' . $livroModel->id;
            } else {
                session()->flash('error', 'Livro não encontrado.');
                return $this->redirectRoute('livros.index', navigate: true);
            }
        } else {
            $this->rules['isbn'] = 'required|string|max:20|unique:livros,isbn';
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $livro = Livro::findOrFail($this->livroId);
        } else {
            $livro = new Livro();
        }

        $livro->fill([
            'isbn' => $this->isbn,
            'nome' => $this->nome,
            'editora_id' => $this->editora_id,
            'bibliografia' => $this->bibliografia,
            'preco' => $this->preco,
        ]);

        if ($this->imagem_capa) {
            // Deletar imagem antiga se existir
            if ($livro->imagem_capa && Storage::disk('public')->exists($livro->imagem_capa)) {
                Storage::disk('public')->delete($livro->imagem_capa);
            }
            $livro->imagem_capa = $this->imagem_capa->store('capas', 'public');
        }

        $livro->save();
        $livro->autores()->sync($this->autoresSelecionados);

        session()->flash('message', $this->isEditing ? 'Livro atualizado com sucesso!' : 'Livro criado com sucesso!');
        return $this->redirectRoute('livros.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.livro-form', [
            'editoras' => Editora::all(),
            'autores' => Autor::all(),
        ]);
    }
}
