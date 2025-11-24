<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use App\Models\Autor;
use Illuminate\Support\Facades\Auth;

#[Title('Autores')]
class AutorForm extends Component
{
    use WithFileUploads;
protected $middleware = ['auth', 'admin'];
    public $autorId;
    public $nome;
    public $foto;
    public $isEditing = false;

    protected $rules = [
        'nome' => 'required|string|max:255',
        'foto' => 'nullable|image|max:2048',
    ];

    public function mount($autor = null)
    {
        // VERIFICAÇÃO DE ADMIN
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso reservado a administradores.');
        }

        if ($autor) {
            $autorModel = Autor::find($autor);
            if ($autorModel) {
                $this->autorId = $autorModel->id;
                $this->nome = $autorModel->nome;
                $this->isEditing = true;
            } else {
                session()->flash('error', 'Autor não encontrado.');
                return $this->redirectRoute('admin.autores.index', navigate: true);
            }
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $autor = Autor::findOrFail($this->autorId);
        } else {
            $autor = new Autor();
        }

        $autor->nome = $this->nome;

        if ($this->foto) {
            // Deletar foto antiga se existir
            if ($autor->foto && Storage::disk('public')->exists($autor->foto)) {
                Storage::disk('public')->delete($autor->foto);
            }
            $autor->foto = $this->foto->store('autores', 'public');
        }

        $autor->save();

        session()->flash('message', $this->isEditing ? 'Autor atualizado com sucesso!' : 'Autor criado com sucesso!');
        return $this->redirectRoute('admin.autores.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.autor-form');
    }
}
