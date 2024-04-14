<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('modalidades', function (Blueprint $table) {
            $nomes_modalidades = ['1º Ano', '2º ano'];
            $sigla_modalidades = ['a', 'b'];
            for ($i = 0; $i <= 1; $i++) {
                $id_modalidade = Str::random();
                DB::table('modalidades')->insert([
                    'id' => $id_modalidade,
                    'nome' => $nomes_modalidades[$i],
                    'sigla' => $sigla_modalidades[$i],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });
    }
};
