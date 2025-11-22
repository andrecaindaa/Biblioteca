<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Storage;
use App\Models\Editora;
use Illuminate\Support\Facades\Auth;

#[Title('Editoras')]
class Editoras extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $sortField = 'nome';

    #[Url]
    public $sortDirection = 'asc';

     public function mount()
    {
        // ✅ VERIFICAÇÃO DE ADMIN (CORRIGIDA)
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso reservado a administradores.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function delete($id)
    {
        $editora = Editora::findOrFail($id);

        if ($editora->logotipo && Storage::disk('public')->exists($editora->logotipo)) {
            Storage::disk('public')->delete($editora->logotipo);
        }

        $editora->delete();

        session()->flash('message', 'Editora eliminada com sucesso!');
    }

    public function render()
    {
        $editoras = Editora::where('nome', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.editoras', [
            'editoras' => $editoras,
        ]);
    }
}
