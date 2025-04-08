<?php

namespace Modules\Dashboard\Http\Controllers;

use GuzzleHttp\Psr7\Query;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('dashboard::index');
    }

    public function buscarProjetosValores(Request $request){
        $dadosRecebidos = $request->except('_token');
        $return = [];

        $query = "SELECT (SELECT SUM(VALOR)
                            FROM projeto
                           WHERE STATUS = 'A'
                             AND YEAR(DATA_INICIO) = YEAR(NOW())) AS TOTAL_FECHADO
                       , (SELECT SUM(VALOR)
                            FROM projeto
                           WHERE STATUS = 'A'
                             AND YEAR(DATA_INICIO) = YEAR(NOW())
                             AND PAGAMENTO_REALIZADO = 'S') AS TOTAL_ENTREGUE
                       , (SELECT SUM(contas_pagar.VALOR)
                            FROM contas_pagar, projeto
                           WHERE contas_pagar.ID_PROJETO = projeto.ID
                             AND contas_pagar.STATUS = 'A'
                             AND projeto.STATUS = 'A'
                             AND YEAR(DATA_INICIO) = YEAR(NOW())) AS TOTAL_GASTO
                    FROM dual";
        $return['dados'] = DB::select($query);

        return $return;
    }

    public function graficoValores(Request $request)
    {
        $ano = $request->input('ANO', date('Y'));
        
        $query = "
            SELECT 
                MONTH(m.mes) AS mes_numero,
                m.mes_nome AS mes,
                COALESCE(SUM(p.VALOR), 0) AS total_valor,
                COALESCE(SUM(cp.total_gasto), 0) AS total_gasto
            FROM 
                (
                    SELECT 1 AS mes, 'January' AS mes_nome UNION ALL
                    SELECT 2, 'February' UNION ALL
                    SELECT 3, 'March' UNION ALL
                    SELECT 4, 'April' UNION ALL
                    SELECT 5, 'May' UNION ALL
                    SELECT 6, 'June' UNION ALL
                    SELECT 7, 'July' UNION ALL
                    SELECT 8, 'August' UNION ALL
                    SELECT 9, 'September' UNION ALL
                    SELECT 10, 'October' UNION ALL
                    SELECT 11, 'November' UNION ALL
                    SELECT 12, 'December'
                ) m
            LEFT JOIN 
                projeto p ON MONTH(p.DATA_INICIO) = m.mes AND YEAR(p.DATA_INICIO) = ? AND p.STATUS = 'A'
            LEFT JOIN 
                (
                    SELECT 
                        MONTH(DATA_PAGAMENTO) AS mes,
                        SUM(VALOR) AS total_gasto
                    FROM 
                        contas_pagar
                    WHERE 
                        STATUS = 'A'
                        AND YEAR(DATA_PAGAMENTO) = ?
                        AND DATA_PAGAMENTO IS NOT NULL
                    GROUP BY 
                        MONTH(DATA_PAGAMENTO)
                ) cp ON cp.mes = m.mes
            GROUP BY 
                m.mes, m.mes_nome
            ORDER BY 
                m.mes
        ";
        
        $dados = DB::select($query, [$ano, $ano]);
        
        return response()->json([
            'labels' => array_column($dados, 'mes'),
            'datasets' => [
                [
                    'label' => 'Valor dos Projetos',
                    'data' => array_column($dados, 'total_valor'),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.7)'
                ],
                [
                    'label' => 'Gastos',
                    'data' => array_column($dados, 'total_gasto'),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.7)'
                ]
            ]
        ]);
    }

    public function buscarCards(Request $request){
        $dadosRecebidos = $request->except("_token");
        $return = [];

        $query = "SELECT (SELECT COUNT(*)
                            FROM users
                           WHERE 1 = 1) AS TOTAL_USUARIOS
                        , (SELECT COUNT(*)
                             FROM projeto
                            WHERE STATUS = 'A'
                              AND PAGAMENTO_REALIZADO = 'N') AS TOTAL_PROJETOS
                        , (SELECT COUNT(*)
                             FROM pessoa
                            WHERE STATUS = 'A'
                              AND ID_TIPO = 2) AS TOTAL_CLIENTES
                    FROM dual";
        $return['dados'] = DB::select($query);

        return $return;
    }
}
