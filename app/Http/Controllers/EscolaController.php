<?php

namespace App\Http\Controllers;

use App\Mail\DadosEscola;
use App\Models\Area;
use Illuminate\Http\Request;
use App\Models\Escola;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;

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

        if (count($request['areas']) > 0) {
            $areas = $request['areas'];
            $request->merge(['id_area1' => $areas[0]]);
            if (count($request['areas']) == 2) {
                $request->merge(['id_area2' => $areas[1]]);
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
        $senhaHash = Hash::make($senha);
        $request->merge(['senha' => $senhaHash]);
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
            'password' => $senhaHash,
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
            'senha' => $senha,
            'linkPortal' => 'http://localhost:5173/',
            'linkEmailDuvida' => "mailto:support@olimpiadasdosertaoprodutivo.com?subject=$nomeEscola - Dúvida em relação a Olímpiadas",
            'linkLogo' =>  'http://localhost:8000/api/img/public/logo'
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
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('username', 'password'))) {
            $dadosEscola = Escola::where('usuario', $request['username'])->first()->makeHidden('senha', 'id', 'created_at', 'updated_at');
            $area1 = Area::select('nome')->where('id', $dadosEscola['id_area1'])->get();
            $area2 = Area::select('nome')->where('id', $dadosEscola['id_area2'])->get();
            $dadosEscola->area1 = $area1;
            $dadosEscola->area2 = $area2;
            return $this->resposta(200, true, [
                'token' => $request->user()->createToken('loginEscola')->plainTextToken,
                'dadosEscola' => $dadosEscola
            ]);
        } else {
            abort(401, 'Credenciais incorretas');
        }
    }
}
