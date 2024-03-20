<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questao extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'titulo',
        'id_prova',
        'id_fase',
        'id_alternativa_correta',
    ];
}
