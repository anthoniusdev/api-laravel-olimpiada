<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assinala extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_aluno',
        'id_questao',
        'id_alternativa_assinalada',
    ];
}
