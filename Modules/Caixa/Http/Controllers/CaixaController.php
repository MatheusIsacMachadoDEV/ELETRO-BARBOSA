<?php

namespace Modules\Caixa\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use DateTime;
use PDF;

class CaixaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('caixa::index');
    }

    public function buscarCaixa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $return = [];
        
        $query = " SELECT caixa.*
                        , (SELECT COUNT(*)
                             FROM caixa_movimentacao
                            WHERE ID_CAIXA = caixa.ID
                              AND DATA_FECHAMENTO IS NULL) AS CAIXA_ABERTO
                     FROM caixa
                    WHERE 1 = 1";
        $result = DB::select($query);
        $return['dados'] = $result;
        
        return $return;
    }

    public function abrirCaixa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $valor = $dadosRecebidos['VALOR'];
        $return = [];
        
        $query = " INSERT INTO caixa_movimentacao(
                                    VALOR_ABERTURA
                                    , ID_CAIXA
                                    , DATA_ABERTURA
                                ) values (
                                    '$valor'
                                    , 1
                                    , DATE_SUB(NOW(), INTERVAL 3 HOUR)
                                )";
        $result = DB::select($query);
        $return['situacao'] = 'sucesso';
        
        return $return;
    }

    public function fecharCaixa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $valor = $dadosRecebidos['VALOR'];
        $return = [];
        
        $query = " UPDATE caixa_movimentacao 
                      SET DATA_FECHAMENTO = DATE_SUB(NOW(), INTERVAL 3 HOUR)
                        , VALOR_FECHAMENTO = '$valor'
                    WHERE DATA_FECHAMENTO IS NULL";
        $result = DB::select($query);
        $return['situacao'] = 'sucesso';
        
        return $return;
    }

    public function impressoCaixa(Request $request){
        $data = (new DateTime())->format('d/m/Y H:i');

        $queryEmpresa = "SELECT empresa_cliente.*
                            FROM empresa_cliente
                            WHERE ID = 1";
        $dadosEmpresa = DB::select($queryEmpresa)[0];

        $queryDadosCaixa = "SELECT VALOR_ABERTURA
                                 , VALOR_FECHAMENTO
                                 , DATE_FORMAT(DATA_ABERTURA, '%d/%m/%Y %H:%i') AS DATA_ABERTURA
                                 , DATE_FORMAT(DATA_FECHAMENTO, '%d/%m/%Y %H:%i') AS DATA_FECHAMENTO
                                 , (VALOR_FECHAMENTO - VALOR_ABERTURA) AS LUCRO
                              FROM caixa_movimentacao
                             WHERE ID_CAIXA = 1
                              ORDER BY DATA_FECHAMENTO DESC";
        $dadosCaixa = DB::select($queryDadosCaixa)[0];

        $queryVenda = "SELECT venda_pdv.ID
                            , DATE_FORMAT(venda_pdv.DATA, '%d/%m/%Y %H:%i') as DATA
                            , venda_pdv.VALOR_TOTAL
                            , (CASE
                               WHEN LENGTH(venda_pdv.CLIENTE_NOME) = 0 
                               THEN '-'
                               ELSE venda_pdv.CLIENTE_NOME
                               END) AS CLIENTE_NOME
                            , (SELECT tipo_pagamento.DESCRICAO
                                    FROM tipo_pagamento
                                WHERE venda_pdv.PAGAMENTO = tipo_pagamento.ID) as PAGAMENTO
                        FROM venda_pdv
                        WHERE STATUS = 'A'
                          AND PAGAMENTO <> 0";
        $dadosVenda = DB::select($queryVenda);

        $queryVendaPix = "SELECT SUM(venda_pdv.VALOR_TOTAL) AS TOTAL
                            FROM venda_pdv
                           WHERE STATUS = 'A'
                             AND PAGAMENTO = 3";
        $dadosVendaPix = DB::select($queryVendaPix)[0];

        $queryVendaDinheiro = "SELECT SUM(venda_pdv.VALOR_TOTAL) AS TOTAL
                                 FROM venda_pdv
                                WHERE STATUS = 'A'
                                  AND PAGAMENTO = 1";
        $dadosVendaDinheiro= DB::select($queryVendaDinheiro)[0];

        $queryVendaCartao = "SELECT SUM(venda_pdv.VALOR_TOTAL) AS TOTAL
                               FROM venda_pdv
                              WHERE STATUS = 'A'
                                AND PAGAMENTO = 2";
        $dadosVendaCartao = DB::select($queryVendaCartao)[0];

        $pdf = PDF::loadView('caixa::impressos.caixa', compact('dadosEmpresa', 'data', 'dadosCaixa', 'dadosVenda', 'dadosVendaPix', 'dadosVendaDinheiro', 'dadosVendaCartao'));
        
        return $pdf->stream('impresso-caixa.pdf');
        
    }
}
