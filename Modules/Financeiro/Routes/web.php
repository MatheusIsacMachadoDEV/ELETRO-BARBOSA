<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::prefix('financeiro')->group(function() {
        Route::get('/contasreceber', 'FinanceiroController@index');
        Route::get('/despesaobra', 'FinanceiroController@despesaObra');
        Route::get('/despesaempresa', 'FinanceiroController@despesaEmpresa');
        Route::get('/diaria', 'FinanceiroController@diaria');

        Route::post('/cadastrar', 'FinanceiroController@inserirCRB')->name('contasreceber.inserir');
        Route::post('/alterar', 'FinanceiroController@alterarCRB')->name('contasreceber.alterar');
        Route::post('/inativar', 'FinanceiroController@inativarCRB')->name('contasreceber.inativar');
        Route::post('/buscar', 'FinanceiroController@buscarCRB')->name('contasreceber.buscar');
        Route::post('/buscar/documento', 'FinanceiroController@buscarDocumentoCRB')->name('contasreceber.buscar.documento');
        Route::post('/inserir/documento', 'FinanceiroController@inserirDocumentoCRB')->name('contasreceber.inserir.documento');
        Route::post('/inativar/documento', 'FinanceiroController@inativarDocumentoCRB')->name('contasreceber.inativar.documento');
        Route::post('/inserir/pagamento', 'FinanceiroController@inserirPagamentoCRB')->name('contasreceber.inserir.pagamento');

        Route::post('/cc/buscar', 'FinanceiroController@buscarContaBancaria')->name('financeiro.cc.buscar');
        Route::post('/cc/inserir', 'FinanceiroController@inserirContaBancaria')->name('financeiro.cc.inserir');
        Route::post('/cc/inativar', 'FinanceiroController@inativarContaBancaria')->name('financeiro.cc.inativar');


        // Rotas para o CRUD de Diárias
        Route::post('/diaria/buscar', 'FinanceiroController@buscarDiaria')->name('diaria.buscar');
        Route::post('/diaria/inserir', 'FinanceiroController@inserirDiaria')->name('diaria.inserir');
        Route::post('/diaria/alterar', 'FinanceiroController@alterarDiaria')->name('diaria.alterar');
        Route::post('/diaria/inativar', 'FinanceiroController@inativarDiaria')->name('diaria.inativar');
    });
});
