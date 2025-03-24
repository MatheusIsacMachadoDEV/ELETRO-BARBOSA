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

Route::prefix('projeto')->group(function() {
    Route::get('/', 'ProjetoController@index');

    // Rotas para o CRUD de Projetos
    Route::post('/buscar', 'ProjetoController@buscarProjeto')->name('projeto.buscar');
    Route::post('/inserir', 'ProjetoController@inserirProjeto')->name('projeto.inserir');
    Route::post('/alterar', 'ProjetoController@alterarProjeto')->name('projeto.alterar');
    Route::post('/inativar', 'ProjetoController@inativarProjeto')->name('projeto.inativar');
});
