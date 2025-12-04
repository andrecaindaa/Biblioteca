<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alertas_livro', function (Blueprint $table) {
        $table->id();
        $table->foreignId('livro_id')->constrained('livros')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->timestamp('notificado_em')->nullable();
        $table->timestamps();

        $table->unique(['livro_id', 'user_id']); // evita duplicados
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas_livro');
    }
};
