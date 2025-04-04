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
    Route::prefix('relatorios')->group(function() {
        Route::get('/', 'RelatoriosController@index');
        Route::get('/material', 'RelatoriosController@material');
        Route::get('/compras', 'RelatoriosController@compras');
        Route::get('/impresso', 'RelatoriosController@imprimirRelatorioListaItensCustomizavel');

        Route::post('/buscar', 'RelatoriosController@buscarDadosRelatorio')->name('relatorio.buscar.dados');
        Route::post('/buscar/modelo', 'RelatoriosController@buscarModeloRelatorio')->name('relatorio.buscar.modelo');
        Route::post('/inserir/cache', 'RelatoriosController@cacheRelatorioCustomizavel')->name('relatorio.cache');
    });
});
