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
use Config;

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

    public function listaMateriais()
    {
        return view('materiais::lista-material');
    }

    public function retiradaDevolucao()
    {
        $idUsuario = auth()->user()->id;
        $query = "SELECT *
                    FROM pessoa
                   where ID_USUARIO = $idUsuario";
        $dadosFuncionario = DB::select($query);

        if(count($dadosFuncionario) > 0){
            $idFuncionario = $dadosFuncionario[0]->ID_USUARIO;
            $nomeFuncionario = $dadosFuncionario[0]->NOME;
        } else {
            $idFuncionario = '';
            $nomeFuncionario = '';
        }

        return view('materiais::retirada-devolucao', compact('idFuncionario', 'nomeFuncionario'));
    }

    public function buscarMaterial(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idUsuario = auth()->user()->id;

        if(Gate::allows('ADMINISTRADOR')){
            $filtroAdministrador = "AND 1 = 1";
        } else {
            $filtroAdministrador = "AND ID_USUARIO = $idUsuario";
        }

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
                            FROM pessoa
                           WHERE ID = material.ID_FORNECEDOR
                             AND STATUS = 'A') AS FORNECEDOR
                       , (SELECT VALOR
                            FROM situacoes
                           WHERE TIPO = 'MATERIAL'
                             AND ID_ITEM = material.TIPO_MATERIAL) as NOME_TIPO_MATERIAL
                    FROM material
                   WHERE STATUS = 'A'
                   $filtro
                   $filtroTipoMaterial
                   $filtroSituacao
                   $filtroID
                   $filtroAdministrador
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
                         FROM kardex
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
    
    public function buscarMaterialLista(Request $request) {
        $dadosRecebidos = $request->except('_token');
        $idUsuario = auth()->user()->id;
    
        if(isset($dadosRecebidos['DATA_INICIO']) && isset($dadosRecebidos['DATA_FIM'])) {
            $filtroData = "AND material_lista.DATA_INSERCAO BETWEEN '{$dadosRecebidos['DATA_INICIO']} 00:00:00'
                            AND '{$dadosRecebidos['DATA_FIM']} 23:59:59'";
        } else if(isset($dadosRecebidos['DATA_FIM']) && !isset($dadosRecebidos['DATA_INICIO'])) {
            $filtroData = "AND material_lista.DATA_INSERCAO <= '{$dadosRecebidos['DATA_FIM']} 23:59:59'";
        } else if(isset($dadosRecebidos['DATA_INICIO']) && !isset($dadosRecebidos['DATA_FIM'])) {
            $filtroData = "AND material_lista.DATA_INSERCAO >= '{$dadosRecebidos['DATA_INICIO']} 00:00:00'";
        } else {
            $filtroData = "AND 1 = 1";
        }
    
        if(isset($dadosRecebidos['ID'])) {
            $filtroID = "AND ID = '{$dadosRecebidos['ID']}'";
        } else {
            $filtroID = 'AND 1 = 1';
        }
    
        if(isset($dadosRecebidos['filtro']) && strlen($dadosRecebidos['filtro']) > 0) {
            $filtro = "AND ((SELECT COUNT(*)
                             FROM users
                             WHERE users.ID = material_lista.ID_USUARIO
                             AND users.name LIKE '%{$dadosRecebidos['filtro']}%') > 0
                          OR (SELECT COUNT(*)
                              FROM material_lista_item
                              WHERE material_lista_item.ID_MATERIAL_LISTA = material_lista.ID
                              AND material_lista_item.NOME_FORNECEDOR LIKE '%{$dadosRecebidos['filtro']}%') > 0)";
        } else {
            $filtro = 'AND 1 = 1';
        }
    
        if(Gate::allows('ADMINISTRADOR')) {
            $filtroUsuario = "AND 1 = 1";
        } else {
            $filtroUsuario = "AND ID_USUARIO = $idUsuario";
        }
    
        $query = "SELECT material_lista.*,
                         (SELECT name
                          FROM users
                          WHERE users.ID = material_lista.ID_USUARIO) as USUARIO_CADASTRO,
                         (SELECT name
                          FROM users
                          WHERE users.ID = material_lista.ID_USUARIO_INATIVACAO) as USUARIO_INATIVACAO
                  FROM material_lista
                  WHERE STATUS = 'A'
                  $filtro
                  $filtroID
                  $filtroData
                  $filtroUsuario
                  ORDER BY material_lista.DATA_INSERCAO DESC";
        $result['dados'] = DB::select($query);
    
        for ($i=0; $i < count($result['dados']); $i++) {
            $idLista = $result['dados'][$i]->ID;
            $queryListaItem = "SELECT ID,
                                       ID_MATERIAL_LISTA,
                                       VALOR_ITEM,
                                       QTDE,
                                       NOME_FORNECEDOR,
                                       ID_FORNECEDOR,
                                       (SELECT name
                                        FROM users
                                        WHERE users.ID = material_lista_item.ID_USUARIO) as USUARIO_CADASTRO
                                FROM material_lista_item
                                WHERE STATUS = 'A'
                                AND ID_MATERIAL_LISTA = $idLista";
            $result['dados'][$i]->ITENS = DB::select($queryListaItem);
        }
    
        return $result;
    }

    public function inserirMaterial(Request $request){
        $dadosRecebidos = $request->except('_token');
        $valor = $dadosRecebidos['valor'];
        $material = $dadosRecebidos['material'];
        $marca = $dadosRecebidos['marca'];
        $QTDE = $dadosRecebidos['QTDE'];
        $disponivel = $dadosRecebidos['disponivel'];
        $ultimaRetirada = isset($dadosRecebidos['ultimaRetirada']) ? "'{$dadosRecebidos['ultimaRetirada']}'" : 'null';
        $TIPO_MATERIAL = $dadosRecebidos['TIPO_MATERIAL'];
        $idFornecedor = isset($dadosRecebidos['ID_FORNECEDOR']) ? $dadosRecebidos['ID_FORNECEDOR'] : 0;
        $descricaoMarca = '';
        $idUsuario = auth()->user()->id;

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
                            , ID_USUARIO
                            ) VALUES (
                            '$material'
                            , $valor
                            , '$descricaoMarca'
                            , $marca
                            , $QTDE
                            , $disponivel
                            , $ultimaRetirada
                            , $TIPO_MATERIAL
                            , $idFornecedor
                            , $idUsuario
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

    public function inserirMaterialLista(Request $request) {
        $dadosRecebidos = $request->except('_token');
        $valorTotal = isset($dadosRecebidos['valorTotal']) ? $dadosRecebidos['valorTotal'] : 0;
        $usuario = auth()->user()->name;
        $idUsuario = auth()->user()->id;
        $return = [];
    
        $bancoDados = Config::get('database.connections.mysql.database');
        $queryNumDoc = "SELECT AUTO_INCREMENT
                        FROM information_schema.TABLES
                        WHERE TABLE_SCHEMA = '$bancoDados'
                        AND TABLE_NAME = 'material_lista'";
        $idCodigo = DB::select($queryNumDoc)[0]->AUTO_INCREMENT;
    
        $query = "INSERT INTO material_lista (
                    ID,
                    VALOR_TOTAL,
                    ID_USUARIO,
                    DATA_INSERCAO,
                    STATUS
                  ) VALUES (
                    $idCodigo,
                    $valorTotal,
                    $idUsuario,
                    NOW(),
                    'A'
                  )";
        $result = DB::select($query);
    
        for ($i=0; $i < count($dadosRecebidos['dadosItens']); $i++) { 
            $valorItem = $dadosRecebidos['dadosItens'][$i]['VALOR_ITEM'];
            $qtde = $dadosRecebidos['dadosItens'][$i]['QTDE'];
            $nomeFornecedor = $dadosRecebidos['dadosItens'][$i]['NOME_FORNECEDOR'];
            $idFornecedor = $dadosRecebidos['dadosItens'][$i]['ID_FORNECEDOR'];
            $idMaterial = $dadosRecebidos['dadosItens'][$i]['ID_ITEM'];
            
            $queryItem = "INSERT INTO material_lista_item (
                            ID_MATERIAL_LISTA,
                            VALOR_ITEM,
                            QTDE,
                            NOME_FORNECEDOR,
                            ID_FORNECEDOR,
                            ID_USUARIO,
                            DATA_INSERCAO,
                            ID_MATERIAL,
                            STATUS
                          ) VALUES (
                            $idCodigo,
                            $valorItem,
                            $qtde,
                            '$nomeFornecedor',
                            $idFornecedor,
                            $idUsuario,
                            NOW(),
                            $idMaterial,
                            'A'
                          )";
            $resultItem = DB::select($queryItem);
        }
    
        $return['situacao'] = 'SUCESSO';
        $return['ID'] = $idCodigo;
    
        return $return;
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
    
    public function alterarMaterialLista(Request $request) {
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['ID'];
        $valorTotal = isset($dadosRecebidos['valorTotal']) ? $dadosRecebidos['valorTotal'] : 0;
        $usuario = auth()->user()->name;
        $idUsuario = auth()->user()->id;
        $return = [];
    
        $query = "UPDATE material_lista
                  SET VALOR_TOTAL = $valorTotal
                  WHERE ID = $idCodigo";
        $result = DB::select($query);
    
        $queryDelete = "DELETE FROM material_lista_item
                        WHERE ID_MATERIAL_LISTA = $idCodigo";
        $resultDelete = DB::select($queryDelete);
    
        for ($i=0; $i < count($dadosRecebidos['dadosItens']); $i++) { 
            $valorItem = $dadosRecebidos['dadosItens'][$i]['VALOR_ITEM'];
            $qtde = $dadosRecebidos['dadosItens'][$i]['QTDE'];
            $nomeFornecedor = $dadosRecebidos['dadosItens'][$i]['NOME_FORNECEDOR'];
            $idFornecedor = $dadosRecebidos['dadosItens'][$i]['ID_FORNECEDOR'];
            $idMaterial = $dadosRecebidos['dadosItens'][$i]['ID_ITEM'];
            
            $queryItem = "INSERT INTO material_lista_item (
                            ID_MATERIAL_LISTA,
                            VALOR_ITEM,
                            QTDE,
                            NOME_FORNECEDOR,
                            ID_FORNECEDOR,
                            ID_USUARIO,
                            DATA_INSERCAO,
                            ID_MATERIAL,
                            STATUS
                          ) VALUES (
                            $idCodigo,
                            $valorItem,
                            $qtde,
                            '$nomeFornecedor',
                            $idFornecedor,
                            $idUsuario,
                            NOW(),
                            $idMaterial,
                            'A'
                          )";
            $resultItem = DB::select($queryItem);
        }
    
        $return['situacao'] = 'SUCESSO';
    
        return $return;
    }

    public function alterarSituacaoLista(Request $request) {
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['ID'];
        $situacao = $dadosRecebidos['SITUACAO'];
        $return = [];
    
        $query = "UPDATE material_lista
                     SET SITUACAO = '$situacao'
                   WHERE ID = $idCodigo";
        $result = DB::select($query);

        $return['situacao'] = 'SUCESSO';
    
        return $return;
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
    
    public function inativarMaterialLista(Request $request) {
        $dadosRecebidos = $request->except('_token');
        $idCodigo = $dadosRecebidos['ID'];
        $idUsuario = auth()->user()->id;
    
        $query = "UPDATE material_lista 
                  SET STATUS = 'I',
                      ID_USUARIO_INATIVACAO = $idUsuario,
                      DATA_INATIVACAO = NOW()
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

    public function imprimirLista($id){
        $data = (new DateTime())->format('d/m/Y H:i');

        $queryOrdemCompra = "SELECT material_lista.*
                                   , (SELECT name
                                        FROM users
                                       WHERE users.ID = material_lista.ID_USUARIO) as USUARIO
                                FROM material_lista
                               WHERE STATUS = 'A'
                                 AND ID = $id";
        $dadosOrdemCompra = DB::select($queryOrdemCompra)[0];

        $queryItemOrdemCompra = "SELECT material_lista_item.*
                                   , (SELECT MATERIAL
                                        FROM material
                                       WHERE material.ID = material_lista_item.ID_MATERIAL) as MATERIAL
                                FROM material_lista_item
                               WHERE STATUS = 'A'
                                 AND ID_MATERIAL_LISTA = $id";
        $dadosItemOrdemCompra = DB::select($queryItemOrdemCompra);

        $queryEmpresa = "SELECT empresa_cliente.*
                           FROM empresa_cliente
                          WHERE ID = 1";
        $dadosEmpresa = DB::select($queryEmpresa)[0];

        // Carregar a view 'ORDEM-COMPRA' passando a variável $data
        $pdf = PDF::loadView('materiais::impressos.lista-material', compact('data', 'dadosOrdemCompra', 'dadosItemOrdemCompra', 'dadosEmpresa'));
        
        // Exibir o PDF inline no navegador
        return $pdf->stream("ORDEM-COMPRA-$id.pdf");
    }
}
