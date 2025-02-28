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

Route::prefix('caixa')->group(function() {
    Route::get('/', 'CaixaController@index')->name('caixa');
    Route::get('/impresso', 'CaixaController@impressoCaixa')->name('caixa.impresso');

    Route::post('/buscar/caixa', 'CaixaController@buscarCaixa')->name('caixa.buscar');
    Route::post('/abrir', 'CaixaController@abrirCaixa')->name('caixa.abrir');
    Route::post('/fechar', 'CaixaController@fecharCaixa')->name('caixa.fechar');
});
