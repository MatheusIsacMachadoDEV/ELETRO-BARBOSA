<?php

namespace Modules\Usuarios\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('usuarios::index');
    }

    public function buscarUsuarios(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $return = [];
        
        // MONTA O FILTRO DE BUSCA DE TEXTO
        if(isset($dadosRecebidos['FILTRO_BUSCA'])){
            $filtroParametro = str_replace(' ', '%', $dadosRecebidos['FILTRO_BUSCA']);
            $filtroBusca = "AND NAME LIKE '%$filtroParametro%'";
        } else {
            $filtroBusca = 'AND 1 = 1';
        }
        
        if(isset($dadosRecebidos['dadosPorPagina']) && isset($dadosRecebidos['offset']) && $dadosRecebidos['dadosPorPagina'] != 'todos'){
            $filtroLimit = "LIMIT ".$dadosRecebidos['dadosPorPagina']."
                           OFFSET ".$dadosRecebidos['offset'];
        }
        
        $queryCount= " SELECT COUNT(*) as COUNT
                           FROM users
                          WHERE 1 = 1
                          $filtroBusca";
        $resultCount = DB::select($queryCount);
        $return['contagem'] = $resultCount[0]->COUNT;
        
        $query = " SELECT users.ID
                        , users.NAME
                       FROM users
                      WHERE 1 = 1
                      $filtroBusca
                      $filtroLimit";
        $result = DB::select($query);
        $return['dados'] = $result;
        
        return $return;
    }
}
