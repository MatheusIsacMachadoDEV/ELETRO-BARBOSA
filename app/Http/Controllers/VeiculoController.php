<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VeiculoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('veiculos');
    }

    public function buscarVeiculo(Request $request){
        $dadosRecebidos = $request->except('_token');
        $placa = $dadosRecebidos['placa'];
        $situacao = $dadosRecebidos['situacao'];

        if($placa != "" && $placa != null){
            $filtroPlaca = "AND PLACA LIKE '%".$placa."%'";
        } else {
            $filtroPlaca = "AND 1 = 1";
        }

        if($situacao == "todos" ){
            $filtroSituacao = "AND SITUACAO IN('PATIO', 'VENDIDO', 'MANUTENCAO')";
        } else if($situacao == "vendidos"){
            $filtroSituacao = "AND SITUACAO = 'VENDIDO'";
        } else if($situacao == "patio"){
            $filtroSituacao = "AND SITUACAO = 'PATIO'";
        } else {
            $filtroSituacao = "AND 1 = 1";
        }

        if(isset($dadosRecebidos['ID'])){
            $filtroVeiculo = "AND ID = ".$dadosRecebidos['ID'];
        } else {
            $filtroVeiculo = "AND 1 = 1";
        }

        $query = "SELECT VEICULOS.*
                       , (SELECT SUM(VALOR)
                            FROM MANUTENCAO 
                           WHERE PLACA = VEICULOS.PLACA
                             AND STATUS = 'A') AS GASTOS
                    FROM VEICULOS
                   WHERE 1 = 1
                     AND STATUS = 'A'
                   $filtroPlaca
                   $filtroSituacao
                   $filtroVeiculo";
        $result = DB::select($query);

        return $result;
    }

    public function buscarManutencao(Request $request){
        $dadosRecebidos = $request->except('_token');
        $placa = $dadosRecebidos['placa'];

        if($placa != "" && $placa != null){
            $filtroManutencao = "AND PLACA = '$placa'";
        } else {
            $filtroManutencao = "AND 1 = 1";
        }

        $query = "SELECT *
                    FROM MANUTENCAO
                   WHERE 1 = 1
                     AND STATUS = 'A'
                   $filtroManutencao";
        $result = DB::select($query);

        return $result;
    }

    public function inserirVeiculo(Request $request){
        $dadosRecebidos = $request->except('_token');
        $placa = $dadosRecebidos['placa'];
        $modelo = $dadosRecebidos['modelo'];
        $renavam = $dadosRecebidos['renavam'];
        $chassis = $dadosRecebidos['chassis'];
        $cor = $dadosRecebidos['cor'];
        $ano = $dadosRecebidos['ano'];
        $km = $dadosRecebidos['km'];
        $dataCompra = $dadosRecebidos['dataCompra'];
        $valorCompra = $dadosRecebidos['valorCompra'];

        $query = "INSERT INTO `veiculos`( `PLACA`
                                        , `MODELO`
                                        , `COR`
                                        , `CHASSIS`
                                        , `RENAVAM`
                                        , `ANO`
                                        , `KM`
                                        , `DATA_COMPRA`
                                        , `VALOR_COMPRA`
                                        , `SITUACAO`
                                        , `STATUS`) 
                                VALUES ( UPPER('$placa')
                                        ,'$modelo'
                                        ,'$cor'
                                        ,'$chassis'
                                        ,'$renavam'
                                        , $ano
                                        , $km
                                        , '$dataCompra'
                                        , $valorCompra
                                        , 'PATIO'
                                        , 'A')";
        $result = DB::select($query);

        return $result;
    }

    public function alterarVeiculo(Request $request){
        $dadosRecebidos = $request->except('_token');
        $id = $dadosRecebidos['ID'];
        $modelo = $dadosRecebidos['modelo'];
        $renavam = $dadosRecebidos['renavam'];
        $chassis = $dadosRecebidos['chassis'];
        $cor = $dadosRecebidos['cor'];
        $ano = $dadosRecebidos['ano'];
        $km = $dadosRecebidos['km'];
        $dataCompra = $dadosRecebidos['dataCompra'];
        $valorCompra = $dadosRecebidos['valorCompra'];

        $query = "UPDATE `veiculos` 
                     SET `MODELO` = '$modelo'
                       , `COR` = '$cor'
                       , `CHASSIS` = '$chassis'
                       , `RENAVAM` = '$renavam'
                       , `ANO` = '$ano'
                       , `KM` = '$km'
                       , `DATA_COMPRA` = '$dataCompra'
                       , `VALOR_COMPRA` = '$valorCompra'
                   WHERE `veiculos`.`ID` = $id";
        $result = DB::select($query);

        return $result;
    }

    public function inativarVeiculo(Request $request){
        $dadosRecebidos = $request->except('_token');
        $id = $dadosRecebidos['id'];

        $query = "UPDATE VEICULOS
                     SET STATUS = 'I'
                       , SITUACAO = 'INATIVO'
                   WHERE ID = $id";
        $result = DB::select($query);

        return $result;
    }

    public function inativarManutencao(Request $request){
        $dadosRecebidos = $request->except('_token');
        $id = $dadosRecebidos['id'];

        $query = "UPDATE MANUTENCAO
                     SET STATUS = 'I'
                       , SITUACAO = 'INATIVO'
                   WHERE ID = $id";
        $result = DB::select($query);

        return $result;
    }

    public function concluirManutencao(Request $request){
        $dadosRecebidos = $request->except('_token');
        $id = $dadosRecebidos['id'];

        $query = "UPDATE MANUTENCAO
                     SET SITUACAO = 'FINALIZADA'
                       , DATA_FIM = NOW()
                   WHERE ID = $id";
        $result = DB::select($query);

        return $result;
    }

    public function inserirManutencao(Request $request){
        $dadosRecebidos = $request->except('_token');
        $placa = $dadosRecebidos['placa'];
        $data = $dadosRecebidos['data'];
        $valor = $dadosRecebidos['valor'];
        $mecanica = $dadosRecebidos['mecanica'];
        $estetica = $dadosRecebidos['estetica'];
        $eletrica = $dadosRecebidos['eletrica'];
        $lataria = $dadosRecebidos['lataria'];
        $outros = $dadosRecebidos['outros'];
        $descricao = $dadosRecebidos['descricao'];

        $query = "INSERT INTO `manutencao` (`PLACA`
                                          , `DATA`
                                          , `VALOR`
                                          , `MECANICA`
                                          , `ELETRICA`
                                          , `ESTETICA`
                                          , `LATARIA`
                                          , `OUTROS`
                                          , `DESCRICAO`
                                          , `SITUACAO`
                                          , `STATUS`) 
                                          VALUES (
                                            '$placa'
                                          , '$data'
                                          , $valor
                                          , '$mecanica'
                                          , '$eletrica'
                                          , '$estetica'
                                          , '$lataria'
                                          , '$outros'
                                          , '$descricao'
                                          , 'EM ANDAMENTO'
                                          , 'A')";
        $result = DB::select($query);

        return $result;
    }

    public function inserirDocumento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $placa = $dadosRecebidos['placa'];
        $caminho = $dadosRecebidos['caminho'];

        $query = "INSERT INTO `veiculo_documento` ( `PLACA`
                                        , `CAMINHO_DOCUMENTO`)
                                        VALUES 
                                        ('$placa'
                                        , '$caminho')";
        $result = DB::select($query);

        return $result;
    }

    public function inativarDocumento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idDocumento = $dadosRecebidos['idDocumento'];

        $query = "UPDATE veiculo_documento
                            SET STATUS = 'I'
                        WHERE ID = $idDocumento";
        $result = DB::select($query);

        return $result;
    }

    public function buscarDocumento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $placa = $dadosRecebidos['placa'];

        $query = "SELECT veiculo_documento.*
                    FROM veiculo_documento
                WHERE 1 = 1
                    AND STATUS = 'A'
                    AND placa = '$placa'";
        $result = DB::select($query);

        return $result;
    }
}
