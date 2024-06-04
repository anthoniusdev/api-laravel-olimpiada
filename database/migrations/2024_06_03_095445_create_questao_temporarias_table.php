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
        Schema::create('questao_temporarias', function (Blueprint $table) {
            $table->string('id_aluno');
            $table->string('id_questao');
            $table->string('id_alternativa_assinalada');
            $table->integer('numeralQuestao');
            $table->foreign('id_aluno')->references('id')->on('alunos')->cascadeOnDelete();
            $table->foreign('id_questao')->references('id')->on('questaos')->cascadeOnDelete();
            $table->foreign('id_alternativa_assinalada')->references('id')->on('alternativas')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questao_temporarias');
    }
};
