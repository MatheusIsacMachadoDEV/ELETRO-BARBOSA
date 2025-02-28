<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;
use DateTime;

class OrdemServicoController extends Controller
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
        return view('ordemServico');
    }

    public function buscarOrdemServico(Request $request){
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

        if(isset($dadosRecebidos['idOrdemServico'])){
            $filtroOrdemID = "AND ID = {$dadosRecebidos['idOrdemServico']}";
        } else {
            $filtroOrdemID = "AND 1 = 1";
        }

        $query = "SELECT ordem_servico.*
                    FROM ordem_servico
                   WHERE STATUS = 'A'
                   $filtroData
                   $filtroOrdemID";
        $result = DB::select($query);

        return $result;
    }

    public function buscarItemOrdemServico(Request $request){
        $dadosRecebidos = $request->except('_token');

        if(isset($dadosRecebidos['idOrdemServico'])){
            $filtro = "AND ID_ORDEM_SERVICO = {$dadosRecebidos['idOrdemServico']}";
        } else {
            $filtro = "AND 1 = 1";
        }

        $query = "SELECT ordem_servico_item.*, COALESCE((SELECT UPPER(DESCRICAO)
                                                           FROM produto_pdv
                                                          WHERE ID = ordem_servico_item.ID_SERVICO
                                                            AND TIPO = 2), ordem_servico_item.DESCRICAO) AS PRODUTO
                    FROM ordem_servico_item
                   WHERE STATUS = 'A'
                   $filtro";
        $result = DB::select($query);

        return $result;
    }

    public function inserirOrdemServico(Request $request){
        $dadosRecebidos = $request->except('_token');
        $valorTotal = $dadosRecebidos['valorTotal'];    
        $pagamento = $dadosRecebidos['pagamento'];
        $dataOrdem = $dadosRecebidos['dataOrdem'];
        $idCliente = $dadosRecebidos['idCliente'];
        $nomeCliente = $dadosRecebidos['nomeCliente'];

        if(strlen($idCliente) == 0){
            $idCliente = 1;
        }

        $queryNovoId = "SELECT COALESCE(MAX(ID), 1) AS AUTO_INCREMENT
                          FROM ordem_servico
                         WHERE 1 = 1";

        $idOrdem = (DB::select($queryNovoId)[0]->AUTO_INCREMENT + 1);

        $query = "INSERT INTO ordem_servico (ID, ID_USUARIO, DATA, VALOR_TOTAL, PAGAMENTO, ID_CLIENTE, NOME_CLIENTE) 
                                    VALUES ($idOrdem, 0, '$dataOrdem', $valorTotal, $pagamento, $idCliente, '$nomeCliente')";
        $result = DB::select($query);

        for ($i=0; $i < count($dadosRecebidos['dadosItemPedido']); $i++) { 
            $idProdutoVenda = $dadosRecebidos['dadosItemPedido'][$i]['idItem'];
            $valorUnitario = $dadosRecebidos['dadosItemPedido'][$i]['valorUnitario'];
            $valorTotalItem = $dadosRecebidos['dadosItemPedido'][$i]['valorTotal'];
            $descItem = $dadosRecebidos['dadosItemPedido'][$i]['descItem'];
            $valorCustoItem = isset($dadosRecebidos['dadosItemPedido'][$i]['valorCusto']) ? $dadosRecebidos['dadosItemPedido'][$i]['valorCusto'] : 0;
            $valorGastoItem = isset($dadosRecebidos['dadosItemPedido'][$i]['valorGasto']) ? $dadosRecebidos['dadosItemPedido'][$i]['valorGasto'] : 0;
            $porcentagem = isset($dadosRecebidos['dadosItemPedido'][$i]['porcentagem']) ? $dadosRecebidos['dadosItemPedido'][$i]['porcentagem'] : 0;
            $qtde = $dadosRecebidos['dadosItemPedido'][$i]['qtde'];

            $queryInsertItemVenda = "INSERT INTO ordem_servico_item(ID_ORDEM_SERVICO, ID_SERVICO, VALOR_UNITARIO, VALOR_TOTAL, QUANTIDADE, VALOR_CUSTO, VALOR_GASTO, PORCENTAGEM, DESCRICAO)
                                                        VALUES ($idOrdem, $idProdutoVenda, $valorUnitario, '$valorTotalItem', $qtde, $valorCustoItem, $valorGastoItem, $porcentagem, '$descItem')";
            $resultItemVenda = DB::select($queryInsertItemVenda);
        }

        if($pagamento == 4){
            $idUsuarioFiado = $dadosRecebidos['idPessoaFiado'];

            $queryFiado = "INSERT INTO ordem_servico_fiado ( ID_ORDEM_SERVICO, ID_PESSOA, VALOR_FIADO)
                                            VALUES($idOrdem, $idUsuarioFiado, $valorTotal)";
            $resultFiado = DB::select($queryFiado);
        }   

        return $result;
    }

    public function alterarVenda(Request $request){
        $dadosRecebidos = $request->except('_token');
        $valor = $dadosRecebidos['valor'];
        $idCodigo = $dadosRecebidos['idCodigo'];
        $pagamento = $dadosRecebidos['pagamento'];
        $observacao = $dadosRecebidos['observacao'];

        $query = "UPDATE ordem_servico 
                     SET OBSERVACAO = '$observacao'
                       , VALOR = $valor
                       , PAGAMENTO = $pagamento
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function inativarOrdemServico(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['idOrdemServico'];

        $query = "UPDATE ordem_servico 
                     SET STATUS = 'I'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function imprimirOrdem($id){
        $data = (new DateTime())->format('d/m/Y H:i');
        $cliente = "Teste";

        $queryOrdemServico = "SELECT ordem_servico.ID
                                   , ordem_servico.DATA
                                   , ordem_servico.VALOR_TOTAL
                                   , ordem_servico.NOME_CLIENTE
                                   , (select tipo_pagamento.DESCRICAO
                                        FROM tipo_pagamento
                                       WHERE ordem_servico.PAGAMENTO = tipo_pagamento.ID) as PAGAMENTO
                                FROM ordem_servico
                               WHERE STATUS = 'A'
                                 AND ID = $id";
        $dadosOrdemServico = DB::select($queryOrdemServico)[0];

        $queryItemOrdemServico = "SELECT ordem_servico_item.ID
                                   , ordem_servico_item.VALOR_UNITARIO
                                   , ordem_servico_item.VALOR_TOTAL
                                   , ordem_servico_item.QUANTIDADE
                                   , ordem_servico_item.VALOR_GASTO
                                   , ordem_servico_item.VALOR_CUSTO
                                   , COALESCE((select produto_pdv.DESCRICAO
                                                 FROM produto_pdv
                                                WHERE produto_pdv.ID = ordem_servico_item.ID_SERVICO), ordem_servico_item.DESCRICAO) as SERVICO
                                FROM ordem_servico_item
                               WHERE STATUS = 'A'
                                 AND ID_ORDEM_SERVICO = $id";
        $dadosItemOrdemServico = DB::select($queryItemOrdemServico);

        $queryEmpresa = "SELECT empresa_cliente.*
                           FROM empresa_cliente
                          WHERE ID = 1";
        $dadosEmpresa = DB::select($queryEmpresa)[0];

        // Carregar a view 'ordemservico' passando a variável $data
        $pdf = PDF::loadView('impressos.ordemservico', compact('data', 'dadosOrdemServico', 'dadosItemOrdemServico', 'dadosEmpresa'));
        
        // Exibir o PDF inline no navegador
        return $pdf->stream('nome-do-arquivo.pdf');
    }
}
