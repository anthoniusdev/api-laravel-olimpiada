<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fase extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'limite_classificados',
        'data_realizacao',
        'horario_inicio',
        'horario_fim',
        'id_area',
    ];
}
