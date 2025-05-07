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
    Route::prefix('materiais')->group(function() {
        Route::get('/', 'MateriaisController@index');
        Route::get('/lista', 'MateriaisController@listaMateriais');
        Route::get('/lista/imprimir/{id}', 'MateriaisController@imprimirLista')->name('material.lista.imprimir');
        Route::get('/retirada-devolucao', 'MateriaisController@retiradaDevolucao');
        Route::get('/etiqueta/{id}', 'MateriaisController@gerarEtiqueta')->name('material.etiqueta');

        Route::post('/buscar', 'MateriaisController@buscarMaterial')->name('material.busca');
        Route::post('/buscar/marca', 'MateriaisController@buscarMarca')->name('material.busca.marca');
        Route::post('/buscar/movimento', 'MateriaisController@buscarMaterialMovimento')->name('material.buscar.movimento');
        Route::post('/buscar/kardex', 'MateriaisController@buscarMaterialKardex')->name('material.buscar.kardex');
        Route::post('/buscar/lista', 'MateriaisController@buscarMaterialLista')->name('material.buscar.lista');
        Route::post('/inserir', 'MateriaisController@inserirMaterial')->name('material.inserir');
        Route::post('/inserir/marca', 'MateriaisController@inserirMarca')->name('material.inserir.marca');
        Route::post('/inserir/retiradda-devolucao', 'MateriaisController@inserirRetiradaDevolucao')->name('material.inserir.devolucao');
        Route::post('/inserir/lista', 'MateriaisController@inserirMaterialLista')->name('material.inserir.lista');
        Route::post('/alterar', 'MateriaisController@alterarMaterial')->name('material.alterar');
        Route::post('/alterar/lista', 'MateriaisController@alterarMaterialLista')->name('material.alterar.lista');
        Route::post('/alterar/situacao', 'MateriaisController@alterarSituacaoLista')->name('material.situacao.lista');
        Route::post('/inativar', 'MateriaisController@inativarMaterial')->name('material.inativar');
        Route::post('/inativar/marca', 'MateriaisController@inativarMarca')->name('material.inativar.marca');
        Route::post('/inativar/movimentacao', 'MateriaisController@inativarMovimentacao')->name('material.inativar.movimentacao');
        Route::post('/inativar/lista', 'MateriaisController@inativarMaterialLista')->name('material.inativar.lista');
    });
});
