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
        Schema::table('alternativa', function (Blueprint $table) {
            $table->foreign('id_questao')->references('id')->on('questao')->cascadeOnDelete();
        });
    }
    public function down()
    {
        Schema::table('alternativa', function (Blueprint $table) {
            $table->dropForeign(['id_questao']);
        });
    }
};
