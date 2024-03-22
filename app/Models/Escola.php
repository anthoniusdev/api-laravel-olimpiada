<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escola extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'nome',
        'email',
        'senha',
        'usuario',
        'cnpj',
        'telefone',
        'codigo_escola',
        'id_area1',
        'id_area2',
        'nome_responsavel',
        'cpf_responsavel',
        'municipio',
    ];
}
