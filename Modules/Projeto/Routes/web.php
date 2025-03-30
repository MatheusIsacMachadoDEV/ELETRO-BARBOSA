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
    Route::prefix('projeto')->group(function() {
        Route::get('/', 'ProjetoController@index');

        // Rotas para o CRUD de Projetos
        Route::post('/buscar', 'ProjetoController@buscarProjeto')->name('projeto.buscar');
        Route::post('/buscar/documento', 'ProjetoController@buscarDocumento')->name('projeto.buscar.documento');
        Route::post('/buscar/pessoa', 'ProjetoController@buscarPessoa')->name('projeto.buscar.pessoa');
        Route::post('/inserir', 'ProjetoController@inserirProjeto')->name('projeto.inserir');
        Route::post('/inserir/documento', 'ProjetoController@inserirDocumento')->name('projeto.inserir.documento');
        Route::post('/inserir/pessoa', 'ProjetoController@inserirPessoa')->name('projeto.inserir.pessoa');
        Route::post('/alterar', 'ProjetoController@alterarProjeto')->name('projeto.alterar');
        Route::post('/inativar', 'ProjetoController@inativarProjeto')->name('projeto.inativar');
        Route::post('/inativar/documento', 'ProjetoController@inativarDocumento')->name('projeto.inativar.documento');
        Route::post('/inativar/pessoa', 'ProjetoController@inativarPessoa')->name('projeto.inativar.pessoa');
        Route::post('/inativar/etapa', 'ProjetoController@inativarEtapa')->name('projeto.inativar.etapa');
        Route::post('/concluir', 'ProjetoController@concluirProjeto')->name('projeto.concluir');

        // Rotas para Etapas de Projeto
        Route::post('/etapa/buscar', 'ProjetoController@buscarEtapa')->name('projeto.etapa.buscar');
        Route::post('/etapa/inserir', 'ProjetoController@inserirEtapa')->name('projeto.etapa.inserir');
        Route::post('/etapa/concluir', 'ProjetoController@concluirEtapa')->name('projeto.etapa.concluir');
    });
});
