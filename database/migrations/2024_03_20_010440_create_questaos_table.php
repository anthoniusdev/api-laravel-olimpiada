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
        Schema::create('questaos', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->longText('titulo');
            $table->string('id_prova');
            $table->string('id_fase');
            $table->string('id_alternativa_correta');
            $table->foreign('id_prova')->references('id')->on('provas')->cascadeOnDelete();
            $table->foreign('id_fase')->references('id')->on('fases')->cascadeOnDelete();
            $table->foreign('id_alternativa_correta')->references('id')->on('alternativas')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questaos');
    }
};
