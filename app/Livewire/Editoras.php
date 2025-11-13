<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Storage;
use App\Models\Editora;

#[Title('Editoras')]
class Editoras extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $editora = Editora::findOrFail($id);

        // Deletar logotipo se existir
        if ($editora->logotipo && Storage::disk('public')->exists($editora->logotipo)) {
            Storage::disk('public')->delete($editora->logotipo);
        }

        $editora->delete();

        session()->flash('message', 'Editora eliminada com sucesso!');
    }

    public function render()
    {
        $editoras = Editora::where('nome', 'like', '%' . $this->search . '%')
            ->orderBy('nome')
            ->paginate(10);

        return view('livewire.editoras', [
            'editoras' => $editoras,
        ]);
    }
}
