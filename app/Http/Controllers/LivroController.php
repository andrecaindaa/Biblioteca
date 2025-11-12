<?php
namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LivroController extends Controller
{
    public function destroy(Livro $livro)
    {
        // apagar imagem associada
        if ($livro->imagem_capa) {
            Storage::disk('public')->delete($livro->imagem_capa);
        }

        $livro->autores()->detach();
        $livro->delete();

        return redirect()->route('livros.index')->with('success', 'Livro eliminado.');
    }
}
