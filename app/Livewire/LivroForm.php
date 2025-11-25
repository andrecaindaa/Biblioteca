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

    protected function rules()
{
    return [
        'isbn' => 'required|string|max:20|unique:livros,isbn' . ($this->livroId ? ',' . $this->livroId : ''),
        'nome' => 'required|string|max:255',
        'editora_id' => 'required|exists:editoras,id',
        'autoresSelecionados' => 'required|array|min:1',
        'bibliografia' => 'required|string',
        'imagem_capa' => 'nullable|image|max:2048',
        'preco' => 'required|numeric|min:0',
    ];
}

protected $messages = [
    'isbn.unique' => 'Este ISBN já existe no sistema.',
];



    public function mount($livro = null)
{
    // VERIFICAÇÃO DE ADMIN
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Acesso reservado a administradores.');
    }

    if ($livro) {
        $livroModel = is_numeric($livro)
            ? Livro::with('autores')->find($livro)
            : $livro;

        if ($livroModel) {
            $this->livroId = $livroModel->id;
            $this->isbn = $livroModel->isbn;
            $this->nome = $livroModel->nome;
            $this->editora_id = $livroModel->editora_id;
            $this->bibliografia = $livroModel->bibliografia;
            $this->preco = $livroModel->preco;
            $this->autoresSelecionados = $livroModel->autores->pluck('id')->toArray();
            $this->isEditing = true;
        } else {
            session()->flash('error', 'Livro não encontrado.');
            return $this->redirectRoute('admin.livros.index', navigate: true);
        }
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
        return $this->redirectRoute('admin.livros.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.livro-form', [
            'editoras' => Editora::all(),
            'autores' => Autor::all(),
        ]);
    }
}
