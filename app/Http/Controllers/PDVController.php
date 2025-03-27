<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DateTime;
use PDF;

class PDVController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('pdv');
    }

    public function produto()
    {
        return view('produto');
    }

    public function despesa()
    {
        return view('gastos');
    }

    public function contasPagar()
    {
        $tipo = 0;
        return view('contaspagar', compact('tipo'));
    }

    public function buscarProdutos(Request $request){
        $dadosRecebidos = $request->except('_token');
        if(strlen($dadosRecebidos['filtro']) > 0){
            $filtro = "AND (UPPER(DESCRICAO) LIKE UPPER('%".$dadosRecebidos['filtro']."%')
                            OR ID = '".$dadosRecebidos['filtro']."'
                            OR CODBARRAS LIKE '%{$dadosRecebidos['filtro']}%')";
        } else {
            $filtro = 'AND 1 = 1';
        }

        if(isset($dadosRecebidos['tipo'])){
            $filtroTipo = "AND (produto_pdv.TIPO = {$dadosRecebidos['tipo']}
                                or produto_pdv.TIPO = 3)";
        } else {
            $filtroTipo = "AND 1 = 1";
        }

        $query = "SELECT produto_pdv.*
                       , COALESCE((SELECT DESCRICAO
                                     FROM venda_pdv_item_classe
                                    WHERE venda_pdv_item_classe.STATUS = 'A'
                                      AND venda_pdv_item_classe.ID = produto_pdv.ID_ITEM_PDV_CLASSE), '-') as CLASSE
                    FROM produto_pdv
                   WHERE STATUS = 'A'
                   $filtro
                   $filtroTipo";
        $result = DB::select($query);

        return $result;
    }

    public function buscarVenda(Request $request){
        $dadosRecebidos = $request->except('_token');
        $result = [];

        if(isset($dadosRecebidos['dataInicio']) && isset($dadosRecebidos['dataTermino'])){
            $filtroData = "AND DATA >= '".$dadosRecebidos['dataInicio']."'
                           AND DATA <= '".$dadosRecebidos['dataTermino']."'";
        } else if(isset($dadosRecebidos['dataTermino']) && !isset($dadosRecebidos['dataInicio'])){
            $filtroData = "AND DATA <= '".$dadosRecebidos['dataTermino']."'";
        } else if(isset($dadosRecebidos['dataInicio']) && !isset($dadosRecebidos['dataTermino'])){
            $filtroData = "AND DATA >= '".$dadosRecebidos['dataInicio']."'";
        } else {
            $filtroData = "AND 1 = 1";
        }

        if(isset($dadosRecebidos['ID_VENDA'])){
            $filtroId = "AND ID = {$dadosRecebidos['ID_VENDA']}";
        } else {
            $filtroId = "AND 1 = 1";
        }

        $query = "SELECT venda_pdv.*
                    FROM venda_pdv
                   WHERE STATUS = 'A'
                   $filtroData
                   $filtroId
                   ORDER BY DATA DESC";
        $result['dadosVenda'] = DB::select($query);

        for ($i=0; $i < count($result['dadosVenda']); $i++) { 
            $idVenda = $result['dadosVenda'][$i]->ID;

            $queryItemVenda = "SELECT *
                                 FROM venda_pdv_item
                                WHERE ID_VENDA = $idVenda";
            $result['dadosVenda'][$i]->ITEM = DB::select($queryItemVenda);
        }

        return $result;
    }

    public function buscarDespesa(Request $request){
        $dadosRecebidos = $request->except('_token');

        if(isset($dadosRecebidos['dataInicio']) && isset($dadosRecebidos['dataTermino'])){
            $filtroData = "AND DATA >= '".$dadosRecebidos['dataInicio']."'
                           AND DATA <= '".$dadosRecebidos['dataTermino']."'";
        } else if(isset($dadosRecebidos['dataTermino']) && !isset($dadosRecebidos['dataInicio'])){
            $filtroData = "AND DATA <= '".$dadosRecebidos['dataTermino']."'";
        } else if(isset($dadosRecebidos['dataInicio']) && !isset($dadosRecebidos['dataTermino'])){
            $filtroData = "AND DATA >= '".$dadosRecebidos['dataInicio']."'";
        } else {
            $filtroData = "AND 1 = 1";
        }

        if(isset($dadosRecebidos['idGasto'])){
            $filtro = "AND ID = {$dadosRecebidos['idGasto']}";
        } else if(isset($dadosRecebidos['filtro'])){            
            $filtro = "AND UPPER(DESCRICAO) LIKE UPPER('%".$dadosRecebidos['filtro']."%')";
        } else {
            $filtro = "AND 1 = 1";
        }

        $query = "SELECT *
                    FROM despesa
                   WHERE STATUS = 'A'
                   $filtro
                   $filtroData
                   ORDER BY DATA DESC";
        $result = DB::select($query);

        return $result;
    }

    public function buscarCPG(Request $request){
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

        if(isset($dadosRecebidos['TIPO']) && $dadosRecebidos['TIPO'] != '0'){
            $filtroTipo = "AND ID_ORIGEM = '{$dadosRecebidos['TIPO']}'";
        } else {
            $filtroTipo = "AND 1 = 1";
        }

        $query = "SELECT *
                    FROM contas_pagar
                   WHERE STATUS = 'A'
                   $filtro
                   $filtroData
                   $filtroSituacao
                   $filtroTipo
                   ORDER BY DATA_VENCIMENTO ASC";
        $result = DB::select($query);

        return $result;
    }

    public function buscarVendaFiado(Request $request){
        $dadosRecebidos = $request->except('_token');

        if(isset($dadosRecebidos['idUsuario'])){
            $filtro = "AND venda_pdv_fiado.ID_PESSOA = {$dadosRecebidos['idUsuario']}";
        }  else {
            $filtro = "AND 1 = 1";
        }

        $query = "SELECT ID_VENDA
                       , ID_PESSOA
                       , DATA
                       , VALOR_TOTAL
                    FROM venda_pdv_fiado, venda_pdv
                   WHERE venda_pdv_fiado.STATUS = 'A'
                     AND venda_pdv.STATUS = 'A'
                     AND venda_pdv_fiado.ID_VENDA = venda_pdv.ID
                   $filtro
                   ORDER BY DATA ASC";
        $result = DB::select($query);

        return $result;
    }

    public function buscarPagamentoFiado(Request $request){
        $dadosRecebidos = $request->except('_token');

        if(isset($dadosRecebidos['idUsuario'])){
            $filtro = "AND venda_pdv_fiado_pagamento.ID_PESSOA = {$dadosRecebidos['idUsuario']}";
        }  else {
            $filtro = "AND 1 = 1";
        }

        $query = "SELECT *
                    FROM venda_pdv_fiado_pagamento
                   WHERE venda_pdv_fiado_pagamento.STATUS = 'A'
                   $filtro
                   ORDER BY DATA ASC";
        $result = DB::select($query);

        return $result;
    }

    public function buscarDocumentoDespesa(Request $request){
        $dadosRecebidos = $request->except('_token');

        if(isset($dadosRecebidos['idDespesa'])){
            $filtro = "AND ID_DESPESA = {$dadosRecebidos['idDespesa']}";
        } else {
            $filtro = "AND 1 = 1";
        }

        $query = "SELECT despesa_documento.*, (SELECT DESCRICAO
                                                 FROM despesa
                                                WHERE ID = despesa_documento.ID_DESPESA) AS DESCRICAO_DESPESA
                    FROM despesa_documento
                   WHERE STATUS = 'A'
                   $filtro";
        $result = DB::select($query);

        return $result;
    }

    public function buscarDocumentoCPG(Request $request){
        $dadosRecebidos = $request->except('_token');

        if(isset($dadosRecebidos['idConta'])){
            $filtro = "AND ID_CPG = {$dadosRecebidos['idConta']}";
        } else {
            $filtro = "AND 1 = 1";
        }

        $query = "SELECT cpg_documento.*, (SELECT DESCRICAO
                                                 FROM contas_pagar
                                                WHERE ID = cpg_documento.ID_CPG) AS DESCRICAO_CPG
                    FROM cpg_documento
                   WHERE STATUS = 'A'
                   $filtro";
        $result = DB::select($query);

        return $result;
    }

    public function buscarItemVenda(Request $request){
        $dadosRecebidos = $request->except('_token');

        if(isset($dadosRecebidos['idVenda'])){
            $filtro = "AND ID_VENDA = {$dadosRecebidos['idVenda']}";
        } else {
            $filtro = "AND 1 = 1";
        }

        $query = "SELECT venda_pdv_item.*, COALESCE((SELECT UPPER(DESCRICAO)
                                                       FROM produto_pdv
                                                      WHERE ID = venda_pdv_item.ID_PRODUTO), venda_pdv_item.DESCRICAO_PRODUTO) AS PRODUTO
                    FROM venda_pdv_item
                   WHERE STATUS = 'A'
                   $filtro";
        $result = DB::select($query);

        return $result;
    }

    public function buscarClassse(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $return = [];
        
        $query = " SELECT venda_pdv_item_classe.*
                     FROM venda_pdv_item_classe
                    WHERE STATUS = 'A'";
        $result = DB::select($query);
        $return['dados'] = $result;
        
        return $return;
    }

    public function inserirProduto(Request $request){
        $dadosRecebidos = $request->except('_token');
        $descricao = $dadosRecebidos['descricao'];
        $valor = $dadosRecebidos['valor'];
        $tipoProduto = $dadosRecebidos['tipoProduto'];
        $QTDEProd = $dadosRecebidos['QTDE'];
        $valorCusto = isset($dadosRecebidos['VALOR_CUSTO']) ? $dadosRecebidos['VALOR_CUSTO'] : 0;
        $valorMinimo = isset($dadosRecebidos['VALOR_MINIMO']) ? $dadosRecebidos['VALOR_MINIMO'] : 0;
        $porcentagemMinima = isset($dadosRecebidos['PORCENTAGEM_MINIMA']) ? $dadosRecebidos['PORCENTAGEM_MINIMA'] : 0;
        $porcentagemVenda = isset($dadosRecebidos['PORCENTAGEM_VENDA']) ? $dadosRecebidos['PORCENTAGEM_VENDA'] : 0;
        $DETALHES = $dadosRecebidos['DETALHES'];
        $CONTROLA_ESTOQUE = $dadosRecebidos['CONTROLA_ESTOQUE'];
        $CLASSE = $dadosRecebidos['CLASSE'];
        $codBarras = '';
        
        if(isset($dadosRecebidos['codBarras'])){
            $codBarras = $dadosRecebidos['codBarras'];
        }

        $query = "INSERT INTO produto_pdv (DESCRICAO, VALOR, TIPO, CODBARRAS, QTDE, VALOR_CUSTO, PORCENTAGEM_MINIMA, PORCENTAGEM_VENDA, VALOR_MINIMO, DETALHES, CONTROLA_ESTOQUE, ID_ITEM_PDV_CLASSE) 
                                    VALUES ('$descricao', $valor, $tipoProduto, '$codBarras', $QTDEProd, '$valorCusto',  $porcentagemMinima, $porcentagemVenda, $valorMinimo, '$DETALHES', '$CONTROLA_ESTOQUE', $CLASSE)";
        $result = DB::select($query);

        return $result;
    }

    public function alterarProduto(Request $request){
        $dadosRecebidos = $request->except('_token');
        $descricao = $dadosRecebidos['descricao'];
        $valor = $dadosRecebidos['valor'];
        $idCodigo = $dadosRecebidos['id'];
        $tipoProduto = $dadosRecebidos['tipoProduto'];
        $QTDEProd = $dadosRecebidos['QTDE'];
        $valorCusto = $dadosRecebidos['VALOR_CUSTO'];
        $valorMinimo = isset($dadosRecebidos['VALOR_MINIMO']) ? $dadosRecebidos['VALOR_MINIMO'] : 0;
        $porcentagemMinima = isset($dadosRecebidos['PORCENTAGEM_MINIMA']) ? $dadosRecebidos['PORCENTAGEM_MINIMA'] : 0;
        $porcentagemVenda = isset($dadosRecebidos['PORCENTAGEM_VENDA']) ? $dadosRecebidos['PORCENTAGEM_VENDA'] : 0;
        $DETALHES = $dadosRecebidos['DETALHES'];
        $CONTROLA_ESTOQUE = $dadosRecebidos['CONTROLA_ESTOQUE'];
        $CLASSE = $dadosRecebidos['CLASSE'];
        $codBarras = '';

        if(isset($dadosRecebidos['codBarras'])){
            $codBarras = $dadosRecebidos['codBarras'];
        }

        $query = "UPDATE produto_pdv 
                     SET DESCRICAO = '$descricao'
                       , VALOR = $valor
                       , TIPO = $tipoProduto
                       , CODBARRAS = '$codBarras'
                       , QTDE = $QTDEProd
                       , VALOR_CUSTO = $valorCusto
                       , PORCENTAGEM_MINIMA = $porcentagemMinima
                       , PORCENTAGEM_VENDA = $porcentagemVenda
                       , VALOR_MINIMO = $valorMinimo
                       , DETALHES = '$DETALHES'
                       , CONTROLA_ESTOQUE = '$CONTROLA_ESTOQUE'
                       , ID_ITEM_PDV_CLASSE = '$CLASSE'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function inativarProduto(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['id'];

        $query = "UPDATE produto_pdv 
                     SET STATUS = 'I'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function inserirVenda(Request $request){
        $dadosRecebidos = $request->except('_token');
        $valorTotal = $dadosRecebidos['valorTotal'];    
        $pagamento = $dadosRecebidos['pagamento'];
        $dataVenda = $dadosRecebidos['dataVenda'];
        $pessoa = isset($dadosRecebidos['pessoa']) ? $dadosRecebidos['pessoa'] : '' ;

        $queryNovoId = "SELECT COALESCE(MAX(ID), 1) AS AUTO_INCREMENT
                          FROM venda_pdv
                         WHERE 1 = 1";

        $IdVenda = (DB::select($queryNovoId)[0]->AUTO_INCREMENT + 1);

        // INSERE A VENDA
        $query = "INSERT INTO venda_pdv (ID, ID_USUARIO, DATA, VALOR_TOTAL,PAGAMENTO, SITUACAO, CLIENTE_NOME) 
                                    VALUES ($IdVenda, 0, '$dataVenda', $valorTotal, $pagamento, 'CONFIRMADO', '$pessoa')";
        $result = DB::select($query);

        // INSERE OS ITENS DA VENDA
        for ($i=0; $i < count($dadosRecebidos['dadosItemPedido']); $i++) { 
            $idProdutoVenda = $dadosRecebidos['dadosItemPedido'][$i]['idItem'];
            $valorUnitario = $dadosRecebidos['dadosItemPedido'][$i]['valorUnitario'];
            $valorTotalItem = $dadosRecebidos['dadosItemPedido'][$i]['valorTotal']; 
            $descricaoItem = $dadosRecebidos['dadosItemPedido'][$i]['descItem']; 
            $valorCustoItem = isset($dadosRecebidos['dadosItemPedido'][$i]['valorCusto']) ? $dadosRecebidos['dadosItemPedido'][$i]['valorCusto'] : 0;
            $valorGastoItem = isset($dadosRecebidos['dadosItemPedido'][$i]['valorGasto']) ? $dadosRecebidos['dadosItemPedido'][$i]['valorGasto'] : 0;
            $porcentagem = isset($dadosRecebidos['dadosItemPedido'][$i]['porcentagem']) ? $dadosRecebidos['dadosItemPedido'][$i]['porcentagem'] : 0;
            $porcentagem = $porcentagem == 'NaN' ? 0 : $porcentagem;
            
            $qtde = $dadosRecebidos['dadosItemPedido'][$i]['qtde'];

            $queryInsertItemVenda = "INSERT INTO venda_pdv_item(ID_VENDA, ID_PRODUTO, VALOR_UNITARIO, VALOR_TOTAL, QUANTIDADE, VALOR_CUSTO, VALOR_GASTO, PORCENTAGEM, DESCRICAO_PRODUTO)
                                                        VALUES ($IdVenda, $idProdutoVenda, $valorUnitario, $valorTotalItem, $qtde, $valorCustoItem, $valorGastoItem, $porcentagem, '$descricaoItem')";
            $resultItemVenda = DB::select($queryInsertItemVenda);

            // VALIDAÇÃO SE O ITEM É UM PRODUTO
            $queryValidacaoQTDE = "SELECT COUNT(*) as COUNT
                                     FROM produto_pdv
                                    WHERE CONTROLA_ESTOQUE = 'S'
                                      AND ID = $idProdutoVenda";
            $resultValidacaoQTDE = DB::select($queryValidacaoQTDE)[0]->COUNT;

            // SE FOR UM PRODUTO , BAIXA A QTDE EM ESTOQUE
            if($resultValidacaoQTDE > 0){
                $queryUpdateQTDE = "UPDATE produto_pdv
                                       SET QTDE = (QTDE - $qtde)
                                     WHERE ID = $idProdutoVenda";
                $resultUpdateQTDE = DB::select($queryUpdateQTDE);
            }
        }

        // SE FOR FIADO INSERE O FIADO
        if($pagamento == 4){
            $idUsuarioFiado = $dadosRecebidos['idPessoaFiado'];

            $queryFiado = "INSERT INTO venda_pdv_fiado ( ID_VENDA, ID_PESSOA, VALOR_FIADO)
                                            VALUES($IdVenda, $idUsuarioFiado, $valorTotal)";
            $resultFiado = DB::select($queryFiado);
        }   

        return $result;
    }

    public function alterarVenda(Request $request){
        $dadosRecebidos = $request->except('_token');
        $ID = $dadosRecebidos['ID'];
        $dataVenda = $dadosRecebidos['dataVenda'];
        $pagamento = $dadosRecebidos['pagamento'];
        $valorTotal = $dadosRecebidos['valorTotal'];
        $pessoa = $dadosRecebidos['pessoa'];
        $dadosItemPedido = $dadosRecebidos['dadosItemPedido'];

        $query = "UPDATE venda_pdv 
                     SET DATA = '$dataVenda'
                        , VALOR_TOTAL = '$valorTotal'
                        , PAGAMENTO = '$pagamento'
                        , CLIENTE_NOME = '$pessoa'
                    WHERE ID = $ID";
        $result = DB::select($query);

        $querDeleteItens = "DELETE FROM venda_pdv_item
                             WHERE ID_VENDA = $ID";
        $resultDeleteItens = DB::select($querDeleteItens);

        for ($i=0; $i < count($dadosItemPedido); $i++) { 
            $idProdutoVenda = $dadosItemPedido[$i]['idItem'];
            $valorUnitario = $dadosItemPedido[$i]['valorUnitario'];
            $valorTotalItem = $dadosItemPedido[$i]['valorTotal']; 
            $descricaoItem = $dadosItemPedido[$i]['descItem']; 
            $valorCustoItem = isset($dadosItemPedido[$i]['valorCusto']) ? $dadosItemPedido[$i]['valorCusto'] : 0;
            $valorGastoItem = isset($dadosItemPedido[$i]['valorGasto']) ? $dadosItemPedido[$i]['valorGasto'] : 0;
            $porcentagem = isset($dadosItemPedido[$i]['porcentagem']) ? $dadosItemPedido[$i]['porcentagem'] : 0;
            $porcentagem = $porcentagem == 'NaN' ? 0 : $porcentagem;
            
            $qtde = $dadosItemPedido[$i]['qtde'];

            $queryInsertItemVenda = "INSERT INTO venda_pdv_item(ID_VENDA, ID_PRODUTO, VALOR_UNITARIO, VALOR_TOTAL, QUANTIDADE, VALOR_CUSTO, VALOR_GASTO, PORCENTAGEM, DESCRICAO_PRODUTO)
                                                        VALUES ($ID, $idProdutoVenda, $valorUnitario, $valorTotalItem, $qtde, $valorCustoItem, $valorGastoItem, $porcentagem, '$descricaoItem')";
            $resultItemVenda = DB::select($queryInsertItemVenda);
        }

        return ['situacao' => 'sucesso'];
    }

    public function inativarVenda(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['idVenda'];

        $query = "UPDATE venda_pdv 
                     SET STATUS = 'I'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function inserirDespesa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $valor = $dadosRecebidos['valor'];
        $data = $dadosRecebidos['data'];
        $descricao = $dadosRecebidos['descricao'];

        if(isset($dadosRecebidos['observacao'])){
            $observacao = $dadosRecebidos['observacao'];
        } else {
            $observacao = null;
        }

        $query = "INSERT INTO despesa (DESCRICAO, VALOR, DATA, OBSERVACAO) 
                                    VALUES ('$descricao', $valor, '$data', '$observacao')";
        $result = DB::select($query);

        return $result;
    }

    public function alterarDespesa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['idCodigo'];
        $valor = $dadosRecebidos['valor'];
        $data = $dadosRecebidos['data'];
        $descricao = $dadosRecebidos['descricao'];

        if(isset($dadosRecebidos['observacao'])){
            $observacao = $dadosRecebidos['observacao'];
        } else {
            $observacao = null;
        }

        $query = "UPDATE despesa 
                     SET DESCRICAO = '$descricao'
                       , VALOR = $valor
                       , DATA = '$data'
                       , OBSERVACAO = '$observacao'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function inativarDespesa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['idGasto'];

        $query = "UPDATE despesa 
                     SET STATUS = 'I'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function inserirCPG(Request $request){
        $dadosRecebidos = $request->except('_token');
        $valor = $dadosRecebidos['valor'];
        $descricao = $dadosRecebidos['descricao'];
        $dataVencimento = $dadosRecebidos['dataVencimento'];
        $observacao = $dadosRecebidos['observacao'];
        $ID_ORIGEM = $dadosRecebidos['ID_ORIGEM'];
        $ID_PROJETO = $dadosRecebidos['ID_PROJETO'];
        $idUsuario = auth()->user()->id;

        $query = "INSERT INTO contas_pagar (ID_USUARIO, DESCRICAO, DATA_VENCIMENTO, VALOR, OBSERVACAO, ID_ORIGEM, ID_PROJETO) 
                                    VALUES ($idUsuario, '$descricao', '$dataVencimento', $valor, '$observacao', $ID_ORIGEM, $ID_PROJETO)";
        $result = DB::select($query);

        return $result;
    }

    public function inserirPagamentoCPG(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['idCPG'];
        $dataPagamento = $dadosRecebidos['dataPagamento'];

        $query = "UPDATE contas_pagar 
                     SET DATA_PAGAMENTO = '$dataPagamento'
                       , SITUACAO = 'PAGA'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function alterarCPG(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['idCodigo'];
        $valor = $dadosRecebidos['valor'];
        $dataVencimento = $dadosRecebidos['dataVencimento'];
        // $dataPagamento = $dadosRecebidos['dataPagamento'];
        $observacao = $dadosRecebidos['observacao'];
        $ID_ORIGEM = $dadosRecebidos['ID_ORIGEM'];
        $ID_PROJETO = $dadosRecebidos['ID_PROJETO'];

        $query = "UPDATE contas_pagar 
                     SET OBSERVACAO = '$observacao'
                       , DATA_VENCIMENTO = '$dataVencimento'
                       , VALOR = $valor
                       , OBSERVACAO = '$observacao'
                       , ID_ORIGEM = $ID_ORIGEM
                       , ID_PROJETO = $ID_PROJETO
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function inativarCPG(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['idCPG'];

        $query = "UPDATE contas_pagar 
                     SET STATUS = 'I'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function inserirDocumentoDespesa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idGasto = $dadosRecebidos['idGasto'];
        $caminhoArquivo = $dadosRecebidos['caminhoArquivo'];

        $query = "INSERT INTO despesa_documento (ID_DESPESA, CAMINHO_DOCUMENTO) 
                                    VALUES ($idGasto, '$caminhoArquivo')";
        $result = DB::select($query);

        return $result;
    }
    
    public function inativarDocumentoDespesa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idDocumento = $dadosRecebidos['idDocumento'];

        $query = "UPDATE despesa_documento 
                     SET STATUS = 'I'
                    WHERE ID = $idDocumento";
        $result = DB::select($query);

        return $result;
    }

    public function inserirDocumentoCPG(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCPG = $dadosRecebidos['idCPG'];
        $caminhoArquivo = $dadosRecebidos['caminhoArquivo'];

        $query = "INSERT INTO cpg_documento (ID_CPG, CAMINHO_DOCUMENTO) 
                                    VALUES ($idCPG, '$caminhoArquivo')";
        $result = DB::select($query);

        return $result;
    }
    
    public function inativarDocumentoCPG(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idDocumento = $dadosRecebidos['idDocumento'];

        $query = "UPDATE cpg_documento 
                     SET STATUS = 'I'
                    WHERE ID = $idDocumento";
        $result = DB::select($query);

        return $result;
    }

    public function inserirPagamentoFiado(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idUsuario = $dadosRecebidos['idUsuario'];
        $valorPagamento = $dadosRecebidos['valorPagamento'];

        $query = "INSERT INTO venda_pdv_fiado_pagamento (ID_PESSOA, VALOR_PAGAMENTO, DATA) 
                                    VALUES ($idUsuario, $valorPagamento, now())";
        $result = DB::select($query);

        return $result;
    }

    public function inativarPagamentoFiado(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idPagamento = $dadosRecebidos['idPagamento'];

        $query = "UPDATE venda_pdv_fiado_pagamento 
                     SET STATUS = 'I'
                    WHERE ID = $idPagamento";
        $result = DB::select($query);

        return $result;
    }

    public function confirmarPedido(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idVenda = $dadosRecebidos['ID_VENDA'];
        $return = [];
        
        $query = " UPDATE venda_pdv
                      SET SITUACAO = 'CONFIRMADO'
                    WHERE ID = $idVenda";
        $result = DB::select($query);

        $return['situacao'] = 'sucesso';
        
        return $return;
    }

    public function cancelarPedido(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idVenda = $dadosRecebidos['ID_VENDA'];
        $return = [];
        
        $query = " UPDATE venda_pdv
                      SET SITUACAO = 'CANCELADO'
                    WHERE ID = $idVenda";
        $result = DB::select($query);
        
        $return['situacao'] = 'sucesso';
        
        return $return;
    }

    public function iniciarEntregaPedido(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idVenda = $dadosRecebidos['ID_VENDA'];
        $return = [];
        
        $query = " UPDATE venda_pdv
                      SET SITUACAO = 'ENTREGA INICIADA'
                    WHERE ID = $idVenda";
        $result = DB::select($query);
        
        $return['situacao'] = 'sucesso';
        
        return $return;
    }

    public function finalizarPedido(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idVenda = $dadosRecebidos['ID_VENDA'];
        $return = [];
        
        $query = " UPDATE venda_pdv
                      SET SITUACAO = 'FINALIZADO'
                    WHERE ID = $idVenda";
        $result = DB::select($query);
        
        $return['situacao'] = 'sucesso';
        
        return $return;
    }

    public function buscarFormaPagamento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $return = [];
        
        $query = " SELECT tipo_pagamento.*
                     FROM tipo_pagamento
                    WHERE 1 = 1";
        $result = DB::select($query);
        $return['dados'] = $result;
        
        return $return;
    }

    public function inserirPagamentoVenda(Request $request){
        $dadosRecebidos = $request->except('_token');
        $id = $dadosRecebidos['ID'];
        $formaPagamento = $dadosRecebidos['FORMA_PAGAMENTO'];
        $return = [];

        $queryCount = "SELECT COUNT(*) AS COUNT
                         FROM caixa_movimentacao
                        WHERE DATA_ABERTURA IS NOT NULL
                          AND DATA_FECHAMENTO IS NULL";
        $caixaAberto = DB::select($queryCount)[0]->COUNT;

        if($caixaAberto > 0){
            $query = " UPDATE venda_pdv
                          SET PAGAMENTO = $formaPagamento
                        WHERE ID = $id";
            $result = DB::select($query);
            $return['situacao'] = 'sucesso';
        } else {
            $return['situacao'] = 'erro';
            $return['mensagem'] = 'Não existe nenhum caixa aberto, realize a abertura do caixa antes de inserir um pagamento.';            
        }
        
        return $return;
    }

    public function imprimirVenda($id){
        $data = (new DateTime())->format('d/m/Y H:i');

        $queryEmpresa = "SELECT empresa_cliente.*
                           FROM empresa_cliente
                          WHERE ID = 1";
        $dadosEmpresa = DB::select($queryEmpresa)[0];

        $queryVenda = "SELECT venda_pdv.ID
                            , DATE_FORMAT(venda_pdv.DATA, '%d/%m/%Y %H:%i') as DATA
                            , venda_pdv.VALOR_TOTAL
                            , venda_pdv.CLIENTE_NOME
                            , (SELECT tipo_pagamento.DESCRICAO
                                 FROM tipo_pagamento
                                WHERE venda_pdv.PAGAMENTO = tipo_pagamento.ID) as PAGAMENTO
                        FROM venda_pdv
                       WHERE STATUS = 'A'
                         AND ID = $id";
        $dadosVenda = DB::select($queryVenda)[0];

        $queryVendaItem = "SELECT venda_pdv_item.ID
                                   , venda_pdv_item.VALOR_UNITARIO
                                   , venda_pdv_item.VALOR_TOTAL
                                   , venda_pdv_item.QUANTIDADE
                                   , venda_pdv_item.OBSERVACAO
                                   , COALESCE((select produto_pdv.DESCRICAO
                                                 FROM produto_pdv
                                                WHERE produto_pdv.ID = venda_pdv_item.ID_PRODUTO), venda_pdv_item.DESCRICAO_PRODUTO) as ITEM
                                FROM venda_pdv_item
                               WHERE STATUS = 'A'
                                 AND ID_VENDA = $id";
        $dadosVendaItem = DB::select($queryVendaItem);

        $pdf = PDF::loadView('impressos.venda', compact('data', 'dadosVenda', 'dadosVendaItem', 'dadosEmpresa'));
        
        return $pdf->stream('impresso-venda.pdf');
    }
}
