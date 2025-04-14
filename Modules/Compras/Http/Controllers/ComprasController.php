<?php

namespace Modules\Compras\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use Config;
use PDF;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        $idUsuario = auth()->user()->id;

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

        if(Gate::allows('ADMINISTRADOR')){
            $filtroUsuario = "AND 1 = 1";
        } else {
            $filtroUsuario = "AND ID_USUARIO = $idUsuario";
        }

        $query = "SELECT ordem_compra.*
                       , (SELECT name
                            FROM users
                           WHERE users.ID = ordem_compra.ID_USUARIO_APROVACAO) as USUARIO_APROVACAO
                       , (SELECT VALOR
                            FROM situacoes
                           WHERE situacoes.ID_ITEM = ordem_compra.ID_SITUACAO
                             AND TIPO = 'ORDEM_COMPRA') AS SITUACAO
                       , (SELECT TITULO
                            FROM projeto
                           WHERE projeto.ID = ordem_compra.ID_PROJETO) AS PROJETO
                       , (SELECT NOME
                            FROM pessoa
                           WHERE pessoa.ID = ordem_compra.ID_PESSOA) AS NOME_CLIENTE
                    FROM ordem_compra
                   WHERE STATUS = 'A'
                   $filtro
                   $filtroSituacao
                   $filtroID
                   $filtroData
                   $filtroUsuario
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
                                      WHERE material.ID = ordem_compra_item.ID_ITEM) AS ITEM
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
        $valor = isset($dadosRecebidos['valorTotal']) ? $dadosRecebidos['valorTotal'] : 0;
        $idProjeto = isset($dadosRecebidos['idProjeto']) ? $dadosRecebidos['idProjeto'] : 0;
        $idCliente = isset($dadosRecebidos['idCliente']) ? $dadosRecebidos['idCliente'] : 0;
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
                                            , ID_PESSOA
                                           ) VALUES (
                                            $idCodigo
                                           , $valor
                                           , '$data'
                                           , 3
                                           , $idProjeto
                                           , '$observacao'
                                           , '$usuario'
                                           , $idUsuario
                                           , $idCliente
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
        $valor = isset($dadosRecebidos['valorTotal']) ? $dadosRecebidos['valorTotal'] : 0;
        $idProjeto = isset($dadosRecebidos['idProjeto']) ? $dadosRecebidos['idProjeto'] : 0;
        $idCliente = isset($dadosRecebidos['idCliente']) ? $dadosRecebidos['idCliente'] : 0;
        $observacao = $dadosRecebidos['observacao'];
        $usuario = auth()->user()->name;
        $idUsuario = auth()->user()->id;
        $return = [];

        $query = "UPDATE ordem_compra
                     SET VALOR = $valor
                       , OBSERVACAO = '$observacao'
                       , DATA_CADASTRO = '$data'
                       , ID_PROJETO = $idProjeto
                       , ID_PESSOA = $idCliente
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

        $queryDadosItemOrdem = "SELECT *
                                  FROM ordem_compra_item
                                 WHERE ID_ORDEM_COMPRA = $idCodigo";
        $dadosItemOrdem = DB::select($queryDadosItemOrdem);

        for ($i=0; $i < count($dadosItemOrdem); $i++) { 
            $idOrdemCompraItem = $dadosItemOrdem[$i]->ID;
            $idItem = $dadosItemOrdem[$i]->ID_ITEM;
            $qtde = $dadosItemOrdem[$i]->QTDE;

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

            if($idSituacao == 1){
                $updateEstoque = "QTDE + $qtde";
            } else {
                $updateEstoque = "QTDE - $qtde";
            }

            $querydadosOrdemCompraUpdate = "UPDATE material
                                               SET QTDE = $updateEstoque
                                             WHERE ID = $idItem";
            $dadosOrdemCompraUpdate = DB::select($querydadosOrdemCompraUpdate);
        }

        if($idSituacao == 1){

            $queryDadosOrdem = "SELECT *
                                  FROM ordem_compra
                                 WHERE ID = $idCodigo";
            $dadosOrdem = DB::select($queryDadosOrdem)[0];

            $queryUpdateAprovacao = "UPDATE ordem_compra 
                                        SET DATA_APROVACAO = NOW()
                                          , ID_USUARIO_APROVACAO = $idUsuario
                                      WHERE ID = $idCodigo";
            $resultUpdateAprovacao = DB::select($queryUpdateAprovacao);

            $queryCPG = "INSERT INTO contas_pagar (ID_USUARIO, DESCRICAO, DATA_VENCIMENTO, VALOR, SITUACAO, DATA_PAGAMENTO, OBSERVACAO, ID_ORIGEM) 
                                    VALUES ($idUsuario, 'Ordem de Compra $idCodigo', now(), {$dadosOrdem->VALOR}, 'PENDENTE', now(), 'CPG automático referente a APROVAÇÃO da ordem de compra : {$dadosOrdem->ID}-{$dadosOrdem->OBSERVACAO}.', 5)";
            $result = DB::select($queryCPG);            
            
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
                                   , (SELECT TITULO
                                        FROM projeto
                                       WHERE projeto.ID = ordem_compra.ID_PROJETO) AS PROJETO
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

        $idPessoa = $dadosOrdemCompra->ID_PESSOA != null ? $dadosOrdemCompra->ID_PESSOA : 0;
        $queryCliente = "SELECT pessoa.*
                           FROM pessoa
                          WHERE ID = '$idPessoa'";
        $dadosCliente = count(DB::select($queryCliente)) > 0 ? DB::select($queryCliente)[0] : [];

        $queryEmpresa = "SELECT empresa_cliente.*
                           FROM empresa_cliente
                          WHERE ID = 1";
        $dadosEmpresa = DB::select($queryEmpresa)[0];

        // Carregar a view 'ORDEM-COMPRA' passando a variável $data
        $pdf = PDF::loadView('compras::impressos.ordem-compra', compact('data', 'dadosOrdemCompra', 'dadosItemOrdemCompra', 'dadosEmpresa', 'dadosCliente'));
        
        // Exibir o PDF inline no navegador
        return $pdf->stream("ORDEM-COMPRA-$id.pdf");
    }

}
