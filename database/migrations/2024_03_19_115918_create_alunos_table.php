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
        Schema::create('aluno', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('nome');
            $table->string('username')->unique();
            $table->string('email');
            $table->string('senha');
            $table->string('cpf')->uniqiue();
            $table->string('codigo_escola');
            $table->string('id_prova_respondida');
            $table->foreign('codigo_escola')->references('codigo_escola')->on('escola')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
