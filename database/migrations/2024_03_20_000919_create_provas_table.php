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
        Schema::create('prova', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_escola');
            $table->string('id_fase');
            $table->string('id_area');
            $table->foreign('id_escola')->references('id')->on('escola')->cascadeOnDelete();
            $table->foreign('id_fase')->references('id')->on('fase')->cascadeOnDelete();
            $table->foreign('id_area')->references('id')->on('area')->cascadeOnDelete();
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
