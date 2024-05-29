<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\EscolaController;
use Illuminate\Support\Facades\Route;


Route::post('admin/login', [Admin::class, "login"]);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/verify-login', function () {
        return ["isAuthenticated" => true];
    });
    Route::get('/get/escolas', [Admin::class, "getEscolas"]);
});

Route::prefix('/escola')->group(function () {
    Route::post('/cadastro', [EscolaController::class, 'store']);
    Route::post('/login', [EscolaController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/alunos-cadastrados', [EscolaController::class, 'getAlunos']);
        Route::post('/logout', [EscolaController::class, 'logout']);
    });
});
Route::prefix('/aluno')->group(function () {
    Route::post('/login', [AlunoController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/cadastro', [AlunoController::class, 'store']);
        Route::post('/logout', [AlunoController::class, 'logout']);
        Route::put('/update', [AlunoController::class, 'update']);
        Route::delete('/delete', [AlunoController::class, 'delete']);
        Route::prefix('/prova')->group(function(){
            Route::prefix('/questao')->group(function(){
                Route::get('/', [AlunoController::class, 'obterQuestao']);
            });
        });
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
