<?php

namespace App\Http\Controllers;

use App\Mail\DadosEscola;
use Illuminate\Http\Request;
use App\Models\Escola;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EscolaController extends Controller
{
    function resposta($codigo, $ok, $msg)
    {
        http_response_code($codigo);
        echo (json_encode([
            'ok' => $ok,
            'msg' => $msg
        ]));
    }
    function enviarEmail($usuario, $codigo, $senha)
    {
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
         * Tratando dados do react para se encaixar na API
         */
        $request->merge(['nome_responsavel' => $request['nomeResponsavel'], 'cpf_responsavel' => $request['cpfResponsavel']]);
        $request->request->remove('nomeResponsavel');
        $request->request->remove('cpfResponsavel');
        // -------------------------------------------------------

        /**
         * Buscando a ID da área no banco de dados pelo nome 
         */
        if (count($request['areas']) > 0) {
            $areas = $request['areas'];
            $id_area1 = DB::select("SELECT id FROM areas WHERE nome = ?", [$areas[0]]);
            $id_area1 = !empty($id_area1) ? $id_area1[0]->id : null;
            $request->merge(['id_area1' => $id_area1]);
            if (count($areas) > 1) {
                $id_area2 = DB::select("SELECT id FROM areas WHERE nome = ?", [$areas[1]]);
                $id_area2 = !empty($id_area2) ? $id_area2[0]->id : null;
                $request->merge(['id_area2' => $id_area2]);
            }
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
        $usuario = Str::lower($usuario);
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
         * Gerando o ID e o código para a escola de forma automática 
         */
        $id_escola = Str::uuid();
        $codigo_escola = Str::random(6);
        $request->merge(['codigo_escola' => $codigo_escola, 'id' => $id_escola]);
        // -------------------------------------------------------

        /**
         * Formatando os dados para inserir na tabela user
         */
        $dados_user = [
            'username' => $usuario,
            'name' => $request['nome'],
            'password' => $senha,
            'tipo' => 'escola',
            'id' => $id_escola
        ];
        // -------------------------------------------------------

        User::create($dados_user);
        Escola::create($request->except('areas'));

        /*
         * Enviando email com dados gerados automaticamente para escola
         */
        $nomeEscola = $request['nome'];
        $dados = [
            'nomeResponsavel' => $request['nome_responsavel'],
            'nomeEscola' => $nomeEscola,
            'codigo' => $request['codigo_escola'],
            'usuario' => $request['usuario'],
            'senha' => $request['senha'],
            'linkPortal' => 'http://localhost:5173/',
            'linkEmailDuvida' => "mailto:support@olimpiadasdosertaoprodutivo.com?subject=$nomeEscola - Dúvida em relação a Olímpiadas"
        ];
        $email = new DadosEscola($dados);
        Mail::to($request['email'])->send($email);
        // -------------------------------------------------------

        /**
         * Enviando resposta para o frontend
         */
        $this->resposta(200, true, "Escola cadastrada com sucesso");
        // -------------------------------------------------------
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
