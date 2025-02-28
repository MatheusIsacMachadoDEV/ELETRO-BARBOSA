<?php

namespace Modules\Cardapio\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;

class CardapioController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('cardapio::index');
    }

    public function pedido()
    {
        return view('cardapio::pedido');
    }

    public function buscarCardapio(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $return = [];
        
        // MONTA O FILTRO
        if(isset($dadosRecebidos['FILTRO_BUSCA'])){
            $filtroBusca = "AND produto_pdv.DESCRICAO LIKE '%{$dadosRecebidos['FILTRO_BUSCA']}%'";
        } else {
            $filtroBusca = "AND 1 = 1";
        }

        if(isset($dadosRecebidos['ID_CARDAPIO'])){
            $filtroID = "AND produto_pdv.ID = '{$dadosRecebidos['ID_CARDAPIO']}'";
        } else {
            $filtroID = "AND 1 = 1";
        }
        
        if(isset($dadosRecebidos['dadosPorPagina']) && isset($dadosRecebidos['offset']) && $dadosRecebidos['dadosPorPagina'] != 'todos'){
            $filtroLimit = "LIMIT ".$dadosRecebidos['dadosPorPagina']."
                           OFFSET ".$dadosRecebidos['offset'];
        }

        if(isset($dadosRecebidos['BUSCAR_INATIVOS']) && $dadosRecebidos['BUSCAR_INATIVOS'] == 'true'){
            $filtroStatus = "AND STATUS = 'I'";
        } else {
            $filtroStatus = "AND STATUS = 'A'";
        }
        
        $queryCount= " SELECT COUNT(*) as COUNT
                         FROM produto_pdv
                        WHERE 1 = 1
                        $filtroStatus
                        $filtroBusca
                        $filtroID";
        $resultCount = DB::select($queryCount);
        $return['contagem'] = $resultCount[0]->COUNT;
        
        $query = " SELECT produto_pdv.*
                        , COALESCE((SELECT DESCRICAO
                                      FROM venda_pdv_item_classe
                                     WHERE venda_pdv_item_classe.STATUS = 'A'
                                       AND venda_pdv_item_classe.ID = produto_pdv.ID_ITEM_PDV_CLASSE), '-') as CLASSE
                     FROM produto_pdv
                    WHERE 1 = 1
                      AND TIPO = 3
                      $filtroBusca
                      $filtroStatus
                      $filtroID
                    ORDER BY NUMERO asc
                      $filtroLimit";
        $result = DB::select($query);
        $return['dados'] = $result;
        $return['query'] = $query;
        
        return $return;
    }

    public function buscarCardapioPedido(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $return = [];
        
        // MONTA O FILTRO
        if(isset($dadosRecebidos['FILTRO_BUSCA'])){
            $filtroBusca = "AND (produto_pdv.DESCRICAO LIKE '%{$dadosRecebidos['FILTRO_BUSCA']}%'
                                OR produto_pdv.DETALHES LIKE '%{$dadosRecebidos['FILTRO_BUSCA']}%')";
        } else {
            $filtroBusca = "AND 1 = 1";
        }
        
        $queryCount= " SELECT COUNT(*) as COUNT
                         FROM produto_pdv
                        WHERE STATUS = 'A'
                      $filtroBusca";
        $resultCount = DB::select($queryCount);
        $return['contagem'] = $resultCount[0]->COUNT;
        
        $query = " SELECT produto_pdv.*
                        , COALESCE((SELECT DESCRICAO
                                      FROM venda_pdv_item_classe
                                     WHERE venda_pdv_item_classe.STATUS = 'A'
                                       AND venda_pdv_item_classe.ID = produto_pdv.ID_ITEM_PDV_CLASSE), '-') as CLASSE
                     FROM produto_pdv
                    WHERE STATUS = 'A'
                      AND TIPO = 3
                      $filtroBusca
                    ORDER BY ID_ITEM_PDV_CLASSE asc";
        $result = DB::select($query);
        $return['dados'] = $result;
        $return['daqueryos'] = $query;
        
        return $return;
    }

    public function inserirCardapio(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $numero = $dadosRecebidos['NUMERO'];
        $item = $dadosRecebidos['ITEM'];
        $descricao = $dadosRecebidos['DESCRICAO'];
        $valor = $dadosRecebidos['VALOR'];
        $CLASSE = $dadosRecebidos['CLASSE'];
        $return = [];
        
        $query = "INSERT INTO produto_pdv(
                                      NUMERO
                                    , DESCRICAO
                                    , DETALHES
                                    , VALOR
                                    , TIPO
                                    , CONTROLA_ESTOQUE
                                    , ID_ITEM_PDV_CLASSE
                                    ) VALUES (
                                    $numero
                                    , '$item'
                                    , '$descricao'
                                    , $valor
                                    , 3
                                    , 'N'
                                    , $CLASSE
                                    )";
        $result = DB::select($query);

        $return['situacao'] = 'sucesso';
        
        return $return;
    }

    public function alterarCardapio(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $ID = $dadosRecebidos['ID'];
        $numero = $dadosRecebidos['NUMERO'];
        $item = $dadosRecebidos['ITEM'];
        $descricao = $dadosRecebidos['DESCRICAO'];
        $valor = $dadosRecebidos['VALOR'];
        $CLASSE = $dadosRecebidos['CLASSE'];
        $return = [];
        
        $query = "UPDATE produto_pdv
                     SET NUMERO = $numero
                       , DESCRICAO = '$item'
                       , DETALHES = '$descricao'
                       , VALOR = $valor
                       , TIPO = 3
                       , CONTROLA_ESTOQUE = 'N'
                       , ID_ITEM_PDV_CLASSE = $CLASSE
                   WHERE ID = $ID";
        $result = DB::select($query);

        $return['situacao'] = 'sucesso';
        
        return $return;
    }

    public function inativarCardapio(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $ID = $dadosRecebidos['ID'];
        $return = [];
        
        $query = "UPDATE produto_pdv
                     SET STATUS = 'I'
                   WHERE ID = $ID";
        $result = DB::select($query);

        $return['situacao'] = 'sucesso';
        
        return $return;
    }

    public function ativarItemCardapio(Request $request){
        $dadosRecebidos = $request->except('_token');
        $filtroLimit = "";
        $ID = $dadosRecebidos['ID'];
        $return = [];
        
        $query = "UPDATE produto_pdv
                     SET STATUS = 'A'
                   WHERE ID = $ID";
        $result = DB::select($query);

        $return['situacao'] = 'sucesso';
        
        return $return;
    }

    public function inserirPedido(Request $request){
        $dadosRecebidos = $request->except('_token')['DADOS_PEDIDO'];
        $NOME = isset($dadosRecebidos['NOME']) ? $dadosRecebidos['NOME'] : '';
        $TELEFONE = isset($dadosRecebidos['TELEFONE']) ? $dadosRecebidos['TELEFONE'] : '';
        $RUA = isset($dadosRecebidos['RUA']) ? $dadosRecebidos['RUA'] : '';
        $NUMERO = isset($dadosRecebidos['NUMERO']) ? $dadosRecebidos['NUMERO'] : '';
        $BAIRRO = isset($dadosRecebidos['BAIRRO']) ? $dadosRecebidos['BAIRRO'] : '';
        $RETIRADA = isset($dadosRecebidos['RETIRADA']) ? $dadosRecebidos['RETIRADA'] : '';
        $CEP = isset($dadosRecebidos['CEP']) ? $dadosRecebidos['CEP'] : '';
        $VALOR_TOTAL = isset($dadosRecebidos['VALOR_TOTAL']) ? $dadosRecebidos['VALOR_TOTAL'] : '0';
        $ITENS = $dadosRecebidos['ITENS'];
        $return = [];
        
        $queryNovoId = "SELECT COALESCE(MAX(ID), 1) AS AUTO_INCREMENT
                          FROM venda_pdv
                         WHERE 1 = 1";

        $IdVenda = (DB::select($queryNovoId)[0]->AUTO_INCREMENT + 1);

        // INSERE A VENDA
        $query = "INSERT INTO venda_pdv (ID, ID_USUARIO, DATA, VALOR_TOTAL, PAGAMENTO, CLIENTE_NOME, CLIENTE_TELEFONE, CLIENTE_CEP, CLIENTE_RUA, CLIENTE_NUMERO, CLIENTE_BAIRRO, PEDIDO_RETIRADA )
                                 VALUES ($IdVenda, 0, DATE_SUB(NOW(), INTERVAL 3 HOUR), $VALOR_TOTAL, 0, '$NOME', '$TELEFONE', '$CEP', '$RUA', '$NUMERO', '$BAIRRO', '$RETIRADA')";
        $result = DB::select($query);

        for ($i=0; $i < count($dadosRecebidos['ITENS']); $i++) { 
            $idProdutoVenda = $dadosRecebidos['ITENS'][$i]['ID_ITEM'];
            $valorUnitario = $dadosRecebidos['ITENS'][$i]['VALOR'];
            $valorTotalItem = $dadosRecebidos['ITENS'][$i]['VALOR_TOTAL']; 
            $qtde = $dadosRecebidos['ITENS'][$i]['QTDE'];
            $descricaoItem = $dadosRecebidos['ITENS'][$i]['ITEM']; 
            $observacao = $dadosRecebidos['ITENS'][$i]['OBS'];       

            $queryInsertItemVenda = "INSERT INTO venda_pdv_item(ID_VENDA, ID_PRODUTO, VALOR_UNITARIO, VALOR_TOTAL, QUANTIDADE,  DESCRICAO_PRODUTO, OBSERVACAO)
                                                        VALUES ($IdVenda, $idProdutoVenda, $valorUnitario, $valorTotalItem, $qtde, '$descricaoItem', '$observacao')";
            $resultItemVenda = DB::select($queryInsertItemVenda);

            $queryDadosItem = "SELECT COUNT(*) AS COUNT
                                 FROM produto_pdv
                                WHERE produto_pdv.ID = $idProdutoVenda
                                  AND produto_pdv.CONTROLA_ESTOQUE = 'S'";
            $itemControlaEstoque = DB::select($queryDadosItem)[0]->COUNT;

            if($itemControlaEstoque > 0){
                $queryUpdateEstoque = "UPDATE produto_pdv
                                          SET QTDE = QTDE - $qtde
                                        WHERE ID = $idProdutoVenda";
                $resultUpdateEstoque = DB::select($queryUpdateEstoque);
            }
        }
        $return['situacao'] = 'sucesso';
        
        return $return;
    }
}
