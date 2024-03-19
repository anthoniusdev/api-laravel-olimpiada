<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_area',
        'nome',
        'limite_classificados',
        'data_realizacao',
        'horario_inicio',
        'horario_fim',
    ];
}
