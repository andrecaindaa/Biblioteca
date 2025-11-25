<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleBooksService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_books.key');

        // Debug da chave
        if(!$this->apiKey) {
            dd('A chave da API não está definida!');
        }
    }

    public function searchBooks($query, $startIndex = 0, $maxResults = 5)
    {
        $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
            'q' => $query,
            'startIndex' => $startIndex,
            'maxResults' => $maxResults,
            'key' => $this->apiKey
        ]);

        // Debug da resposta
        if ($response->failed()) {
            dd('Erro ao ligar à API:', $response->body());
        }

        $data = $response->json();
        dd($data); // Vai mostrar toda a resposta da API
        return $data['items'] ?? [];
    }
}
