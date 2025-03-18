<?php

namespace Modules\GestaoEmpresa\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GestaoEmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('gestaoempresa::index');
    }

    public function funcionarios()
    {
        return view('gestaoempresa::funcionarios');
    }

    
}
