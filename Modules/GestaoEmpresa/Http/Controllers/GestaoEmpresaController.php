<?php

namespace Modules\GestaoEmpresa\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use GuzzleHttp\Psr7\Query;

class GestaoEmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(){
        return view('gestaoempresa::index');
    }

    public function funcionarios(){
        return view('gestaoempresa::funcionarios');
    }

    public function uniformes(){
        return view('gestaoempresa::uniformes');
    }

    public function buscarUniforme(Request $request){
        $dadosRecebidos = $request->except('_token');
        
        if(isset($dadosRecebidos['FILTRO_ADICIONAL']) && strlen($dadosRecebidos['FILTRO_ADICIONAL']) > 0){
            $filtroAdicional = $dadosRecebidos['FILTRO_ADICIONAL'];
        } else {
            $filtroAdicional = "";
        }

        if(isset($dadosRecebidos['filtro']) && strlen($dadosRecebidos['filtro']) > 0){
            $filtro = "AND (DESCRICAO LIKE '%{$dadosRecebidos['filtro']}%' OR CODIGO LIKE '%{$dadosRecebidos['filtro']}%')";
        } else {
            $filtro = "";
        }
        
        $query = "SELECT * 
                    FROM uniforme 
                   WHERE STATUS = 'A' 
                   $filtro 
                   $filtroAdicional
                   ORDER BY DESCRICAO ASC";
        $result['dados'] = DB::select($query);
        $result['query'] = $query;
        
        return $result;
    }  

    public function buscarUniformeUsuario(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idUsuario = $dadosRecebidos['ID_USUARIO'];
        $return = [];

        $query = "SELECT uu.*
                       , (SELECT DESCRICAO
                            FROM uniforme
                           WHERE uniforme.ID = uu.ID_UNIFORME) as DESCRICAO
                    FROM uniforme_usuario uu
                   WHERE STATUS = 'A'
                     AND uu.ID_USUARIO = $idUsuario
                   ORDER BY uu.DATA_INSERCAO desc";
        $return['dados'] = DB::select($query);

        return $return;
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

    public function inserirUniformeUsuario(Request $request){
        $dadosRecebidos = $request->validate([
            'ID_USUARIO' => 'required|integer',
            'ID_UNIFORME' => 'required|integer',
            'QTDE' => 'required|integer|min:1'
        ]);
        $idUsuario = $dadosRecebidos['ID_USUARIO'];
        $idUniforme = $dadosRecebidos['ID_UNIFORME'];
        $qtde = $dadosRecebidos['QTDE'];
        $return = [];

        $queryValidacao = "SELECT QUANTIDADE
                             FROM uniforme
                            WHERE ID = $idUniforme";
        $quantidadeAtual = DB::select($queryValidacao)[0]->QUANTIDADE;

        if($quantidadeAtual < $qtde){
            $return['situacao'] = 'erro';
            $return['mensagem'] = "O uniforme tem apenas $quantidadeAtual unidades disponíveis.";
            return $return;
        }

        $query = "INSERT INTO uniforme_usuario (ID_USUARIO, ID_UNIFORME, QTDE) 
                  VALUES ($idUsuario, $idUniforme, $qtde)";
        DB::select($query);

        $queryUpdate = "UPDATE uniforme
                           SET QUANTIDADE = QUANTIDADE - $qtde
                             , DATA_ULTIMA_SAIDA = now()
                         WHERE ID = $idUniforme";
        DB::select($queryUpdate);

        $return['situacao'] = 'sucesso';

        return $return;
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

    public function inativarUniformeUsuario(Request $request){
        $id = $request->input('ID');
        
        $queryUpdate = "UPDATE uniforme_usuario
                           SET STATUS = 'I'
                         WHERE ID = $id";
        DB::select($queryUpdate);

        $queryQtde = "SELECT QTDE
                           , ID_UNIFORME
                        FROM uniforme_usuario
                       WHERE ID = $id";
        $dadosRetornar = DB::select($queryQtde)[0];

        $queryUpdateUniforme = "UPDATE uniforme
                                   SET QUANTIDADE = QUANTIDADE + {$dadosRetornar->QTDE}
                                 WHERE ID = {$dadosRetornar->ID_UNIFORME}";
        DB::select($queryUpdateUniforme);

        return response()->json(['success' => 'Uniforme devolvido com sucesso!']);
    }
}
