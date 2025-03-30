<?php

namespace Modules\Materiais\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use DateTime;
use PDF;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use GuzzleHttp\Psr7\Query;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MateriaisController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('materiais::index');
    }

    public function retiradaDevolucao()
    {
        return view('materiais::retirada-devolucao');
    }

    public function buscarMaterial(Request $request){
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

        if(isset($dadosRecebidos['tipo_material']) && $dadosRecebidos['tipo_material'] > 0){
            $filtroTipoMaterial = "AND TIPO_MATERIAL = {$dadosRecebidos['tipo_material']}";
        } else {
            $filtroTipoMaterial = 'AND 1 = 1';
        }

        if(isset($dadosRecebidos['situacao']) && $dadosRecebidos['situacao'] > 0){
            $filtroSituacao = "AND SITUACAO = {$dadosRecebidos['situacao']}";
        } else {
            $filtroSituacao = 'AND 1 = 1';
        }

        $query = "SELECT material.*
                       , (SELECT NOME
                            FROM PESSOA
                           WHERE ID = material.ID_FORNECEDOR
                             AND STATUS = 'A') AS FORNECEDOR
                    FROM material
                   WHERE STATUS = 'A'
                   $filtro
                   $filtroTipoMaterial
                   $filtroSituacao
                   $filtroID
                   ORDER BY MATERIAL ASC";
        $result['dados'] = DB::select($query);

        return $result;
    }

    public function buscarMarca(Request $request){
        $dadosRecebidos = $request->except('_token');

        $query = "SELECT material_marca.*
                    FROM material_marca
                   WHERE STATUS = 'A'
                   ORDER BY DESCRICAO ASC";
        $result = DB::select($query);

        return $result;
    }

    public function buscarMaterialMovimento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $idUsuario = auth()->user()->id;
        $return = [];
        
        // MONTA O FILTRO DE DATA
        if(isset($dadosRecebidos['dataInicio']) && isset($dadosRecebidos['dataTermino'])){
            $filtroData = "AND TABELA.DATA BETWEEN '{{$dadosRecebidos['dataInicio']}}'
                                                 AND '{{$dadosRecebidos['dataTermino']}}'";
        } else if(isset($dadosRecebidos['dataTermino']) && !isset($dadosRecebidos['dataInicio'])){
            $filtroData = "AND TABELA.DATA <= ''";
        } else if(isset($dadosRecebidos['dataInicio']) && !isset($dadosRecebidos['dataTermino'])){
            $filtroData = "AND TABELA.DATA >= ''";
        } else {
            $filtroData = "AND 1 = 1";
        }
        
        // MONTA O FILTRO DE BUSCA DE TEXTO
        if(isset($dadosRecebidos['FILTRO_BUSCA'])){
            $filtroParametro = str_replace(' ', '%', $dadosRecebidos['FILTRO_BUSCA']);
            $filtroBusca = "AND (EQUIPAMENTO LIKE '%$filtroParametro%'
                                OR PESSOA LIKE '%$filtroParametro%')";
        } else {
            $filtroBusca = 'AND 1 = 1';
        }

        if(isset($dadosRecebidos['ID_FUNCIONARIO'])){
            $filtroFuncionario = "AND (SELECT COUNT(*)
                                   FROM pessoa
                                  WHERE pessoa.ID_USUARIO = material_movimento.ID_PESSOA
                                    AND pessoa.ID = '{$dadosRecebidos['ID_FUNCIONARIO']}') > 0";
        } else {
            $filtroFuncionario = 'AND 1 = 1';
        }
        
        if(isset($dadosRecebidos['dadosPorPagina']) && isset($dadosRecebidos['offset']) && $dadosRecebidos['dadosPorPagina'] != 'todos'){
            $filtroLimit = "LIMIT ".$dadosRecebidos['dadosPorPagina']."
                           OFFSET ".$dadosRecebidos['offset'];
        }

        if(Gate::allows('ADMINISTRADOR')){
            $filtroUsuario = "AND 1 = 1";
        } else {
            $filtroUsuario = "AND ID_PESSOA = $idUsuario";
        }
        
        $queryCount= " SELECT COUNT(*) as COUNT
                         FROM material_movimento
                        WHERE STATUS = 'A'
                        $filtroBusca
                        $filtroData
                        $filtroFuncionario
                        $filtroUsuario";
        $resultCount = DB::select($queryCount);
        $return['contagem'] = $resultCount[0]->COUNT;
        
        $query = " SELECT material_movimento.*
                     FROM material_movimento
                    WHERE STATUS = 'A'
                    $filtroData
                    $filtroBusca
                    $filtroFuncionario
                    $filtroUsuario
                    ORDER BY DATA DESC
                    $filtroLimit";
        $result = DB::select($query);
        $return['dados'] = $result;
        
        return $return;
    }

    public function buscarMaterialKardex(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $return = [];
        
        // MONTA O FILTRO DE BUSCA DE TEXTO
        if(isset($dadosRecebidos['ID_MATERIAL'])){
            $filtroMaterial = "AND ID_MATERIAL= '{$dadosRecebidos['ID_MATERIAL']}'";
        } else {
            $filtroMaterial = 'AND 1 = 1';
        }
        
        if(isset($dadosRecebidos['dadosPorPagina']) && isset($dadosRecebidos['offset']) && $dadosRecebidos['dadosPorPagina'] != 'todos'){
            $filtroLimit = "LIMIT ".$dadosRecebidos['dadosPorPagina']."
                           OFFSET ".$dadosRecebidos['offset'];
        }
        
        $queryCount= " SELECT COUNT(*) as COUNT
                         FROM KARDEX
                        WHERE STATUS = 'A'
                        $filtroMaterial
                        $filtroLimit";
        $resultCount = DB::select($queryCount);
        $return['contagem'] = $resultCount[0]->COUNT;
        
        $query = " SELECT (SELECT material.MATERIAL
                             FROM material
                            WHERE material.ID = kardex.ID_MATERIAL) AS MATERIAL
                        , (SELECT situacoes.VALOR
                             FROM situacoes
                            WHERE situacoes.ID_ITEM = kardex.TIPO
                              AND situacoes.TIPO = 'KARDEX') AS TIPO_MOVIMENTACAO
                        , kardex.DATA_CADASTRO
                        , kardex.VALOR
                        , (SELECT name
                             FROM users
                            WHERE users.id = kardex.ID_USUARIO) AS USUARIO
                        , ORIGEM
                        , ID
                    FROM kardex
                   WHERE STATUS = 'A'
                   $filtroMaterial
                   $filtroLimit";
        $result = DB::select($query);
        $return['dados'] = $result;
        
        return $return;
    }

    public function inserirMaterial(Request $request){
        $dadosRecebidos = $request->except('_token');
        $valor = $dadosRecebidos['valor'];
        $material = $dadosRecebidos['material'];
        $marca = $dadosRecebidos['marca'];
        $QTDE = $dadosRecebidos['QTDE'];
        $disponivel = $dadosRecebidos['disponivel'];
        $ultimaRetirada = $dadosRecebidos['ultimaRetirada'];
        $TIPO_MATERIAL = $dadosRecebidos['TIPO_MATERIAL'];
        $idFornecedor = isset($dadosRecebidos['ID_FORNECEDOR']) ? $dadosRecebidos['ID_FORNECEDOR'] : 0;
        $descricaoMarca = '';

        if($marca > 0){
            $queryMarca = "SELECT DESCRICAO
                             FROM material_marca
                            WHERE ID = $marca";
            $descricaoMarca = DB::select($queryMarca)[0]->DESCRICAO;
        }

        $query = "INSERT INTO material
                            (MATERIAL
                            , VALOR
                            , MARCA
                            , ID_MARCA
                            , QTDE
                            , SITUACAO
                            , DATA_ULTIMA_RETIRADA
                            , TIPO_MATERIAL
                            , ID_FORNECEDOR
                            ) VALUES (
                            '$material'
                            , $valor
                            , '$descricaoMarca'
                            , $marca
                            , $QTDE
                            , $disponivel
                            , '$ultimaRetirada'
                            , $TIPO_MATERIAL
                            , $idFornecedor
                            )";
        $result = DB::select($query);

        return $result;
    }

    public function inserirMarca(Request $request){
        $dadosRecebidos = $request->except('_token');
        $marca = $dadosRecebidos['MARCA'];

        $query = "INSERT INTO material_marca
                            (DESCRICAO
                            ) VALUES (
                            '$marca'
                            )";
        $result = DB::select($query);

        return $result;
    }

    public function inserirRetiradaDevolucao(Request $request){
        $dadosRecebidos = $request->except('_token');
        $data = str_replace('T', ' ', $dadosRecebidos['data']);
        $pessoa = $dadosRecebidos['pessoa'];
        $idPessoa = $dadosRecebidos['idPessoa'];
        $equipamento = $dadosRecebidos['equipamento'];
        $idEquipamento = $dadosRecebidos['idEquipamento'];
        $tipo = $dadosRecebidos['tipo'];

        $query = "INSERT INTO material_movimento
	                          (TIPO_MOVIMENTO
                               , `DATA`
                               , ID_PESSOA
                               , PESSOA
                               , ID_EQUIPAMENTO
                               , EQUIPAMENTO
                            ) VALUES (
                                $tipo
                               , '$data'
                               , '$idPessoa'
                               , '$pessoa'
                               , '$idEquipamento'
                               , '$equipamento'
                            )";
        $result = DB::select($query);

        $queryMaterialMovimento = "SELECT COALESCE(MAX(DATA), NOW()) as DATA_MOVIMENTACAO
                                     FROM material_movimento
                                    WHERE ID_EQUIPAMENTO = $idEquipamento";
        $dataUltimaMovimentacao = DB::select($queryMaterialMovimento)[0]->DATA_MOVIMENTACAO;

        $queryUpdateMaterial = "UPDATE material
                                   SET DATA_ULTIMA_RETIRADA = '$dataUltimaMovimentacao'
                                     , USUARIO_ULTIMA_RETIRADA = '$idPessoa'
                                     , SITUACAO = '$tipo'
                                 WHERE ID = $idEquipamento";
        $resultUpdateMaterial = DB::select($queryUpdateMaterial);  

        return $result;
    }

    public function alterarMaterial(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idMaterial = $dadosRecebidos['ID'];
        $valor = $dadosRecebidos['valor'];
        $material = $dadosRecebidos['material'];
        $marca = $dadosRecebidos['marca'];
        $QTDE = $dadosRecebidos['QTDE'];
        $disponivel = $dadosRecebidos['disponivel'];
        $ultimaRetirada = strlen($dadosRecebidos['ultimaRetirada']) > 0 ? "'{$dadosRecebidos['ultimaRetirada']}'" : 'null';
        $TIPO_MATERIAL = $dadosRecebidos['TIPO_MATERIAL'];
        $idFornecedor = isset($dadosRecebidos['ID_FORNECEDOR']) ? $dadosRecebidos['ID_FORNECEDOR'] : 0;
        $descricaoMarca = '';

        if($marca > 0){
            $queryMarca = "SELECT DESCRICAO
                             FROM material_marca
                            WHERE ID = $marca";
            $descricaoMarca = DB::select($queryMarca)[0]->DESCRICAO;
        }

        $query = "UPDATE material
                     SET MATERIAL = '$material'
                       , VALOR = $valor
                       , MARCA = '$descricaoMarca'
                       , ID_MARCA = $marca
                       , QTDE = $QTDE
                       , SITUACAO = $disponivel
                       , DATA_ULTIMA_RETIRADA = $ultimaRetirada
                       , TIPO_MATERIAL = $TIPO_MATERIAL
                       , ID_FORNECEDOR = $idFornecedor
                   WHERE ID = $idMaterial";
        $result = DB::select($query);

        return $result;
    }

    public function inativarMaterial(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['ID'];

        $query = "UPDATE material 
                     SET STATUS = 'I'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function inativarMarca(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['ID'];

        $query = "UPDATE material_marca 
                     SET STATUS = 'I'
                    WHERE ID = $idCodigo";
        $result = DB::select($query);

        return $result;
    }

    public function inativarMovimentacao(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['ID'];
        $idPessoaUltimaMovimentacao = auth()->user()->id;

        $query = "UPDATE material_movimento 
                     SET STATUS = 'I'
                   WHERE ID = $idCodigo";
        $result = DB::select($query);

        $queryMaterialMovimento = "SELECT COALESCE(MAX(DATA), NOW()) as DATA_MOVIMENTACAO
                                     FROM material_movimento
                                    WHERE ID_EQUIPAMENTO = (SELECT ID_EQUIPAMENTO
                                                              FROM material_movimento 
                                                             WHERE ID = $idCodigo)
                                    GROUP BY ID_EQUIPAMENTO";
        $resultUltimaMovimentacao = DB::select($queryMaterialMovimento);
        $dataUltimaMovimentacao = $resultUltimaMovimentacao[0]->DATA_MOVIMENTACAO;

        $queryUpdateMaterial = "UPDATE material
                                   SET DATA_ULTIMA_RETIRADA = '$dataUltimaMovimentacao'
                                     , USUARIO_ULTIMA_RETIRADA = '$idPessoaUltimaMovimentacao'
                                 WHERE ID = (SELECT ID_EQUIPAMENTO
                                               FROM material_movimento 
                                              WHERE ID = $idCodigo)";
        $resultUpdateMaterial = DB::select($queryUpdateMaterial);  

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
