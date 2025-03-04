<?php

namespace Modules\Compras\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use Config;
use PDF;
use DateTime;

class ComprasController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('compras::index');
    }

    public function buscarOrdemCompra(Request $request){
        $dadosRecebidos = $request->except('_token');

        if(isset($dadosRecebidos['DATA_INICIO']) && isset($dadosRecebidos['DATA_FIM'])){
            $filtroData = "AND ordem_compra.DATA_CADASTRO BETWEEN '{$dadosRecebidos['DATA_INICIO']} 00:00:00'
                                                AND '{$dadosRecebidos['DATA_FIM']} 23:59:59'";
        } else if(isset($dadosRecebidos['DATA_FIM']) && !isset($dadosRecebidos['DATA_INICIO'])){
            $filtroData = "AND ordem_compra.DATA_CADASTRO <= '{$dadosRecebidos['DATA_FIM']} 23:59:59'";
        } else if(isset($dadosRecebidos['DATA_INICIO']) && !isset($dadosRecebidos['DATA_FIM'])){
            $filtroData = "AND ordem_compra.DATA_CADASTRO >= '{$dadosRecebidos['DATA_INICIO']} 00:00:00'";
        } else {
            $filtroData = "AND 1 = 1";
        }

        if(isset($dadosRecebidos['ID'])){
            $filtroID = "AND ID = '{$dadosRecebidos['ID']}'";
        } else {
            $filtroID = 'AND 1 = 1';
        }

        if(isset($dadosRecebidos['ID_SITUACAO']) && $dadosRecebidos['ID_SITUACAO'] > 0){
            $filtroSituacao = "AND ID_SITUACAO = '{$dadosRecebidos['ID_SITUACAO']}'";
        } else {
            $filtroSituacao = 'AND 1 = 1';
        }

        if(isset($dadosRecebidos['filtro']) && strlen($dadosRecebidos['filtro']) > 0){
            $filtro = "AND ((SELECT COUNT(*)
                               FROM users
                              WHERE users.ID = ordem_compra.ID_USUARIO
                                AND users.name LIKE '%{$dadosRecebidos['filtro']}%') > 0
                            OR (SELECT COUNT(*)
                                  FROM users
                                 WHERE users.ID = ordem_compra.ID_USUARIO_APROVACAO
                                   AND users.name LIKE '%{$dadosRecebidos['filtro']}%') > 0
                            OR ordem_compra.OBSERVACAO LIKE '%{$dadosRecebidos['filtro']}%')";
        } else {
            $filtro = 'AND 1 = 1';
        }

        $query = "SELECT ordem_compra.*
                       , (SELECT name
                            FROM users
                           WHERE users.ID = ordem_compra.ID_USUARIO_APROVACAO) as USUARIO_APROVACAO
                       , (SELECT VALOR
                            FROM situacoes
                           WHERE situacoes.ID_ITEM = ordem_compra.ID_SITUACAO
                             AND TIPO = 'ORDEM_COMPRA') AS SITUACAO
                    FROM ordem_compra
                   WHERE STATUS = 'A'
                   $filtro
                   $filtroSituacao
                   $filtroID
                   $filtroData
                   ORDER BY ordem_compra.DATA_CADASTRO DESC";
        $result['dados'] = DB::select($query);
        $result['query'] = $query;

        for ($i=0; $i < count($result['dados']) ; $i++) {
          $idOrdem = $result['dados'][$i]->ID;
          $queryOrdemItem = "SELECT ID
                                  , ID_ITEM
                                  , ID_UNICO_ITEM AS ID_UNICO
                                  , (SELECT MATERIAL
                                       FROM material
                                      WHERE material.ID = ordem_compra_item.ID_UNICO_ITEM) AS ITEM
                                  , OBSERVACAO
                                  , QTDE
                                  , VALOR_UNITARIO
                                  , VALOR_TOTAL
                               FROM ordem_compra_item
                              WHERE STATUS = 'A'
                                AND ID_ORDEM_COMPRA = $idOrdem";
          $result['dados'][$i]->ITENS = DB::select($queryOrdemItem);

        }

        return $result;
    }

    public function buscarDocumentoOrdemCompra(Request $request){
      $dadosRecebidos = $request->except('_token');
      $idOrdemCompra = $dadosRecebidos['ID_ORDEM'];
      $return = [];
      
      $query = " SELECT ordem_compra_documento.*
                     FROM ordem_compra_documento
                    WHERE STATUS = 'A'
                     AND ID_ORDEM_COMPRA = $idOrdemCompra";
      $result = DB::select($query);
      $return['dados'] = $result;
      
      return $return;
    }

    public function inserirOrdemCompra(Request $request){
        $dadosRecebidos = $request->except('_token');
        $data = $dadosRecebidos['data'];
        $valor = $dadosRecebidos['valorTotal'];
        $idProjeto = isset($dadosRecebidos['idProjeto']) ? $dadosRecebidos['idProjeto'] : 0;
        $observacao = $dadosRecebidos['observacao'];
        $usuario = auth()->user()->name;
        $idUsuario = auth()->user()->id;
        $return = [];

        $bancoDados = Config::get('database.connections.mysql.database'); // PEGA A DATABASE DO PROJETO ( FICA NO .ENV)
        $queryNumDoc = "SELECT AUTO_INCREMENT
                          FROM information_schema.TABLES
                         WHERE TABLE_SCHEMA = '$bancoDados'
                           AND TABLE_NAME = 'ordem_compra'"; // PEGA O AUTO INCREMENT DA TABELA EM QUESTAO ( NESSE EXMPLO NFCE)
        $idCodigo = DB::select($queryNumDoc)[0]->AUTO_INCREMENT; // ATRIBUI O AUTO INCREMENT A UMA VARIAVEL

        $query = "INSERT INTO ordem_compra ( ID
                                            , VALOR
                                            , DATA_CADASTRO
                                            , ID_SITUACAO
                                            , ID_PROJETO
                                            , OBSERVACAO
                                            , USUARIO
                                            , ID_USUARIO
                                           ) VALUES (
                                            $idCodigo
                                           , $valor
                                           , '$data'
                                           , 3
                                           , $idProjeto
                                           , '$observacao'
                                           , '$usuario'
                                           , $idUsuario
                                           )";
        $result = DB::select($query);

        for ($i=0; $i < count($dadosRecebidos['dadosItens']) ; $i++) { 
            $valor_total = $dadosRecebidos['dadosItens'][$i]['VALOR_TOTAL'];
            $valor_unitario = $dadosRecebidos['dadosItens'][$i]['VALOR_UNITARIO'];
            $id_item = $dadosRecebidos['dadosItens'][$i]['ID_ITEM'];
            $id_item_unico = $dadosRecebidos['dadosItens'][$i]['ID_UNICO'];
            $qtde = $dadosRecebidos['dadosItens'][$i]['QTDE'];
            $observacao_item = $dadosRecebidos['dadosItens'][$i]['OBSERVACAO'];
            
            $queryItem = "INSERT INTO ordem_compra_item ( 
                                                      ID_ORDEM_COMPRA
                                                    , ID_ITEM
                                                    , ID_UNICO_ITEM
                                                    , VALOR_UNITARIO
                                                    , VALOR_TOTAL
                                                    , QTDE
                                                    , OBSERVACAO
                                                    , USUARIO
                                                    , ID_USUARIO
                                                    ) VALUES (
                                                      $idCodigo
                                                    , $id_item
                                                    , $id_item_unico
                                                    , $valor_unitario
                                                    , $valor_total
                                                    , $qtde
                                                    , '$observacao_item'
                                                    , '$usuario'
                                                    , $idUsuario
                                                    )";
            $resultItem = DB::select($queryItem);
        }

        $return['situacao'] = 'SUCESSO';

        return $return;
    }

    public function inserirDocumentoOrdemCompra(Request $request){
      $dadosRecebidos = $request->except('_token');
      $idOrdem = $dadosRecebidos['ID_ORDEM'];
      $caminhoArquivo = $dadosRecebidos['caminhoArquivo'];

      $query = "INSERT INTO ordem_compra_documento (ID_ORDEM_COMPRA, CAMINHO_DOCUMENTO) 
                                  VALUES ($idOrdem, '$caminhoArquivo')";
      $result = DB::select($query);

      return $result;
  }

    public function alterarOrdemCompra(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['ID'];
        $data = $dadosRecebidos['data'];
        $valor = $dadosRecebidos['valorTotal'];
        $idProjeto = isset($dadosRecebidos['idProjeto']) ? $dadosRecebidos['idProjeto'] : 0;
        $observacao = $dadosRecebidos['observacao'];
        $usuario = auth()->user()->name;
        $idUsuario = auth()->user()->id;
        $return = [];

        $query = "UPDATE ordem_compra
                     SET VALOR = $valor
                       , OBSERVACAO = '$observacao'
                       , DATA_CADASTRO = '$data'
                       , ID_PROJETO = $idProjeto
                   WHERE ID = $idCodigo";
        $result = DB::select($query);

        $queryDelete = "DELETE FROM ordem_compra_item
                         WHERE ID_ORDEM_COMPRA = $idCodigo";
        $resultDelete = DB::select($queryDelete);

        for ($i=0; $i < count($dadosRecebidos['dadosItens']) ; $i++) { 
            $valor_total = $dadosRecebidos['dadosItens'][$i]['VALOR_TOTAL'];
            $valor_unitario = $dadosRecebidos['dadosItens'][$i]['VALOR_UNITARIO'];
            $id_item = $dadosRecebidos['dadosItens'][$i]['ID_ITEM'];
            $id_item_unico = $dadosRecebidos['dadosItens'][$i]['ID_UNICO'];
            $qtde = $dadosRecebidos['dadosItens'][$i]['QTDE'];
            $observacao_item = $dadosRecebidos['dadosItens'][$i]['OBSERVACAO'];
            
            $queryItem = "INSERT INTO ordem_compra_item ( 
                                                      ID_ORDEM_COMPRA
                                                    , ID_ITEM
                                                    , ID_UNICO_ITEM
                                                    , VALOR_UNITARIO
                                                    , VALOR_TOTAL
                                                    , QTDE
                                                    , OBSERVACAO
                                                    , USUARIO
                                                    , ID_USUARIO
                                                    ) VALUES (
                                                      $idCodigo
                                                    , $id_item
                                                    , $id_item_unico
                                                    , $valor_unitario
                                                    , $valor_total
                                                    , $qtde
                                                    , '$observacao_item'
                                                    , '$usuario'
                                                    , $idUsuario
                                                    )";
            $resultItem = DB::select($queryItem);
        }

        $return['situacao'] = 'SUCESSO';

        return $return;
    }

    public function alterarSituacaoOrdemCompra(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['ID'];
        $idSituacao = $dadosRecebidos['ID_SITUACAO'];
        $usuario = auth()->user()->name;
        $idUsuario = auth()->user()->id;
        $return = [];

        $query = "UPDATE ordem_compra 
                     SET ID_SITUACAO = $idSituacao
                   WHERE ID = $idCodigo";
        $result = DB::select($query);

        if($idSituacao == 1){
            $queryDadosOrdem = "SELECT *
                                  FROM ordem_compra_item
                                 WHERE ID_ORDEM_COMPRA = $idCodigo";
            $dadosOrdem = DB::select($queryDadosOrdem);

            $queryUpdateAprovacao = "UPDATE ordem_compra 
                                        SET DATA_APROVACAO = NOW()
                                          , ID_USUARIO_APROVACAO = $idUsuario
                                      WHERE ID = $idCodigo";
            $resultUpdateAprovacao = DB::select($queryUpdateAprovacao);
            
            for ($i=0; $i < count($dadosOrdem); $i++) { 
                $idOrdemCompraItem = $dadosOrdem[$i]->ID;
                $idItem = $dadosOrdem[$i]->ID_ITEM;
                $qtde = $dadosOrdem[$i]->QTDE;

                $queryDadosKardex = "INSERT INTO kardex
                                                 (
                                                   ID_MATERIAL
                                                 , DATA_CADASTRO
                                                 , VALOR
                                                 , ID_ORIGEM
                                                 , ORIGEM
                                                 , TIPO
                                                 , ID_USUARIO
                                                 , USUARIO
                                                ) VALUES (
                                                  $idItem
                                                 , NOW()
                                                 , $qtde
                                                 , $idOrdemCompraItem
                                                 , 'ordem_compra_item'
                                                 , $idSituacao
                                                 , $idUsuario
                                                 , '$usuario'
                                                 )";
                $dadosKardex = DB::select($queryDadosKardex);

                $querydadosOrdemCompraUpdate = "UPDATE material
                                                   SET QTDE = QTDE + $qtde
                                                 WHERE ID = $idItem";
                $dadosOrdemCompraUpdate = DB::select($querydadosOrdemCompraUpdate);
                
            }
        }

        $return['SITUACAO'] = 'SUCESSO';
        return $return;
    }

    public function inativarOrdemCompra(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['ID'];

        $query = "UPDATE ordem_compra 
                     SET STATUS = 'I'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function inativarDocumentoOrdemCompra(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['idDocumento'];

        $query = "UPDATE ordem_compra_documento 
                     SET STATUS = 'I'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function imprimirOrdemCompra($id){
        $data = (new DateTime())->format('d/m/Y H:i');

        $queryOrdemCompra = "SELECT ordem_compra.*
                                   , (SELECT name
                                        FROM users
                                       WHERE users.ID = ordem_compra.ID_USUARIO_APROVACAO) as USUARIO_APROVACAO
                                   , (SELECT VALOR
                                        FROM situacoes
                                       WHERE situacoes.ID_ITEM = ordem_compra.ID_SITUACAO
                                         AND TIPO = 'ORDEM_COMPRA') AS SITUACAO
                                FROM ordem_compra
                               WHERE STATUS = 'A'
                                 AND ID = $id";
        $dadosOrdemCompra = DB::select($queryOrdemCompra)[0];

        $queryItemOrdemCompra = "SELECT ordem_compra_item.*
                                   , (SELECT MATERIAL
                                        FROM material
                                       WHERE material.ID = ordem_compra_item.ID_ITEM) as MATERIAL
                                FROM ordem_compra_item
                               WHERE STATUS = 'A'
                                 AND ID_ORDEM_COMPRA = $id";
        $dadosItemOrdemCompra = DB::select($queryItemOrdemCompra);

        $queryEmpresa = "SELECT empresa_cliente.*
                           FROM empresa_cliente
                          WHERE ID = 1";
        $dadosEmpresa = DB::select($queryEmpresa)[0];

        // Carregar a view 'ORDEM-COMPRA' passando a variável $data
        $pdf = PDF::loadView('compras::impressos.ordem-compra', compact('data', 'dadosOrdemCompra', 'dadosItemOrdemCompra', 'dadosEmpresa'));
        
        // Exibir o PDF inline no navegador
        return $pdf->stream("ORDEM-COMPRA-$id.pdf");
    }

}
