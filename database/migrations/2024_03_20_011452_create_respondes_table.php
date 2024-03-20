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
        Schema::create('responde', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_aluno');
            $table->string('id_prova');
            $table->float('pontuacao');
            $table->boolean('bool_classificado');
            $table->foreign('id_aluno')->references('id')->on('aluno')->cascadeOnDelete();
            $table->foreign('id_prova')->references('id')->on('prova')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respondes');
    }
};
