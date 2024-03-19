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
        Schema::create('escolas', function (Blueprint $table) {
            $table->string('id_escola')->primary();
            $table->string('nome');
            $table->string('cnpj')->unique();
            $table->string('telefone');
            $table->string('email');
            $table->string('usuario')->unique();
            $table->string('senha');
            $table->string('codigo_escola')->unique();
            $table->string('id_area1');
            $table->string('id_area2');
            $table->foreign('id_area1')->references('id_area')->on('areas');
            $table->foreign('id_area2')->references('id_area')->on('areas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escolas');
    }
};
