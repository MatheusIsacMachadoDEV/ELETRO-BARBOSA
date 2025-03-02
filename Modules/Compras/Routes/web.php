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

Route::prefix('compras')->group(function() {
    Route::get('/', 'ComprasController@index');

    Route::post('/buscar', 'ComprasController@buscarOrdemCompra')->name('compras.buscar.ordem');
    Route::post('/inserir', 'ComprasController@inserirOrdemCompra')->name('compras.inserir.ordem');
    Route::post('/alterar', 'ComprasController@alterarOrdemCompra')->name('compras.alterar.ordem');
    Route::post('/inativar', 'ComprasController@inativarOrdemCompra')->name('compras.inativar.ordem');
});
