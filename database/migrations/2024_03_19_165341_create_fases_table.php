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
        Schema::create('fases', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('fase');
            $table->integer('limite_classificados');
            $table->date('data_realizacao');
            $table->time('horario_inicio');
            $table->time('horario_fim');
            $table->string('id_area');
            $table->foreign('id_area')->references('id')->on('areas')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fases');
    }
};
