<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\QuestaoTemporaria;

class QuestaoTemporariaController extends Controller
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $assinala_questao_temporariamente = QuestaoTemporaria::create([
                'numeralQuestao' => $request->input('numero_questao'),
                'id_aluno' => $request->input('id_aluno'),
                'id_questao' => $request->input('id_questao'),
                'id_alternativa_assinalada' => $request->input('id_alternativa_assinalada')
            ]);
            $this->resposta(200, true, "Questao " . $request['numero_questao'] . " assinalada temporariamente", $assinala_questao_temporariamente);
        } catch (Exception $e) {
            return response()->json([
                'ok' => false,
                'msg' => 'Erro ao assinalar questão ' . $request['numero_questao'] . ' temporariamente',
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
