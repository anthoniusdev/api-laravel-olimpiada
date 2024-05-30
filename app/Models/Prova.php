<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prova extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'id_fase',
        'id_escola',
        'id_area',
        'modalidade'

    ];
}
