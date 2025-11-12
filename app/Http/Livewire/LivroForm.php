<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Livro;
use App\Models\Editora;
use App\Models\Autor;
use Illuminate\Support\Facades\Storage;

class LivroForm extends Component
{
    use WithFileUploads;

    public $livro;
    public $nome, $isbn, $preco, $editora_id;
    public $autoresSelecionados = [];
    public $imagem_capa;

    public function mount(Livro $livro = null)
    {
        $this->livro = $livro;

        if ($livro) {
            $this->nome = $livro->nome;
            $this->isbn = $livro->isbn;
            $this->preco = $livro->preco;
            $this->editora_id = $livro->editora_id;
            $this->autoresSelecionados = $livro->autores->pluck('id')->toArray();
        }
    }

    protected $rules = [
        'nome' => 'required|string|max:255',
        'isbn' => 'required|string|max:50',
        'preco' => 'required|numeric',
        'editora_id' => 'required|exists:editoras,id',
        'autoresSelecionados' => 'required|array|min:1',
        'imagem_capa' => 'nullable|image|max:2048',
    ];

    public function save()
    {
        $this->validate();

        $livro = $this->livro ?? new Livro();

        $livro->nome = $this->nome;
        $livro->isbn = $this->isbn;
        $livro->preco = $this->preco;
        $livro->editora_id = $this->editora_id;

        if ($this->imagem_capa) {
            if ($livro->imagem_capa) {
                Storage::disk('public')->delete($livro->imagem_capa);
            }
            $livro->imagem_capa = $this->imagem_capa->store('capas','public');
        }

        $livro->save();
        $livro->autores()->sync($this->autoresSelecionados);

        session()->flash('success', 'Livro salvo com sucesso!');
        return redirect()->route('livros.index');
    }

    public function render()
    {
        return view('livewire.livro-form', [
            'editoras' => Editora::all(),
            'autores' => Autor::all(),
            'livro' => $this->livro
        ]);
    }
}
