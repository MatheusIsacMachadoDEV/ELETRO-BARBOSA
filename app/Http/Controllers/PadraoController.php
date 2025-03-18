<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use DB;
use Config;

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

    public function atualizarModulos(Request $request)
    {
        $modulos = $this->obterModulosDoProjeto();

        foreach ($modulos as $modulo) {
            // Verifica se o módulo já existe
            $queryVerifica = "SELECT COUNT(*) AS total 
                                FROM modulos 
                               WHERE nome = '$modulo'";
            $resultado = DB::select($queryVerifica);

            if ($resultado[0]->total == 0) {
                // Insere o módulo se não existir
                $queryInsercao = "INSERT INTO modulos (NOME, DATA_INSERCAO) 
                                       VALUES ('$modulo', NOW())";
                DB::select($queryInsercao);
            }
        }
    }

    private function obterModulosDoProjeto()
    {
        $modulosPath = base_path('Modules'); // Diretório padrão do Laravel Modules
        $modulos = [];

        if (File::exists($modulosPath)) {
            $pastas = File::directories($modulosPath); // Obtém as pastas dentro de "Modules"

            foreach ($pastas as $pasta) {
                $modulos[] = basename($pasta); // Pega apenas o nome do módulo
            }
        }

        return $modulos;
    }

    public function atualizarMenus()
    {
        $menus = Config::get('adminlte.menu'); // Obtém os menus do config/adminlte.php
    
        // Debug: Exibe todos os menus obtidos do arquivo de configuração
        \Log::debug('Menus obtidos do adminlte.php:', $menus);
    
        // Itera sobre os menus e insere no banco de dados
        foreach ($menus as $index => $menu) {
            \Log::debug("Processando menu índice $index:", $menu);
            $this->processarMenu($menu);
        }
    
        return response()->json(['message' => 'Menus registrados com sucesso.']);
    }
    
    private function processarMenu($menu, $paiId = null)
    {
        // Debug: Exibe o menu atual sendo processado
        \Log::debug('Menu sendo processado:', $menu);
    
        // Ignora menus que não devem ser inseridos no banco de dados
        if (isset($menu['type']) && in_array($menu['type'], ['navbar', 'fullscreen-widget', 'sidebar-menu'])) {
            \Log::debug('Menu ignorado (tipo especial):', $menu);
            return;
        }
    
        // Se for um header, apenas ignora (não insere no banco de dados)
        if (isset($menu['header'])) {
            \Log::debug('Menu ignorado (header):', $menu);
            return;
        }
    
        // Se for um menu válido (com text, url e icon), insere no banco de dados
        if (isset($menu['text']) && isset($menu['url']) && isset($menu['icon'])) {
            \Log::debug('Menu válido encontrado. Inserindo no banco de dados:', $menu);
            $this->inserirMenu($menu, $paiId);
        } else {
            \Log::debug('Menu inválido (faltando text, url ou icon):', $menu);
        }
    
        // Se houver submenus, processa recursivamente
        if (isset($menu['submenu']) && is_array($menu['submenu'])) {
            \Log::debug('Submenus encontrados. Processando recursivamente:', $menu['submenu']);
            foreach ($menu['submenu'] as $submenu) {
                $this->processarMenu($submenu, $paiId);
            }
        }
    }
    
    private function inserirMenu($menu, $paiId = null)
    {
        // Debug: Exibe o menu que está sendo inserido
        \Log::debug('Inserindo menu:', $menu);
    
        // Verifica se o menu já existe
        $queryVerifica = "SELECT ID FROM menus WHERE NOME = ? AND PAI_ID <=> ?";
        $resultado = DB::select($queryVerifica, [$menu['text'], $paiId]);
    
        if (empty($resultado)) {
            // Insere o menu
            $queryInsercao = "INSERT INTO menus (NOME, URL, ICONE, PAI_ID, DATA_INSERCAO) VALUES (?, ?, ?, ?, NOW())";
            DB::insert($queryInsercao, [$menu['text'], $menu['url'], $menu['icon'], $paiId]);
    
            // Obtém o ID do menu recém-inserido
            $menuId = DB::getPdo()->lastInsertId();
            \Log::debug("Menu inserido com sucesso. ID: $menuId");
        } else {
            $menuId = $resultado[0]->ID; // Já existe, então usa o ID existente
            \Log::debug("Menu já existe. ID: $menuId");
        }
    
        // Se houver submenus, insere recursivamente
        if (isset($menu['submenu']) && is_array($menu['submenu'])) {
            \Log::debug('Submenus encontrados. Inserindo recursivamente:', $menu['submenu']);
            foreach ($menu['submenu'] as $submenu) {
                $this->inserirMenu($submenu, $menuId);
            }
        }
    }
}
