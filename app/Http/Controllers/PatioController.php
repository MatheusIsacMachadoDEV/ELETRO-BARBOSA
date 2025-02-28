<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PatioController extends Controller
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
        return view('patio');
    }

    public function buscarGraficoMensal(Request $request){
        $dadosRecebidos = $request->except('_token');

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

        $query ="SELECT COALESCE(SUM(TOTAL.GASTOS), 0) AS GASTOS
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
                                  $filtroData) VENDAS
                          WHERE 1 = 1) TOTAL";
        $result = DB::select($query);

        return $result;
    }

    public function buscarGraficoAnual(Request $request){
        $dadosRecebidos = $request->except('_token');
        $anoAtual = date("Y");

        $queryVenda ="SELECT (SELECT COALESCE(SUM(VALOR), 0)
                           FROM VENDA
                          WHERE STATUS = 'A'
                            AND DATA >= '$anoAtual-01-01'
                            AND DATA <= '$anoAtual-01-31') AS JAN
                      , (SELECT COALESCE(SUM(VALOR), 0)
                           FROM VENDA
                          WHERE STATUS = 'A'
                            AND DATA >= '$anoAtual-02-01'
                            AND DATA <= '$anoAtual-02-31') AS FEV
                      , (SELECT COALESCE(SUM(VALOR), 0)
                           FROM VENDA
                          WHERE STATUS = 'A'
                            AND DATA >= '$anoAtual-03-01'
                            AND DATA <= '$anoAtual-03-31') AS MAR
                      , (SELECT COALESCE(SUM(VALOR), 0)
                           FROM VENDA
                          WHERE STATUS = 'A'
                            AND DATA >= '$anoAtual-04-01'
                            AND DATA <= '$anoAtual-04-31') AS ABR
                      , (SELECT COALESCE(SUM(VALOR), 0)
                           FROM VENDA
                          WHERE STATUS = 'A'
                            AND DATA >= '$anoAtual-05-01'
                            AND DATA <= '$anoAtual-05-31') AS MAI
                      , (SELECT COALESCE(SUM(VALOR), 0)
                           FROM VENDA
                          WHERE STATUS = 'A'
                            AND DATA >= '$anoAtual-06-01'
                            AND DATA <= '$anoAtual-06-31') AS JUN
                      , (SELECT COALESCE(SUM(VALOR), 0)
                           FROM VENDA
                          WHERE STATUS = 'A'
                            AND DATA >= '$anoAtual-07-01'
                            AND DATA <= '$anoAtual-07-31') AS JUL
                      , (SELECT COALESCE(SUM(VALOR), 0)
                           FROM VENDA
                          WHERE STATUS = 'A'
                            AND DATA >= '$anoAtual-08-01'
                            AND DATA <= '$anoAtual-08-31') AS AGO
                      , (SELECT COALESCE(SUM(VALOR), 0)
                           FROM VENDA
                          WHERE STATUS = 'A'
                            AND DATA >= '$anoAtual-09-01'
                            AND DATA <= '$anoAtual-09-31') AS SETEM
                      , (SELECT COALESCE(SUM(VALOR), 0)
                           FROM VENDA
                          WHERE STATUS = 'A'
                            AND DATA >= '$anoAtual-10-01'
                            AND DATA <= '$anoAtual-10-31') AS OUTUB
                      , (SELECT COALESCE(SUM(VALOR), 0)
                           FROM VENDA
                          WHERE STATUS = 'A'
                            AND DATA >= '$anoAtual-11-01'
                            AND DATA <= '$anoAtual-11-31') AS NOV
                      , (SELECT COALESCE(SUM(VALOR), 0)
                           FROM VENDA
                          WHERE STATUS = 'A'
                            AND DATA >= '$anoAtual-12-01'
                            AND DATA <= '$anoAtual-12-31') AS DEZ
                   FROM DUAL";
        $resultVenda = DB::select($queryVenda);

        $return['LUCRO'] = $resultVenda;

        $queryGastos = "SELECT (SELECT SUM(VALORES.TOTAL)
                                  FROM (SELECT COALESCE(SUM(VEICULOS.VALOR_COMPRA), 0) AS TOTAL
                                          FROM VEICULOS                           
                                         WHERE VEICULOS.STATUS = 'A'
                                           AND VEICULOS.DATA_COMPRA >= '$anoAtual-01-01'
                                           AND VEICULOS.DATA_COMPRA <= '$anoAtual-01-31'
                                            
                                         UNION ALL

                                        SELECT COALESCE(SUM(MANUTENCAO.VALOR), 0) AS TOTAL
                                          FROM MANUTENCAO
                                         WHERE MANUTENCAO.DATA >= '$anoAtual-01-01'
                                           AND MANUTENCAO.DATA <= '$anoAtual-01-31'
                                           AND MANUTENCAO.STATUS = 'A') VALORES) as JAN
                             , (SELECT SUM(VALORES.TOTAL)
                                  FROM (SELECT COALESCE(SUM(VEICULOS.VALOR_COMPRA), 0) AS TOTAL
                                          FROM VEICULOS                           
                                         WHERE VEICULOS.STATUS = 'A'
                                           AND VEICULOS.DATA_COMPRA >= '$anoAtual-02-01'
                                           AND VEICULOS.DATA_COMPRA <= '$anoAtual-02-31'
                                            
                                         UNION ALL

                                        SELECT COALESCE(SUM(MANUTENCAO.VALOR), 0) AS TOTAL
                                          FROM MANUTENCAO
                                         WHERE MANUTENCAO.DATA >= '$anoAtual-02-01'
                                           AND MANUTENCAO.DATA <= '$anoAtual-02-31'
                                           AND MANUTENCAO.STATUS = 'A') VALORES) as FEV
                             , (SELECT SUM(VALORES.TOTAL)
                                  FROM (SELECT COALESCE(SUM(VEICULOS.VALOR_COMPRA), 0) AS TOTAL
                                          FROM VEICULOS                           
                                         WHERE VEICULOS.STATUS = 'A'
                                           AND VEICULOS.DATA_COMPRA >= '$anoAtual-03-01'
                                           AND VEICULOS.DATA_COMPRA <= '$anoAtual-03-31'
                                            
                                         UNION ALL

                                        SELECT COALESCE(SUM(MANUTENCAO.VALOR), 0) AS TOTAL
                                          FROM MANUTENCAO
                                         WHERE MANUTENCAO.DATA >= '$anoAtual-03-01'
                                           AND MANUTENCAO.DATA <= '$anoAtual-03-31'
                                           AND MANUTENCAO.STATUS = 'A') VALORES) as MAR   
                             , (SELECT SUM(VALORES.TOTAL)
                                  FROM (SELECT COALESCE(SUM(VEICULOS.VALOR_COMPRA), 0) AS TOTAL
                                          FROM VEICULOS                           
                                         WHERE VEICULOS.STATUS = 'A'
                                           AND VEICULOS.DATA_COMPRA >= '$anoAtual-04-01'
                                           AND VEICULOS.DATA_COMPRA <= '$anoAtual-04-31'
                                            
                                         UNION ALL

                                        SELECT COALESCE(SUM(MANUTENCAO.VALOR), 0) AS TOTAL
                                          FROM MANUTENCAO
                                         WHERE MANUTENCAO.DATA >= '$anoAtual-04-01'
                                           AND MANUTENCAO.DATA <= '$anoAtual-04-31'
                                           AND MANUTENCAO.STATUS = 'A') VALORES) as ABR   
                             , (SELECT SUM(VALORES.TOTAL)
                                  FROM (SELECT COALESCE(SUM(VEICULOS.VALOR_COMPRA), 0) AS TOTAL
                                          FROM VEICULOS                           
                                         WHERE VEICULOS.STATUS = 'A'
                                           AND VEICULOS.DATA_COMPRA >= '$anoAtual-05-01'
                                           AND VEICULOS.DATA_COMPRA <= '$anoAtual-05-31'
                                            
                                         UNION ALL

                                        SELECT COALESCE(SUM(MANUTENCAO.VALOR), 0) AS TOTAL
                                          FROM MANUTENCAO
                                         WHERE MANUTENCAO.DATA >= '$anoAtual-05-01'
                                           AND MANUTENCAO.DATA <= '$anoAtual-05-31'
                                           AND MANUTENCAO.STATUS = 'A') VALORES) as MAI   
                             , (SELECT SUM(VALORES.TOTAL)
                                  FROM (SELECT COALESCE(SUM(VEICULOS.VALOR_COMPRA), 0) AS TOTAL
                                          FROM VEICULOS                           
                                         WHERE VEICULOS.STATUS = 'A'
                                           AND VEICULOS.DATA_COMPRA >= '$anoAtual-06-01'
                                           AND VEICULOS.DATA_COMPRA <= '$anoAtual-06-31'
                                            
                                         UNION ALL

                                        SELECT COALESCE(SUM(MANUTENCAO.VALOR), 0) AS TOTAL
                                          FROM MANUTENCAO
                                         WHERE MANUTENCAO.DATA >= '$anoAtual-06-01'
                                           AND MANUTENCAO.DATA <= '$anoAtual-06-31'
                                           AND MANUTENCAO.STATUS = 'A') VALORES) as JUN   
                             , (SELECT SUM(VALORES.TOTAL)
                                  FROM (SELECT COALESCE(SUM(VEICULOS.VALOR_COMPRA), 0) AS TOTAL
                                          FROM VEICULOS                           
                                         WHERE VEICULOS.STATUS = 'A'
                                           AND VEICULOS.DATA_COMPRA >= '$anoAtual-07-01'
                                           AND VEICULOS.DATA_COMPRA <= '$anoAtual-07-31'
                                            
                                         UNION ALL

                                        SELECT COALESCE(SUM(MANUTENCAO.VALOR), 0) AS TOTAL
                                          FROM MANUTENCAO
                                         WHERE MANUTENCAO.DATA >= '$anoAtual-07-01'
                                           AND MANUTENCAO.DATA <= '$anoAtual-07-31'
                                           AND MANUTENCAO.STATUS = 'A') VALORES) as JUL   
                             , (SELECT SUM(VALORES.TOTAL)
                                  FROM (SELECT COALESCE(SUM(VEICULOS.VALOR_COMPRA), 0) AS TOTAL
                                          FROM VEICULOS                           
                                         WHERE VEICULOS.STATUS = 'A'
                                           AND VEICULOS.DATA_COMPRA >= '$anoAtual-08-01'
                                           AND VEICULOS.DATA_COMPRA <= '$anoAtual-08-31'
                                            
                                         UNION ALL

                                        SELECT COALESCE(SUM(MANUTENCAO.VALOR), 0) AS TOTAL
                                          FROM MANUTENCAO
                                         WHERE MANUTENCAO.DATA >= '$anoAtual-08-01'
                                           AND MANUTENCAO.DATA <= '$anoAtual-08-31'
                                           AND MANUTENCAO.STATUS = 'A') VALORES) as AGO   
                             , (SELECT SUM(VALORES.TOTAL)
                                  FROM (SELECT COALESCE(SUM(VEICULOS.VALOR_COMPRA), 0) AS TOTAL
                                          FROM VEICULOS                           
                                         WHERE VEICULOS.STATUS = 'A'
                                           AND VEICULOS.DATA_COMPRA >= '$anoAtual-09-01'
                                           AND VEICULOS.DATA_COMPRA <= '$anoAtual-09-31'
                                            
                                         UNION ALL

                                        SELECT COALESCE(SUM(MANUTENCAO.VALOR), 0) AS TOTAL
                                          FROM MANUTENCAO
                                         WHERE MANUTENCAO.DATA >= '$anoAtual-09-01'
                                           AND MANUTENCAO.DATA <= '$anoAtual-09-31'
                                           AND MANUTENCAO.STATUS = 'A') VALORES) as SETEM   
                             , (SELECT SUM(VALORES.TOTAL)
                                  FROM (SELECT COALESCE(SUM(VEICULOS.VALOR_COMPRA), 0) AS TOTAL
                                          FROM VEICULOS                           
                                         WHERE VEICULOS.STATUS = 'A'
                                           AND VEICULOS.DATA_COMPRA >= '$anoAtual-10-01'
                                           AND VEICULOS.DATA_COMPRA <= '$anoAtual-10-31'
                                            
                                         UNION ALL

                                        SELECT COALESCE(SUM(MANUTENCAO.VALOR), 0) AS TOTAL
                                          FROM MANUTENCAO
                                         WHERE MANUTENCAO.DATA >= '$anoAtual-10-01'
                                           AND MANUTENCAO.DATA <= '$anoAtual-10-31'
                                           AND MANUTENCAO.STATUS = 'A') VALORES) as OUTUB   
                             , (SELECT SUM(VALORES.TOTAL)
                                  FROM (SELECT COALESCE(SUM(VEICULOS.VALOR_COMPRA), 0) AS TOTAL
                                          FROM VEICULOS                           
                                         WHERE VEICULOS.STATUS = 'A'
                                           AND VEICULOS.DATA_COMPRA >= '$anoAtual-11-01'
                                           AND VEICULOS.DATA_COMPRA <= '$anoAtual-11-31'
                                            
                                         UNION ALL

                                        SELECT COALESCE(SUM(MANUTENCAO.VALOR), 0) AS TOTAL
                                          FROM MANUTENCAO
                                         WHERE MANUTENCAO.DATA >= '$anoAtual-11-01'
                                           AND MANUTENCAO.DATA <= '$anoAtual-11-31'
                                           AND MANUTENCAO.STATUS = 'A') VALORES) as NOV   
                             , (SELECT SUM(VALORES.TOTAL)
                                  FROM (SELECT COALESCE(SUM(VEICULOS.VALOR_COMPRA), 0) AS TOTAL
                                          FROM VEICULOS                           
                                         WHERE VEICULOS.STATUS = 'A'
                                           AND VEICULOS.DATA_COMPRA >= '$anoAtual-12-01'
                                           AND VEICULOS.DATA_COMPRA <= '$anoAtual-12-31'
                                            
                                         UNION ALL

                                        SELECT COALESCE(SUM(MANUTENCAO.VALOR), 0) AS TOTAL
                                          FROM MANUTENCAO
                                         WHERE MANUTENCAO.DATA >= '$anoAtual-12-01'
                                           AND MANUTENCAO.DATA <= '$anoAtual-12-31'
                                           AND MANUTENCAO.STATUS = 'A') VALORES) as DEZ                    
                          FROM DUAL";
        $resultGastos = DB::select($queryGastos);
        $return['GASTOS'] = $resultGastos;

        return $return;
    }
}
