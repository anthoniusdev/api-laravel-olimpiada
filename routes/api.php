<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CPFController;
use App\Http\Controllers\EscolaController;
use App\Http\Controllers\TesteController;
use App\Mail\EscolaDados;
use App\Models\Aluno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/verify-login', function () {
        return ["isAuthenticated" => true];
    });
});

Route::prefix('/escola')->group(function () {
    Route::post('/cadastro', [EscolaController::class, 'store']);
    Route::post('/login', [EscolaController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function(){
        //
    });
});
Route::prefix('/aluno')->group(function () {
    Route::post('/cadastro', [AlunoController::class, 'store']);
    Route::post('/login', [AlunoController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function(){
        //
    });
});

// // Rotas para verificar
// Route::prefix('/verify')->group(function(){
//     Route::post('/cpf', [CPFController::class, 'validarCPF']);
//     Route::post('cnpj', [CNPJController::class, 'validarCNPJ']);
// });

// Rota para obter as imagens
Route::prefix('/img')->group(function () {
    // Rota para obter as imagens publicas
    Route::prefix('/public')->group(function () {
        Route::get('/logo', function () {
            return response()->file(public_path('mail/logo3.png'));
        });
    });
});
