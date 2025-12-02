<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisicao_id')->constrained('requisicoes')->onDelete('cascade');
            $table->foreignId('livro_id')->constrained('livros')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('rating')->nullable(); // 1..5
            $table->text('comentario')->nullable();
            $table->enum('status', ['suspenso','ativo','recusado'])->default('suspenso');
            $table->text('justificacao_recusa')->nullable();
            $table->timestamps();

            $table->unique(['requisicao_id']); // garantir 1 review por requisição
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
