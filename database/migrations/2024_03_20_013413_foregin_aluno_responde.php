<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->foreign('id_prova_respondida')->references('id')->on('respondes')->cascadeOnDelete();
        });
    }
    public function down()
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->dropForeign(['id_prova_respondida']);
        });
    }
};
