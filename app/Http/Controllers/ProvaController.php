<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Prova;
use App\Models\Responde;
use Exception;
use Illuminate\Support\Facades\DB;


class ProvaController extends Controller
{
    function resposta($codigo, $ok, $msg, $prova)
    {
        http_response_code($codigo);
        echo (json_encode([
            'ok' => $ok,
            'msg' => $msg,
            'fase' => $prova
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
            $id_prova = Str::uuid();

            $prova = Prova::create(
                [
                    'id' => $id_prova,
                    'id_fase' => $request->input('id_fase'),
                    'id_area' => $request->input('id_area'),
                    'modalidade' => $request->input('modalidade')
                ]
            );

            $this->resposta(200, true, "Prova cadastrada com sucesso!", $prova);
        } catch (Exception $e) {
            return response()->json([
                'ok' => false,
                'msg' => 'Erro ao cadastrar a Prova',
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
    public function iniciarProva(Request $request)
    {
        try {
            if ($request['id_area'] == 'IL933QzqrGA5eO4z') {
                $modalidade_aluno = 'a';
            } else {
                $modalidade_aluno = Aluno::select('modalidade')->where('usuario', $request['usuario'])->first();
                $modalidade_aluno = $modalidade_aluno->modalidade;
            }
            $aluno_id = $this->retornaID($request['usuario']);
            $aluno_id = $aluno_id['id'];
            $id_prova = DB::select('SELECT id FROM provas WHERE modalidade = ? AND id_area = ?', [$modalidade_aluno, $request['id_area']]);
            $id_prova = $id_prova[0]->id;
            $id = Str::uuid();
            $dados = [
                'id' => $id,
                'id_prova' => $id_prova,
                'id_aluno' => $aluno_id,
                'status' => 'iniciada',
                'data_hora_inicio' => now(),
                'pontuacao' => 0,
                'bool_classificado' => false
            ];
            Responde::create($dados);
            if (Responde::where('id', $id)->exists()) {
                return response()->json([
                    'ok' => true,
                    'msg' => 'Prova iniciada com sucesso'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro ao processar a requisicao',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function finalizarProva(Request $request)
    {
        try {
            $aluno_id = $this->retornaID($request['usuario']);
            $aluno_id = $aluno_id['id'];
            if ($request['id_area'] == 'IL933QzqrGA5eO4z') {
                $modalidade_aluno = 'a';
            } else {
                $modalidade_aluno = Aluno::select('modalidade')->where('usuario', $request['usuario'])->first();
                $modalidade_aluno = $modalidade_aluno->modalidade;
            }
            $id_prova = DB::select('SELECT id FROM provas WHERE modalidade = ? AND id_area = ?', [$modalidade_aluno, $request['id_area']]);
            $id_prova = $id_prova[0]->id;
            $atualizacao = Responde::where('id_aluno', $aluno_id)->where('id_prova', $id_prova)->update(['status' => 'finalizada']);
            if ($atualizacao > 0) {
                return response()->json([
                    'ok' => true,
                    'msg' => 'Prova finalizada com sucesso'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro ao processar a requisicao',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getStatus(Request $request)
    {
        try {
            $aluno_id = $this->retornaID($request['usuario'])['id'];
            if ($request['id_area'] == 'IL933QzqrGA5eO4z') {
                $modalidade_aluno = 'a';
            } else {
                $modalidade_aluno = Aluno::select('modalidade')->where('usuario', $request['usuario'])->first();
                $modalidade_aluno = $modalidade_aluno->modalidade;
            }
            $id_prova = DB::table('provas')
                ->where('modalidade', $modalidade_aluno)
                ->where('id_area', $request['id_area'])
                ->value('id');

            if (!$id_prova) {
                return response()->json([
                    'ok' => true,
                    'status' => 'nao_iniciada'
                ]);
            }

            $count_status = DB::table('respondes')
                ->where('id_aluno', $aluno_id)
                ->where('id_prova', $id_prova)
                ->count();

            if ($count_status > 0) {
                $status = optional(
                    Responde::select('status')
                        ->where('id_aluno', $aluno_id)
                        ->where('id_prova', $id_prova)
                        ->first()
                )->status;

                return response()->json([
                    'ok' => true,
                    'status' => $status
                ]);
            }else{
                return response()->json([
                    'ok' => true,
                    'status' => 'nao_iniciada'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro ao processar a requisicao',
                'message' => $e->getMessage()
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
