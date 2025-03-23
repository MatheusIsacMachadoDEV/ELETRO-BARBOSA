<?php

namespace Modules\Financeiro\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use DateTime;
use PDF;

class FinanceiroController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('financeiro::index');
    }

    public function despesaObra()
    {
        $tipo = 3;
        return view('contaspagar', compact('tipo'));
    }

    public function despesaEmpresa()
    {
        $tipo = 2;
        return view('contaspagar', compact('tipo'));
    }

    public function diaria()
    {
        return view('financeiro::diaria');
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

    public function buscarDiaria(Request $request)
    {
        $dadosRecebidos = $request->except('_token');

        if (isset($dadosRecebidos['filtro']) && strlen($dadosRecebidos['filtro']) > 0) {
            $filtro = "AND (u.NOME LIKE '%{$dadosRecebidos['filtro']}%')";
        } else {
            $filtro = "";
        }

        $query = "SELECT d.*
                       , u.name AS NOME_USUARIO 
                    FROM diaria d
                   INNER JOIN users u ON d.ID_USUARIO = u.id
                   WHERE d.STATUS = 'A' $filtro
                   ORDER BY d.DATA_INICIO DESC";
        $result['dados'] = DB::select($query);

        return response()->json($result);
    }

    // Inserir diária
    public function inserirDiaria(Request $request)
    {
        $dadosRecebidos = $request->except('_token');

        $idUsuario = $dadosRecebidos['ID_USUARIO'];
        $dataInicio = $dadosRecebidos['DATA_INICIO'];
        $dataFim = $dadosRecebidos['DATA_FIM'];
        $valorDia = $dadosRecebidos['VALOR_DIA'];

        // Calcula o tempo em dias
        $tempoDias = (strtotime($dataFim) - strtotime($dataInicio)) / (60 * 60 * 24);
        $valorTotal = $tempoDias * $valorDia;

        $query = "
            INSERT INTO diaria (ID_USUARIO, DATA_INICIO, DATA_FIM, TEMPO_DIAS, VALOR_DIA, VALOR_TOTAL, STATUS) 
            VALUES ($idUsuario, '$dataInicio', '$dataFim', $tempoDias, $valorDia, $valorTotal, 'A')
        ";
        DB::insert($query);

        return response()->json(['success' => 'Diária inserida com sucesso!']);
    }

    // Alterar diária
    public function alterarDiaria(Request $request)
    {
        $dadosRecebidos = $request->except('_token');

        $idDiaria = $dadosRecebidos['ID'];
        $idUsuario = $dadosRecebidos['ID_USUARIO'];
        $dataInicio = $dadosRecebidos['DATA_INICIO'];
        $dataFim = $dadosRecebidos['DATA_FIM'];
        $valorDia = $dadosRecebidos['VALOR_DIA'];

        // Calcula o tempo em dias e o valor total
        $tempoDias = (strtotime($dataFim) - strtotime($dataInicio)) / (60 * 60 * 24);
        $valorTotal = $tempoDias * $valorDia;

        $query = "
            UPDATE diaria 
            SET ID_USUARIO = $idUsuario, 
                DATA_INICIO = '$dataInicio', 
                DATA_FIM = '$dataFim', 
                TEMPO_DIAS = $tempoDias, 
                VALOR_DIA = $valorDia, 
                VALOR_TOTAL = $valorTotal 
            WHERE ID = $idDiaria
        ";
        DB::update($query);

        return response()->json(['success' => 'Diária alterada com sucesso!']);
    }

    // Inativar diária
    public function inativarDiaria(Request $request)
    {
        $dadosRecebidos = $request->except('_token');
        $idDiaria = $dadosRecebidos['ID'];

        $query = "UPDATE diaria SET STATUS = 'I' WHERE ID = $idDiaria";
        DB::update($query);

        return response()->json(['success' => 'Diária inativada com sucesso!']);
    }

}
