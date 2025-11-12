<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Livro;
use App\Models\Editora;
use App\Models\Autor;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LivrosExport;

class Livros extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'nome';
    public $sortDirection = 'asc';
    public $editoraFilter = '';
    public $autorFilter = '';
    public $perPage = 10;

    protected $updatesQueryString = ['search','sortField','sortDirection','editoraFilter','autorFilter','perPage'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function exportExcel()
    {
        return Excel::download(new LivrosExport, 'livros.xlsx');
    }

    public function render()
    {
        $query = Livro::with(['editora','autores'])
            ->when($this->search, fn($q) => $q->where('nome','like','%'.$this->search.'%')->orWhere('isbn','like','%'.$this->search.'%'))
            ->when($this->editoraFilter, fn($q) => $q->where('editora_id',$this->editoraFilter))
            ->when($this->autorFilter, fn($q) => $q->whereHas('autores', fn($q2)=> $q2->where('autor_id', $this->autorFilter)))
            ->orderBy($this->sortField, $this->sortDirection);

        $livros = $query->paginate($this->perPage);

        return view('livewire.livros', [
            'livros' => $livros,
            'editoras' => Editora::all(),
            'autores' => Autor::all(),
        ]);
    }
}
