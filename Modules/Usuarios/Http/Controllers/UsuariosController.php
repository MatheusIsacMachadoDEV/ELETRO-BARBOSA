<?php

namespace Modules\Usuarios\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use GuzzleHttp\Psr7\Query;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('usuarios::index');
    }

    public function buscarUsuarios(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $return = [];
        
        // MONTA O FILTRO DE BUSCA DE TEXTO
        if(isset($dadosRecebidos['FILTRO_BUSCA'])){
            $filtroParametro = str_replace(' ', '%', $dadosRecebidos['FILTRO_BUSCA']);
            $filtroBusca = "AND NAME LIKE '%$filtroParametro%'";
        } else {
            $filtroBusca = 'AND 1 = 1';
        }

        if(isset($dadosRecebidos['ID'])){
            $filtroID = "AND ID = {$dadosRecebidos['ID']}";
        } else {
            $filtroID = 'AND 1 = 1';
        }
        
        if(isset($dadosRecebidos['dadosPorPagina']) && isset($dadosRecebidos['offset']) && $dadosRecebidos['dadosPorPagina'] != 'todos'){
            $filtroLimit = "LIMIT ".$dadosRecebidos['dadosPorPagina']."
                           OFFSET ".$dadosRecebidos['offset'];
        }
        
        $queryCount= " SELECT COUNT(*) as COUNT
                           FROM users
                          WHERE 1 = 1
                          $filtroBusca";
        $resultCount = DB::select($queryCount);
        $return['contagem'] = $resultCount[0]->COUNT;
        
        $query = " SELECT users.ID
                        , users.NAME
                        , users.EMAIL
                       FROM users
                      WHERE EMAIL <> 'adm@adm'
                        AND NAME <> 'axs'
                      $filtroBusca
                      $filtroID
                      $filtroLimit";
        $result = DB::select($query);
        $return['dados'] = $result;
        
        return $return;
    }

    public function inserirUsuarios(Request $request){
        $dadosRecebidos = $request->except('_token');
        $nome = $dadosRecebidos['nome'];
        $email = $dadosRecebidos['email'];
        $senha = $dadosRecebidos['senha'];

        $senhaHash = Hash::make($senha);

        $queryValidacao = "SELECT COUNT(*) AS TOTAL
                             FROM users
                            WHERE EMAIL = '$email'";
        $resultValidacao = DB::select($queryValidacao)[0]->TOTAL;

        if($resultValidacao > 0){
            $return['SITUACAO'] = 'ERRO';
            $return['MENSAGEM'] = 'Email já esta em uso!';
            return $return;
        }

        $query = "INSERT INTO users
                            (name
                            , email
                            , password
                            , created_at
                            , updated_at
                            ) VALUES (
                            '$nome'
                            , '$email'
                            , '$senhaHash'
                            , NOW()
                            , NOW()
                            )";
        $result = DB::select($query);

        $return['SITUACAO'] = 'SUCESSO';
        return $return;
    }

    public function alterarUsuarios(Request $request){
        $dadosRecebidos = $request->except('_token');
        $ID = $dadosRecebidos['ID'];
        $nome = $dadosRecebidos['nome'];
        $email = $dadosRecebidos['email'];
        $senha = isset($dadosRecebidos['senha']) ? $dadosRecebidos['senha'] : '';
        $return = [];

        if($senha != ''){
            $camposAdicionais = ", password = '$senha'";
        }

        $queryValidacao = "SELECT COUNT(*) AS TOTAL
                             FROM users
                            WHERE EMAIL = '$email'";
        $resultValidacao = DB::select($queryValidacao)[0]->TOTAL;

        if($resultValidacao > 0){
            $return['SITUACAO'] = 'ERRO';
            $return['MENSAGEM'] = 'Email já esta em uso!';
            return $return;
        }

        $query = "UPDATE users
                     SET name = '$nome'
                       , email = '$email'
                       , updated_at = NOW()
                       $camposAdicionais
                   WHERE id = $ID";
        $result = DB::select($query);

        $return['SITUACAO'] = 'SUCESSO';
        return $return;
    }

    public function inativarUsuarios(Request $request){
        $dadosRecebidos = $request->except('_token');
        $ID = $dadosRecebidos['ID'];

        $query = "DELETE FROM users
                   WHERE id = $ID";
        $result = DB::select($query);
    }
}
