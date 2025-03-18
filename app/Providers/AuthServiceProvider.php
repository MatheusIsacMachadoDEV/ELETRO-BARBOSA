<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('GESTAO_PATIO', function () {
            return false;
        });

        Gate::define('CAIXA_PDV', function () {
            return false;
        });

        Gate::define('GESTAO_PDV', function () {
            if(auth()->user()->email == 'caixa@kamalu.com'){
                return false;
            } else {
                return false;

            }
        });

        Gate::define('ADMIN_PDV', function () {
            if(auth()->user()->email == 'adm@adm.com' || auth()->user()->email == 'administrativo@kamalu.com'){                    
                return true;
            } else {
                return false;
            }
        });

        Gate::define('ADMINISTRADOR', function () {
            if(auth()->user()->email == 'adm@adm.com'){                    
                return true;
            } else {
                return false;
            }
        });

        Gate::define('ORDEM_SERVICO', function () {
            return false;
        });
        Gate::define('CARDAPIO', function () {
            return false;
        });
    }
}
