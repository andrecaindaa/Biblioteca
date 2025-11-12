<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Autor;

class AutorForm extends Component
{
    public $autor;
    public $nome;

    public function mount($autor = null)
    {
        if ($autor) {
            $this->autor = $autor;
            $this->nome = $autor->nome;
        }
    }

    public function save()
    {
        if ($this->autor) {
            $this->autor->update(['nome' => $this->nome]);
        } else {
            Autor::create(['nome' => $this->nome]);
        }

        session()->flash('message', 'Autor salvo com sucesso!');
        return redirect()->route('autores.index');
    }

   public function render()
{
    return view('livewire.autor-form', [
        'autorId' => $this->autor ? $this->autor->id : null
    ]);
}

}
