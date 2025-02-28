<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
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
        return view('lucroPDV');
    }

    public function bucarDashboardMensal (Request $request){
        $dadosRecebidos = $request->except('_token');
        $return = [];

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

        $queryVenda ="SELECT COALESCE(SUM(venda_pdv.VALOR_TOTAL), 0) AS VALOR_TOTAL_VENDA
                        FROM venda_pdv
                       WHERE venda_pdv.`STATUS` = 'A'
                         $filtroData";
        $return['VENDAS'] = DB::select($queryVenda)[0]->VALOR_TOTAL_VENDA;

        $queryGastos = "SELECT COALESCE(SUM(despesa.VALOR), 0) AS VALOR_TOTAL_GASTOS
                          FROM despesa 
                         WHERE despesa.`STATUS` = 'A'
                         $filtroData";
        $return['GASTOS'] = DB::select($queryGastos)[0]->VALOR_TOTAL_GASTOS;

        return $return;
    }

    public function buscarDashboardAnual(Request $request){
        $dadosRecebidos = $request->except('_token');
        $anoAtual = date("Y");

        $queryVenda ="SELECT (SELECT COALESCE(SUM(venda_pdv.VALOR_TOTAL), 0) AS VALOR_TOTAL_VENDA
                                FROM venda_pdv
                               WHERE venda_pdv.`STATUS` = 'A'
                                 AND venda_pdv.DATA >= '$anoAtual-01-01'
                                 AND venda_pdv.DATA <= '$anoAtual-01-31') AS JAN
                           , (SELECT COALESCE(SUM(venda_pdv.VALOR_TOTAL), 0) AS VALOR_TOTAL_VENDA
                                FROM venda_pdv
                               WHERE venda_pdv.`STATUS` = 'A'
                                 AND venda_pdv.DATA >= '$anoAtual-02-01'
                                 AND venda_pdv.DATA <= '$anoAtual-02-29') AS FEV
                           , (SELECT COALESCE(SUM(venda_pdv.VALOR_TOTAL), 0) AS VALOR_TOTAL_VENDA
                                FROM venda_pdv
                               WHERE venda_pdv.`STATUS` = 'A'
                                 AND venda_pdv.DATA >= '$anoAtual-03-01'
                                 AND venda_pdv.DATA <= '$anoAtual-03-31') AS MAR
                           , (SELECT COALESCE(SUM(venda_pdv.VALOR_TOTAL), 0) AS VALOR_TOTAL_VENDA
                                FROM venda_pdv
                               WHERE venda_pdv.`STATUS` = 'A'
                                 AND venda_pdv.DATA >= '$anoAtual-04-01'
                                 AND venda_pdv.DATA <= '$anoAtual-04-30') AS ABR
                           , (SELECT COALESCE(SUM(venda_pdv.VALOR_TOTAL), 0) AS VALOR_TOTAL_VENDA
                                FROM venda_pdv
                               WHERE venda_pdv.`STATUS` = 'A'
                                 AND venda_pdv.DATA >= '$anoAtual-05-01'
                                 AND venda_pdv.DATA <= '$anoAtual-05-31') AS MAI
                           , (SELECT COALESCE(SUM(venda_pdv.VALOR_TOTAL), 0) AS VALOR_TOTAL_VENDA
                                FROM venda_pdv
                               WHERE venda_pdv.`STATUS` = 'A'
                                 AND venda_pdv.DATA >= '$anoAtual-06-01'
                                 AND venda_pdv.DATA <= '$anoAtual-06-30') AS JUN
                           , (SELECT COALESCE(SUM(venda_pdv.VALOR_TOTAL), 0) AS VALOR_TOTAL_VENDA
                                FROM venda_pdv
                               WHERE venda_pdv.`STATUS` = 'A'
                                 AND venda_pdv.DATA >= '$anoAtual-07-01'
                                 AND venda_pdv.DATA <= '$anoAtual-07-31') AS JUL
                           , (SELECT COALESCE(SUM(venda_pdv.VALOR_TOTAL), 0) AS VALOR_TOTAL_VENDA
                                FROM venda_pdv
                               WHERE venda_pdv.`STATUS` = 'A'
                                 AND venda_pdv.DATA >= '$anoAtual-08-01'
                                 AND venda_pdv.DATA <= '$anoAtual-08-30') AS AGO
                           , (SELECT COALESCE(SUM(venda_pdv.VALOR_TOTAL), 0) AS VALOR_TOTAL_VENDA
                                FROM venda_pdv
                               WHERE venda_pdv.`STATUS` = 'A'
                                 AND venda_pdv.DATA >= '$anoAtual-09-01'
                                 AND venda_pdv.DATA <= '$anoAtual-09-30') AS SETEM
                           , (SELECT COALESCE(SUM(venda_pdv.VALOR_TOTAL), 0) AS VALOR_TOTAL_VENDA
                                FROM venda_pdv
                               WHERE venda_pdv.`STATUS` = 'A'
                                 AND venda_pdv.DATA >= '$anoAtual-10-01'
                                 AND venda_pdv.DATA <= '$anoAtual-10-30') AS OUTUB
                           , (SELECT COALESCE(SUM(venda_pdv.VALOR_TOTAL), 0) AS VALOR_TOTAL_VENDA
                                FROM venda_pdv
                               WHERE venda_pdv.`STATUS` = 'A'
                                 AND venda_pdv.DATA >= '$anoAtual-11-01'
                                 AND venda_pdv.DATA <= '$anoAtual-11-30') AS NOV
                           , (SELECT COALESCE(SUM(venda_pdv.VALOR_TOTAL), 0) AS VALOR_TOTAL_VENDA
                                FROM venda_pdv
                               WHERE venda_pdv.`STATUS` = 'A'
                                 AND venda_pdv.DATA >= '$anoAtual-12-01'
                                 AND venda_pdv.DATA <= '$anoAtual-12-31') AS DEZ
                      
                      FROM DUAL";
        $resultVenda = DB::select($queryVenda);

        $return['LUCRO'] = $resultVenda;

        $queryGastos = "SELECT (SELECT COALESCE(SUM(despesa.VALOR), 0) AS VALOR_TOTAL_GASTOS
                                  FROM despesa 
                                 WHERE despesa.`STATUS` = 'A'
                                   AND despesa.DATA >= '$anoAtual-01-01'
                                   AND despesa.DATA <= '$anoAtual-01-31') as JAN
                             , (SELECT COALESCE(SUM(despesa.VALOR), 0) AS VALOR_TOTAL_GASTOS
                                  FROM despesa 
                                 WHERE despesa.`STATUS` = 'A'
                                   AND despesa.DATA >= '$anoAtual-02-01'
                                   AND despesa.DATA <= '$anoAtual-02-29') as FEV
                             , (SELECT COALESCE(SUM(despesa.VALOR), 0) AS VALOR_TOTAL_GASTOS
                                  FROM despesa 
                                 WHERE despesa.`STATUS` = 'A'
                                   AND despesa.DATA >= '$anoAtual-03-01'
                                   AND despesa.DATA <= '$anoAtual-03-31') as MAR
                             , (SELECT COALESCE(SUM(despesa.VALOR), 0) AS VALOR_TOTAL_GASTOS
                                  FROM despesa 
                                 WHERE despesa.`STATUS` = 'A'
                                   AND despesa.DATA >= '$anoAtual-04-01'
                                   AND despesa.DATA <= '$anoAtual-04-30') as ABR
                             , (SELECT COALESCE(SUM(despesa.VALOR), 0) AS VALOR_TOTAL_GASTOS
                                  FROM despesa 
                                 WHERE despesa.`STATUS` = 'A'
                                   AND despesa.DATA >= '$anoAtual-05-01'
                                   AND despesa.DATA <= '$anoAtual-05-31') as MAI
                             , (SELECT COALESCE(SUM(despesa.VALOR), 0) AS VALOR_TOTAL_GASTOS
                                  FROM despesa 
                                 WHERE despesa.`STATUS` = 'A'
                                   AND despesa.DATA >= '$anoAtual-06-01'
                                   AND despesa.DATA <= '$anoAtual-06-30') as JUN	
                             , (SELECT COALESCE(SUM(despesa.VALOR), 0) AS VALOR_TOTAL_GASTOS
                                  FROM despesa 
                                 WHERE despesa.`STATUS` = 'A'
                                   AND despesa.DATA >= '$anoAtual-07-01'
                                   AND despesa.DATA <= '$anoAtual-07-31') as JUL
                             , (SELECT COALESCE(SUM(despesa.VALOR), 0) AS VALOR_TOTAL_GASTOS
                                  FROM despesa 
                                 WHERE despesa.`STATUS` = 'A'
                                   AND despesa.DATA >= '$anoAtual-08-01'
                                   AND despesa.DATA <= '$anoAtual-08-31') as AGO
                             , (SELECT COALESCE(SUM(despesa.VALOR), 0) AS VALOR_TOTAL_GASTOS
                                  FROM despesa 
                                 WHERE despesa.`STATUS` = 'A'
                                   AND despesa.DATA >= '$anoAtual-09-01'
                                   AND despesa.DATA <= '$anoAtual-09-30') as SETEM
                             , (SELECT COALESCE(SUM(despesa.VALOR), 0) AS VALOR_TOTAL_GASTOS
                                  FROM despesa 
                                 WHERE despesa.`STATUS` = 'A'
                                   AND despesa.DATA >= '$anoAtual-10-01'
                                   AND despesa.DATA <= '$anoAtual-10-31') as OUTUB
                             , (SELECT COALESCE(SUM(despesa.VALOR), 0) AS VALOR_TOTAL_GASTOS
                                  FROM despesa 
                                 WHERE despesa.`STATUS` = 'A'
                                   AND despesa.DATA >= '$anoAtual-11-01'
                                   AND despesa.DATA <= '$anoAtual-11-30') as NOV
                             , (SELECT COALESCE(SUM(despesa.VALOR), 0) AS VALOR_TOTAL_GASTOS
                                  FROM despesa 
                                 WHERE despesa.`STATUS` = 'A'
                                   AND despesa.DATA >= '$anoAtual-12-01'
                                   AND despesa.DATA <= '$anoAtual-12-31') as DEZ
                          FROM DUAL";
        $resultGastos = DB::select($queryGastos);
        $return['GASTOS'] = $resultGastos;

        return $return;
    }    

    public function buscarListaDashboard(Request $request){
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

        $query = " SELECT venda_pdv.*
                        , (SELECT COALESCE(SUM(venda_pdv_item.VALOR_TOTAL), 0)
                             FROM venda_pdv_item
                            WHERE venda_pdv_item.ID_VENDA = venda_pdv.ID) AS VALOR_TOTAL_VENDA
                     FROM venda_pdv
                    WHERE venda_pdv.`STATUS` = 'A'
                    $filtroData";
        $result = DB::select($query);

        for ($i=0; $i < count($result); $i++) {
            $queryValorCusto = "SELECT COALESCE(SUM(dados.valor_custo), 0) as VALOR_TOTAL_CUSTO
                                  FROM ( SELECT COALESCE((venda_pdv_item.QUANTIDADE * produto_pdv.VALOR_CUSTO), 0)  AS valor_custo
                                           FROM venda_pdv_item, produto_pdv
                                          WHERE venda_pdv_item.ID_PRODUTO = produto_pdv.ID 
                                            AND venda_pdv_item.ID_VENDA = {$result[$i]->ID}) AS dados";
            $result[$i]->VALOR_TOTAL_CUSTO = DB::select($queryValorCusto)[0]->VALOR_TOTAL_CUSTO;
        }

        $return['dados'] = $result;

        return $return;
    }
}