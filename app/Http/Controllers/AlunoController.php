<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\DadosAluno;
use App\Models\Area;
use App\Models\Escola;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

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
        if (Aluno::where('email', $request['email'])->exists()) {
            return response()->json([
                'msg' => 'Este email já foi cadastrado'
            ], 422);
        } else {
            /** 
             * Tratando dados do react para se encaixar na API
             */

            $request->merge(['codigo_escola' => $request['codigoEscola']]);
            $request->request->remove('codigoEscola');
            // -------------------------------------------------------

            if (count($request['areas']) > 0) {
                $areas = $request['areas'];
                $request->merge(['id_area' => $areas[0]]);
                if (count($request['areas']) == 2) {
                    $request->merge(['id_area2' => $areas[1]]);
                }
            }

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
    public function getData()
    {
        $retorno = Aluno::select('usuario', 'nome', 'email', 'id_area', 'codigo_escola', 'cpf')->get();
        return $retorno;
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('username', 'password'))) {
            $dadosAluno = Aluno::where('usuario', $request['username'])->first()->makeHidden('id', 'senha', 'created_at', 'updated_at');
            $nomeEscola = Escola::select('nome')->where('codigo_escola', $dadosAluno['codigo_escola'])->first();
            if ($nomeEscola !== null) {
                $dadosAluno->nomeEscola = $nomeEscola['nome'];
            }
            $area1 = Area::select('nome')->where('id', $dadosAluno['id_area'])->first();
            if ($area1 !== null) {
                $dadosAluno->area = $area1['nome'];
            }
            return $this->resposta(200, true, [
                'token' => $request->user()->createToken('loginAluno')->plainTextToken,
                'dadosAluno' => $dadosAluno
            ]);
        } else {
            abort(401, 'Credenciais incorretas');
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}
