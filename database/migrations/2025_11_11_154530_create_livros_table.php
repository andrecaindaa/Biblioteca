<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLivrosTable extends Migration
{
    public function up()
    {
       Schema::create('livros', function (Blueprint $table) {
    $table->id();
    $table->string('isbn')->unique();
    $table->string('nome');
    $table->foreignId('editora_id')->constrained('editoras')->onDelete('cascade');
    $table->text('bibliografia')->nullable();
    $table->string('imagem_capa')->nullable();
    $table->decimal('preco', 8, 2)->nullable();
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('livros');
    }
}


