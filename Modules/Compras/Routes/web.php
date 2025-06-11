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
    Route::prefix('compras')->group(function() {
        Route::get('/', 'ComprasController@index');
        Route::get('/imprimir/ordem/{id}', 'ComprasController@imprimirOrdemCompra')->name('compra.imprimir.ordem');
        Route::get('/imprimir/orcamento/{id}', 'ComprasController@imprimirOrcamento')->name('compra.imprimir.orcamento');

        Route::post('/buscar', 'ComprasController@buscarOrdemCompra')->name('compras.buscar.ordem');
        Route::post('/buscar/arquivo', 'ComprasController@buscarDocumentoOrdemCompra')->name('compras.buscar.documento.ordem');
        Route::post('/inserir', 'ComprasController@inserirOrdemCompra')->name('compras.inserir.ordem');
        Route::post('/inserir/arquivo', 'ComprasController@inserirDocumentoOrdemCompra')->name('compras.inserir.documento.ordem');
        Route::post('/alterar', 'ComprasController@alterarOrdemCompra')->name('compras.alterar.ordem');
        Route::post('/alterar/situacao', 'ComprasController@alterarSituacaoOrdemCompra')->name('compras.alterar.situacao');
        Route::post('/inativar', 'ComprasController@inativarOrdemCompra')->name('compras.inativar.ordem');
        Route::post('/inativar/arquivo', 'ComprasController@inativarDocumentoOrdemCompra')->name('compras.inativar.documento');

        Route::post('/orcamento/buscar', 'ComprasController@buscarOrcamento')->name('compras.buscar.orcamento');
        Route::post('/orcamento/buscar/arquivo', 'ComprasController@buscarDocumentoOrcamento')->name('compras.buscar.documento.orcamento');
        Route::post('/orcamento/inserir', 'ComprasController@inserirOrcamento')->name('compras.inserir.orcamento');
        Route::post('/orcamento/inserir/arquivo', 'ComprasController@inserirDocumentoOrcamento')->name('compras.inserir.documento.orcamento');
        Route::post('/orcamento/alterar', 'ComprasController@alterarOrcamento')->name('compras.alterar.orcamento');
        Route::post('/orcamento/alterar/situacao', 'ComprasController@alterarSituacaoOrcamento')->name('compras.alterar.situacao.orcamento');
        Route::post('/orcamento/inativar', 'ComprasController@inativarOrcamento')->name('compras.inativar.orcamento');
        Route::post('/orcamento/inativar/arquivo', 'ComprasController@inativarDocumentoOrcamento')->name('compras.inativar.documento.orcamento');
    });
});
