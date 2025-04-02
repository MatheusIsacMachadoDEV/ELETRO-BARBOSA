@extends('adminlte::page')

@section('title', 'Faturamento')

@section('content_header')
    <h1>Faturamento</h1>
@stop

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card p-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label>Data Início</label>
                                <input type="date" class="form-control" id="dataInicio" value="{{ date('Y-m-01') }}">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label>Data Fim</label>
                                <input type="date" class="form-control" id="dataFim" value="{{ date('Y-m-t') }}">
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary" id="btnFiltrar">Filtrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Resumo -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 id="totalReceber">R$ 0,00</h3>
                    <p>Total a Receber</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="totalRecebido">R$ 0,00</h3>
                    <p>Total Recebido</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cash-register"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3 id="totalPagar">R$ 0,00</h3>
                    <p>Total a Pagar</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 id="totalPago">R$ 0,00</h3>
                    <p>Total Pago</p>
                </div>
                <div class="icon">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Contas a Receber - Pagas no Período</h3>
                </div>
                <div class="card-body">
                    <canvas id="graficoReceber" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Contas a Pagar - Pagas no Período</h3>
                </div>
                <div class="card-body">
                    <canvas id="graficoPagar" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabelas -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Últimas Contas a Receber</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Vencimento</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tabelaReceber">
                            <!-- Dados serão carregados via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Últimas Contas a Pagar</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Vencimento</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tabelaPagar">
                            <!-- Dados serão carregados via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')    
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{env('APP_URL')}}/main.css">
@stop

@section('js')
    <script src="{{env('APP_URL')}}/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        // Variáveis globais para os gráficos
        let graficoReceber;
        let graficoPagar;

        function carregarDados() {
            const dataInicio = $('#dataInicio').val();
            const dataFim = $('#dataFim').val();

            $.ajax({
                url: "{{ route('dashboard.financeiro.dados') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    data_inicio: dataInicio,
                    data_fim: dataFim
                },
                success: function(response) {
                    // Atualiza os cards
                    $('#totalReceber').text(mascaraFinanceira(response.totais.total_a_receber));
                    $('#totalRecebido').text(mascaraFinanceira(response.totais.total_recebido));
                    $('#totalPagar').text(mascaraFinanceira(response.totais.total_a_pagar));
                    $('#totalPago').text(mascaraFinanceira(response.totais.total_pago));

                    // Atualiza os gráficos
                    atualizarGraficoReceber(response.graficos.receber);
                    atualizarGraficoPagar(response.graficos.pagar);

                    // Atualiza as tabelas
                    atualizarTabelaReceber(response.ultimas_contas.receber);
                    atualizarTabelaPagar(response.ultimas_contas.pagar);
                },
                error: function(err) {
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Ocorreu um erro ao carregar os dados.',
                        icon: 'error'
                    });
                    console.error(err);
                }
            });
        }

        function atualizarGraficoReceber(dados) {
            const ctx = document.getElementById('graficoReceber').getContext('2d');
            
            if (graficoReceber) {
                graficoReceber.destroy();
            }

            graficoReceber = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dados.labels,
                    datasets: [{
                        label: 'Valor Recebido (R$)',
                        data: dados.valores,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function atualizarGraficoPagar(dados) {
            const ctx = document.getElementById('graficoPagar').getContext('2d');
            
            if (graficoPagar) {
                graficoPagar.destroy();
            }

            graficoPagar = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dados.labels,
                    datasets: [{
                        label: 'Valor Pago (R$)',
                        data: dados.valores,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function atualizarTabelaReceber(dados) {
            let html = '';
            dados.forEach(conta => {
                html += `
                    <tr>
                        <td>${conta.DESCRICAO}</td>
                        <td>${mascaraFinanceira(conta.VALOR)}</td>
                        <td>${moment(conta.DATA_VENCIMENTO).format('DD/MM/YYYY')}</td>
                        <td>${conta.SITUACAO}</td>
                    </tr>
                `;
            });
            $('#tabelaReceber').html(html);
        }

        function atualizarTabelaPagar(dados) {
            let html = '';
            dados.forEach(conta => {
                html += `
                    <tr>
                        <td>${conta.DESCRICAO}</td>
                        <td>${mascaraFinanceira(conta.VALOR)}</td>
                        <td>${moment(conta.DATA_VENCIMENTO).format('DD/MM/YYYY')}</td>
                        <td>${conta.SITUACAO}</td>
                    </tr>
                `;
            });
            $('#tabelaPagar').html(html);
        }

        // Função para formatar valores monetários
        function formatarMoeda(valor) {
            return parseFloat(valor).toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
        }

        $(document).ready(function() {
            // Carrega os dados iniciais
            carregarDados();

            // Configura o evento do botão filtrar
            $('#btnFiltrar').click(function() {
                carregarDados();
            });
        });
    </script>
@stop