<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'nome',
        'email',
        'username',
        'senha',
        'cpf',
        'codigo_escola',
    ];
}
