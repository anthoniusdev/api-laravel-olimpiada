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
        }else if(Aluno::where('cpf', $request['cpf'])->exists()){
            return response()->json(['msg' => 'Este CPF já foi cadastrado', 422]);
        } 
        else {
            //Tratando dados do react para se encaixar na API

            $request->merge(['codigo_escola' => $request->codigoEscola]);
            $request->request->remove('codigoEscola');

            // Tratamento das áreas
            if (count($request->areas) > 0) {
                $request->merge(['id_area' => $request->areas[0]]);
                if (count($request->areas) == 2) {
                    $request->merge(['id_area2' => $request->areas[1]]);
                }
            }

            // Geração do usuário
            $usuario = $this->gerandoUsuario($request->nome);
            $request->merge(['usuario' => $usuario]);
            
            //Gerando a senha para o aluno
            $senha = Str::random(20);
            $senhaHash = Hash::make($senha);
            $request->merge(['senha' => $senhaHash]);


            //Gerando o id para o aluno
            $id_aluno = Str::uuid();
            $request->merge(['id' => $id_aluno]);

            //Formatando os dados para inserir na tabela user
            $dados_user = [
                'username' => $usuario,
                'name' => $request->nome,
                'password' => $senhaHash,
                'tipo' => 'aluno',
                'id' => $id_aluno
            ];

            //inserindo os dados no BD
            User::create($dados_user);
            Aluno::create($request->all());

            /*
         * Enviando email com dados gerados automaticamente para o aluno
         */
           $this->envioEmail($request, $usuario, $senha);
        }

    }

    private function gerandoUsuario($nome){
        $usuario = str_replace(' ', '', $nome);
        $mapeamento = [
            'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u',
            'ç' => 'c',
        ];
        $usuario = strtr($usuario, $mapeamento);
        $usuario = Str::lower($usuario);
        $codigo = rand(1000, 9999);
        return $usuario . $codigo;
    }
    private function envioEmail($request, $usuario, $senha){
        $nomeAluno = $request->nome;
        $dados = [
            'nomeResponsavel' => $request->nome_responsavel,
            'nomeAluno' => $nomeAluno,
            'codigo' => $request->codigo_escola,
            'usuario' => $usuario,
            'senha' => $senha,
            'linkPortal' => 'http://localhost:5173/',
            'linkEmailDuvida' => "mailto:support@olimpiadasdosertaoprodutivo.com?subject=$nomeAluno' - Dúvida em relação a Olímpiadas",
            'linkLogo' =>  'http://localhost:8000/api/img/public/logo'
        ];
        $email = new DadosAluno($dados);
        Mail::to($request->email)->send($email);
        // -------------------------------------------------------
        return response()->json(["msg" => "O aluno foi cadastrado com sucesso!"], 200);
    }


    public function show(string $id)
    {
        return Aluno::findOrFail($id);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request)
    {
        $aluno = Aluno::where('cpf', $request->cpf)->first();
    
        if ($aluno) {
            $aluno->nome = $request->nome;
            $aluno->email = $request->email;
            $aluno->modalidade = $request->modalidade;
            $aluno->id_area = $request->id_area1;
            $aluno->id_area2 = $request->id_area2;
            $aluno->save();
    
            return response()->json(["msg" => "Cadastro do Aluno(a) $aluno->nome atualizado com sucesso"], 200);
        } else {
            return response()->json(["msg" => "Aluno não encontrado"], 404);
        }
    }
    
    public function delete(Request $request) {
        $aluno = Aluno::where('cpf', $request['cpf'])->first();

        if($aluno){
            $aluno->delete();
            return response()->json(["msg" => "Aluno(a) deletado(a) com sucesso"], 200);
        }else{
            return response()->json(["msg" => "Aluno não encontrado"], 404);
        }
    }

    public function getData()
    {
        $retorno = Aluno::select('usuario', 'nome', 'email', 'id_area', 'codigo_escola', 'cpf')->get();
        return $retorno;
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('username', 'password'))) {
            $dadosAluno = Aluno::where('usuario', $request['username'])->first()->makeHidden('senha', 'created_at', 'updated_at');
            $nomeEscola = Escola::select('nome')->where('codigo_escola', $dadosAluno['codigo_escola'])->first();
            if ($nomeEscola !== null) {
                $dadosAluno->nomeEscola = $nomeEscola['nome'];
            }
            $area1 = Area::select('nome')->where('id', $dadosAluno['id_area'])->first();
            $area2 = Area::select('nome')->where('id', $dadosAluno['id_area2'])->first();
            if ($area1 !== null) {
                $dadosAluno->area1 = $area1['nome'];
            }
            if($area2 !== null){
                $dadosAluno->area2 = $area2['nome'];
            }else{
               
            }
            return $this->resposta(200, true, [
                'token' => $request->user()->createToken('loginAluno')->plainTextToken,
                'dadosAluno' => $dadosAluno
            ]);
        } else {
            return response()->json(['msg' => 'Credenciais incorretas'], 401);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
    public static function retornaID($cpf){
        $id = DB::select('SELECT id FROM alunos WHERE cpf = ?', [$cpf]);
        foreach ($id as $ids) {
            return ["id" => $ids->id];
        };
    }
}
