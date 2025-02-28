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
Route::get('/cardapio/pedido', 'CardapioController@pedido');
Route::post('/cardapio/buscar/pedido', 'CardapioController@buscarCardapioPedido')->name('cardapio.buscar.pedido');
Route::post('/cardapio/pedido/inserir', 'CardapioController@inserirPedido')->name('cardapio.pedido.inserir');

Route::middleware(['auth'])->group(function () {
    Route::prefix('cardapio')->group(function() {
        Route::get('/', 'CardapioController@index');

        Route::post('/buscar', 'CardapioController@buscarCardapio')->name('cardapio.buscar');
        Route::post('/inserir', 'CardapioController@inserirCardapio')->name('cardapio.inserir');
        Route::post('/alterar', 'CardapioController@alterarCardapio')->name('cardapio.alterar');
        Route::post('/inativar', 'CardapioController@inativarCardapio')->name('cardapio.inativar');
        Route::post('/ativar', 'CardapioController@ativarItemCardapio')->name('cardapio.ativar.item');
    });
});
