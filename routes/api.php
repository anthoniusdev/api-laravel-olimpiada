<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\EscolaController;
use Illuminate\Support\Facades\Route;

Route::apiResource('escola', EscolaController::class);

Route::apiResource('aluno', AlunoController::class);