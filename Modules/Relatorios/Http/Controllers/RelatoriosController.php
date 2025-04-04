<?php

namespace Modules\Relatorios\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use DateTime;
use Config;
use PDF;
class RelatoriosController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('relatorios::index');
    }

    public function material()
    {
        $codigo = 'MATERIAL';

        return view('relatorios::index', compact('codigo'));
    }
    public function compras()
    {
        $codigo = 'COMPRAS';

        return view('relatorios::index', compact('codigo'));
    }

    public function buscarModeloRelatorio(Request $request){
        $dadosRecebidos = $request->except('_token');
        $tipo = $dadosRecebidos['TIPO'];
        $return = [];
        
        $queryRelatorio= " SELECT *
                             FROM relatorio_modelo
                            WHERE TIPO = '$tipo'";
        $return['dados'] = DB::select($queryRelatorio);
        
        return $return;
    }

    public function buscarDadosRelatorio(Request $request){
        $dadosRecebidos = $request->except('_token');
        $relatorio = $dadosRecebidos['CODIGO'];
        $return = [];
        
        // MONTA O FILTRO DE BUSCA DE TEXTO
        if(isset($dadosRecebidos['FILTRO_BUSCA'])){
            $filtroParametro = str_replace(' ', '%', $dadosRecebidos['FILTRO_BUSCA']);
            $filtroBusca = "AND COLUNA LIKE '%filtroParametro%'";
        } else {
            $filtroBusca = 'AND 1 = 1';
        }
        
        $queryRelatorio= " SELECT NOME
                            , QUERY
                         FROM relatorio_modelo
                        WHERE CODIGO = '$relatorio'";
        $resultRelatorio = DB::select($queryRelatorio)[0];
        
        $query = $resultRelatorio->QUERY;

        $query = str_replace('%FILTRO_ADICIONAL%', $filtroBusca, $query);
        $result = DB::select($query);
        $return['dados'] = $result;
        session()->put('relatorioGSSoftware', $result);
        
        return $return;
    }

    public function imprimirRelatorioListaItensCustomizavel() {
        $dadosRelatorioGSS = session()->get('relatorioGSSoftware');

        $dadosRelatorioGSS = array_map(function($item) {
            return (object) $item;
        }, $dadosRelatorioGSS);

        $data = (new DateTime())->format('d/m/Y H:i');

        $queryEmpresa = "SELECT empresa_cliente.*
                           FROM empresa_cliente
                          WHERE ID = 1";
        $dadosEmpresa = DB::select($queryEmpresa)[0];

        $tituloRelatorio = "Relatório gerado em $data";

        // Carregar a view 'ORDEM-COMPRA' passando a variável $data
        $pdf = PDF::loadView('relatorios::impressos.relatorio', compact('dadosRelatorioGSS', 'dadosEmpresa', 'data', 'tituloRelatorio'));
        
        // Exibir o PDF inline no navegador
        return $pdf->stream();
    }

    public function cacheRelatorioCustomizavel(Request $request){
        $dadosRecebidos = $request->except('_token');
        $dadosCache = $dadosRecebidos['DADOS_CACHE'];

        session()->put('relatorioGSSoftware', $dadosCache);
    }
}
