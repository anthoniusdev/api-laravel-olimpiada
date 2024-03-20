<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Escola;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EscolaController extends Controller
{
    function resposta($codigo, $ok, $msg){
        // header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Headers: *");
        // header("Content-Type: application/json");
    
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
        return Escola::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /**
         * Buscando a ID da área no banco de dados pelo nome 
         */
        if (count($request['areas']) > 0) {
            $areas = $request['areas'];
            $id_area1 = DB::select("SELECT id FROM areas WHERE nome = ?", [$areas[0]]);
            $id_area1 = !empty($id_area1) ? $id_area1[0]->id : null ;
            $request->merge(['id_area1' => $id_area1]);
            // $id_area2 = '';
            if (count($areas) > 1) {
                $id_area2 = DB::select("SELECT id FROM areas WHERE nome = ?", [$areas[1]]);
                $id_area2 = !empty($id_area2) ? $id_area2[0]->id : null ;
            }
            // $request->merge(['id_area2' => $id_area2]);
        }
        // -------------------------------------------------------

        /** 
         * Gerando o usuário para a escola de forma automática 
         */
        $usuario = $request['nome'];
        $usuario = str_replace(' ', '', $usuario);
        $mapeamento = array(
            'á' => 'a',
            'à' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'í' => 'i',
            'ì' => 'i',
            'î' => 'i',
            'ó' => 'o',
            'ò' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ú' => 'u',
            'ù' => 'u',
            'û' => 'u',
            'ç' => 'c',
        );
        $usuario = strtr($usuario, $mapeamento);
        $codigo = rand(1000, 9999);
        $usuario = $usuario . $codigo;
        $request->merge(['usuario' => $usuario]);
        // -------------------------------------------------------

        /** 
         * Gerando a senha para a escola de forma automática 
         */
        $senha = Str::random(20);
        $request->merge(['senha' => $senha]);
        // -------------------------------------------------------

        /** 
         * Gerando o ID para a escola de forma automática 
         */
        $id_escola = Str::uuid();
        $codigo_escola = Str::random(6);
        $request->merge(['codigo_escola' => $codigo_escola, 'id' => $id_escola]);
        // -------------------------------------------------------
    
        Escola::create($request->except(['areas']));
        $this->resposta(200, true, "Escola cadastrada com sucesso");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Escola::findOrFail($id);
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
