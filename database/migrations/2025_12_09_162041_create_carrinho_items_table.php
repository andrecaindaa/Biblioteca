<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarrinhoItemsTable extends Migration
{
    public function up()
    {
        Schema::create('carrinho_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrinho_id')->constrained('carrinhos')->cascadeOnDelete();
            $table->foreignId('livro_id')->constrained('livros')->cascadeOnDelete();
            $table->integer('quantidade')->default(1);
            $table->timestamps();

            $table->unique(['carrinho_id','livro_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrinho_items');
    }
}
