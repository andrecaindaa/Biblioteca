<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique(); // admin, cidadao
            $table->timestamps();
        });

        // Inserir roles padrão
        DB::table('roles')->insert([
            ['id' => 1, 'nome' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nome' => 'cidadao', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
