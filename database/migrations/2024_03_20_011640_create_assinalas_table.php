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
        Schema::create('assinala', function (Blueprint $table) {
            $table->string('id_aluno');
            $table->string('id_questao');
            $table->string('id_alternativa_assinalada');
            $table->foreign('id_aluno')->references('id')->on('aluno');
            $table->foreign('id_questao')->references('id')->on('questao');
            $table->foreign('id_alternativa_assinalada')->references('id')->on('alternativa')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assinalas');
    }
};
