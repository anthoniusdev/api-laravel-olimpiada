<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Escola;
use Illuminate\Support\Str;
use App\Models\geraCodigoEscola;

class EscolaController extends Controller
{
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
        if (count($request['areas']) > 0) {
            $areas = $request['areas'];
            if (count($areas) > 1) {
                $request->merge(['id_area1' => '2bqsoM57nkhPztAg', 'id_area2' => '4Euj4TVkkutnI2nx']); 
                Editar para colocar o id certinho de forma automática. 
            } else {
                $request->merge(['id_area1' => $areas[0]]);
            }
        }
        // Gerando o usuário para a escola de forma automática ---
        $usuario = $request['nome'];
        $usuario = str_replace(' ', '', $usuario);
        $usuario = iconv('UTF-8', 'ASCII//TRANSLIT', $usuario);
        $codigo = rand(1000, 9999);
        $usuario = $usuario . $codigo;
        $request->merge(['usuario' => $usuario]);
        // -------------------------------------------------------
        if (!$request['senha']) {
            $request->merge(['senha' => '']);
        }
        $id_escola = Str::uuid();
        $codigo_escola = Str::random(6);
        $request->merge(['codigo_escola' => $codigo_escola, 'id' => $id_escola]);
        Escola::create($request->except(['areas']));
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
