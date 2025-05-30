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
    Route::prefix('controleponto')->group(function() {
        Route::get('/', 'ControlePontoController@index');

        Route::post('/registrar', 'ControlePontoController@registrarPonto')->name('controle.ponto.registrar');
        Route::post('/editar', 'ControlePontoController@editarPonto')->name('controle.ponto.editar');
        Route::post('/inativar', 'ControlePontoController@inativarPonto')->name('controle.ponto.inativar');
        Route::post('/buscar', 'ControlePontoController@buscarPonto')->name('controle.ponto.buscar');
    });
});
