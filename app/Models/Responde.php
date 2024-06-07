<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responde extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'status',
        'data_hora_inicio',
        'id_aluno',
        'id_prova', 
        'pontuacao',
        'bool_classificado',
    ];
}
