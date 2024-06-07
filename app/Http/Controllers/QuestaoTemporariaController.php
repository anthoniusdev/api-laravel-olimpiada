<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\QuestaoTemporaria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public static function retornaID($username)
    {
        $id = DB::select('SELECT id FROM alunos WHERE usuario = ?', [$username]);
        foreach ($id as $ids) {
            return ["id" => $ids->id];
        };
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
            $aluno_id = $this->retornaID($request['usuario']);
            $aluno_id = $aluno_id['id'];

            $alunoController = new AlunoController();
            $resultado = $alunoController->verificarTempoProva($request);

            //tratando a resposta json retornada pela funcao
            $status_prova = json_decode($resultado->getContent(), true);

            $questaoTemporaria = QuestaoTemporaria::where('id_aluno', $aluno_id)->where('id_questao', $request['id_questao'])->update(['id_alternativa_assinalada' => $request['id_alternativa_assinalada']]);
            if ($questaoTemporaria > 0) {
                return response()->json([
                    'ok' => true,
                    'msg' => 'Resposta atualizada com sucesso',
                    'status_prova' => $status_prova
                ]);
            } else {
                $dados_questao = [
                    'numeralQuestao' => $request->input('numero_questao'),
                    'id_aluno' => $aluno_id,
                    'id_questao' => $request->input('id_questao'),
                    'id_alternativa_assinalada' => $request->input('id_alternativa_assinalada')
                ];
                QuestaoTemporaria::create($dados_questao);
                return response()->json([
                    'msg' => "Questao " . $request['numero_questao'] . " assinalada temporariamente",
                    'status_prova' => $status_prova
                ]);
            }

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
