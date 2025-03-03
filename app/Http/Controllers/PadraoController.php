<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PadraoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

   
    public function buscarSituacoes(Request $request){
        $dadosRecebidos = $request->except('_token');
        $tipoSituacao = $dadosRecebidos['TIPO'];
        $return = [];
        
        $query = " SELECT situacoes.*
                     FROM situacoes
                    WHERE STATUS = 'A'
                      AND TIPO = '$tipoSituacao'";
        $result = DB::select($query);
        $return['dados'] = $result;
        
        return $return;
    }
}
