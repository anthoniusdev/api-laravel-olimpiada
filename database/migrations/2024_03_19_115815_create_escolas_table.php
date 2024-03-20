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
            $table->string('id')->primary();
            $table->string('usuario')->unique();
            $table->string('senha');
            $table->string('nome');
            $table->string('email');
            $table->string('id_area1')->nullable()->default(null);
            $table->string('id_area2')->nullable()->default(null);
            $table->string('cnpj')->unique();
            $table->string('telefone');
            $table->string('codigo_escola')->unique();
            // $table->string('nome_responsavel');
            // $table->string('cpf_responsavel');
            // $table->string('municipio');
            $table->foreign('id_area1')->references('id')->on('areas')->cascadeOnDelete();
            $table->foreign('id_area2')->references('id')->on('areas')->cascadeOnDelete();
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
