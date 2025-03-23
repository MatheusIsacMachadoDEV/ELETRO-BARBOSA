<?php

namespace Modules\GestaoEmpresa\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;

class GestaoEmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('gestaoempresa::index');
    }

    public function funcionarios()
    {
        return view('gestaoempresa::funcionarios');
    }

    public function uniformes()
    {
        return view('gestaoempresa::uniformes');
    }

    public function buscarUniforme(Request $request){
        $dadosRecebidos = $request->except('_token');
        
        if(isset($dadosRecebidos['filtro']) && strlen($dadosRecebidos['filtro']) > 0){
            $filtro = "AND (DESCRICAO LIKE '%{$dadosRecebidos['filtro']}%' OR CODIGO LIKE '%{$dadosRecebidos['filtro']}%')";
        } else {
            $filtro = "";
        }
        
        $query = "SELECT * FROM uniforme WHERE STATUS = 'A' $filtro ORDER BY DESCRICAO ASC";
        $result['dados'] = DB::select($query);
        $result['query'] = $query;
        
        return $result;
    }

    public function inserirUniforme(Request $request){
        $dadosRecebidos = $request->except('_token');
        $descricao = $dadosRecebidos['DESCRICAO'];
        $codigo = $dadosRecebidos['CODIGO'];
        $quantidade = $dadosRecebidos['QUANTIDADE'];
        
        $query = "INSERT INTO uniforme (DESCRICAO, CODIGO, QUANTIDADE) 
                                  VALUES ('$descricao', '$codigo', $quantidade)";
        $result = DB::select($query);
        
        return $result;
    }
    
    public function alterarUniforme(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['ID'];
        $descricao = $dadosRecebidos['DESCRICAO'];
        $codigo = $dadosRecebidos['CODIGO'];
        $quantidade = $dadosRecebidos['QUANTIDADE'];
        
        $query = "UPDATE uniforme 
                     SET DESCRICAO = '$descricao', 
                         CODIGO = '$codigo', 
                         QUANTIDADE = $quantidade
                   WHERE ID = $idCodigo";
        $result = DB::select($query);
        
        return $result;
    }
    
    public function inativarUniforme(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['ID'];
        
        $query = "UPDATE uniforme 
                     SET STATUS = 'I' 
                   WHERE ID = $idCodigo";
        $result = DB::select($query);
        
        return $result;
    }    
}
