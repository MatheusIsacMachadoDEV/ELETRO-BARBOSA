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
    Route::prefix('gestaoempresa')->group(function() {
        Route::get('/', 'GestaoEmpresaController@index');
        Route::get('/funcionarios', 'GestaoEmpresaController@funcionarios');
        Route::get('/uniformes', 'GestaoEmpresaController@uniformes');

        Route::post('/uniforme/buscar', 'GestaoEmpresaController@buscarUniforme')->name('uniforme.buscar');
        Route::post('/uniforme/inserir', 'GestaoEmpresaController@inserirUniforme')->name('uniforme.inserir');
        Route::post('/uniforme/alterar', 'GestaoEmpresaController@alterarUniforme')->name('uniforme.alterar');
        Route::post('/uniforme/inativar', 'GestaoEmpresaController@inativarUniforme')->name('uniforme.inativar');
    });
});
