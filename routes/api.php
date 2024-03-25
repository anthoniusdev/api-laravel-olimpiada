<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EscolaController;
use App\Mail\EscolaDados;
use Illuminate\Support\Facades\Route;

Route::prefix('/escola')->group(function(){
    Route::post('/cadastro', [EscolaController::class, 'store']);
    Route::post('/login', [AuthController::class, 'loginEscola']);
});
Route::prefix('/aluno')->group(function(){
    Route::post('/cadastro', [AlunoController::class, 'store']);
});
