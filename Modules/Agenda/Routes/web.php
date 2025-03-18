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
    Route::prefix('agenda')->group(function() {

        Route::get('/', [Modules\Agenda\Http\Controllers\AgendaController::class, 'index'])->name('agenda');/* Matheus 18/07/2023 08:49:28 - UTILIZADA PARA ABRIR A AGENDA.BLADE.PHP */
        Route::post('/local/inserir', [Modules\Agenda\Http\Controllers\AgendaController::class, 'inserirLocalAgenda'])->name('agenda.inserir.local');/* Matheus 18/07/2023 08:49:52 - INSERE OS LOCAIS DISPONIVEIS PARA EVENTO */
        Route::post('/local/dados', [Modules\Agenda\Http\Controllers\AgendaController::class, 'dadosLocal'])->name('agenda.dados.local');/* Matheus 18/07/2023 08:51:04 - BUSCA OS LOCAIS CADASTRADOS */
        Route::post('/local/buscar', [Modules\Agenda\Http\Controllers\AgendaController::class, 'buscarLocais'])->name('agenda.buscar.local');/* Matheus 18/07/2023 08:51:04 - BUSCA OS LOCAIS CADASTRADOS */
        Route::post('/local/inativar', [Modules\Agenda\Http\Controllers\AgendaController::class, 'inativarLocal'])->name('agenda.inativar.local');/* Matheus 18/07/2023 08:51:26 - SETA O LOCAL COMO INATIVO */
        Route::post('/evento/inserir', [Modules\Agenda\Http\Controllers\AgendaController::class, 'inserirEventoAgenda'])->name('agenda.inserir.evento');/* Matheus 18/07/2023 08:51:19 - INSERE O EVENTO EM SI */
        Route::post('/evento/dados', [Modules\Agenda\Http\Controllers\AgendaController::class, 'dadosEvento'])->name('agenda.dados.evento');/* Matheus 18/07/2023 08:51:26 - DADOS DOS EVENTOS DO USUARIO */
        Route::post('/evento/editar', [Modules\Agenda\Http\Controllers\AgendaController::class, 'editarEventoAgenda'])->name('agenda.editar.evento');/* Matheus 18/07/2023 08:51:26 - EDITA O EVENTO */
        Route::post('/evento/excluir', [Modules\Agenda\Http\Controllers\AgendaController::class, 'excluirEventoAgenda'])->name('agenda.excluir.evento');/* Matheus 18/07/2023 08:51:26 - EXCLUI O EVENTO */
        Route::post('/buscar/pendentes', [Modules\Agenda\Http\Controllers\AgendaController::class, 'buscarEventosPendentes'])->name('agenda.buscar.pendentes');/* Matheus 08/08/2024 15:08:00 - BUSCA EVENTOS QUE AINDA NAO FORAM VISUALIZADOS */
        Route::post('/pendentes/visualizar', [Modules\Agenda\Http\Controllers\AgendaController::class, 'visualizarEventosPendentes'])->name('agenda.visualizar.eventos');/* Matheus 08/08/2024 15:08:00 - MARCA EVENTOS COMO VISUALIZADOS */
        Route::post('/evento/finalizar', [Modules\Agenda\Http\Controllers\AgendaController::class, 'finalizarEvento'])->name('agenda.finalizar.evento');/* Matheus 08/08/2024 16:12:00 - MARCA EVENTOS COMO VISUALIZADOS */
    });
});
