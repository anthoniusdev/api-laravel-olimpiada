<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escola extends Model
{
    protected $fillable = [
        'id_escola',
        'nome',
        'email',
        'senha',
        'usuario',
        'cnpj',
        'telefone',
        'codigo_escola',
        'area1',
        'area2',
    ];
}
