<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FaturamentoController extends Controller
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
        return view('faturamento');
    }

    public function buscarFaturamento(Request $request){
        $dadosRecebidos = $request->except('_token');

        if(isset($dadosRecebidos['placa'])){
            if($dadosRecebidos['placa'] != null  && $dadosRecebidos['placa'] != ""){
                $placa = $dadosRecebidos['placa'];
                $filtroPlaca = "AND PLACA LIKE '%$placa%'";                
            } else {
                $filtroPlaca = "AND 1 = 1";
            }
        } else {
            $filtroPlaca = "AND 1 = 1";
        }

        if(isset($dadosRecebidos['dataInicio']) && isset($dadosRecebidos['dataFim'])){
            $dataInicio = $dadosRecebidos['dataInicio'];
            $dataFim = $dadosRecebidos['dataFim'];
            $filtroData = "AND DATA >= '$dataInicio'
                           AND DATA <= '$dataFim'";
        } else if(isset($dadosRecebidos['dataInicio']) && !isset($dadosRecebidos['dataFim'])) {
            $dataInicio = $dadosRecebidos['dataInicio'];
            $filtroData = "AND DATA >= '$dataInicio'";

        } else if(isset($dadosRecebidos['dataFim']) && !isset($dadosRecebidos['dataInicio'])) {
            $dataFim = $dadosRecebidos['dataFim'];
            $filtroData = "AND DATA <= '$dataFim'";

        } else {
            $filtroData = "AND 1 = 1";
        }

        $query = " SELECT VENDAS.*
                        , VENDAS.VALOR_VENDA - (VENDAS.VALOR_COMPRA + VENDAS.GASTOS) AS LUCRO
                    FROM ( SELECT ID
                                , PLACA
                                , (SELECT UPPER(NOME)
                                     FROM pessoa
                                    WHERE ID = ID_COMPRADOR) as COMPRADOR
                                , (SELECT DOCUMENTO
                                     FROM pessoa
                                    WHERE ID = ID_COMPRADOR) as COMPRADOR_DOCUMENTO   
                                , VALOR AS VALOR_VENDA
                                , (SELECT VALOR_COMPRA
                                     FROM VEICULOS 
                                    WHERE VEICULOS.PLACA = VENDA.PLACA) as VALOR_COMPRA
                                , (SELECT COALESCE(SUM(VALOR),0)
                                     FROM MANUTENCAO 
                                    WHERE PLACA = VENDA.PLACA
                                      AND STATUS = 'A') AS GASTOS
                                , DATA
                            FROM VENDA
                            where STATUS = 'A'
                            $filtroPlaca
                            $filtroData) VENDAS
                   where 1 = 1";
        $result = DB::select($query);

        $return['dados'] = $result;

        $queryTotal ="SELECT COALESCE(SUM(TOTAL.GASTOS), 0) AS GASTOS
                           , COALESCE(SUM(TOTAL.LUCRO), 0) AS LUCRO
                           , COALESCE(SUM(TOTAL.VALOR_VENDA), 0) AS VENDAS
                           , COALESCE(SUM(TOTAL.VALOR_COMPRA), 0) AS COMPRAS
                        FROM ( SELECT VENDAS.GASTOS
                                    , VENDAS.VALOR_VENDA - (VENDAS.VALOR_COMPRA + VENDAS.GASTOS) AS LUCRO
                                    , VENDAS.VALOR_VENDA
                                    , VENDAS.VALOR_COMPRA 
                                 FROM (SELECT ID
                                            , VALOR AS VALOR_VENDA
                                            , (SELECT VALOR_COMPRA
                                                FROM VEICULOS 
                                                WHERE VEICULOS.PLACA = VENDA.PLACA) as VALOR_COMPRA
                                            , (SELECT COALESCE(SUM(VALOR),0)
                                                FROM MANUTENCAO 
                                                WHERE PLACA = VENDA.PLACA
                                                AND STATUS = 'A') AS GASTOS
                                        FROM VENDA
                                        where STATUS = 'A'
                                        $filtroPlaca
                                        $filtroData) VENDAS
                                WHERE 1 = 1) TOTAL";
        $resultTotal = DB::select($queryTotal);

        $return['total'] = $resultTotal;

        return $return;
    }
}
