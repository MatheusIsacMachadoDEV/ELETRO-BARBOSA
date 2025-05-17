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
        Route::get('/documento', 'GestaoEmpresaController@documento');

        Route::post('/uniforme/buscar', 'GestaoEmpresaController@buscarUniforme')->name('uniforme.buscar');
        Route::post('/uniforme/inserir', 'GestaoEmpresaController@inserirUniforme')->name('uniforme.inserir');
        Route::post('/uniforme/alterar', 'GestaoEmpresaController@alterarUniforme')->name('uniforme.alterar');
        Route::post('/uniforme/inativar', 'GestaoEmpresaController@inativarUniforme')->name('uniforme.inativar');

        // Rotas para Uniformes do Usuário
        Route::post('/uniforme/usuario/buscar', 'GestaoEmpresaController@buscarUniformeUsuario')->name('uniforme.usuario.buscar');
        Route::post('/uniforme/usuario/inserir', 'GestaoEmpresaController@inserirUniformeUsuario')->name('uniforme.usuario.inserir');
        Route::post('/uniforme/usuario/remover', 'GestaoEmpresaController@inativarUniformeUsuario')->name('uniforme.usuario.remover');

        Route::post('/documento/buscar/caminho', 'GestaoEmpresaController@buscarCaminho')->name('empresa.documento.buscar.caminho');
        Route::post('/documento/buscar/documento', 'GestaoEmpresaController@buscarDocumento')->name('empresa.documento.buscar');
        Route::post('/documento/inserir', 'GestaoEmpresaController@inserirDocumento')->name('empresa.documento.inserir');
        Route::post('/pasta/inserir', 'GestaoEmpresaController@inserirPasta')->name('empresa.pasta.inserir');
        Route::post('/documento/inativar', 'GestaoEmpresaController@inativarDocumento')->name('empresa.documento.inativar');
        Route::post('/pasta/inativar', 'GestaoEmpresaController@inativarPasta')->name('empresa.pasta.inativar');
    });
});
