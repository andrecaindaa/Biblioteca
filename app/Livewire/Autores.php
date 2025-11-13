<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Storage;
use App\Models\Autor;

#[Title('Autores')]
class Autores extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $sortField = 'nome';

    #[Url]
    public $sortDirection = 'asc';

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
        $autor = Autor::findOrFail($id);

        // Deletar foto se existir
        if ($autor->foto && Storage::disk('public')->exists($autor->foto)) {
            Storage::disk('public')->delete($autor->foto);
        }

        $autor->delete();

        session()->flash('message', 'Autor eliminado com sucesso!');
    }

    public function render()
    {
        $autores = Autor::where('nome', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.autores', [
            'autores' => $autores,
        ]);
    }
}
