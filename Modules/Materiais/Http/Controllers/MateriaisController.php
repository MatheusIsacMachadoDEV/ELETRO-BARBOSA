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

    public function inserirMaterial(Request $request){
        $dadosRecebidos = $request->except('_token');
        $valor = $dadosRecebidos['valor'];
        $material = $dadosRecebidos['material'];
        $marca = $dadosRecebidos['marca'];
        $QTDE = $dadosRecebidos['QTDE'];
        $disponivel = $dadosRecebidos['disponivel'];
        $ultimaRetirada = $dadosRecebidos['ultimaRetirada'];
        $TIPO_MATERIAL = $dadosRecebidos['TIPO_MATERIAL'];
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
                            ) VALUES (
                            '$material'
                            , $valor
                            , '$descricaoMarca'
                            , $marca
                            , $QTDE
                            , $disponivel
                            , '$ultimaRetirada'
                            , $TIPO_MATERIAL
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
