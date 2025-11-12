<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Editora;

class EditoraForm extends Component
{
    public $editora;
    public $editoraId;
    public $nome;

    protected $rules = [
        'nome' => 'required|string|max:255',
    ];

    public function mount(Editora $editora = null)
    {
        if ($editora && $editora->exists) {
            $this->editora = $editora;
            $this->editoraId = $editora->id;
            $this->nome = $editora->nome;
        }
    }

    public function save()
    {
        $this->validate();

        Editora::updateOrCreate(
            ['id' => $this->editoraId],
            ['nome' => $this->nome]
        );

        session()->flash('message', 'Editora salva com sucesso!');
        return redirect()->route('editoras.index');
    }

    public function render()
    {
        return view('livewire.editora-form', [
            'editora' => $this->editora,
        ]);
    }
}
