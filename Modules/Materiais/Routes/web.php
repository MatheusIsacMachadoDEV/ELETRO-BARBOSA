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

Route::prefix('materiais')->group(function() {
    Route::get('/', 'MateriaisController@index');
    Route::get('/retirada-devolucao', 'MateriaisController@retiradaDevolucao');
    Route::get('/etiqueta/{id}', 'MateriaisController@gerarEtiqueta')->name('material.etiqueta');

    Route::post('/buscar', 'MateriaisController@buscarMaterial')->name('material.busca');
    Route::post('/buscar/marca', 'MateriaisController@buscarMarca')->name('material.busca.marca');
    Route::post('/inserir', 'MateriaisController@inserirMaterial')->name('material.inserir');
    Route::post('/inserir/marca', 'MateriaisController@inserirMarca')->name('material.inserir.marca');
    Route::post('/alterar', 'MateriaisController@alterarMaterial')->name('material.alterar');
    Route::post('/inativar', 'MateriaisController@inativarMaterial')->name('material.inativar');
    Route::post('/inativar/marca', 'MateriaisController@inativarMarca')->name('material.inativar.marca');
});
