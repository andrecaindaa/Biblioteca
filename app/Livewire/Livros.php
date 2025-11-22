<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Livro;
use App\Exports\LivrosExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class Livros extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $sortField = 'nome';

    #[Url]
    public $sortDirection = 'asc';
/*
    public function mount()
    {
        // VERIFICAÇÃO DE ADMIN
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso reservado a administradores.');
        }
    }*/

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
        $livro = Livro::findOrFail($id);

        // Deletar imagem da capa se existir
        if ($livro->imagem_capa && \Illuminate\Support\Facades\Storage::disk('public')->exists($livro->imagem_capa)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($livro->imagem_capa);
        }

        $livro->delete();

        session()->flash('message', 'Livro eliminado com sucesso!');
    }

    public function exportExcel()
    {
        return Excel::download(new LivrosExport, 'livros.xlsx');
    }

    public function render()
    {
        $livros = Livro::with(['editora', 'autores'])
            ->where('nome', 'like', '%' . $this->search . '%')
            ->orWhere('isbn', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.livros', [
            'livros' => $livros,
        ]);
    }
}
