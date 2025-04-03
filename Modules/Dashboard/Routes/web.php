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

Route::prefix('dashboard')->group(function() {
    Route::get('/', 'DashboardController@index');

    Route::post('/projeto/valores', 'DashboardController@buscarProjetosValores')->name('dashboard.buscar.projeto.valores');
    Route::post('/projeto/grafico', 'DashboardController@graficoValores')->name('dashboard.buscar.grafico.valores');
});
