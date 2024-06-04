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
        Schema::table('questao_temporarias', function (Blueprint $table) {
            $table->string('id_alternativa_assinalada')->nullable()->change();
            $table->integer('numeralQuestao')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questao_temporarias', function (Blueprint $table) {
            //
        });
    }
};
