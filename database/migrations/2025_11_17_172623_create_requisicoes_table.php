<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisicoes', function (Blueprint $table) {
            $table->id();

            // FK para User (cidadão que requisita)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // FK para Livro
            $table->foreignId('livro_id')->constrained()->onDelete('cascade');

            // Guardar número sequencial (ex: REC-0001)
            $table->string('numero')->unique();

            // Datas
            $table->date('data_requisicao');
            $table->date('data_prevista_entrega');
            $table->date('data_entrega_real')->nullable();

            // Caminho da foto do cidadão no momento da requisição
            $table->string('foto_cidadao')->nullable();

            // Status: ativo | entregue
            $table->enum('status', ['ativo', 'entregue'])->default('ativo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisicoes');
    }
};
