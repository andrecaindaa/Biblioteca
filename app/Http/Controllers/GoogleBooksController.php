<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Autor;
use App\Models\Editora;
use App\Services\GoogleBooksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GoogleBooksController extends Controller
{
    protected $googleBooks;

    public function __construct(GoogleBooksService $googleBooks)
    {
        $this->googleBooks = $googleBooks;
    }
/*
    public function search(Request $request)
    {
        $query = $request->input('q');
        if (!$query) {
            return view('googlebooks.search', ['books' => []]);
        }

        $books = $this->googleBooks->searchBooks($query);

        $booksNormalized = array_map(function($item) {
            return $this->googleBooks->normalizeBookData($item);
        }, $books);

        return view('googlebooks.search', [
            'books' => $booksNormalized,
            'query' => $query
        ]);
    }
*/
    public function import(Request $request)
    {
        try {
            $data = $request->only([
                'isbn', 'nome', 'editora', 'autores',
                'bibliografia', 'imagem_capa', 'preco'
            ]);

            // Validação básica
            if (empty($data['nome']) || $data['nome'] === 'Sem título') {
                return back()->with('error', 'Nome do livro é obrigatório.');
            }

            DB::transaction(function() use ($data) {
                // Criar ou obter a editora
                $editora = Editora::firstOrCreate([
                    'nome' => $data['editora'] ?? 'Desconhecida'
                ]);

                // Processar preço de forma segura
                $preco = $this->processarPreco($data['preco'] ?? 0);

                // Baixar e guardar a capa
                $nomeCapa = $this->downloadCover($data['imagem_capa'] ?? null);

                // Criar livro
                $livro = Livro::create([
                    'isbn' => $this->limitarIsbn($data['isbn'] ?? null),
                    'nome' => $data['nome'],
                    'bibliografia' => $data['bibliografia'] ?? '',
                    'preco' => $preco,
                    'editora_id' => $editora->id,
                    'imagem_capa' => $nomeCapa,
                ]);

                // Criar autores e relacionar
                $autoresIds = [];
                $autores = is_array($data['autores']) ? $data['autores'] : [];

                foreach ($autores as $nomeAutor) {
                    if (!empty(trim($nomeAutor))) {
                        $autor = Autor::firstOrCreate([
                            'nome' => trim($nomeAutor)
                        ]);
                        $autoresIds[] = $autor->id;
                    }
                }

                if (!empty($autoresIds)) {
                    $livro->autores()->sync($autoresIds);
                }
            });

            return back()->with('success', 'Livro importado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao importar livro: ' . $e->getMessage());
            return back()->with('error', 'Erro ao importar livro: ' . $e->getMessage());
        }
    }

    /**
     * Processa o preço de forma segura
     */
    protected function processarPreco($preco)
    {
        if ($preco === null || $preco === '') {
            return 0.0;
        }

        // Se for string, remove caracteres não numéricos exceto ponto e vírgula
        if (is_string($preco)) {
            $preco = preg_replace('/[^\d,\.]/', '', $preco);
            // Substitui vírgula por ponto para formato decimal
            $preco = str_replace(',', '.', $preco);
        }

        // Converte para float
        $precoFloat = floatval($preco);

        // Garante que é um número positivo
        return max(0.0, $precoFloat);
    }

    /**
     * Limita o ISBN para o tamanho máximo do campo no banco
     */
    protected function limitarIsbn($isbn)
    {
        if (!$isbn) {
            return null;
        }

        // Assume que o campo isbn na tabela é varchar(255)
        return substr($isbn, 0, 255);
    }

    protected function downloadCover($url)
    {
        if (!$url) return null;

        try {
            $contents = @file_get_contents($url);
            if (!$contents) return null;

            $name = 'capa_'.time().'_'.rand(1000,9999).'.jpg';
            $path = 'capas/'.$name;

            Storage::disk('public')->put($path, $contents);
            return $path;

        } catch (\Exception $e) {
            Log::error('Erro ao baixar capa: ' . $e->getMessage());
            return null;
        }
    }

    public function search(Request $request)
{
    $query = $request->input('q');
    $page = $request->input('page', 1);
    $startIndex = ($page - 1) * 5;

    if (!$query) {
        return view('googlebooks.search', ['books' => []]);
    }

    $books = $this->googleBooks->searchBooks($query, $startIndex, 5);

    $booksNormalized = array_map(function($item) {
        return $this->googleBooks->normalizeBookData($item);
    }, $books);

    return view('googlebooks.search', [
        'books' => $booksNormalized,
        'query' => $query,
        'currentPage' => $page,
        'hasMore' => count($books) === 5
    ]);
}
}
