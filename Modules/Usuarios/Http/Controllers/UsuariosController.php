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
    public function index(){
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

        if(isset($dadosRecebidos['FILTRO_ADICIONAL']) && $dadosRecebidos['FILTRO_ADICIONAL'] == 'SEM_FUNCIONARIO'){
            $filtroAdicional = "AND (SELECT COUNT(*)
                                FROM pessoa
                               WHERE ID_USUARIO = users.ID) = 0";
        } else {
            $filtroAdicional = 'AND 1 = 1';
        }
        
        if(isset($dadosRecebidos['dadosPorPagina']) && isset($dadosRecebidos['offset']) && $dadosRecebidos['dadosPorPagina'] != 'todos'){
            $filtroLimit = "LIMIT ".$dadosRecebidos['dadosPorPagina']."
                           OFFSET ".$dadosRecebidos['offset'];
        }
        
        $queryCount= " SELECT COUNT(*) as COUNT
                           FROM users
                          WHERE 1 = 1
                          $filtroBusca
                          $filtroAdicional";
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
                      $filtroAdicional
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

        $queryValidacao = "SELECT COUNT(*) AS TOTAL
                             FROM users
                            WHERE EMAIL = '$email'
                              AND id <> $ID";
        $resultValidacao = DB::select($queryValidacao)[0]->TOTAL;

        if($resultValidacao > 0){
            $return['SITUACAO'] = 'ERRO';
            $return['MENSAGEM'] = 'Email já esta em uso!';
            return $return;
        }

        if($senha != ''){
            $senhaHash = Hash::make($senha);
            $camposAdicionais = ", password = '$senhaHash'";
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

    public function buscar(Request $request){
        $idUsuario = $request->input('ID_USUARIO');
        
        $menus = DB::select("
            SELECT mu.*, m.NOME, m.URL 
            FROM menus_usuario mu
            JOIN menus m ON mu.ID_MENU = m.ID
            WHERE mu.ID_USUARIO = ? AND mu.STATUS = 'A'
            ORDER BY m.NOME
        ", [$idUsuario]);

        return response()->json(['dados' => $menus]);
    }

    public function inserir(Request $request){
        $dados = $request->validate([
            'ID_USUARIO' => 'required|integer',
            'ID_MENU' => 'required|integer'
        ]);

        // Verifica se o menu já está vinculado
        $existe = DB::select("
            SELECT COUNT(*) AS total 
            FROM menus_usuario 
            WHERE ID_USUARIO = ? AND ID_MENU = ? AND STATUS = 'A'
        ", [$dados['ID_USUARIO'], $dados['ID_MENU']]);

        if ($existe[0]->total > 0) {
            return response()->json(['error' => 'Este menu já está vinculado ao usuário!'], 400);
        }

        DB::insert("
            INSERT INTO menus_usuario (ID_USUARIO, ID_MENU) 
            VALUES (?, ?)
        ", [$dados['ID_USUARIO'], $dados['ID_MENU']]);

        return response()->json(['success' => 'Menu vinculado com sucesso!']);
    }

    public function remover(Request $request){
        $id = $request->input('ID');
        
        DB::update("
            UPDATE menus_usuario 
            SET STATUS = 'I' 
            WHERE ID = ?
        ", [$id]);

        return response()->json(['success' => 'Menu desvinculado com sucesso!']);
    }

    public function buscarMenus(Request $request){
        $dadosRecebidos = $request->except('_token');
        
        if(isset($dadosRecebidos['ID_USUARIO'])){
            $filtroUsuario = "AND (SELECT COUNT(*)
                                    FROM menus_usuario
                                   WHERE STATUS = 'A'
                                     AND ID_MENU = menus.ID
                                     AND ID_USUARIO = '{$dadosRecebidos['ID_USUARIO']}') = 0";
        } else {
            $filtroUsuario = "AND 1 = 1";
        }

        $queryMenus = "SELECT ID
                            , NOME
                            , URL 
                         FROM menus 
                        WHERE 1 = 1
                        $filtroUsuario
                        ORDER BY NOME";
        $menus = DB::select($queryMenus);                 

        return response()->json($menus);
    }
}
