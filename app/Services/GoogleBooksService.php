<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleBooksService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_books.key');
    }

    public function searchBooks($query, $startIndex = 0, $maxResults = 5)
    {
        try {
            $response = Http::timeout(30)->get('https://www.googleapis.com/books/v1/volumes', [
                'q' => $query,
                'startIndex' => $startIndex,
                'maxResults' => $maxResults,
                'key' => $this->apiKey
            ]);

            if ($response->failed()) {
                Log::error('Google Books API error: ' . $response->body());
                return [];
            }

            $data = $response->json();
            return $data['items'] ?? [];

        } catch (\Exception $e) {
            Log::error('Google Books API exception: ' . $e->getMessage());
            return [];
        }
    }

    public function normalizeBookData($item)
    {
        $info = $item['volumeInfo'] ?? [];
        $saleInfo = $item['saleInfo'] ?? [];

        // Processar preço de forma segura
        $preco = 0.0;
        if (isset($saleInfo['listPrice']['amount']) && is_numeric($saleInfo['listPrice']['amount'])) {
            $preco = floatval($saleInfo['listPrice']['amount']);
        } elseif (isset($saleInfo['retailPrice']['amount']) && is_numeric($saleInfo['retailPrice']['amount'])) {
            $preco = floatval($saleInfo['retailPrice']['amount']);
        }

        // Processar ISBN
        $isbn = null;
        if (isset($info['industryIdentifiers'])) {
            foreach ($info['industryIdentifiers'] as $identifier) {
                if ($identifier['type'] === 'ISBN_13' || $identifier['type'] === 'ISBN_10') {
                    $isbn = $identifier['identifier'];
                    break;
                }
            }
        }

        return [
            'isbn' => $isbn,
            'nome' => $info['title'] ?? 'Sem título',
            'editora' => $info['publisher'] ?? 'Desconhecida',
            'autores' => $info['authors'] ?? [],
            'bibliografia' => $info['description'] ?? '',
            'imagem_capa' => $info['imageLinks']['thumbnail'] ?? $info['imageLinks']['smallThumbnail'] ?? null,
            'preco' => $preco,
        ];
    }
}
