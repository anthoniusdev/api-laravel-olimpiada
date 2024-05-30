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
        Schema::table('areas', function (Blueprint $table) {
            $nomes_area = ['Química', 'Física', 'História', 'Empreendorismo e Inovação'];
            for ($i = 0; $i <= 3; $i++) {
                $id_area = Str::random();
                DB::table('areas')->insert([
                    'id' => $id_area,
                    'nome' => $nomes_area[$i],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });
    }
};
