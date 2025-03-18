<?php

namespace Modules\ControlePonto\Http\Controllers;

use GuzzleHttp\Psr7\Query;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ControlePontoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('controleponto::index');
    }

    public function buscarPonto(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $idUsuario = auth()->user()->id;
        $return = [];
        
        // MONTA O FILTRO DE DATA
        if(isset($dadosRecebidos['DATA_INICIO']) && isset($dadosRecebidos['DATA_TERMINO'])){
            $filtroData = "AND ponto_eletronico.DATA_ENTRADA BETWEEN '{$dadosRecebidos['DATA_INICIO']} 00:00:00'
                                                                AND '{$dadosRecebidos['DATA_TERMINO']} 23:59:59'";
        } else if(isset($dadosRecebidos['DATA_TERMINO']) && !isset($dadosRecebidos['DATA_INICIO'])){
            $filtroData = "AND ponto_eletronico.DATA_ENTRADA <= '{$dadosRecebidos['DATA_TERMINO']} 23:59:59'";
        } else if(isset($dadosRecebidos['DATA_INICIO']) && !isset($dadosRecebidos['DATA_TERMINO'])){
            $filtroData = "AND ponto_eletronico.DATA_ENTRADA >= '{$dadosRecebidos['DATA_INICIO']} 00:00:00'";
        } else {
            $filtroData = "AND 1 = 1";
        }
        
        // MONTA O FILTRO DE BUSCA DE TEXTO
        if(isset($dadosRecebidos['FILTRO_BUSCA'])){
            $filtroParametro = str_replace(' ', '%', $dadosRecebidos['FILTRO_BUSCA']);
            $filtroBusca = "AND COLUNA LIKE '%filtroParametro%'";
        } else {
            $filtroBusca = 'AND 1 = 1';
        }
        
        if(isset($dadosRecebidos['dadosPorPagina']) && isset($dadosRecebidos['offset']) && $dadosRecebidos['dadosPorPagina'] != 'todos'){
            $filtroLimit = "LIMIT ".$dadosRecebidos['dadosPorPagina']."
                           OFFSET ".$dadosRecebidos['offset'];
        }

        if(Gate::allows('ADMINISTRADOR')){
            $filtroPonto = "AND 1 = 1";
        } else {
            $filtroPonto = "AND ID_USUARIO = $idUsuario";
        }
        
        $queryCount= " SELECT COUNT(*) as COUNT
                         FROM ponto_eletronico
                        WHERE 1 = 1
                        $filtroBusca
                        $filtroData
                        $filtroPonto ";
        $resultCount = DB::select($queryCount);
        $return['contagem'] = $resultCount[0]->COUNT;
        
        $query = " SELECT ponto_eletronico.*
                        , (SELECT NAME
                             FROM users
                            WHERE ID = ponto_eletronico.ID_USUARIO) AS NOME_USUARIO
                     FROM ponto_eletronico
                    WHERE 1 = 1
                    $filtroData
                    $filtroBusca
                    $filtroPonto 
                    ORDER BY DATA_ENTRADA DESC
                    $filtroLimit";
        $result = DB::select($query);
        $return['dados'] = $result;

        if(Gate::allows('ADMINISTRADOR')){
            $return['query'] = $query;
        }
        
        return $return;
    }

    public function registrarPonto(Request $request){
        $dadosRecebidos = $request->except('_roken');
        $latitude = $dadosRecebidos['LATITUDE'];
        $longitude = $dadosRecebidos['LONGITUDE'];
        $idUsuario = auth()->user()->id;
        $retorno = [];

        $queryPontoAberto = "SELECT COALESCE(MAX(ID), 0) as PONTO_ABERTO
                               FROM ponto_eletronico
                              WHERE ID_USUARIO = $idUsuario
                                AND DATA_SAIDA IS NULL";
        $pontoAberto = DB::select($queryPontoAberto)[0]->PONTO_ABERTO;

        if($pontoAberto > 0){
            $queryPonto = "UPDATE ponto_eletronico
                              SET DATA_SAIDA = NOW()
                                , LATITUDE_SAIDA = $latitude
                                , LONGITUDE_SAIDA = $longitude
                            WHERE ID = $pontoAberto";
        } else {
            $queryPonto = "INSERT INTO ponto_eletronico ( ID_USUARIO
                                                        , DATA_ENTRADA
                                                        , LATITUDE_ENTRADA
                                                        , LONGITUDE_ENTRADA
                                                        ) VALUES (
                                                          $idUsuario
                                                        , NOW()
                                                        , '$latitude'
                                                        , '$longitude'
                                                        )";
        }

        $resultPonto = DB::select($queryPonto);

        $retorno['SITUACAO'] = 'SUCESSO';
        $retorno['PONTO_ABERTO'] = $pontoAberto;

        return $retorno;
    }
}
