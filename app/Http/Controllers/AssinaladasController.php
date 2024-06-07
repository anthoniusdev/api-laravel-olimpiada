<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Area;
use App\Models\Assinala;
use App\Models\Prova;
use App\Models\QuestaoTemporaria;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssinaladasController extends Controller
{

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
            if ($request['id_area'] == 'IL933QzqrGA5eO4z') {
                $modalidade_aluno = 'a';
            } else {
                $modalidade_aluno = Aluno::select('modalidade')->where('usuario', $request['usuario'])->first();
                $modalidade_aluno = $modalidade_aluno->modalidade;
            }
            $id_prova = DB::select('SELECT id FROM provas WHERE modalidade = ? AND id_area = ?', [$modalidade_aluno, $request['id_area']]);
            $id_prova = $id_prova[0]->id;
            $aluno_id = $this->retornaID($request['usuario']);
            $aluno_id = $aluno_id['id'];
            $questoesTemporarias = DB::select('SELECT * from questao_temporarias qt where id_aluno = ? and id_questao in (select id from questaos where id_prova = ?)', [$aluno_id, $id_prova]);
            foreach ($questoesTemporarias as $questao) {
                Assinala::create([
                    'id_aluno' => $aluno_id,
                    'id_questao' => $questao->id_questao,
                    'id_alternativa_assinalada' => $questao->id_alternativa_assinalada
                ]);
                $assinalada = Assinala::where('id_aluno', $aluno_id)->where('id_questao', $questao->id_questao)->where('id_alternativa_assinalada', $questao->id_alternativa_assinalada)->first();
                echo "<h6>|id do aluno: $assinalada->id_aluno --- id da questão: $assinalada->id_questao --- id da alternativa assinalada: $assinalada->id_alternativa_assinalada |</h6>";
            }

            return response()->json([
                'ok' => true,
                'msg' => 'Prova assinalada',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'ok' => false,
                'msg' => 'Erro ao assinalar prova',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public static function retornaID($username)
    {
        $id = DB::select('SELECT id FROM alunos WHERE usuario = ?', [$username]);
        foreach ($id as $ids) {
            return ["id" => $ids->id];
        };
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
