<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\DadosAluno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AlunoController extends Controller
{
    function resposta($codigo, $ok, $msg)
    {
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
        /** 
         * Tratando dados do react para se encaixar na API
         */
        $request->merge(['codigo_escola' => $request['codigoEscola']]);
        $request->request->remove('codigoEscola');
        // -------------------------------------------------------
        
        /**
         * Buscando a ID da área no banco de dados pelo nome 
         */
        if ($request['area']) {
            $area = $request['area'];
            $id_area = DB::select("SELECT id FROM areas WHERE nome = ?", [$area]);
            $id_area = !empty($id_area) ? $id_area[0]->id : null;
            $request->merge(['id_area' => $id_area]);
        }else{
            $this->resposta(500, false, "Área não escolhida");
        }
        // -------------------------------------------------------
        /**
         * Buscando a ID da modalide no banco de dados pela sigla 
         */
        if ($request['modalidade']) {
            $sigla_modalidade = $request['modalidade'];
            $id_modalidade = DB::select("SELECT id FROM modalidades WHERE sigla = ?", [$sigla_modalidade]);
            $id_modalidade = !empty($id_modalidade) ? $id_modalidade[0]->id : null;
            $request->merge(['id_modalidade' => $id_modalidade]);
        }else{
            $this->resposta(500, false, "Modalidade não escolhida");
        }
        // -------------------------------------------------------

        /** 
         * Gerando o usuário para o aluno de forma automática 
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
         * Gerando a senha para o aluno de forma automática 
         */
        $senha = Str::random(20);
        $senhaHash = Hash::make($senha);
        $request->merge(['senha' => $senhaHash]);
        // -------------------------------------------------------

        /** 
         * Gerando o id para o aluno de forma automática 
         */
        $id_aluno = Str::uuid();
        $request->merge(['id' => $id_aluno]);
        // -------------------------------------------------------

        /**
         * Formatando os dados para inserir na tabela user
         */
        $dados_user = [
            'username' => $usuario,
            'name' => $request['nome'],
            'password' => $senhaHash,
            'tipo' => 'aluno',
            'id' => $id_aluno
        ];
        // -------------------------------------------------------
        User::create($dados_user);
        Aluno::create($request->all());

        /*
         * Enviando email com dados gerados automaticamente para o aluno
         */
        $nomeAluno = $request['nome'];
        $dados = [
            'nomeResponsavel' => $request['nome_responsavel'],
            'nomeAluno' => $nomeAluno,
            'codigo' => $request['codigo_escola'],
            'usuario' => $usuario,
            'senha' => $senha,
            'linkPortal' => 'http://localhost:5173/',
            'linkEmailDuvida' => "mailto:support@olimpiadasdosertaoprodutivo.com?subject=$nomeAluno' - Dúvida em relação a Olímpiadas",
            'linkLogo' =>  'http://localhost:8000/api/img/public/logo'
        ];
        $email = new DadosAluno($dados);
        Mail::to($request['email'])->send($email);
        // -------------------------------------------------------
        $this->resposta(200, true, "O aluno foi cadastro com sucesso");
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
