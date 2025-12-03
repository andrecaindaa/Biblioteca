<?php

namespace App\Services;

use App\Models\Livro;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class RelatedBooksService
{
    // número máximo de candidatos a comparar para performance
    protected $candidateLimit = 500;

    // tempo de cache em minutos
    protected $cacheMinutes = 60;

    // stopwords PT + EN (reduzido, podes ampliar)
    protected $stopwords = [
        // Portuguese (abridged)
        'o','a','os','as','um','uma','uns','umas','de','do','da','dos','das','em','no','na','nos','nas',
        'por','para','com','sem','sob','sobre','e','ou','mas','se','que','como','quando','onde','quem',
        'isso','este','esta','estes','estas','ser','foi','era','são','ter','tem','há',
        // English (abridged)
        'the','a','an','and','or','in','on','at','for','with','without','of','to','is','are','was','were',
        'this','that','these','those','by','from','as','be','have','has','had'
    ];

    public function getRelated(Livro $livro, int $limit = 5)
    {
        if (! $livro->bibliografia) {
            return collect();
        }

        $cacheKey = "related_books:{$livro->id}:{$limit}";

        return Cache::remember($cacheKey, $this->cacheMinutes * 60, function() use ($livro, $limit) {
            // candidatos: livros com bibliografia, diferente do atual
            $candidates = Livro::whereNotNull('bibliografia')
                ->where('id', '!=', $livro->id)
                ->limit($this->candidateLimit)
                ->get(['id','nome','bibliografia','imagem_capa']);

            // extrair termos do livro base
            $baseTerms = $this->extractTerms($livro->bibliografia);

            // vector do base
            $baseTf = $this->termFrequencies($baseTerms);

            $scores = [];

            foreach ($candidates as $c) {
                $candTerms = $this->extractTerms($c->bibliografia);
                if (empty($candTerms)) continue;

                $candTf = $this->termFrequencies($candTerms);

                // calcular similaridade (cosine)
                $cosine = $this->cosineSimilarity($baseTf, $candTf);

                // juntar Jaccard (sobre sets) para robustez
                $jaccard = $this->jaccardSimilarity(array_keys($baseTf), array_keys($candTf));

                // score final: 0.7*cosine + 0.3*jaccard (ajustável)
                $score = 0.7 * $cosine + 0.3 * $jaccard;

                if ($score > 0) {
                    $scores[] = [
                        'livro' => $c,
                        'score' => $score,
                    ];
                }
            }

            // ordenar desc por score e devolver top $limit
            $sorted = collect($scores)
                ->sortByDesc('score')
                ->take($limit)
                ->pluck('livro');

            return $sorted->values();
        });
    }

    protected function extractTerms(?string $text): array
    {
        if (! $text) return [];

        // normalizar
        $text = mb_strtolower($text, 'UTF-8');

        // remover HTML (se existir)
        $text = strip_tags($text);

        // remover pontuação e números, manter letras e espaços
        $text = preg_replace('/[^\p{L}\s]/u', ' ', $text);

        // dividir por espaços
        $parts = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        if (! $parts) return [];

        // remover stopwords e palavras curtas
        $terms = [];
        foreach ($parts as $p) {
            $p = trim($p);
            if (strlen($p) <= 2) continue; // corta palavras muito curtas
            if (in_array($p, $this->stopwords, true)) continue;
            $terms[] = $p;
        }
        return $terms;
    }

    protected function termFrequencies(array $terms): array
    {
        $tf = [];
        foreach ($terms as $t) {
            if (! isset($tf[$t])) $tf[$t] = 0;
            $tf[$t] += 1;
        }
        // opcional: normalizar pelo total
        $total = array_sum($tf) ?: 1;
        foreach ($tf as $k => $v) {
            $tf[$k] = $v / $total;
        }
        return $tf;
    }

    protected function cosineSimilarity(array $tf1, array $tf2): float
    {
        // dot product
        $dot = 0.0;
        foreach ($tf1 as $term => $w1) {
            if (isset($tf2[$term])) {
                $dot += $w1 * $tf2[$term];
            }
        }
        // norms
        $norm1 = sqrt(array_sum(array_map(function($v){ return $v*$v; }, $tf1)));
        $norm2 = sqrt(array_sum(array_map(function($v){ return $v*$v; }, $tf2)));

        if ($norm1 == 0 || $norm2 == 0) return 0.0;
        return $dot / ($norm1 * $norm2);
    }

    protected function jaccardSimilarity(array $a, array $b): float
    {
        $setA = array_unique($a);
        $setB = array_unique($b);
        $inter = count(array_intersect($setA, $setB));
        $union = count(array_unique(array_merge($setA, $setB)));
        if ($union == 0) return 0.0;
        return $inter / $union;
    }
}
