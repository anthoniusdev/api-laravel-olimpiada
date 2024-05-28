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
        Schema::create('provas', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_escola');
            $table->string('id_fase');
            $table->string('id_area');
            $table->string('modalidade');
            $table->foreign('id_escola')->references('id')->on('escolas')->cascadeOnDelete();
            $table->foreign('id_fase')->references('id')->on('fases')->cascadeOnDelete();
            $table->foreign('id_area')->references('id')->on('areas')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provas');
    }
};
