<?php

namespace Modules\Projeto\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use Illuminate\Support\Facades\Gate;

class ProjetoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('projeto::index');
    }

    public function buscarProjeto(Request $request)
    {
        $dadosRecebidos = $request->except('_token');
        $idUsuario = auth()->user()->id;

        if(Gate::allows('ADMINISTRADOR')){
            $filtroAdministrador = "AND 1 = 1";
        } else {
            $filtroAdministrador = "AND ((SELECT COUNT(*)
                                           FROM projeto_pessoa, pessoa
                                          WHERE pessoa.ID = projeto_pessoa.ID_PESSOA
                                            AND projeto_pessoa.ID_PROJETO = p.ID
                                            AND pessoa.STATUS = 'A'
                                            AND projeto_pessoa.STATUS = 'A'
                                            AND pessoa.ID_USUARIO = $idUsuario) > 0 
                                        OR p.ID_USUARIO_INSERCAO = $idUsuario)";
        }

        if (isset($dadosRecebidos['ID'])) {
            $filtroID = "AND p.ID = {$dadosRecebidos['ID']}";
        } else {
            $filtroID = "";
        }

        if (isset($dadosRecebidos['LIMIT'])) {
            $filtroLimit = "LIMIT {$dadosRecebidos['LIMIT']}";
        } else {
            $filtroLimit = "";
        }

        if (isset($dadosRecebidos['FILTRO_ADICIONAL'])) {
            $filtroAdicional = $dadosRecebidos['FILTRO_ADICIONAL'];
        } else {
            $filtroAdicional = "";
        }

        if (isset($dadosRecebidos['filtro']) && strlen($dadosRecebidos['filtro']) > 0) {
            $filtro = "AND (TITULO LIKE '%{$dadosRecebidos['filtro']}%'
                             OR DESCRICAO LIKE '%{$dadosRecebidos['filtro']}%'
                             OR YEAR(DATA_INICIO) = '{$dadosRecebidos['filtro']}'
                             OR (SELECT COUNT(*)
                                   FROM pessoa
                                  WHERE pessoa.ID = P.ID_CLIENTE
                                    AND NOME LIKE '%{$dadosRecebidos['filtro']}%') > 0)";
        } else {
            $filtro = "";
        }

        $query = "SELECT p.*
                       , (SELECT name
                            FROM users
                           WHERE id = p.ID_USUARIO_INSERCAO) AS NOME_USUARIO 
                       , (SELECT NOME
                            FROM pessoa
                           WHERE pessoa.ID = p.ID_CLIENTE) AS CLIENTE
                       , (SELECT SUM(VALOR)
                            FROM contas_pagar
                           WHERE contas_pagar.ID_PROJETO = p.ID
                             AND contas_pagar.STATUS = 'A') AS VALOR_GASTO
                       , ROUND(
                            (SELECT COUNT(*) 
                            FROM projeto_etapa 
                            WHERE projeto_etapa.ID_PROJETO = p.ID 
                                AND projeto_etapa.STATUS = 'A'
                                AND projeto_etapa.SITUACAO = 'Concluído') * 100.0 /
                            NULLIF(
                            (SELECT COUNT(*) 
                                FROM projeto_etapa 
                                WHERE projeto_etapa.ID_PROJETO = p.ID 
                                AND projeto_etapa.STATUS = 'A'), 0), 2) AS PORCENTAGEM_ETAPA
                    FROM projeto p
                   WHERE p.STATUS = 'A' 
                   $filtro
                   $filtroID
                   $filtroAdministrador 
                   $filtroAdicional
                   ORDER BY p.DATA_INSERCAO DESC
                   $filtroLimit";
        $result['dados'] = DB::select($query);
        $result['query'] = $query;

        for ($i=0; $i < count($result['dados']) ; $i++) {
            $idProjeto = $result['dados'][$i]->ID;

            $query = "SELECT *
                           , (SELECT name
                                FROM users
                               WHERE id = contas_pagar.ID_USUARIO) AS NOME_USUARIO 
                        FROM contas_pagar
                       WHERE STATUS = 'A'
                         AND ID_PROJETO = $idProjeto
                       ORDER BY DATA_INSERCAO DESC";
            $result['dados'][$i]->GASTOS_PROJETO = DB::select($query);

            $queryDocumento = "SELECT p.*
                                    , (SELECT TITULO
                                         FROM projeto
                                        WHERE ID = p.ID_PROJETO) AS PROJETO
                                 FROM projeto_documento p
                                WHERE p.STATUS = 'A' 
                                  AND ID_PROJETO = $idProjeto";
            $result['dados'][$i]->DOCUMENTO = DB::select($queryDocumento);
        }

        return response()->json($result);
    }

    public function buscarDocumento(Request $request)
    {
        $dadosRecebidos = $request->except('_token');
        $idProjeto = $dadosRecebidos['ID_PROJETO'];

        $query = "SELECT p.*
                       , (SELECT TITULO
                            FROM projeto
                           WHERE ID = p.ID_PROJETO) AS PROJETO
                    FROM projeto_documento p
                   WHERE p.STATUS = 'A' 
                     AND ID_PROJETO = $idProjeto";
        $result['dados'] = DB::select($query);

        return response()->json($result);
    }

    public function buscarPessoa(Request $request)
    {
        $dadosRecebidos = $request->except('_token');
        $idProjeto = $dadosRecebidos['ID_PROJETO'];

        $query = "SELECT p.*
                       , (SELECT NOME
                            FROM pessoa
                           WHERE ID = p.ID_PESSOA) AS PESSOA
                    FROM projeto_pessoa p
                   WHERE p.STATUS = 'A' 
                     AND ID_PROJETO = $idProjeto";
        $result['dados'] = DB::select($query);

        return response()->json($result);
    }

    public function buscarEtapa(Request $request)
    {
        $idProjeto = $request->input('ID_PROJETO');
        
        $etapas = DB::select("
            SELECT * 
            FROM projeto_etapa 
            WHERE ID_PROJETO = ? AND STATUS = 'A'
            ORDER BY DATA_INSERCAO DESC
        ", [$idProjeto]);

        return response()->json(['dados' => $etapas]);
    }

    // Inserir projeto
    public function inserirProjeto(Request $request)
    {
        $dadosRecebidos = $request->except('_token');

        $titulo = $dadosRecebidos['TITULO'];
        $descricao = $dadosRecebidos['DESCRICAO'];
        $dataInicio = $dadosRecebidos['DATA_INICIO'];
        $dataFim = $dadosRecebidos['DATA_FIM'];
        $valor = $dadosRecebidos['VALOR'];
        $ID_CLIENTE = $dadosRecebidos['ID_CLIENTE'];
        $idUsuario = auth()->user()->id; // Usuário logado

        $query = "
            INSERT INTO projeto (TITULO, DESCRICAO, DATA_INICIO, DATA_FIM, VALOR, ID_USUARIO_INSERCAO, ID_CLIENTE) 
            VALUES ('$titulo', '$descricao', '$dataInicio', '$dataFim', $valor, $idUsuario, $ID_CLIENTE)
        ";
        DB::insert($query);

        return response()->json(['success' => 'Projeto inserido com sucesso!']);
    }

    public function inserirDocumento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idProjeto = $dadosRecebidos['ID_PROJETO'];
        $caminhoArquivo = $dadosRecebidos['caminhoArquivo'];

        $query = "INSERT INTO projeto_documento (ID_PROJETO, CAMINHO_DOCUMENTO) 
                                    VALUES ($idProjeto, '$caminhoArquivo')";
        $result = DB::select($query);

        return $result;
    }

    public function inserirPessoa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idProjeto = $dadosRecebidos['ID_PROJETO'];
        $ID_PESSOA = $dadosRecebidos['ID_PESSOA'];

        $query = "INSERT INTO projeto_pessoa (ID_PROJETO, ID_PESSOA) 
                                    VALUES ($idProjeto, '$ID_PESSOA')";
        $result = DB::select($query);

        return $result;
    }

    public function inserirEtapa(Request $request)
    {
        $dados = $request->validate([
            'ID_PROJETO' => 'required|integer',
            'DESCRICAO' => 'required|string|max:200'
        ]);

        DB::insert("
            INSERT INTO projeto_etapa (ID_PROJETO, DESCRICAO, SITUACAO) 
            VALUES (?, ?, 'Pendente')
        ", [$dados['ID_PROJETO'], $dados['DESCRICAO']]);

        return response()->json(['success' => 'Etapa cadastrada com sucesso!']);
    }

    // Alterar projeto
    public function alterarProjeto(Request $request)
    {
        $dadosRecebidos = $request->except('_token');

        $idProjeto = $dadosRecebidos['ID'];
        $titulo = $dadosRecebidos['TITULO'];
        $descricao = $dadosRecebidos['DESCRICAO'];
        $dataInicio = $dadosRecebidos['DATA_INICIO'];
        $dataFim = $dadosRecebidos['DATA_FIM'];
        $valor = $dadosRecebidos['VALOR'];
        $ID_CLIENTE = $dadosRecebidos['ID_CLIENTE'];

        $query = "
            UPDATE projeto 
            SET TITULO = '$titulo', 
                DESCRICAO = '$descricao', 
                DATA_INICIO = '$dataInicio', 
                DATA_FIM = '$dataFim', 
                VALOR = $valor ,
                ID_CLIENTE = $ID_CLIENTE 
            WHERE ID = $idProjeto
        ";
        DB::update($query);

        return response()->json(['success' => 'Projeto alterado com sucesso!']);
    }

    // Inativar projeto
    public function inativarProjeto(Request $request)
    {
        $dadosRecebidos = $request->except('_token');
        $idProjeto = $dadosRecebidos['ID_PROJETO'];

        $query = "UPDATE projeto SET STATUS = 'I' WHERE ID = $idProjeto";
        DB::update($query);

        return response()->json(['success' => 'Projeto inativado com sucesso!']);
    }

    public function inativarEtapa(Request $request)
    {
        $dadosRecebidos = $request->except('_token');
        $idEtapa = $dadosRecebidos['ID_ETAPA'];

        $query = "UPDATE projeto_etapa SET STATUS = 'I' WHERE ID = $idEtapa";
        DB::update($query);

        return response()->json(['success' => 'Projeto inativado com sucesso!']);
    }

    public function inativarDocumento(Request $request)
    {
        $dadosRecebidos = $request->except('_token');
        $idDocumento = $dadosRecebidos['idDocumento'];

        $query = "UPDATE projeto_documento SET STATUS = 'I' WHERE ID = $idDocumento";
        DB::update($query);

        return response()->json(['success' => 'Documento inativado com sucesso!']);
    }

    public function concluirProjeto(Request $request)
    {
        $dadosRecebidos = $request->except('_token');
        $ID_PROJETO = $dadosRecebidos['ID_PROJETO'];
        $idUsuario = auth()->user()->id;

        $query = "UPDATE projeto 
                     SET PAGAMENTO_REALIZADO = 'S' 
                       , DATA_PAGAMENTO = NOW()
                       , ID_USUARIO_PAGAMENTO = $idUsuario
                   WHERE ID = $ID_PROJETO";
        DB::update($query);

        $queryDados = "SELECT *
                         FROM projeto
                        WHERE ID = $ID_PROJETO";
        $dadosProjeto = DB::select($queryDados)[0];

        $queryCRB = "INSERT INTO contas_receber ( DESCRICAO, DATA_VENCIMENTO, VALOR, OBSERVACAO, ID_ORIGEM, ID_PROJETO, ID_USUARIO) 
                            VALUES ('Projeto: {$dadosProjeto->ID} - {$dadosProjeto->TITULO}', '{$dadosProjeto->DATA_FIM}', {$dadosProjeto->VALOR}, 'Registro inserido automaticamente atravez da conclusão do projeto.', 3, $ID_PROJETO, $idUsuario)";
        $result = DB::select($queryCRB);

        return response()->json(['success' => 'Documento inativado com sucesso!']);
    }

    public function concluirEtapa(Request $request)
    {
        $idEtapa = $request->input('ID');
        
        DB::update("
            UPDATE projeto_etapa 
            SET SITUACAO = 'Concluído', 
                DATA_CONCLUSAO = NOW() 
            WHERE ID = ?
        ", [$idEtapa]);

        return response()->json(['success' => 'Etapa concluída com sucesso!']);
    }

    public function inativarPessoa(Request $request)
    {
        $dadosRecebidos = $request->except('_token');
        $ID = $dadosRecebidos['ID'];

        $query = "UPDATE projeto_pessoa SET STATUS = 'I' WHERE ID = $idDocumento";
        DB::update($query);

        return response()->json(['success' => 'Pessoa inativado com sucesso!']);
    }
}
