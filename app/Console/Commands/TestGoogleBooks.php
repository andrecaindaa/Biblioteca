<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestGoogleBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-google-books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = new \App\Services\GoogleBooksService();
        $books = $service->searchBooks('Harry Potter');

        foreach ($books as $book) {
            $info = $book['volumeInfo'];
            $this->info('Título: ' . ($info['title'] ?? 'Sem título'));
            $this->info('Autores: ' . implode(', ', $info['authors'] ?? ['Desconhecido']));
            $this->info('ISBN: ' . ($info['industryIdentifiers'][0]['identifier'] ?? 'N/A'));
            $this->info('---');
        }
    }
}
