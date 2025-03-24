<?php

namespace Modules\Projeto\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;

class ProjetoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('projeto::index');
    }

    public function buscarProjeto(Request $request)
    {
        $dadosRecebidos = $request->except('_token');

        if (isset($dadosRecebidos['filtro']) && strlen($dadosRecebidos['filtro']) > 0) {
            $filtro = "AND (TITULO LIKE '%{$dadosRecebidos['filtro']}%' OR DESCRICAO LIKE '%{$dadosRecebidos['filtro']}%')";
        } else {
            $filtro = "";
        }

        $query = "
            SELECT p.*, u.name AS NOME_USUARIO 
            FROM projeto p
            LEFT JOIN users u ON p.ID_USUARIO_INSERCAO = u.id
            WHERE p.STATUS = 'A' $filtro
            ORDER BY p.DATA_INSERCAO DESC
        ";
        $result['dados'] = DB::select($query);

        return response()->json($result);
    }

    // Inserir projeto
    public function inserirProjeto(Request $request)
    {
        $dadosRecebidos = $request->except('_token');

        $titulo = $dadosRecebidos['TITULO'];
        $descricao = $dadosRecebidos['DESCRICAO'];
        $dataInicio = $dadosRecebidos['DATA_INICIO'];
        $dataFim = $dadosRecebidos['DATA_FIM'];
        $valor = $dadosRecebidos['VALOR'];
        $idUsuario = auth()->user()->id; // Usuário logado

        $query = "
            INSERT INTO projeto (TITULO, DESCRICAO, DATA_INICIO, DATA_FIM, VALOR, ID_USUARIO_INSERCAO) 
            VALUES ('$titulo', '$descricao', '$dataInicio', '$dataFim', $valor, $idUsuario)
        ";
        DB::insert($query);

        return response()->json(['success' => 'Projeto inserido com sucesso!']);
    }

    // Alterar projeto
    public function alterarProjeto(Request $request)
    {
        $dadosRecebidos = $request->except('_token');

        $idProjeto = $dadosRecebidos['ID'];
        $titulo = $dadosRecebidos['TITULO'];
        $descricao = $dadosRecebidos['DESCRICAO'];
        $dataInicio = $dadosRecebidos['DATA_INICIO'];
        $dataFim = $dadosRecebidos['DATA_FIM'];
        $valor = $dadosRecebidos['VALOR'];

        $query = "
            UPDATE projeto 
            SET TITULO = '$titulo', 
                DESCRICAO = '$descricao', 
                DATA_INICIO = '$dataInicio', 
                DATA_FIM = '$dataFim', 
                VALOR = $valor 
            WHERE ID = $idProjeto
        ";
        DB::update($query);

        return response()->json(['success' => 'Projeto alterado com sucesso!']);
    }

    // Inativar projeto
    public function inativarProjeto(Request $request)
    {
        $dadosRecebidos = $request->except('_token');
        $idProjeto = $dadosRecebidos['ID'];

        $query = "UPDATE projeto SET STATUS = 'I' WHERE ID = $idProjeto";
        DB::update($query);

        return response()->json(['success' => 'Projeto inativado com sucesso!']);
    }
}
