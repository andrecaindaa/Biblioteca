<?php
namespace App\Exports;

use App\Models\Livro;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LivrosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Livro::with('editora','autores')->get()->map(function($l){
            return [
                'isbn' => $l->isbn,
                'nome' => $l->nome,
                'editora' => $l->editora->nome ?? '',
                'autores' => $l->autores->pluck('nome')->implode(', '),
                'preco' => $l->preco,
            ];
        });
    }

    public function headings(): array
    {
        return ['ISBN','Nome','Editora','Autores','Preço'];
    }
}
