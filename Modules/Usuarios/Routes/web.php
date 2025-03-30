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
    Route::prefix('usuarios')->group(function() {
        Route::get('/', 'UsuariosController@index');
        Route::post('/buscar', 'UsuariosController@buscarUsuarios')->name('usuarios.buscar');
        Route::post('/alterar', 'UsuariosController@alterarUsuarios')->name('usuarios.alterar');
        Route::post('/inserir', 'UsuariosController@inserirUsuarios')->name('usuarios.inserir');
        Route::post('/inativar', 'UsuariosController@inativarUsuarios')->name('usuarios.inativar');

        // Rotas para Menus do Usuário
        Route::post('/menu/buscar', 'UsuariosController@buscar')->name('menu.usuario.buscar');
        Route::post('/menu/inserir', 'UsuariosController@inserir')->name('menu.usuario.inserir');
        Route::post('/menu/remover', 'UsuariosController@remover')->name('menu.usuario.remover');
        Route::post('/menu/opcoes', 'UsuariosController@buscarMenus')->name('menu.opcoes');
    });
});
