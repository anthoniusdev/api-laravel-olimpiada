<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questao;
use App\Models\Alternativa;
use Exception;
use Illuminate\Support\Str;


class AlternativaController extends Controller
{
    function resposta($codigo, $ok, $msg, $alternativa)
    {
        http_response_code($codigo);
        echo (json_encode([
            'ok' => $ok,
            'msg' => $msg,
            'alternativa' => $alternativa
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
        $questao_existe = Questao::where('id', $request->input('id_questao'))->exists();
        $alternativa_existe = Alternativa::where('texto', $request->input('texto'))->exists();

        if (!$questao_existe) {
            return response()->json(['msg' => 'Questão inexistente'], 422);
        }else if($alternativa_existe){
            return response()->json(['msg' => 'Alternativa já existe'], 422);
        }else{
            try {
                $id_alternativa = Str::uuid();
    
                $alternativa = [
                    'id' => $id_alternativa,
                    'texto' => $request->input('texto'),
                    'id_questao' => $request->input('id_questao')
                ];
        
                $alternativa = Alternativa::create($alternativa);
        
                $this->resposta(200, true, "Alternativa criada com sucesso", $alternativa);
                
            } catch (Exception $e) {
                return response()->json([
                    'ok' => false,
                    'msg' => 'Erro ao cadastrar a Alternativa',
                    'error' => $e->getMessage()
                ], 500);
            }
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
