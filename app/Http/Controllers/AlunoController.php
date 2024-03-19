<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AlunoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Aluno::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request['usuario']) {
            $request->merge(['usuario' => '']);
        }
        if (!$request['senha']) {
            $request->merge(['senha' => '']);
        }
        if (!$request['codigo_escola']) {
            $request->merge(['codigo_escola' => '']);
        }
        $id_aluno = Str::uuid();
        $request->merge(['id_aluno' => $id_aluno]);
        Aluno::create($request->all());
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Aluno::findOrFail($id);
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
