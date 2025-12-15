<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('modulo');
            $table->string('acao');
            $table->unsignedBigInteger('objeto_id')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->string('browser')->nullable();
            $table->timestamps(); // data + hora
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
