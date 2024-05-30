<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Questao;
use Exception;

class QuestaoController extends Controller
{
    function resposta($codigo, $ok, $msg)
    {
        http_response_code($codigo);
        echo (json_encode([
            'ok' => $ok,
            'msg' => $msg
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
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $idQuestao = Str::uuid();

            $questao = [
                'id' => $idQuestao,
                'titulo' => $request->input('titulo'),
                'id_prova' => $request->input('id_prova'),
                'id_fase' => $request->input('id_fase'),
                'id_alternativa_correta' => $request->input('id_alternativa_correta')
            ];
            
            Questao::create($questao);
    
            $this->resposta(200, true, "Questao cadastrada com sucesso!");
    
        } catch (Exception $e) {
            return response()->json([
                'ok' => false,
                'msg' => 'Erro ao cadastrar a Alternativa',
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
