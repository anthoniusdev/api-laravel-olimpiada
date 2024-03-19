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
        if(count($request['areas']) > 0){
            $areas = $request['areas'];
            if(count($areas) > 1){
            $request->merge(['area1' => $areas[0], 'area2' => $areas[1]]);
            }else{
                $request->merge(['area1' => $areas[0]]);
            }
        }
        if (!$request['usuario']) {
            $request->merge(['usuario' => '']);
        }
        if (!$request['senha']) {
            $request->merge(['senha' => '']);
        }
        $id_escola = Str::uuid();
        $codigo_escola = Str::random(6);
        $request->merge(['codigo_escola' => $codigo_escola, 'id_escola' => $id_escola]);
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
