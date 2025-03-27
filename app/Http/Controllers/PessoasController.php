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
        $documento = ($dadosRecebidos['documento'] == null ? '00000000000' : $dadosRecebidos['documento']);
        $telefone = $dadosRecebidos['telefone'];
        $ID_TIPO = $dadosRecebidos['ID_TIPO'];
        $ID_USUARIO = $dadosRecebidos['ID_USUARIO'];
        $email = ($dadosRecebidos['email'] == null ? '' : $dadosRecebidos['email']);
        $data_nascimento = ($dadosRecebidos['data_nascimento'] == null ? '2024-01-01' : $dadosRecebidos['data_nascimento']);

        $query = "INSERT INTO `pessoa` ( `NOME`
                                       , `DOCUMENTO`
                                       , `TELEFONE`
                                       , `EMAIL`
                                       , `DATA_NASCIMENTO`
                                       , `ID_TIPO`
                                       , `ID_USUARIO`)
                               VALUES ('$nome'
                                      , $documento
                                      , '$telefone'
                                      , '$email'
                                      , '$data_nascimento'
                                      , $ID_TIPO
                                      , $ID_USUARIO)";
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
        $data_nascimento = $dadosRecebidos['data_nascimento'];
        $ID_USUARIO = $dadosRecebidos['ID_USUARIO'];

        $query = "UPDATE `pessoa` 
                    SET `NOME` = '$nome'
                    , `DOCUMENTO` = $documento
                    , `TELEFONE` = '$telefone'
                    , `EMAIL` = '$email'
                    , `ID_TIPO` = $ID_TIPO
                    , `DATA_NASCIMENTO` = '$data_nascimento'
                    , `ID_USUARIO` = '$ID_USUARIO'
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
