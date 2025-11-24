<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use App\Models\Editora;
use Illuminate\Support\Facades\Auth;

#[Title('Editoras')]
class EditoraForm extends Component
{
    use WithFileUploads;

    public $editoraId;
    public $nome;
    public $logotipo;
    public $notas;
    public $isEditing = false;

    protected $rules = [
        'nome' => 'required|string|max:255',
        'logotipo' => 'nullable|image|max:2048',
        'notas' => 'nullable|string|max:1000',
    ];

    public function mount($editora = null)
    {
        // VERIFICAÇÃO DE ADMIN
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso reservado a administradores.');
        }

        if ($editora) {
            $editoraModel = Editora::find($editora);
            if ($editoraModel) {
                $this->editoraId = $editoraModel->id;
                $this->nome = $editoraModel->nome;
                $this->notas = $editoraModel->notas;
                $this->isEditing = true;
            } else {
                session()->flash('error', 'Editora não encontrada.');
                return $this->redirectRoute('admin.editoras.index', navigate: true);
            }
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $editora = Editora::findOrFail($this->editoraId);
        } else {
            $editora = new Editora();
        }

        $editora->nome = $this->nome;
        $editora->notas = $this->notas;

        if ($this->logotipo) {
            if ($editora->logotipo && Storage::disk('public')->exists($editora->logotipo)) {
                Storage::disk('public')->delete($editora->logotipo);
            }
            $editora->logotipo = $this->logotipo->store('editoras', 'public');
        }

        $editora->save();

        session()->flash('message', $this->isEditing ? 'Editora atualizada com sucesso!' : 'Editora criada com sucesso!');
        return $this->redirectRoute('admin.editoras.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.editora-form');
    }
}
