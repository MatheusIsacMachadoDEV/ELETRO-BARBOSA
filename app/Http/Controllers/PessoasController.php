<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PessoasController extends Controller
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
        return view('pessoa');
    }   
    
    public function buscarPessoa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $nome = isset($dadosRecebidos['nome']) ? $dadosRecebidos['nome'] : '';
        $filtroIdTipo = "AND 1 = 1";
        $filtroIdProjeto = "AND 1 = 1";
        $filtroPontoEletronico = "AND 1 = 1";
        $filtroDiaria = "AND diaria.DATA_FIM >= CURDATE()";

        if($nome != "" && $nome != null){
            $filtro = "AND NOME like '%$nome%'";
        } else {
            $filtro = "AND 1 = 1";
        }

        if(isset($dadosRecebidos['ID_TIPO']) && $dadosRecebidos['ID_TIPO'] != 0){
            $filtroIdTipo = "AND ID_TIPO = ".$dadosRecebidos['ID_TIPO'];
        }

        if(isset($dadosRecebidos['ID_PROJETO'])){
            $filtroIdProjeto = "AND (SELECT COUNT(*)
                                       FROM projeto_pessoa
                                      WHERE projeto_pessoa.ID_PESSOA = pessoa.ID
                                        AND projeto_pessoa.STATUS = 'A'
                                        AND projeto_pessoa.ID_PROJETO = {$dadosRecebidos['ID_PROJETO']}) = 0 ";
        }

        if(isset($dadosRecebidos['id'])){
            $filtro = "AND ID = ".$dadosRecebidos['id'];
        }

        if(isset($dadosRecebidos['FILTRO_DIARIA'])){
            $filtroDiaria = "AND (DATA_FIM BETWEEN STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-{$dadosRecebidos['FILTRO_DIARIA']}-1'), '%Y-%m-%d') 
                                              AND LAST_DAY(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-{$dadosRecebidos['FILTRO_DIARIA']}-1'), '%Y-%m-%d')))
                            AND (DATA_INICIO BETWEEN STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-{$dadosRecebidos['FILTRO_DIARIA']}-1'), '%Y-%m-%d') 
                                              AND LAST_DAY(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-{$dadosRecebidos['FILTRO_DIARIA']}-1'), '%Y-%m-%d')))";
        }

        if(isset($dadosRecebidos['PONTO_ELETRONICO'])){
            $filtroPontoEletronico = "AND (DATA_ENTRADA BETWEEN STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-{$dadosRecebidos['PONTO_ELETRONICO']}-1'), '%Y-%m-%d') 
                                              AND LAST_DAY(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-{$dadosRecebidos['PONTO_ELETRONICO']}-1'), '%Y-%m-%d')))
                            AND (DATA_SAIDA BETWEEN STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-{$dadosRecebidos['PONTO_ELETRONICO']}-1'), '%Y-%m-%d') 
                                              AND LAST_DAY(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-{$dadosRecebidos['PONTO_ELETRONICO']}-1'), '%Y-%m-%d')))";
        }

        $query = "SELECT pessoa.*
                       , (SELECT SUM(COALESCE(venda_pdv_fiado.VALOR_FIADO , 0))
                            FROM venda_pdv_fiado, venda_pdv
                           where venda_pdv_fiado.ID_VENDA = venda_pdv.ID
                             AND venda_pdv_fiado.STATUS = 'A'
                             AND venda_pdv.STATUS = 'A'
                             AND venda_pdv_fiado.ID_PESSOA = pessoa.ID) as VALOR_FIADO
                       , (SELECT SUM(COALESCE(venda_pdv_fiado_pagamento.VALOR_PAGAMENTO , 0))
                            FROM venda_pdv_fiado_pagamento
                           where venda_pdv_fiado_pagamento.STATUS = 'A'
                             AND venda_pdv_fiado_pagamento.ID_PESSOA = pessoa.ID) as VALOR_PAGAMENTO
                       , (SELECT VALOR
                            FROM situacoes
                           where situacoes.STATUS = 'A'
                             AND situacoes.TIPO = 'PESSOA_TIPO'
                             AND situacoes.ID_ITEM = pessoa.ID_TIPO) as TIPO_PESSOA
                       , (SELECT name
                            FROM users
                           WHERE users.id = pessoa.ID_USUARIO) as USUARIO
                       , (SELECT COUNT(*)
                            FROM projeto_pessoa
                           WHERE projeto_pessoa.ID_PESSOA = pessoa.ID
                             AND projeto_pessoa.STATUS = 'A') as TOTAL_PROJETO
                       , (SELECT COUNT(*)
                            FROM diaria
                           WHERE diaria.ID_USUARIO = pessoa.ID_USUARIO
                             AND diaria.STATUS = 'A'
                             $filtroDiaria) as TOTAL_DIARIA
                       , COALESCE((SELECT ROUND(SUM(TIMESTAMPDIFF(SECOND, DATA_ENTRADA, DATA_SAIDA)) / 3600, 2)
                            FROM ponto_eletronico
                           WHERE ponto_eletronico.ID_USUARIO = pessoa.ID_USUARIO
                           $filtroPontoEletronico
                           GROUP BY ponto_eletronico.ID_USUARIO),0) AS TOTAL_HORAS
                        , (SELECT SUM(VALOR_TOTAL)
                            FROM diaria
                           WHERE diaria.ID_USUARIO = pessoa.ID_USUARIO
                             AND diaria.STATUS = 'A'
                             $filtroDiaria) as VALOR_TOTAL_DIARIA
                    FROM pessoa
                   WHERE 1 = 1
                     AND STATUS = 'A'
                   $filtro
                   $filtroIdTipo
                   $filtroIdProjeto
                   ORDER BY NOME asc";
        $result = DB::select($query);

        return $result;
    }

    public function inserirPessoa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $nome = $dadosRecebidos['nome'];
        $documento = (isset($dadosRecebidos['documento']) ? $dadosRecebidos['documento'] : '00000000000');
        $telefone = $dadosRecebidos['telefone'];
        $ID_TIPO = $dadosRecebidos['ID_TIPO'];
        $ID_USUARIO = $dadosRecebidos['ID_USUARIO'];
        $email = (isset($dadosRecebidos['email']) ? $dadosRecebidos['email'] : '');
        $ESTADO = (isset($dadosRecebidos['ESTADO']) ? $dadosRecebidos['ESTADO'] : '');
        $CIDADE = (isset($dadosRecebidos['CIDADE']) ? $dadosRecebidos['CIDADE'] : '');
        $RUA = (isset($dadosRecebidos['RUA']) ? $dadosRecebidos['RUA'] : '');
        $NUMERO = (isset($dadosRecebidos['NUMERO']) ? $dadosRecebidos['NUMERO'] : '');
        $SALARIO_BASE = (isset($dadosRecebidos['SALARIO_BASE']) ? $dadosRecebidos['SALARIO_BASE'] : '0');
        $CARGO = (isset($dadosRecebidos['CARGO']) ? $dadosRecebidos['CARGO'] : '');
        $HORAS_MENSAIS = (isset($dadosRecebidos['HORAS_MENSAIS']) ? $dadosRecebidos['HORAS_MENSAIS'] : '0');
        $data_nascimento = (isset($dadosRecebidos['data_nascimento']) ? $dadosRecebidos['data_nascimento'] : '2000-01-01' );

        $query = "INSERT INTO `pessoa` ( `NOME`
                                       , `DOCUMENTO`
                                       , `TELEFONE`
                                       , `EMAIL`
                                       , `DATA_NASCIMENTO`
                                       , `ID_TIPO`
                                       , `ESTADO`
                                       , `CIDADE`
                                       , `RUA`
                                       , `NUMERO`
                                       , `ID_USUARIO`
                                       , `HORAS_MENSAIS`
                                       , `CARGO`
                                       , `SALARIO_BASE`
                                       ) VALUES (
                                       '$nome'
                                      , $documento
                                      , '$telefone'
                                      , '$email'
                                      , '$data_nascimento'
                                      , $ID_TIPO
                                      , '$ESTADO'
                                      , '$CIDADE'
                                      , '$RUA'
                                      , '$NUMERO'
                                      , $ID_USUARIO
                                      , $HORAS_MENSAIS
                                      , '$CARGO'
                                      , $SALARIO_BASE
                                      )";
        $result = DB::select($query);

        return $result;
    }

    public function alterarPessoa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $id = $dadosRecebidos['id'];
        $nome = $dadosRecebidos['nome'];
        $documento = $dadosRecebidos['documento'];
        $telefone = $dadosRecebidos['telefone'];
        $ID_TIPO = $dadosRecebidos['ID_TIPO'];
        $email = $dadosRecebidos['email'];
        $data_nascimento = isset($dadosRecebidos['data_nascimento']) ? "'{$dadosRecebidos['data_nascimento']}'" : 'null';
        $ID_USUARIO = $dadosRecebidos['ID_USUARIO'];
        $ESTADO = ($dadosRecebidos['ESTADO'] == null ? '' : $dadosRecebidos['ESTADO']);
        $CIDADE = ($dadosRecebidos['CIDADE'] == null ? '' : $dadosRecebidos['CIDADE']);
        $RUA = ($dadosRecebidos['RUA'] == null ? '' : $dadosRecebidos['RUA']);
        $NUMERO = ($dadosRecebidos['NUMERO'] == null ? '' : $dadosRecebidos['NUMERO']);
        $SALARIO_BASE = (isset($dadosRecebidos['SALARIO_BASE']) ? $dadosRecebidos['SALARIO_BASE'] : '0');
        $CARGO = (isset($dadosRecebidos['CARGO']) ? $dadosRecebidos['CARGO'] : '');
        $HORAS_MENSAIS = (isset($dadosRecebidos['HORAS_MENSAIS']) ? $dadosRecebidos['HORAS_MENSAIS'] : '0');

        $query = "UPDATE `pessoa` 
                    SET `NOME` = '$nome'
                    , `DOCUMENTO` = $documento
                    , `TELEFONE` = '$telefone'
                    , `EMAIL` = '$email'
                    , `ID_TIPO` = $ID_TIPO
                    , `DATA_NASCIMENTO` = $data_nascimento
                    , `ID_USUARIO` = '$ID_USUARIO'
                    , `ESTADO` = '$ESTADO'
                    , `CIDADE` = '$CIDADE'
                    , `RUA` = '$RUA'
                    , `NUMERO` = '$NUMERO'
                    , `SALARIO_BASE` = '$SALARIO_BASE'
                    , `CARGO` = '$CARGO'
                    , `HORAS_MENSAIS` = '$HORAS_MENSAIS'
                WHERE ID = $id";
        $result = DB::select($query);

        return $result;
    }

    public function inativarPessoa(Request $request){
        $dadosRecebidos = $request->except('_token');
        $id = $dadosRecebidos['id'];

        $query = "UPDATE pessoa
                        SET STATUS = 'I'
                    WHERE ID = $id";
        $result = DB::select($query);

        return $result;
    }  

    public function inserirDocumento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idPessoa = $dadosRecebidos['idPessoa'];
        $caminho = $dadosRecebidos['caminho'];

        $query = "INSERT INTO `pessoa_documento` ( `ID_PESSOA`
                                        , `CAMINHO_DOCUMENTO`)
                                        VALUES 
                                        ($idPessoa
                                        , '$caminho')";
        $result = DB::select($query);

        return $result;
    }

    public function inativarDocumento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idDocumento = $dadosRecebidos['idDocumento'];

        $query = "UPDATE pessoa_documento
                            SET STATUS = 'I'
                        WHERE ID = $idDocumento";
        $result = DB::select($query);

        return $result;
    }

    public function buscarDocumento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idPessoa = $dadosRecebidos['idPessoa'];

        $query = "SELECT pessoa_documento.*
                       , (SELECT NOME
                            FROM pessoa 
                           WHERE ID = pessoa_documento.ID_PESSOA) AS NOME_PESSOA
                    FROM pessoa_documento
                WHERE 1 = 1
                    AND STATUS = 'A'
                    AND ID_PESSOA = $idPessoa";
        $result = DB::select($query);

        return $result;
    }

    public function alterarDocumentoPendente(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idPessoa = $dadosRecebidos['idPessoa'];

        $query = "SELECT DOC_PENDENTE
                    FROM pessoa
                   WHERE ID = $idPessoa";
        $result = DB::select($query);

        if($result[0]->DOC_PENDENTE === 'S'){
            $queryUpdate = "UPDATE pessoa
                               SET DOC_PENDENTE = 'N'
                             WHERE ID = $idPessoa";
            $resultUpdate = DB::select($queryUpdate);
        } else {
            $queryUpdate = "UPDATE pessoa
                               SET DOC_PENDENTE = 'S'
                             WHERE ID = $idPessoa";
            $resultUpdate = DB::select($queryUpdate);
        }

        return $result[0]->DOC_PENDENTE;
    }
}
