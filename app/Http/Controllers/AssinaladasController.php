<?php

namespace App\Http\Controllers;

use App\Models\Assinala;
use App\Http\Controllers\AlunoController;
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
            $aluno_id = $this->retornaID($request['usuario']);
            $aluno_id = $aluno_id['id'];


            $assinala_questao = Assinala::create([
                'id_aluno' => $aluno_id,
                'id_questao' => $request->input('id_questao'),
                'id_alternativa_assinalada' => $request->input('id_alternativa_assinalada')
            ]);

            $alunoController = new AlunoController();
            $resultado = $alunoController->validarProvaRespondida($request);

            //tratando a resposta json retornada pela funcao
            $status_prova = json_decode($resultado->getContent(), true);

            return response()->json([
                'ok' => true,
                'msg' => 'Questao assinalada',
                'assinala_questao' => $assinala_questao,
                'status_prova' => $status_prova
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'ok' => false,
                'msg' => 'Erro ao assinalar questão',
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
