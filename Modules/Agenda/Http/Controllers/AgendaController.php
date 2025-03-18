<?php

namespace Modules\Agenda\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('agenda::index');
    }
    /* Matheus 14/07/2023 11:43:53 - FUNÇÕES DE AGENDA */
    public function inserirLocalAgenda(Request $request){
        $dadosRecebidos = $request->except('_token');
        $codCli = $dadosRecebidos['codCli'];
        $nomeCli = $dadosRecebidos['nomeCli'];
        $clienteBD = $dadosRecebidos['cliente'];
        $descricao = $dadosRecebidos['descricao'];
        $tamanho = $dadosRecebidos['tamanho'];
        $qtde = $dadosRecebidos['qtde'];
        $preco = $dadosRecebidos['preco'];
        $observacao = $dadosRecebidos['observacao'];


        $query = "INSERT INTO agenda_local (ID_USUARIO, NOMEUSU, CLIENTE, DESCRICAO, TAMANHO, QTDE_MAX, PRECO, OBSERVACAO, ATIVO)
                                    values ($codCli 
                                    , '$nomeCli'
                                    , '$clienteBD'
                                    , '$descricao'
                                    , $tamanho
                                    , $qtde
                                    , $preco
                                    , '$observacao'
                                    , 1)";
        $result = DB::select($query);

        return $result;
    }

    public function inserirEventoAgenda(Request $request){
        $dadosRecebidos = $request->except('_token');
        $id_usuario = $dadosRecebidos['id_usuario'];
        $codEvento = $dadosRecebidos['codevento'];
        $titulo = $dadosRecebidos['titulo'];
        $responsavel = $dadosRecebidos['responsavel'];
        $idLocal = isset($dadosRecebidos['id_local']) ? $dadosRecebidos['id_local'] : 0;
        $preco = isset($dadosRecebidos['preco']) ? $dadosRecebidos['preco'] : 0;
        $observacao = $dadosRecebidos['observacao'];
        $cor = $dadosRecebidos['cor'];
        $data_ini = str_replace('T', ' ', $dadosRecebidos['data_ini']);
        $data_fim = str_replace('T', ' ', $dadosRecebidos['data_fim']);
        $cliente = $dadosRecebidos['cliente'];
        $diaTodo = ( $dadosRecebidos['diaTodo'] == 'true' ? 1 : 0); 
        $codcli = $dadosRecebidos['CODCLI'];

        $query = "INSERT INTO agenda_evento(ID_USUARIO, CODEVENTO, TITULO, RESPONSAVEL, ID_LOCAL, PRECO, OBSERVACAO, COR_EVENTO, DATA_INI, DATA_FIM, CLIENTE, DIA_TODO, ID_PESSOA, VISUALIZADO, DATA_VISUALIZACAO)
                                    VALUES( $id_usuario, '$codEvento', '$titulo', '$responsavel', '$idLocal', '$preco', '$observacao', '$cor', '$data_ini', '$data_fim', '$cliente', $diaTodo, '$codcli', 'S', NOW())";
        $result = DB::select($query);
    }

    public function editarEventoAgenda(Request $request){
        $dadosRecebidos = $request->except('_token');

        if(isset($dadosRecebidos['editDragNDrop'])){            
            $data_ini = str_replace('T', ' ', $dadosRecebidos['data_ini']);
            $data_fim = str_replace('T', ' ', $dadosRecebidos['data_fim']);
            $cliente = $dadosRecebidos['cliente'];
            $id_usuario = $dadosRecebidos['id_usuario'];
            $codEvento = $dadosRecebidos['codevento'];

            $query = "UPDATE agenda_evento 
                        SET  DATA_INI = '$data_ini'
                        , DATA_FIM = '$data_fim'
                    WHERE CLIENTE = '$cliente'
                        AND ID_USUARIO = $id_usuario
                        AND CODEVENTO = '$codEvento'";
            $result = DB::select($query);            
        } else {
            $id_usuario = $dadosRecebidos['id_usuario'];
            $codEvento = $dadosRecebidos['codevento'];
            $titulo = $dadosRecebidos['titulo'];
            $responsavel = $dadosRecebidos['responsavel'];
            $idLocal = isset($dadosRecebidos['id_local']) ? $dadosRecebidos['id_local'] : 0;
            $preco = isset($dadosRecebidos['preco']) ? $dadosRecebidos['preco'] : 0;
            $observacao = $dadosRecebidos['observacao'];
            $data_ini = $dadosRecebidos['data_ini'];
            $data_fim = $dadosRecebidos['data_fim'];
            $cliente = $dadosRecebidos['cliente'];
            $idEvento = $dadosRecebidos['ID'];
            $codcli = $dadosRecebidos['CODCLI'];

            $query = "UPDATE agenda_evento 
                        SET TITULO = '$titulo'
                        , RESPONSAVEL = '$responsavel'
                        , ID_LOCAL = $idLocal
                        , PRECO = $preco
                        , OBSERVACAO = '$observacao'
                        , DATA_INI = '$data_ini'
                        , DATA_FIM = '$data_fim'
                        , ID_PESSOA = '$codcli'
                    WHERE ID = $idEvento
                        AND CLIENTE = '$cliente'
                        AND ID_USUARIO = $id_usuario
                        AND CODEVENTO = '$codEvento'";
            $result = DB::select($query);
        }

        return $result;
    }

    public function excluirEventoAgenda(Request $request){
        $dadosRecebidos = $request->except('_token');
        $id_usuario = $dadosRecebidos['id_usuario'];
        $codEvento = $dadosRecebidos['codevento'];
        $cliente = $dadosRecebidos['cliente'];
        $idEvento = $dadosRecebidos['ID'];

        $query = "DELETE FROM agenda_evento 
                WHERE ID_USUARIO = $id_usuario
                    AND CLIENTE = '$cliente'
                    AND CODEVENTO = '$codEvento'
                    AND ID = $idEvento"; 
        $result = DB::select($query);

        return $result;

    }

    public function inativarLocal(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idLocal = $dadosRecebidos['ID'];

        $query = "UPDATE agenda_local 
                    SET ATIVO = 0
                WHERE ID = $idLocal";
        $result = DB::select($query);

    }

    public function dadosLocal(Request $request){
        $dadosRecebidos = $request->except('_token');
        $clienteBD = $dadosRecebidos['cliente'];
        $query = "SELECT * 
                    FROM agenda_local
                WHERE 1 = 1
                    AND ATIVO = 1
                    AND CLIENTE = '$clienteBD'";
        $result = DB::select($query);

        return $result;
    }

    public function dadosEvento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $id_usuario = isset($dadosRecebidos['id_usuario']) ? $dadosRecebidos['id_usuario'] : 0;
        $cliente = $dadosRecebidos['cliente'];

        if(isset($dadosRecebidos['id_evento'])){
            $idEvento = $dadosRecebidos['id_evento'];
            $filtroEvento = "and CODEVENTO = '$idEvento'";
        } else {
            $filtroEvento = "and 1 = 1";
        }

        if($id_usuario == 0){
            $id_usuario = auth()->user()->id;
        }

        $query = "SELECT *, (SELECT DESCRICAO
                            FROM agenda_local LOC
                            WHERE LOC.ID = ID_LOCAL) AS LOCAL
                    FROM agenda_evento 
                WHERE ID_USUARIO = $id_usuario
                    AND CLIENTE = '$cliente'
                    $filtroEvento";
        $result = DB::select($query);

        return $result;
    }

    public function buscarLocais(Request $request){
        $dadosRecebidos = $request->except('_token');
        $dadosRecebidos = $dadosRecebidos['param'];

        if(strlen($dadosRecebidos) > 2){
            $query = "SELECT ID AS ID_LOCAL
                        ,  DESCRICAO
                        , PRECO
                        FROM agenda_local
                    WHERE UPPER(DESCRICAO) LIKE UPPER('%$dadosRecebidos%')
                        AND ATIVO = 1";

            $resposta = DB::select($query);
        }

        if (empty($resposta)){
            $resposta = [
                [
                    'ID_LOCAL' => 0,
                    'DESCRICAO'=> 'Nenhum Cadastro Encontrado.',
                ]
            ];
        }

        return $resposta;
    }

    public function buscarEventosPendentes(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idUsuario = auth()->user()->id;
        $dataHoje = date('Y-m-d');
        $return = [];
        
        $queryCount = " SELECT count(*) as COUNT
                    FROM agenda_evento
                    WHERE 1 = 1
                    AND ID_USUARIO = $idUsuario
                    AND DATE(DATA_INI) = CURDATE()
                    AND NOTIFICADO = 'N'";
        $resultCount = DB::select($queryCount)[0]->COUNT;
        $return['dados'] = $resultCount;            
        
        return $return;
    }

    public function visualizarEventosPendentes(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idUsuario = auth()->user()->id;
        $return = [];
        
        $query = " UPDATE agenda_evento
                    SET VISUALIZADO = 'S'
                        , NOTIFICADO = 'S'
                        , DATA_VISUALIZACAO = CURRENT_TIMESTAMP()
                    WHERE ID_USUARIO = $idUsuario
                    AND NOTIFICADO = 'N'
                    AND DATE(DATA_INI) = CURDATE()";
        $result = DB::select($query);
        $return['situacao'] = 'sucesso';
        
        return $return;
    }

    public function finalizarEvento(Request $request){
        $dadosRecebidos = $request->except('_token');
        $idEvento = $dadosRecebidos['ID_EVENTO'];
        $return = [];
        
        $query = " UPDATE agenda_evento
                    SET FINALIZADO = 'S'
                        , DATA_FINALIZACAO = CURRENT_TIMESTAMP()
                        , COR_EVENTO = '#000000'
                    WHERE ID = $idEvento";
        $result = DB::select($query);
        $return['situacao'] = 'sucesso';
        
        return $return;
    }
    /* Matheus 14/07/2023 11:44:07 - FIM FUNÇÕES AGENDA */

}
