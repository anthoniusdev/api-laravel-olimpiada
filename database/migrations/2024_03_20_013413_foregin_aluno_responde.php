<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('aluno', function (Blueprint $table) {
            $table->foreign('id_prova_respondida')->references('id')->on('responde')->cascadeOnDelete();
        });
    }
    public function down()
    {
        Schema::table('aluno', function (Blueprint $table) {
            $table->dropForeign(['id_prova_respondida']);
        });
    }
};
