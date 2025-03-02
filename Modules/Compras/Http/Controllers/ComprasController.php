<?php

namespace Modules\Compras\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;

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

        if(isset($dadosRecebidos['ID'])){
            $filtroID = "AND ID = '{$dadosRecebidos['ID']}'";
        } else {
            $filtroID = 'AND 1 = 1';
        }

        if(isset($dadosRecebidos['filtro']) && strlen($dadosRecebidos['filtro']) > 0){
            $filtro = "AND (UPPER(MATERIAL) LIKE UPPER('%".$dadosRecebidos['filtro']."%')
                            OR MATERIAL LIKE '%{$dadosRecebidos['filtro']}%'
                            OR CONCAT('EB', ID) LIKE '{$dadosRecebidos['filtro']}')";
        } else {
            $filtro = 'AND 1 = 1';
        }

        if(isset($dadosRecebidos['situacao']) && $dadosRecebidos['situacao'] > 0){
            $filtroSituacao = "AND SITUACAO = {$dadosRecebidos['situacao']}";
        } else {
            $filtroSituacao = 'AND 1 = 1';
        }

        $query = "SELECT ordem_compra.*
                       , (SELECT NAME
                            FROM users
                           WHERE users.ID = ordem_compra.ID_USUARIO_APROVACAO) as USUARIO_APROVACAO
                       , (SELECT VALOR
                            FROM situacoes
                           WHERE situacoes.ID_ITEM = ordem_compra.ID_SITUACAO) AS SITUACAO
                    FROM ordem_compra
                   WHERE STATUS = 'A'
                   $filtro
                   $filtroSituacao
                   $filtroID
                   ORDER BY ordem_compra.DATA_CADASTRO ASC";
        $result['dados'] = DB::select($query);

        return $result;
    }

    public function inserirOrdemCompra(Request $request){
        $dadosRecebidos = $request->except('_token');
        $valor = $dadosRecebidos['valor'];
        $situacao = $dadosRecebidos['situacao'];
        $observacao = $dadosRecebidos['observacao'];
        $usuario = auth()->user()->NAME;
        $idUsuario = auth()->user()->ID;
        $return = [];

        $bancoDados = Config::get('database.connections.mysql.database'); // PEGA A DATABASE DO PROJETO ( FICA NO .ENV)
        $queryNumDoc = "SELECT AUTO_INCREMENT
                          FROM information_schema.TABLES
                         WHERE TABLE_SCHEMA = '$bancoDados'
                           AND TABLE_NAME = 'ordem_servico'"; // PEGA O AUTO INCREMENT DA TABELA EM QUESTAO ( NESSE EXMPLO NFCE)
        $idCodigo = executarSQL($queryNumDoc)[0]->AUTO_INCREMENT; // ATRIBUI O AUTO INCREMENT A UMA VARIAVEL

        $query = "INSERT INTO ordem_compra ( ID
                                            , VALOR
                                            , ID_SITUACAO
                                            , OBSERVACAO
                                            , USUARIO
                                            , ID_USUARIO
                                           ) VALUES (
                                            $idCodigo
                                           , $valor
                                           , $situacao
                                           , $observacao
                                           , $usuario
                                           , $idUsuario
                                           )";
        $result = DB::select($query);

        for ($i=0; $i < count($dadosRecebidos['dadosItens']) ; $i++) { 
            $valor_total = $dadosRecebidos['dadosItens'][$i]->VALOR_TOTAL;
            $valor_unitario = $dadosRecebidos['dadosItens'][$i]->VALOR_UNITARIO;
            $id_item = $dadosRecebidos['dadosItens'][$i]->ID_ITEM;
            $qtde = $dadosRecebidos['dadosItens'][$i]->QTDE;
            $observacao_item = $dadosRecebidos['dadosItens'][$i]->OBSERVACAO;
            
            $queryItem = "INSERT INTO ordem_compra_item ( ID_ORDEM_SERVICO
                                                    , ID_ITEM
                                                    , VALOR_UNITARIO
                                                    , VALOR_TOTAL
                                                    , QTDE
                                                    , OBSERVACAO
                                                    , USUARIO
                                                    , ID_USUARIO
                                                    ) VALUES (
                                                    $idCodigo
                                                    , $id_item
                                                    , $valor_unitario
                                                    , $valor_total
                                                    , $qtde
                                                    , '$observacao_item'
                                                    , $usuario
                                                    , $idUsuario
                                                    )";
            $resultItem = DB::select($queryItem);
        }

        $return['situacao'] = 'SUCESSO';

        return $return;
    }

    public function alterarOrdemCompra(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['ID_ORDEM_COMPRA'];
        $valor = $dadosRecebidos['valor'];
        $situacao = $dadosRecebidos['situacao'];
        $observacao = $dadosRecebidos['observacao'];
        $usuario = auth()->user()->NAME;
        $idUsuario = auth()->user()->ID;
        $return = [];

        $query = "UPDATE ordem_compra
                     SET VALOR = $valor
                       , ID_SITUACAO = $situacao
                       , OBSERVACAO = '$observacao'
                   WHERE ID = $idCodigo";
        $result = DB::select($query);

        $queryDelete = "DELETE ordem_compra_item
                         WHERE ID_ORDEM_COMPRA = $idOrdemCompra";
        $resultDelete = DB::select($queryDelete);

        for ($i=0; $i < count($dadosRecebidos['dadosItens']) ; $i++) { 
            $valor_total = $dadosRecebidos['dadosItens'][$i]->VALOR_TOTAL;
            $valor_unitario = $dadosRecebidos['dadosItens'][$i]->VALOR_UNITARIO;
            $id_item = $dadosRecebidos['dadosItens'][$i]->ID_ITEM;
            $qtde = $dadosRecebidos['dadosItens'][$i]->QTDE;
            $observacao_item = $dadosRecebidos['dadosItens'][$i]->OBSERVACAO;
            
            $queryItem = "INSERT INTO ordem_compra_item ( ID_ORDEM_SERVICO
                                                    , ID_ITEM
                                                    , VALOR_UNITARIO
                                                    , VALOR_TOTAL
                                                    , QTDE
                                                    , OBSERVACAO
                                                    , USUARIO
                                                    , ID_USUARIO
                                                    ) VALUES (
                                                    $idCodigo
                                                    , $id_item
                                                    , $valor_unitario
                                                    , $valor_total
                                                    , $qtde
                                                    , '$observacao_item'
                                                    , $usuario
                                                    , $idUsuario
                                                    )";
            $resultItem = DB::select($queryItem);
        }

        $return['situacao'] = 'SUCESSO';

        return $return;
    }

    public function inativaOrdemCompra(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['ID'];

        $query = "UPDATE ordem_compra 
                     SET STATUS = 'I'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function gerarEtiqueta($idMaterial){
        $codigoEtiqueta = "EB$idMaterial";

        $queryEmpresa = "SELECT empresa_cliente.* 
                        FROM empresa_cliente 
                        WHERE ID = 1";
        $dadosEmpresa = DB::select($queryEmpresa)[0];
       
        $qrCode = new QrCode($codigoEtiqueta);
        $qrCode->setSize(200);

        $writer = new PngWriter();
        $qrCodePng = $writer->write($qrCode);
       
        $qrCodeBase64 = base64_encode($qrCodePng->getString());
       
        $qrCodeBase64 = 'data:image/png;base64,' . $qrCodeBase64;
       
        $pdf = PDF::loadView('materiais::impressos.etiqueta-material', compact('codigoEtiqueta', 'dadosEmpresa', 'qrCodeBase64'))->setPaper([0, 0, 100, 100], 'mm');;

        return $pdf->stream("etiqueta-$idMaterial.pdf");
    }

}
