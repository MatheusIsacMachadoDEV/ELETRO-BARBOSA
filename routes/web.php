<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Auth::routes();

Route::post('/atualizar/modulos', [App\Http\Controllers\PadraoController::class, 'atualizarModulos'])->name('modulos.atualizar');
Route::post('/atualizar/menus', [App\Http\Controllers\PadraoController::class, 'atualizarMenus'])->name('menus.atualizar');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::get('/', [Modules\Dashboard\Http\Controllers\DashboardController::class, 'index'])->name('faturamento');
    Route::get('/home', [Modules\Dashboard\Http\Controllers\DashboardController::class, 'index'])->name('home');

    Route::post('/situacao/buscar', [App\Http\Controllers\PadraoController::class, 'buscarSituacoes'])->name('buscar.situacoes');

    Route::get('/venda', [App\Http\Controllers\VendaController::class, 'index'])->name('venda');
    Route::post('/venda/inserir', [App\Http\Controllers\VendaController::class, 'inserirVenda'])->name('venda.inserir');
    Route::post('/venda/inserir/documento', [App\Http\Controllers\VendaController::class, 'inserirDocumentoVenda'])->name('venda.inserir.documento');
    Route::post('/venda/inativar', [App\Http\Controllers\VendaController::class, 'inativarVenda'])->name('venda.inativar');
    Route::post('/venda/inativar/documento', [App\Http\Controllers\VendaController::class, 'inativarDocumento'])->name('venda.documento.inativar');
    Route::post('/venda/buscar/veiculo', [App\Http\Controllers\VendaController::class, 'buscarVeiculo'])->name('venda.buscar.veiculo');
    Route::post('/venda/buscar/documento', [App\Http\Controllers\VendaController::class, 'buscarDocumento'])->name('venda.buscar.documentos');
    Route::post('/venda/buscar', [App\Http\Controllers\VendaController::class, 'buscarVenda'])->name('venda.buscar');

    Route::get('/veiculos', [App\Http\Controllers\VeiculoController::class, 'index'])->name('veiculo');
    Route::post('/veiculos/inserir', [App\Http\Controllers\VeiculoController::class, 'inserirVeiculo'])->name('veiculo.inserir');
    Route::post('/veiculos/alterar', [App\Http\Controllers\VeiculoController::class, 'alterarVeiculo'])->name('veiculo.alterar');
    Route::post('/veiculos/buscar', [App\Http\Controllers\VeiculoController::class, 'buscarVeiculo'])->name('veiculo.buscar');
    Route::post('/veiculos/inativar', [App\Http\Controllers\VeiculoController::class, 'inativarVeiculo'])->name('veiculo.inativar');

    Route::post('/manutencao/inserir', [App\Http\Controllers\VeiculoController::class, 'inserirManutencao'])->name('manutencao.inserir');
    Route::post('/manutencao/buscar', [App\Http\Controllers\VeiculoController::class, 'buscarManutencao'])->name('manutencao.buscar');
    Route::post('/manutencao/inativar', [App\Http\Controllers\VeiculoController::class, 'inativarManutencao'])->name('manutencao.inativar');
    Route::post('/manutencao/concluir', [App\Http\Controllers\VeiculoController::class, 'concluirManutencao'])->name('manutencao.concluir');
    Route::post('/veiculo/inserir/documento', [App\Http\Controllers\VeiculoController::class, 'inserirDocumento'])->name('veiculo.inserir.documento');
    Route::post('/veiculo/inativar/documento', [App\Http\Controllers\VeiculoController::class, 'inativarDocumento'])->name('veiculo.inativar.documento');
    Route::post('/veiculo/buscar/documento', [App\Http\Controllers\VeiculoController::class, 'buscarDocumento'])->name('veiculo.buscar.documento');

    Route::get('/pessoas', [App\Http\Controllers\PessoasController::class, 'index'])->name('pessoas');
    Route::post('/pessoas/inserir', [App\Http\Controllers\PessoasController::class, 'inserirPessoa'])->name('pessoa.inserir');
    Route::post('/pessoas/inserir/documento', [App\Http\Controllers\PessoasController::class, 'inserirDocumento'])->name('pessoa.inserir.documento');
    Route::post('/pessoas/alterar', [App\Http\Controllers\PessoasController::class, 'alterarPessoa'])->name('pessoa.alterar');
    Route::post('/pessoas/alterar/documentoPendente', [App\Http\Controllers\PessoasController::class, 'alterarDocumentoPendente'])->name('pessoa.alterar.docPendente');
    Route::post('/pessoas/inativar', [App\Http\Controllers\PessoasController::class, 'inativarPessoa'])->name('pessoa.inativar');
    Route::post('/pessoas/inativar/documento', [App\Http\Controllers\PessoasController::class, 'inativarDocumento'])->name('pessoa.inativar.documento');
    Route::post('/pessoas/buscar', [App\Http\Controllers\PessoasController::class, 'buscarPessoa'])->name('pessoa.buscar');
    Route::post('/pessoas/buscar/documento', [App\Http\Controllers\PessoasController::class, 'buscarDocumento'])->name('pessoa.buscar.documento');

    Route::get('/faturamento', [App\Http\Controllers\FaturamentoController::class, 'index'])->name('faturamento');
    Route::post('/faturamento/buscar', [App\Http\Controllers\FaturamentoController::class, 'buscarFaturamento'])->name('faturamento.buscar');

    Route::get('/patio', [App\Http\Controllers\PatioController::class, 'index'])->name('patio');
    Route::post('/patio/grafico/mensal', [App\Http\Controllers\PatioController::class, 'buscarGraficoMensal'])->name('patio.grafico.mensal');
    Route::post('/patio/grafico/anual', [App\Http\Controllers\PatioController::class, 'buscarGraficoAnual'])->name('patio.grafico.anual');        

    Route::get('/pdv', [App\Http\Controllers\PDVController::class, 'index'])->name('pdv');
    Route::get('/pdv/venda/impresso/{id}', [App\Http\Controllers\PDVController::class, 'imprimirVenda'])->name('pdv.venda.imprimir');
    Route::post('/pdv/cadastrar', [App\Http\Controllers\PDVController::class, 'inserirVenda'])->name('pdv.inserir');
    Route::post('/pdv/alterar', [App\Http\Controllers\PDVController::class, 'alterarVenda'])->name('pdv.alterar');
    Route::post('/pdv/inativar', [App\Http\Controllers\PDVController::class, 'inativarVenda'])->name('pdv.inativar');
    Route::post('/pdv/buscar', [App\Http\Controllers\PDVController::class, 'buscarVenda'])->name('pdv.buscar');
    Route::post('/pdv/buscar/item', [App\Http\Controllers\PDVController::class, 'buscarItemVenda'])->name('pdv.buscar.item');
    Route::post('/pdv/buscar/forma/pagamento', [App\Http\Controllers\PDVController::class, 'buscarFormaPagamento'])->name('pdv.buscar.forma.pagamento');
    Route::post('/pdv/pedido/confirmar', [App\Http\Controllers\PDVController::class, 'confirmarPedido'])->name('pdv.pedido.confirmar');
    Route::post('/pdv/pedido/cancelar', [App\Http\Controllers\PDVController::class, 'cancelarPedido'])->name('pdv.pedido.cancelar');
    Route::post('/pdv/pedido/finalizar', [App\Http\Controllers\PDVController::class, 'finalizarPedido'])->name('pdv.pedido.finalizar');
    Route::post('/pdv/pedido/entrega', [App\Http\Controllers\PDVController::class, 'iniciarEntregaPedido'])->name('pdv.pedido.iniciar.entrega');
    Route::post('/pdv/buscar/fiado', [App\Http\Controllers\PDVController::class, 'buscarVendaFiado'])->name('pdv.buscar.venda.fiado');
    Route::post('/pdv/buscar/fiado/pagamento', [App\Http\Controllers\PDVController::class, 'buscarPagamentoFiado'])->name('pdv.buscar.venda.fiado.pagamento');
    Route::post('/pdv/inserir/fiado/pagamento', [App\Http\Controllers\PDVController::class, 'inserirPagamentoFiado'])->name('pdv.inserir.venda.fiado.pagamento');
    Route::post('/pdv/inserir/venda/pagamento', [App\Http\Controllers\PDVController::class, 'inserirPagamentoVenda'])->name('pdv.venda.inserir.pagamento');
    Route::post('/pdv/inativar/fiado/pagamento', [App\Http\Controllers\PDVController::class, 'inativarPagamentoFiado'])->name('pdv.inativar.venda.fiado.pagamento');

    Route::get('/produto', [App\Http\Controllers\PDVController::class, 'produto'])->name('produto');
    Route::post('/produto/cadastrar', [App\Http\Controllers\PDVController::class, 'inserirProduto'])->name('produto.inserir');
    Route::post('/produto/alterar', [App\Http\Controllers\PDVController::class, 'alterarProduto'])->name('produto.alterar');
    Route::post('/produto/inativar', [App\Http\Controllers\PDVController::class, 'inativarProduto'])->name('produto.inativar');
    Route::post('/produto/buscar', [App\Http\Controllers\PDVController::class, 'buscarProdutos'])->name('produto.buscar');
    Route::post('/produto/buscar/classe', [App\Http\Controllers\PDVController::class, 'buscarClassse'])->name('produto.buscar.classe');

    Route::get('/despesa', [App\Http\Controllers\PDVController::class, 'despesa'])->name('despesa');
    Route::post('/despesa/cadastrar', [App\Http\Controllers\PDVController::class, 'inserirDespesa'])->name('despesa.inserir');
    Route::post('/despesa/alterar', [App\Http\Controllers\PDVController::class, 'alterarDespesa'])->name('despesa.alterar');
    Route::post('/despesa/inativar', [App\Http\Controllers\PDVController::class, 'inativarDespesa'])->name('despesa.inativar');
    Route::post('/despesa/buscar', [App\Http\Controllers\PDVController::class, 'buscarDespesa'])->name('despesa.buscar');
    Route::post('/despesa/buscar/documento', [App\Http\Controllers\PDVController::class, 'buscarDocumentoDespesa'])->name('despesa.buscar.documento');
    Route::post('/despesa/inserir/documento', [App\Http\Controllers\PDVController::class, 'inserirDocumentoDespesa'])->name('despesa.inserir.documento');
    Route::post('/despesa/inativar/documento', [App\Http\Controllers\PDVController::class, 'inativarDocumentoDespesa'])->name('despesa.inativar.documento');

    Route::get('/contaspagar', [App\Http\Controllers\PDVController::class, 'contasPagar'])->name('contaspagar');
    Route::post('/contaspagar/cadastrar', [App\Http\Controllers\PDVController::class, 'inserirCPG'])->name('contaspagar.inserir');
    Route::post('/contaspagar/alterar', [App\Http\Controllers\PDVController::class, 'alterarCPG'])->name('contaspagar.alterar');
    Route::post('/contaspagar/inativar', [App\Http\Controllers\PDVController::class, 'inativarCPG'])->name('contaspagar.inativar');
    Route::post('/contaspagar/buscar', [App\Http\Controllers\PDVController::class, 'buscarCPG'])->name('contaspagar.buscar');
    Route::post('/contaspagar/buscar/documento', [App\Http\Controllers\PDVController::class, 'buscarDocumentoCPG'])->name('contaspagar.buscar.documento');
    Route::post('/contaspagar/inserir/documento', [App\Http\Controllers\PDVController::class, 'inserirDocumentoCPG'])->name('contaspagar.inserir.documento');
    Route::post('/contaspagar/inativar/documento', [App\Http\Controllers\PDVController::class, 'inativarDocumentoCPG'])->name('contaspagar.inativar.documento');
    Route::post('/contaspagar/inserir/pagamento', [App\Http\Controllers\PDVController::class, 'inserirPagamentoCPG'])->name('contaspagar.inserir.pagamento');

    Route::get('/ordemServico', [App\Http\Controllers\OrdemServicoController::class, 'index'])->name('ordemservico');
    Route::post('/ordemServico/cadastrar', [App\Http\Controllers\OrdemServicoController::class, 'inserirOrdemServico'])->name('ordem.inserir');
    Route::post('/ordemServico/alterar', [App\Http\Controllers\OrdemServicoController::class, 'alterarOrdemServico'])->name('ordem.alterar');
    Route::post('/ordemServico/inativar', [App\Http\Controllers\OrdemServicoController::class, 'inativarOrdemServico'])->name('ordem.inativar');
    Route::post('/ordemServico/buscar', [App\Http\Controllers\OrdemServicoController::class, 'buscarOrdemServico'])->name('ordem.buscar');
    Route::post('/ordemServico/buscar/item', [App\Http\Controllers\OrdemServicoController::class, 'buscarItemOrdemServico'])->name('ordem.buscar.item');
    Route::get('/ordemServico/impresso/{id}', [App\Http\Controllers\OrdemServicoController::class, 'imprimirOrdem'])->name('ordem.imprimir');

    Route::get('/lucroPDV', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.pdv');
    Route::post('/lucroPDV/mensal', [App\Http\Controllers\DashboardController::class, 'bucarDashboardMensal'])->name('dashboard.buscar.mensal');
    Route::post('/lucroPDV/anual', [App\Http\Controllers\DashboardController::class, 'buscarDashboardAnual'])->name('dashboard.buscar.anual');
    Route::post('/lucroPDV/lista', [App\Http\Controllers\DashboardController::class, 'buscarListaDashboard'])->name('dashboard.buscar.lista');
});


