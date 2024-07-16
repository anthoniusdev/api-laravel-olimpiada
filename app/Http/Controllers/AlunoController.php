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
use App\Models\Responde;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;
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
        } else if (Aluno::where('cpf', $request['cpf'])->exists()) {
            return response()->json(['msg' => 'Este CPF já foi cadastrado', 422]);
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
                'linkPortal' => 'https://olimpiadasdosertaoprodutivo.com/',
                'linkEmailDuvida' => "mailto:support@olimpiadasdosertaoprodutivo.com?subject=$nomeAluno' - Dúvida em relação a Olímpiadas",
                'linkLogo' =>  'https://api.olimpiadasdosertaoprodutivo.com/api/img/public/logo'
            ];
            $email = new DadosAluno($dados);
            Mail::to($request['email'])->send($email);
            // -------------------------------------------------------
            return response()->json(["msg" => "O aluno foi cadastro com sucesso"], 200);
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
    public function update(Request $request)
    {
        $aluno = Aluno::where('cpf', $request['cpf'])->first();
        $aluno['nome'] = $request['nome'];
        $aluno['modalidade'] = $request['modalidade'];
        $aluno['id_area'] = $request['id_area1'];
        $aluno['id_area2'] = $request['id_area2'];
        $aluno->save();
        return response()->json(["msg" => "Cadastro do Aluno(a) $aluno->nome atualizado com sucesso"], 200);
    }
    public function delete(Request $request)
    {
        Aluno::where('cpf', $request['cpf'])->first()->delete();
        return response()->json(["msg" => "Aluno(a) deletado(a) com sucesso"], 200);
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
            $dadosAluno = Aluno::where('usuario', $request['username'])->first();
            if ($dadosAluno) {
                $dadosAluno = $dadosAluno->makeHidden('id', 'senha', 'created_at', 'updated_at');
                $dadosAluno['idAluno'] = $this->retornaID($dadosAluno['usuario']);
                $dadosAluno['idAluno'] = $dadosAluno['idAluno']['id'];
                $nomeEscola = Escola::select('nome')->where('codigo_escola', $dadosAluno['codigo_escola'])->first();
                if ($nomeEscola !== null) {
                    $dadosAluno->nomeEscola = $nomeEscola['nome'];
                }
                $area1 = Area::select('nome')->where('id', $dadosAluno['id_area'])->first();
                $area2 = Area::select('nome')->where('id', $dadosAluno['id_area2'])->first();
                if ($area1 !== null) {
                    $dadosAluno->area1 = $area1['nome'];
                }
                if ($area2 !== null) {
                    $dadosAluno->area2 = $area2['nome'];
                }

                return $this->resposta(200, true, [
                    'token' => $request->user()->createToken('loginAluno')->plainTextToken,
                    'dadosAluno' => $dadosAluno
                ]);
            }
        }
        return response()->json(['msg' => 'Credenciais incorretas'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
    public static function retornaID($username)
    {
        $id = DB::select('SELECT id FROM alunos WHERE usuario = ?', [$username]);
        foreach ($id as $ids) {
            return ["id" => $ids->id];
        };
    }


    public function obterQuestaoAleatoria(Request $request)
    {
        // Obtém o ID do aluno  -- funcionando
        $aluno_id = $this->retornaID($request['usuario']);
        $aluno_id = $aluno_id['id'];
        // Obtém a modalidade de aluno -- funcionando
        if ($request['id_area'] == 'IL933QzqrGA5eO4z') {
            $modalidade_aluno = 'a';
        } else {
            $modalidade_aluno = Aluno::select('modalidade')->where('id', $aluno_id)->first();
            $modalidade_aluno = $modalidade_aluno->modalidade;
        }
        // Verificando se a questão já foi resgastada
        $questao = DB::select('SELECT q.id , qt.id_alternativa_assinalada, q.path_img, q.titulo from questao_temporarias qt inner join questaos q on q.id = qt.id_questao inner join provas p on q.id_prova = p.id inner join areas a on a.id = p.id_area where qt.numeralQuestao = ? and qt.id_aluno = ? and p.modalidade = ? and a.id = ?', [$request['numero_questao'], $aluno_id, $modalidade_aluno, $request['id_area']]);
        if (count($questao) > 0) { // Se sim, é só pegar as alternativas
            $questao = $questao[0];
            $alternativas = DB::select('SELECT id, texto as alternativa FROM alternativas WHERE id_questao = ?', [$questao->id]);
        } else { // Se não, tem que preparar questões novas

            // Prepara a consulta para obter uma questão que não foi resgastada
            $questoesNaoRespondidas = DB::select('SELECT q.id, q.titulo, q.path_img FROM questaos q INNER JOIN provas p ON p.id = q.id_prova INNER JOIN areas a ON a.id = p.id_area WHERE p.modalidade = ? AND p.id_area = ? and q.id not in (SELECT id_questao FROM questao_temporarias WHERE id_aluno = ?)', [$modalidade_aluno, $request['id_area'], $aluno_id]);
            if (count($questoesNaoRespondidas) > 0) {
                // Seleciona uma questão aleatória do array de questões não resgastadas
                $questao = $questoesNaoRespondidas[array_rand($questoesNaoRespondidas)];

                $alternativas = DB::select('SELECT id, texto as alternativa FROM alternativas WHERE id_questao = ?', [$questao->id]);
                DB::insert('INSERT INTO questao_temporarias (id_aluno, id_questao, id_alternativa_assinalada, numeralQuestao) values (?, ?, ?, ?)', [$aluno_id, $questao->id, null, $request['numero_questao']]);
            } else {
                $questao = null;
                $alternativas = null;
            }
        }
        return response()->json([
            'questao' => $questao,
            'alternativas' => $alternativas,
        ])->withHeaders([
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Accept' => '*'
        ])->send();
    }
    // public function validarProvaRespondida(Request $request)
    // {
    //     try {
    //         $aluno_id = $this->retornaID(Auth::user()->username);
    //         $aluno_id = $aluno_id['id'];

    //         $totalQuestoes = DB::table('questaos')->count();

    //         // Obtém o número de questões respondidas pelo aluno
    //         $questoesRespondidas = DB::table('questao_temporarias')
    //             ->where('id_aluno', $aluno_id)
    //             ->distinct('id_questao') 
    //             ->count('id_questao');


    //         $provaRespondida = $questoesRespondidas >= $totalQuestoes ? true : false;

    //         return response()->json([
    //             'prova_encerrada' => false,
    //             'prova_respondida' => $provaRespondida
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'error' => 'Erro ao processar a requisicao',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    public function verificarTempoProva(Request $request)
    {
        $aluno_id = $this->retornaID($request['usuario']);
        $aluno_id = $aluno_id['id'];
        if ($request['id_area'] == 'IL933QzqrGA5eO4z') {
            $modalidade_aluno = 'a';
        } else {
            $modalidade_aluno = Aluno::select('modalidade')->where('usuario', $request['usuario'])->first();
            $modalidade_aluno = $modalidade_aluno->modalidade;
        }
        $id_prova = DB::select('SELECT id FROM provas WHERE modalidade = ? AND id_area = ?', [$modalidade_aluno, $request['id_area']]);
        $id_prova = $id_prova[0]->id;
        // Obtém a data e hora de criação da prova para o aluno
        $prova = DB::table('respondes')->where('id_aluno', $aluno_id)->where('id_prova', $id_prova)->first();
        // echo json_encode($prova);
        if (!$prova) {
            return response()->json([
                'error' => 'prova não_iniciada'
            ], 404);
        }

        // // Calcula o tempo decorrido desde o início da prova
        // echo $prova->data_hora_inicio;
        $inicioProva = Carbon::parse($prova->data_hora_inicio);
        $tempoDecorrido = $inicioProva->diffInMinutes(Carbon::now());

        //verifica com qtd de minutos
        if ($tempoDecorrido > 120) {
            Responde::where('id_aluno', $aluno_id)->where('id_prova', $id_prova)->update(['status' => 'finalizada']);
            return response()->json([
                'prova_encerrada' => true,
                'mensagem' => 'O tempo maximo para realizar a prova foi excedido.'
            ]);
        } else {
            return response()->json([
                'prova_encerrada' => false,
                'mensagem' => 'Faltam ' . 120 - $tempoDecorrido . ' minutos para acabar o tempo.'
            ]);
        }
    }


    public function result(Request $request)
    {
        // Obter o ID do aluno
        $aluno_id = $this->retornaID($request['usuario']);
        $aluno_id = $aluno_id['id'];

        // Obter as áreas do aluno
        $area1 = DB::select('select id_area from alunos where id = ?', [$aluno_id])[0]->id_area ?? null;
        $area2 = DB::select('select id_area2 from alunos where id = ?', [$aluno_id])[0]->id_area2 ?? null;

        // Query para obter dados e calcular acertos para a área 1
        $query1 = DB::select("
        SELECT DISTINCT
            a.nome as 'Nome do aluno', 
            e.nome as 'Nome da escola', 
            ar.nome as 'Nome da área', 
            a.modalidade, 
            ass.id_alternativa_assinalada,
            q.id_alternativa_correta 
        FROM 
            assinalas ass 
            INNER JOIN alunos a on a.id = ass.id_aluno 
            INNER JOIN escolas e on e.codigo_escola = a.codigo_escola 
            INNER JOIN questaos q on q.id = ass.id_questao 
            INNER JOIN provas p on p.id = q.id_prova 
            INNER JOIN areas ar on ar.id = p.id_area 
        WHERE 
            a.id = ? 
            AND ar.id = ? 
    ", [$aluno_id, $area1]);

        $query2 = DB::select("
        SELECT DISTINCT
            a.nome as 'Nome do aluno', 
            e.nome as 'Nome da escola', 
            ar.nome as 'Nome da área', 
            a.modalidade, 
            ass.id_alternativa_assinalada,
            q.id_alternativa_correta 
        FROM 
            assinalas ass 
            INNER JOIN alunos a on a.id = ass.id_aluno 
            INNER JOIN escolas e on e.codigo_escola = a.codigo_escola 
            INNER JOIN questaos q on q.id = ass.id_questao 
            INNER JOIN provas p on p.id = q.id_prova 
            INNER JOIN areas ar on ar.id = p.id_area 
        WHERE 
            a.id = ? 
            AND ar.id = ? 
    ", [$aluno_id, $area2]);

        // Contar os acertos para a área 1
        $acertos1 = collect($query1)->filter(function ($item) {
            return isset($item->id_alternativa_assinalada) && isset($item->id_alternativa_correta) && $item->id_alternativa_assinalada == $item->id_alternativa_correta;
        })->count();

        $acertos2 = collect($query2)->filter(function ($item) {
            return isset($item->id_alternativa_assinalada) && isset($item->id_alternativa_correta) && $item->id_alternativa_assinalada == $item->id_alternativa_correta;
        })->count();

        // Retornar os resultados
        return response()->json([
            'acertos1' => $acertos1,
            'acertos2' => $acertos2,
        ]);
    }
}
