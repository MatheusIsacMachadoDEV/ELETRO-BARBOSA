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

    public function documento(){
        return view('gestaoempresa::documento');
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

    public function buscarCaminho(Request $request) {
        $dadosRecebidos = $request->except('_token');
        $idPastaAtual = $dadosRecebidos['ID_PASTA_ATUAL'];
    
        if ($idPastaAtual == 0) {
            $etapas = [['NOME' => 'Geral']];
        } else {
            // Solução para MySQL 5.7/7.4 que não tem WITH RECURSIVE
            $etapas = [];
            $currentId = $idPastaAtual;
            
            while ($currentId != null) {
                $pasta = DB::table('pastas')
                    ->select('ID', 'NOME', 'ID_PASTA_PAI', 'ID_DADO_REFERIDO')
                    ->where('ID', $currentId)
                    ->where('ID_DADO_REFERIDO', '1')
                    ->where('TIPO', 'ADMINISTRATIVO')
                    ->whereRaw('ID_DADO_REFERIDO > 0')
                    ->first();
                
                if (!$pasta) break;
                
                array_unshift($etapas, ['NOME' => $pasta->NOME, 'ID' => $pasta->ID]);
                $currentId = $pasta->ID_PASTA_PAI;
            }
        }
    
        return response()->json(['dados' => $etapas]);
    }

    public function buscarDocumento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $ID_PASTA_ATUAL = $dadosRecebidos['ID_PASTA_ATUAL'];
        $idUsuario = auth()->user()->id;              

        // if(Gate::allows('ADMINISTRADOR')){
            $filtroAdministrador = "AND 1 = 1";
        // } else {
        //     $filtroAdministrador = "AND ID_USUARIO = $idUsuario";
        // }

        $query = "  SELECT pastas.ID
                         , NOME AS CAMINHO_DOCUMENTO
                         , 1 AS TIPO
                      FROM pastas
                     WHERE STATUS = 'A'
                       AND ID_PASTA_PAI = $ID_PASTA_ATUAL
                       AND TIPO = 'ADMINISTRATIVO'
                       $filtroAdministrador
        
                    UNION ALL

                    SELECT p.ID
                         , CAMINHO_DOCUMENTO
                         , 2 AS TIPO
                      FROM documento_administrativo p
                     WHERE p.STATUS = 'A' 
                       AND ID_TIPO = $ID_PASTA_ATUAL";
        $result['dados'] = DB::select($query);

        return response()->json($result);
    }

    public function inserirDocumento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $caminhoArquivo = $dadosRecebidos['caminhoArquivo'];
        $ID_TIPO = $dadosRecebidos['ID_TIPO'];

        $query = "INSERT INTO documento_administrativo (CAMINHO_DOCUMENTO, ID_TIPO) 
                                    VALUES ('$caminhoArquivo', $ID_TIPO)";
        $result = DB::select($query);

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

    public function inserirPasta(Request $request){
        $dadosRecebidos = $request->except('_token');
        $NOME = $dadosRecebidos['NOME'];
        $ID_DOCUMENTO = $dadosRecebidos['ID_DOCUMENTO'] ?? 1;
        $ID_PASTA_PAI = $dadosRecebidos['ID_PASTA_PAI'];
        $idUsuario = auth()->user()->id;              

        $query = "INSERT INTO pastas (NOME, ID_DADO_REFERIDO, ID_PASTA_PAI, ID_USUARIO, TIPO) 
                                    VALUES ('$NOME', $ID_DOCUMENTO, $ID_PASTA_PAI, $idUsuario, 'ADMINISTRATIVO')";
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

    public function inativarDocumento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idDocumento = $dadosRecebidos['idDocumento'];

        $query = "UPDATE documento_administrativo SET STATUS = 'I' WHERE ID = $idDocumento";
        DB::update($query);

        return response()->json(['success' => 'Documento inativado com sucesso!']);
    }

    public function inativarPasta(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idPasta = $dadosRecebidos['idPasta'];
        $idUsuario = auth()->user()->id;

        $query = "UPDATE pastas 
                     SET STATUS = 'I'
                       , DATA_INATIVACAO = NOW()
                       , ID_USUARIO_INATIVACAO = $idUsuario
                   WHERE ID = $idPasta";
        DB::update($query);

        return response()->json(['success' => 'Documento inativado com sucesso!']);
    }
}
