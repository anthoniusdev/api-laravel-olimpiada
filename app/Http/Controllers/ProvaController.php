<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Prova;
use Exception;

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
                    'modalidade' =>$request->input('modalidade')
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
