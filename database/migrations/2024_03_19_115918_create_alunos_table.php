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
        Schema::create('alunos', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('nome');
            $table->string('usuario')->unique();
            $table->string('email');
            $table->string('senha');
            $table->string('cpf')->uniqiue();
            $table->string('codigo_escola');
            $table->string('id_area');
            $table->string('modalidade');
            $table->foreign('id_area')->references('id')->on('areas')->cascadeOnDelete();
            $table->foreign('codigo_escola')->references('codigo_escola')->on('escolas')->cascadeOnDelete();
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
