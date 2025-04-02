<?php

namespace Modules\Financeiro\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use DateTime;
use GuzzleHttp\Psr7\Query;
use PDF;

class FinanceiroController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(){
        return view('financeiro::index');
    }

    public function despesaObra(){
        $tipo = 3;
        return view('contaspagar', compact('tipo'));
    }

    public function despesaEmpresa(){
        $tipo = 2;
        return view('contaspagar', compact('tipo'));
    }

    public function diaria(){
        return view('financeiro::diaria');
    }

    public function folhaPagamento(){
        return view('financeiro::folha-pagamento');
    }

    public function faturamento(){
        return view('financeiro::faturamento');
    }

    public function buscarCRB(Request $request){
        $dadosRecebidos = $request->except('_token');

        if(isset($dadosRecebidos['dataInicio']) && isset($dadosRecebidos['dataTermino'])){
            $filtroData = "AND DATA_VENCIMENTO >= '".$dadosRecebidos['dataInicio']."'
                           AND DATA_VENCIMENTO <= '".$dadosRecebidos['dataTermino']."'";
        } else if(isset($dadosRecebidos['dataTermino']) && !isset($dadosRecebidos['dataInicio'])){
            $filtroData = "AND DATA_VENCIMENTO <= '".$dadosRecebidos['dataTermino']."'";
        } else if(isset($dadosRecebidos['dataInicio']) && !isset($dadosRecebidos['dataTermino'])){
            $filtroData = "AND DATA_VENCIMENTO >= '".$dadosRecebidos['dataInicio']."'";
        } else {
            $filtroData = "AND 1 = 1";
        }

        if(isset($dadosRecebidos['idCPG'])){
            $filtro = "AND ID = {$dadosRecebidos['idCPG']}";
        } else if(isset($dadosRecebidos['filtro'])){            
            $filtro = "AND UPPER(DESCRICAO) LIKE UPPER('%".$dadosRecebidos['filtro']."%')";
        } else {
            $filtro = "AND 1 = 1";
        }

        if(isset($dadosRecebidos['filtroSituacao']) && $dadosRecebidos['filtroSituacao'] != 'T'){
            $filtroSituacao = "AND SITUACAO = '{$dadosRecebidos['filtroSituacao']}'";
        } else {
            $filtroSituacao = "AND 1 = 1";
        }

        $query = "SELECT *
                    FROM contas_receber
                   WHERE STATUS = 'A'
                   $filtro
                   $filtroData
                   $filtroSituacao
                   ORDER BY DATA_VENCIMENTO ASC";
        $result = DB::select($query);

        return $result;
    }

    public function buscarContaBancaria(Request $request){
        $dadosRecebidos = $request->except('_token');

        if(isset($dadosRecebidos['id'])){
            $filtro = "AND ID_PESSOA = {$dadosRecebidos['id']}";
        } else {
            $filtro = "AND 1 = 1";
        }

        $query = "SELECT *
                    FROM conta_bancaria
                   WHERE STATUS = 'A'
                   $filtro
                   ORDER BY BANCO ASC";
        $result = DB::select($query);

        return $result;
    }

    public function inserirCRB(Request $request){
        $dadosRecebidos = $request->except('_token');
        $valor = $dadosRecebidos['valor'];
        $descricao = $dadosRecebidos['descricao'];
        $dataVencimento = $dadosRecebidos['dataVencimento'];
        $observacao = $dadosRecebidos['observacao'];
        $ID_ORIGEM = $dadosRecebidos['ID_ORIGEM'];

        $query = "INSERT INTO contas_receber (ID_USUARIO, DESCRICAO, DATA_VENCIMENTO, VALOR, OBSERVACAO, ID_ORIGEM) 
                                    VALUES (0, '$descricao', '$dataVencimento', $valor, '$observacao', $ID_ORIGEM)";
        $result = DB::select($query);

        return $result;
    }

    public function inserirContaBancaria(Request $request){
        $dadosRecebidos = $request->except('_token');
        $ID_PESSOA = $dadosRecebidos['ID_PESSOA'];
        $BANCO = $dadosRecebidos['BANCO'];
        $AGENCIA = $dadosRecebidos['AGENCIA'];
        $NUMERO = $dadosRecebidos['NUMERO'];
        $PIX = isset($dadosRecebidos['PIX']) ? $dadosRecebidos['PIX'] : '';

        $query = "INSERT INTO conta_bancaria
                            (ID_PESSOA, BANCO, AGENCIA, NUMERO, PIX)
                            VALUES ($ID_PESSOA, '$BANCO', '$AGENCIA', '$NUMERO', '$PIX')";
        $result = DB::select($query);

        return $result;
    }

    public function inserirPagamentoCRB(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['idCPG'];
        $dataPagamento = $dadosRecebidos['dataPagamento'];

        $query = "UPDATE contas_receber 
                     SET DATA_PAGAMENTO = '$dataPagamento'
                       , SITUACAO = 'PAGA'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function alterarCRB(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['idCodigo'];
        $valor = $dadosRecebidos['valor'];
        $dataVencimento = $dadosRecebidos['dataVencimento'];
        // $dataPagamento = $dadosRecebidos['dataPagamento'];
        $observacao = $dadosRecebidos['observacao'];
        $ID_ORIGEM = $dadosRecebidos['ID_ORIGEM'];

        $query = "UPDATE contas_receber 
                     SET OBSERVACAO = '$observacao'
                       , DATA_VENCIMENTO = '$dataVencimento'
                       , VALOR = $valor
                       , OBSERVACAO = '$observacao'
                       , ID_ORIGEM = $ID_ORIGEM
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function inativarCRB(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['idCPG'];

        $query = "UPDATE contas_receber 
                     SET STATUS = 'I'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function inativarContaBancaria(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['id'];

        $query = "UPDATE conta_bancaria 
                     SET STATUS = 'I'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function buscarDocumentoCRB(Request $request){
        $dadosRecebidos = $request->except('_token');

        if(isset($dadosRecebidos['idConta'])){
            $filtro = "AND ID_CPG = {$dadosRecebidos['idConta']}";
        } else {
            $filtro = "AND 1 = 1";
        }

        $query = "SELECT cpg_documento.*, (SELECT DESCRICAO
                                                 FROM contas_receber
                                                WHERE ID = cpg_documento.ID_CPG) AS DESCRICAO_CPG
                    FROM cpg_documento
                   WHERE STATUS = 'A'
                   $filtro";
        $result = DB::select($query);

        return $result;
    }

    public function inserirDocumentoCRB(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCPG = $dadosRecebidos['idCPG'];
        $caminhoArquivo = $dadosRecebidos['caminhoArquivo'];

        $query = "INSERT INTO cpg_documento (ID_CPG, CAMINHO_DOCUMENTO) 
                                    VALUES ($idCPG, '$caminhoArquivo')";
        $result = DB::select($query);

        return $result;
    }
    
    public function inativarDocumentoCRB(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idDocumento = $dadosRecebidos['idDocumento'];

        $query = "UPDATE cpg_documento 
                     SET STATUS = 'I'
                    WHERE ID = $idDocumento";
        $result = DB::select($query);

        return $result;
    }

    public function buscarDiaria(Request $request){
        $dadosRecebidos = $request->except('_token');

        if (isset($dadosRecebidos['filtro']) && strlen($dadosRecebidos['filtro']) > 0) {
            $filtro = "AND (u.NOME LIKE '%{$dadosRecebidos['filtro']}%')";
        } else {
            $filtro = "";
        }

        $query = "SELECT diaria.*
                       , (SELECT NOME
                            FROM pessoa
                           WHERE pessoa.ID = diaria.ID_USUARIO) AS NOME_USUARIO 
                    FROM diaria 
                   WHERE diaria.STATUS = 'A' 
                   $filtro
                   ORDER BY diaria.DATA_INICIO DESC";
        $result['dados'] = DB::select($query);

        return response()->json($result);
    }

    public function inserirDiaria(Request $request){
        $dadosRecebidos = $request->except('_token');

        $idFuncionario = $dadosRecebidos['ID_USUARIO'];
        $funcionario = $dadosRecebidos['USUARIO'];
        $dataInicio = $dadosRecebidos['DATA_INICIO'];
        $dataFim = $dadosRecebidos['DATA_FIM'];
        $valorDia = $dadosRecebidos['VALOR_DIA'];
        $descricao = $dadosRecebidos['DESCRICAO'];
        $idUsuarioInsercao = auth()->user()->id;
        $codEvento = substr(md5(uniqid()), 0, 6);

        // Calcula o tempo em dias
        $tempoDias = (strtotime($dataFim) - strtotime($dataInicio)) / (60 * 60 * 24);
        $valorTotal = $tempoDias * $valorDia;

        $queryUsuario = "SELECT ID_USUARIO
                           FROM pessoa
                          WHERE ID = $idFuncionario";
        $idUsuario = DB::select($queryUsuario)[0]->ID_USUARIO;

        $query = "INSERT INTO diaria (DESCRICAO, ID_USUARIO, DATA_INICIO, DATA_FIM, TEMPO_DIAS, VALOR_DIA, VALOR_TOTAL, STATUS, ID_USUARIO_INSERCAO) 
                              VALUES ('$descricao', $idUsuario , '$dataInicio', '$dataFim', $tempoDias, $valorDia, $valorTotal, 'A', $idUsuarioInsercao)";
        DB::insert($query);

        $dataInicioDiaria = "$dataInicio 08:00:00";
        $dataFimDiaria = "$dataFim 18:00:00";

        $queryEvento = "INSERT INTO agenda_evento(ID_USUARIO, CODEVENTO, TITULO, RESPONSAVEL, ID_LOCAL, PRECO, OBSERVACAO, COR_EVENTO, DATA_INI, DATA_FIM, CLIENTE, DIA_TODO, ID_PESSOA, VISUALIZADO, DATA_VISUALIZACAO)
                                    VALUES( $idUsuario, '$codEvento', 'Diária: $descricao', '$funcionario', 0, 0, 'Diária: $descricao - Duração:  $tempoDias - Valor: $valorTotal', '_b1hix88l9', '$dataInicioDiaria', '$dataFimDiaria', 'gss_eletro_barbosa', 0, 0, 'N', NULL)";
        DB::select($queryEvento);

        return response()->json(['success' => 'Diária inserida com sucesso!']);
    }

    public function alterarDiaria(Request $request){
        $dadosRecebidos = $request->except('_token');

        $idDiaria = $dadosRecebidos['ID'];
        $idUsuario = $dadosRecebidos['ID_USUARIO'];
        $dataInicio = $dadosRecebidos['DATA_INICIO'];
        $dataFim = $dadosRecebidos['DATA_FIM'];
        $valorDia = $dadosRecebidos['VALOR_DIA'];
        $descricao = $dadosRecebidos['DESCRICAO'];

        // Calcula o tempo em dias e o valor total
        $tempoDias = (strtotime($dataFim) - strtotime($dataInicio)) / (60 * 60 * 24);
        $valorTotal = $tempoDias * $valorDia;

        $query = "UPDATE diaria 
                     SET ID_USUARIO = $idUsuario, 
                         DATA_INICIO = '$dataInicio', 
                         DATA_FIM = '$dataFim', 
                         TEMPO_DIAS = $tempoDias, 
                         VALOR_DIA = $valorDia, 
                         VALOR_TOTAL = $valorTotal ,
                         DESCRICAO = $descricao
                   WHERE ID = $idDiaria";
        DB::update($query);

        return response()->json(['success' => 'Diária alterada com sucesso!']);
    }

    public function inativarDiaria(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idDiaria = $dadosRecebidos['ID'];

        $query = "UPDATE diaria SET STATUS = 'I' WHERE ID = $idDiaria";
        DB::update($query);

        return response()->json(['success' => 'Diária inativada com sucesso!']);
    }

    public function pagarDiaria(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idDiaria = $dadosRecebidos['ID'];
        $idUsuario = auth()->user()->id;

        $query = "UPDATE diaria 
                     SET PAGAMENTO_REALIZADO = 'S'
                       , DATA_PAGAMENTO = NOW()
                   WHERE ID = $idDiaria";
        DB::update($query);

        $queryDadosDiaria = "SELECT DESCRICAO
                                  , VALOR_TOTAL
                               FROM diaria
                              WHERE ID = $idDiaria";
        $dadosDiaria = DB::select($queryDadosDiaria)[0];

        $descricaoDiaria = $dadosDiaria->DESCRICAO;
        $valorDiaria = $dadosDiaria->VALOR_TOTAL;

        $queryCPG = "INSERT INTO contas_pagar (ID_USUARIO, DESCRICAO, DATA_VENCIMENTO, VALOR, SITUACAO, DATA_PAGAMENTO, OBSERVACAO, ID_ORIGEM) 
                                    VALUES ($idUsuario, '$descricaoDiaria', now(), $valorDiaria, 'PAGA', now(), 'CPG referente à diária: $idDiaria - $descricaoDiaria', 6)";
        $result = DB::select($queryCPG);

        return response()->json(['success' => 'Diária paga com sucesso!']);
    }

    public function buscarPagamentoPessoa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtro = isset($dadosRecebidos['filtro']) ? $dadosRecebidos['filtro'] : '';
        
        $query = "SELECT pp.*
                       , p.NOME AS NOME_PESSOA
                       , u.name AS NOME_USUARIO
                       , p.SALARIO_BASE
                       , p.HORAS_MENSAIS
                  FROM pessoa_pagamento pp
                  JOIN pessoa p ON pp.ID_PESSOA = p.ID
                  LEFT JOIN users u ON pp.ID_USUARIO = u.id
                  WHERE pp.STATUS = 'A' 
                  AND (p.NOME LIKE '%$filtro%' OR pp.OBSERVACAO LIKE '%$filtro%')
                  ORDER BY pp.DATA_AGENDAMENTO DESC";
        
        $result['dados'] = DB::select($query);
        
        return response()->json($result);
    }

    public function inserirPagamentoPessoa(Request $request){
        $dadosRecebidos = $request->except('_token');

        $idPessoa = $dadosRecebidos['ID_PESSOA'];
        $valor = $dadosRecebidos['VALOR'];
        $observacao = isset($dadosRecebidos['OBSERVACAO']) ? $dadosRecebidos['OBSERVACAO'] : '';
        $dataAgendamento = $dadosRecebidos['DATA_AGENDAMENTO'];
        
        $HORAS_REGISTRADAS = isset($dadosRecebidos['HORAS_REGISTRADAS']) ? $dadosRecebidos['HORAS_REGISTRADAS'] : 0;
        $HORAS_MENSAIS = isset($dadosRecebidos['HORAS_MENSAIS']) ? $dadosRecebidos['HORAS_MENSAIS'] : 0;
        $HORAS_EXTRAS = isset($dadosRecebidos['HORAS_EXTRAS']) ? $dadosRecebidos['HORAS_EXTRAS'] : 0;
        $VALOR_EXTRAS = isset($dadosRecebidos['VALOR_EXTRAS']) ? $dadosRecebidos['VALOR_EXTRAS'] : 0;
        $VALOR_DIARIAS = isset($dadosRecebidos['VALOR_DIARIAS']) ? $dadosRecebidos['VALOR_DIARIAS'] : 0;
        $VALOR_DESCONTOS = isset($dadosRecebidos['VALOR_DESCONTOS']) ? $dadosRecebidos['VALOR_DESCONTOS'] : 0;
        $VALOR_INSS = isset($dadosRecebidos['VALOR_INSS']) ? $dadosRecebidos['VALOR_INSS'] : 0;
        $PERIODO = isset($dadosRecebidos['PERIODO']) ? $dadosRecebidos['PERIODO'] : 0;
        $idUsuario = auth()->user()->id;
        $codEvento = substr(md5(uniqid()), 0, 6);

        // Busca o nome da pessoa
        $queryPessoa = "SELECT NOME FROM pessoa WHERE ID = $idPessoa";
        $nomePessoa = DB::select($queryPessoa)[0]->NOME;

        $query = "INSERT INTO pessoa_pagamento(ID_PESSOA, VALOR,  OBSERVACAO, DATA_AGENDAMENTO, ID_USUARIO, HORAS_REGISTRADAS, HORAS_MENSAIS, HORAS_EXTRAS, VALOR_HORA_EXTRA, VALOR_DIARIA, VALOR_DESCONTO, VALOR_INSS, PERIODO) 
                  VALUES ($idPessoa, $valor,'$observacao', '$dataAgendamento', $idUsuario, $HORAS_REGISTRADAS, $HORAS_MENSAIS, $HORAS_EXTRAS, $VALOR_EXTRAS, $VALOR_DIARIAS, $VALOR_DESCONTOS, $VALOR_INSS, $PERIODO)";
        
        DB::insert($query);

        $queryAgenda = "SELECT ID_USUARIO
                          FROM pessoa
                         WHERE pessoa.ID = $idPessoa";
        $idUsuarioPessoa = DB::select($queryAgenda)[0]->ID_USUARIO;

        $queryEvento = "INSERT INTO agenda_evento(ID_USUARIO, CODEVENTO, TITULO, RESPONSAVEL, ID_LOCAL, PRECO, OBSERVACAO, COR_EVENTO, DATA_INI, DATA_FIM, CLIENTE, DIA_TODO, ID_PESSOA, VISUALIZADO, DATA_VISUALIZACAO)
                                    VALUES( $idUsuarioPessoa, '$codEvento', 'Pagamento', '$nomePessoa', 0, $valor, 'O seu pagamento foi agendado para esta data!', '_b1hix88l9', '$dataAgendamento', '$dataAgendamento', 'gss_eletro_barbosa', 0, 0, 'N', NULL)";
        DB::select($queryEvento);

        return response()->json(['success' => 'Pagamento agendado com sucesso!']);
    }

    public function alterarPagamentoPessoa(Request $request){
        $dadosRecebidos = $request->except('_token');

        $idPagamento = $dadosRecebidos['ID'];
        $idPessoa = $dadosRecebidos['ID_PESSOA'];
        $valor = $dadosRecebidos['VALOR'];
        $observacao = isset($dadosRecebidos['OBSERVACAO']) ? $dadosRecebidos['OBSERVACAO'] : '';
        $dataAgendamento = $dadosRecebidos['DATA_AGENDAMENTO'];

        $HORAS_REGISTRADAS = isset($dadosRecebidos['HORAS_REGISTRADAS']) ? $dadosRecebidos['HORAS_REGISTRADAS'] : 0;
        $HORAS_MENSAIS = isset($dadosRecebidos['HORAS_MENSAIS']) ? $dadosRecebidos['HORAS_MENSAIS'] : 0;
        $HORAS_EXTRAS = isset($dadosRecebidos['HORAS_EXTRAS']) ? $dadosRecebidos['HORAS_EXTRAS'] : 0;
        $VALOR_EXTRAS = isset($dadosRecebidos['VALOR_EXTRAS']) ? $dadosRecebidos['VALOR_EXTRAS'] : 0;
        $VALOR_DIARIAS = isset($dadosRecebidos['VALOR_DIARIAS']) ? $dadosRecebidos['VALOR_DIARIAS'] : 0;
        $VALOR_DESCONTOS = isset($dadosRecebidos['VALOR_DESCONTOS']) ? $dadosRecebidos['VALOR_DESCONTOS'] : 0;
        $VALOR_INSS = isset($dadosRecebidos['VALOR_INSS']) ? $dadosRecebidos['VALOR_INSS'] : 0;

        $query = "UPDATE pessoa_pagamento 
                  SET ID_PESSOA = $idPessoa 
                    , VALOR = $valor,
                    , OBSERVACAO = '$observacao',
                    , DATA_AGENDAMENTO = '$dataAgendamento'
                    , HORAS_REGISTRADAS = $HORAS_REGISTRADAS
                    , HORAS_MENSAIS = $HORAS_MENSAIS
                    , HORAS_EXTRAS = $HORAS_EXTRAS
                    , VALOR_HORA_EXTRA = $VALOR_EXTRAS
                    , VALOR_DIARIA = $VALOR_DIARIAS
                    , VALOR_DESCONTO = $VALOR_DESCONTOS
                    , VALOR_INSS = $VALOR_INSS
                  WHERE ID = $idPagamento";
        
        DB::update($query);

        return response()->json(['success' => 'Pagamento atualizado com sucesso!']);
    }

    public function inativarPagamentoPessoa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idPagamento = $dadosRecebidos['ID'];

        $query = "UPDATE pessoa_pagamento 
                     SET STATUS = 'I' 
                  WHERE ID = $idPagamento";
        
        DB::update($query);

        return response()->json(['success' => 'Pagamento removido com sucesso!']);
    }

    public function confirmarPagamentoPessoa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idPagamento = $dadosRecebidos['ID'];
        $idUsuario = auth()->user()->id;

        $query = "UPDATE pessoa_pagamento 
                     SET PAGO = 'S'
                       , DATA_PAGAMENTO = NOW()
                   WHERE ID = $idPagamento";
        
        DB::update($query);

        $queryPessoa = "SELECT *
                       , (SELECT NOME
                            FROM pessoa
                           WHERE pessoa.ID = pessoa_pagamento.ID_PESSOA) as NOME
                    FROM pessoa_pagamento
                   WHERE ID = $idPagamento";        
        $dadosPessoa = DB::select($queryPessoa)[0];

        $descricaoPagamento = "Pagamento para {$dadosPessoa->NOME}";

        $queryCPG = "INSERT INTO contas_pagar (ID_USUARIO, DESCRICAO, DATA_VENCIMENTO, VALOR, SITUACAO, DATA_PAGAMENTO, OBSERVACAO, ID_ORIGEM) 
                                       VALUES ($idUsuario, '$descricaoPagamento', now(), {$dadosPessoa->VALOR}, 'PAGA', now(), 'CPG automático referente ao pagamento para a pessoa: {$dadosPessoa->NOME}. ID Pagamento $idPagamento.', 7)";
        $result = DB::select($queryCPG);

        return response()->json(['success' => 'Pagamento confirmado com sucesso!']);
    }

    public function imprimirFolhaPagamento($id){
        $data = (new DateTime())->format('d/m/Y H:i');
        
        // Consulta principal para obter os dados do pagamento
        $queryPagamento = "SELECT pessoa_pagamento.*
                                , pessoa.NOME as NOME_FUNCIONARIO
                                , CARGO AS FUNCAO
                                , pessoa.SALARIO_BASE
                            FROM pessoa_pagamento
                            JOIN pessoa ON pessoa_pagamento.ID_PESSOA = pessoa.ID
                        WHERE pessoa_pagamento.STATUS = 'A'
                            AND pessoa_pagamento.ID = $id";
        $dadosPagamento = DB::select($queryPagamento)[0];
        
        // Consulta para obter dados do empregador
        $queryEmpregador = "SELECT empresa_cliente.*
                            FROM empresa_cliente
                            WHERE ID = 1";
        $dadosEmpresa = DB::select($queryEmpregador)[0];
        
        // Preparar os dados para a view (agora usando os nomes de variáveis que estão na view)
        $data = [
            'dataEmissao' => $data,
            'dadosPagamento' => $dadosPagamento,
            'dadosEmpresa' => $dadosEmpresa
        ];
        
        // Carregar a view passando os dados
        $pdf = PDF::loadView('financeiro::impressos.folha-pagamento', $data);
        
        // Corrigindo o nome do arquivo (havia um typo em NOME_FUNCIONARIO)
        $nomeArquivo = 'Holerite_' . date('m_Y', strtotime($dadosPagamento->DATA_AGENDAMENTO)) . '.pdf';
        
        return $pdf->stream($nomeArquivo);
    }

    public function buscarDadosFaturamento(Request $request)
    {
        $dadosRecebidos = $request->except('_token');
        $dataInicio = $dadosRecebidos['data_inicio'];
        $dataFim = $dadosRecebidos['data_fim'];

        // Dados totais
        $totais = [
            'total_a_receber' => $this->getTotalContasReceber($dataInicio, $dataFim, false),
            'total_recebido' => $this->getTotalContasReceber($dataInicio, $dataFim, true),
            'total_a_pagar' => $this->getTotalContasPagar($dataInicio, $dataFim, false),
            'total_pago' => $this->getTotalContasPagar($dataInicio, $dataFim, true)
        ];

        // Dados para gráficos
        $graficos = [
            'receber' => $this->getDadosGraficoReceber($dataInicio, $dataFim),
            'pagar' => $this->getDadosGraficoPagar($dataInicio, $dataFim)
        ];

        // Últimas contas
        $ultimasContas = [
            'receber' => $this->getUltimasContasReceber(),
            'pagar' => $this->getUltimasContasPagar()
        ];

        return response()->json([
            'totais' => $totais,
            'graficos' => $graficos,
            'ultimas_contas' => $ultimasContas
        ]);
    }

    private function getTotalContasReceber($dataInicio, $dataFim, $pago)
    {
        $filtroPago = $pago ? "AND SITUACAO = 'PAGA'" : "AND SITUACAO != 'PAGA'";
        
        $query = "SELECT SUM(VALOR) AS total
                    FROM contas_receber
                   WHERE STATUS = 'A'
                     AND DATA_VENCIMENTO BETWEEN '$dataInicio' AND '$dataFim'
                  $filtroPago";
        
        $result = DB::select($query);
        return $result[0]->total ?? 0;
    }

    private function getTotalContasPagar($dataInicio, $dataFim, $pago)
    {
        $filtroPago = $pago ? "AND SITUACAO = 'PAGA'" : "AND SITUACAO != 'PAGA'";
        
        $query = "SELECT SUM(VALOR) AS total
                  FROM contas_pagar
                  WHERE STATUS = 'A'
                  AND DATA_VENCIMENTO BETWEEN '$dataInicio' AND '$dataFim'
                  $filtroPago";
        
        $result = DB::select($query);
        return $result[0]->total ?? 0;
    }

    private function getDadosGraficoReceber($dataInicio, $dataFim)
    {
        $query = "SELECT DATE_FORMAT(DATA_PAGAMENTO, '%d/%m') AS dia,
                         SUM(VALOR) AS total
                  FROM contas_receber
                  WHERE STATUS = 'A'
                  AND SITUACAO = 'PAGA'
                  AND DATA_PAGAMENTO BETWEEN '$dataInicio' AND '$dataFim'
                  GROUP BY DATA_PAGAMENTO
                  ORDER BY DATA_PAGAMENTO";
        
        $result = DB::select($query);
        
        $labels = [];
        $valores = [];
        
        foreach ($result as $item) {
            $labels[] = $item->dia;
            $valores[] = $item->total;
        }
        
        return [
            'labels' => $labels,
            'valores' => $valores
        ];
    }

    private function getDadosGraficoPagar($dataInicio, $dataFim)
    {
        $query = "SELECT DATE_FORMAT(DATA_PAGAMENTO, '%d/%m') AS dia,
                         SUM(VALOR) AS total
                  FROM contas_pagar
                  WHERE STATUS = 'A'
                  AND SITUACAO = 'PAGA'
                  AND DATA_PAGAMENTO BETWEEN '$dataInicio' AND '$dataFim'
                  GROUP BY DATA_PAGAMENTO
                  ORDER BY DATA_PAGAMENTO";
        
        $result = DB::select($query);
        
        $labels = [];
        $valores = [];
        
        foreach ($result as $item) {
            $labels[] = $item->dia;
            $valores[] = $item->total;
        }
        
        return [
            'labels' => $labels,
            'valores' => $valores
        ];
    }

    private function getUltimasContasReceber()
    {
        $query = "SELECT *
                  FROM contas_receber
                  WHERE STATUS = 'A'
                  ORDER BY DATA_VENCIMENTO DESC
                  LIMIT 10";
        
        return DB::select($query);
    }

    private function getUltimasContasPagar()
    {
        $query = "SELECT *
                  FROM contas_pagar
                  WHERE STATUS = 'A'
                  ORDER BY DATA_VENCIMENTO DESC
                  LIMIT 10";
        
        return DB::select($query);
    }

}
