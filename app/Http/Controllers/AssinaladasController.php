<?php

namespace App\Http\Controllers;

use App\Models\Assinala;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssinaladasController extends Controller
{
    function resposta($codigo, $ok, $msg, $assinalada)
    {
        http_response_code($codigo);
        echo (json_encode([
            'ok' => $ok,
            'msg' => $msg,
            'fase' => $assinalada
        ]));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $id_aluno = Auth::user()->id;
            $assinala_questao = Assinala::create([
                'id_aluno' => $request->input('id_aluno'),
                'id_questao' => $request->input('id_questao'),
                'id_alternativa_assinalada' => $request->input('id_alternativa_assinalada')
            ]);
            $this->resposta(200, true, 'Questao assinalada', $assinala_questao);
        
        } catch (Exception $e) {
            return response()->json([
                'ok' => false,
                'msg' => 'Erro ao assinalar questão',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
