<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use DB;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('ADMINISTRADOR', function () {
            if(auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1){                    
                return true;
            } else {
                return false;
            }
        });

        Gate::define('ESTOQUE_MATERIAIS', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND STATUS = 'A'
                         AND NOME = 'Materiais'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('LISTA_MATERIAIS', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND STATUS = 'A'
                         AND NOME = 'Lista de Materiais'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('ESTOQUE_RETIRADA', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND STATUS = 'A'
                         AND NOME = 'Retiradas / Devoluções'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('ESTOQUE_RELATORIO', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND STATUS = 'A'
                         AND NOME = 'Relatórios de Estoque'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('COMPRAS_RELATORIO', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Relatórios de Compras'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('COMPRAS_PEDIDO', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Pedido de Compra'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('FINANCEIRO_CPG', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Contas a Pagar'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('FINANCEIRO_CRB', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Contas a Receber'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('FINANCEIRO_DESPESAS_PROJETO', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Despesas de Projeto'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('FINANCEIRO_DESPESAS_EMPRESA', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Despesas da Empresa'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('FINANCEIRO_DIARIA', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Diárias'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('FINANCEIRO_FOLHA', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Folha de Pagamento'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('FINANCEIRO_FATURAMENTO', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Faturamento'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('FINANCEIRO_RELATORIOS', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Relatórios Financeiros'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('DEPARTAMENTO_PESSOAL_AGENDA', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Agenda'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('DEPARTAMENTO_PROJETOS', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Projetos'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('DEPARTAMENTO_PESSOAL_PESSOAS', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Pessoas'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('DEPARTAMENTO_PESSOAL_FUNCIONARIO', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Funcionários'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('DEPARTAMENTO_PESSOAL_PONTO', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Controle de Ponto'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('DEPARTAMENTO_PESSOAL_UNIFORMES', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Uniformes e EPI'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('DEPARTAMENTO_PESSOAL_USUARIOS', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Usuários'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });
        
        Gate::define('DEPARTAMENTO_PESSOAL_DOCUMENTO', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Documentos'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('ORCAMENTO', function () {
            $idUsuario = auth()->user()->id;

            $query = "SELECT COUNT(*) AS TOTAL
                        FROM menus_usuario, menus
                       WHERE menus_usuario.ID_MENU = menus.ID
                         AND ID_USUARIO = $idUsuario
                         AND menus_usuario.STATUS = 'A'
                         AND menus.NOME = 'Orçamento'";
            $validacao = DB::select($query)[0]->TOTAL;

            if((auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1) || $validacao > 0){
                return true;
            } else {
                return false;
            }
        });

        Gate::define('GESTAO_COMPRAS', function () {
            if(auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1){                    
                return true;
            } else {
                return false;
            }
        });

        Gate::define('VISUALIZAR_COMPRAS', function () {
            if(auth()->user()->id == 16 || auth()->user()->id == 15 || auth()->user()->email == 'adm@adm.com' || auth()->user()->ADMINISTRADOR == 1){                    
                return true;
            } else {
                return false;
            }
        });

        Gate::define('CARDAPIO', function () {
            if( auth()->user()->email == 'adm@adm.com'){                    
                return true;
            } else {
                return false;
            }
        });

    }
}
