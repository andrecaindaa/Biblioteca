<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Livro;

class Catalogo extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'nome';
    public $sortDirection = 'asc';
    public $perPage = 12;
    public $filterEditora = null;
    public $filterAutor = null;

    protected $queryString = ['search', 'sortField', 'sortDirection', 'filterEditora', 'filterAutor', 'perPage'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->filterEditora = null;
        $this->filterAutor = null;
    }

    public function render()
    {
        $query = Livro::with(['editora', 'autores']);

        if ($this->search) {
            $query->where(function($q){
                $q->where('nome', 'like', '%'.$this->search.'%')
                  ->orWhere('isbn', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterEditora) {
            $query->where('editora_id', $this->filterEditora);
        }

        if ($this->filterAutor) {
            $query->whereHas('autores', function($q){
                $q->where('autores.id', $this->filterAutor);
            });
        }

        $livros = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Carregar dados auxiliares para filtros
        $editoras = \App\Models\Editora::orderBy('nome')->get();
        $autores = \App\Models\Autor::orderBy('nome')->get();

        return view('livewire.catalogo', [
            'livros' => $livros,
            'editoras' => $editoras,
            'autores' => $autores,
        ]);
    }
}
