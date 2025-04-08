@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="totalUsuarios">0</h3>
                        <p>Total de Usuários</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="totalClientes">0</h3>
                        <p>Total de Clientes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="totalProjetos">0</h3>
                        <p>Projetos em Execução</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-powerpoint"></i>
                    </div>
                </div>
            </div>
        </div>

        @CAN('ADMINISTRADOR')
        <div class="row pt-1">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Projetos em Andamento</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="d-none d-lg-table-cell">ID</th>
                                    <th class="d-none d-lg-table-cell">Projeto</th>
                                    <th class="d-none d-lg-table-cell">Progresso</th>
                                    <th class="d-none d-lg-table-cell">%</th>
                                </tr>
                            </thead>
                            <tbody id="tabelaObrasAndamento">
                                <!-- Dados serão carregados via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Gastos X Lucro</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="d-none d-lg-table-cell">Projeto</th>
                                    <th class="d-none d-lg-table-cell">Valor</th>
                                    <th class="d-none d-lg-table-cell">Gastos</th>
                                    <th class="d-none d-lg-table-cell">Lucro</th>
                                </tr>
                            </thead>
                            <tbody id="tabelaGastos">
                                <!-- Dados serão carregados via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção Projetos Fechados -->
        <div class="row d-flex">
            <div class="card col-6 col-md-3">
                <div class="card-body text-center">
                    <span class="badge bg-info" id="spanValorProjetoFechado">R$ 0,00</span>
                    <h6 class="text-muted">PROJETOS FECHADOS</h6>
                </div>
            </div>
            <div class="card col-6 col-md-3">
                <div class="card-body text-center">
                    <span class="badge bg-warning" id="spanValorProjetoEntregue">R$ 0,00</span>
                    <h6 class="text-muted">PROJETOS ENTREGUES</h6>
                </div>
            </div>
            <div class="card col-6 col-md-3">
                <div class="card-body text-center">
                    <span class="badge bg-danger" id="spanValorGastoTotal">R$ 0,00</span>
                    <h6 class="text-muted">GASTO TOTAL</h6>
                </div>
            </div>
            <div class="card col-6 col-md-3">
                <div class="card-body text-center">
                    <span class="badge bg-success" id="spanValorLucroTotal">R$ 0,00</span>
                    <h6 class="text-muted">LUCRO TOTAL</h6>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Gráfico de Gastos X Valor Projeto</h3>
                <div class="card-tools">
                    <select id="selectAno" class="form-control form-control-sm">
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024" >2024</option>
                        <option value="2025" selected>2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="gastosChart"></canvas>
                </div>
            </div>
        </div>
        @ENDCAN
    </div>
@stop

@section('footer')
    <center>
        <span id="spnVersao">
            Desenvolvido por <a href="https://gssoftware.app.br/" target="_blank">GSSoftware</a>
        </span>
    </center>
@stop

@section('css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .table{
            background-color: #f8f9fa;
        }
        .table td, .table th{
            padding: 0px!important;
        }
        table tr:nth-child(even) /* CSS3 */ {
            background: #007bff18;
        }
        .ui-autocomplete{
            z-index: 1050;
        }
        .estoque-baixo{
            background-color: #f3707059!important;
        }   
        .card {
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card-header {
            font-weight: bold;
        }
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
        .table th {
            border-top: none;
        }
    </style>
@stop

@section('js')
    <script src="{{env('APP_URL')}}/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        function buscarDadosProjetos(){
            editar = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                },
                url:"{{route('dashboard.buscar.projeto.valores')}}",
                success:function(r){
                    var dados = r.dados[0];

                    $('#spanValorProjetoFechado').html(mascaraFinanceira(dados['TOTAL_FECHADO']));
                    $('#spanValorProjetoEntregue').html(mascaraFinanceira(dados['TOTAL_ENTREGUE']));
                    $('#spanValorLucroTotal').html(mascaraFinanceira(dados['TOTAL_FECHADO'] - dados['TOTAL_GASTO']));
                    $('#spanValorGastoTotal').html(mascaraFinanceira(dados['TOTAL_GASTO']));
                },
                error:err=>{exibirErro(err)}
            })
        }

        function buscarDadosProjetosValores(){
            editar = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'LIMIT': 10,
                    'FILTRO_ADICIONAL': "AND YEAR(DATA_INICIO) = YEAR(NOW())"
                },
                url:"{{route('projeto.buscar')}}",
                success:function(r){
                    popularListaDadosProjetosAndamento(r.dados);
                    popularListaDadosGastosLucro(r.dados);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function buscarCards(){
            editar = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}'
                },
                url:"{{route('dashboard.buscar.cards')}}",
                success:function(r){
                    var dados = r.dados[0];

                    $('#totalUsuarios').text(dados['TOTAL_USUARIOS']);
                    $('#totalProjetos').text(dados['TOTAL_PROJETOS']);
                    $('#totalClientes').text(dados['TOTAL_CLIENTES']);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularListaDadosGastosLucro(dados){
            var htmlTabela = "";
            for(i=0; i< dados.length; i++){
                var Keys = Object.keys(dados[i]);
                for(j=0;j<Keys.length;j++){
                    if(dados[i][Keys[j]] == null){
                        dados[i][Keys[j]] = "";
                    }
                }
                
                htmlTabela += `
                    <tr id="tableRowdados" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['TITULO']}</td>
                        <td class="tdTexto"><span class="badge bg-info">${mascaraFinanceira(dados[i]['VALOR'])}</span></td>
                        <td class="tdTexto"><span class="badge bg-warning">${mascaraFinanceira(dados[i]['VALOR_GASTO'])}</span></td>
                        <td class="tdTexto"><span class="badge bg-success">${mascaraFinanceira(dados[i]['VALOR'] - dados[i]['VALOR_GASTO'])}</span></td>
                    </tr>                    

                    <tr class="d-lg-none">
                        <td class="row d-flex ">
                            <div class="col-12 d-flex justify-content-center">
                                <span><b>${dados[i]['TITULO']}</b> - ${mascaraFinanceira(dados[i]['VALOR'])}</span>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <span>Gasto : <b>${mascaraFinanceira(dados[i]['VALOR_GASTO'])}</b></span>
                                <span>Lucro : <b>${mascaraFinanceira(dados[i]['VALOR'] - dados[i]['VALOR_GASTO'])}</b></span>
                            </div>
                        </td>
                    </tr>
                `;
            }
            $('#tabelaGastos').html(htmlTabela);
        }

        function popularListaDadosProjetosAndamento(dados){
            var htmlTabela = "";
            for(i=0; i< dados.length; i++){
                var Keys = Object.keys(dados[i]);
                for(j=0;j<Keys.length;j++){
                    if(dados[i][Keys[j]] == null){
                        dados[i][Keys[j]] = "";
                    }
                }
                var porcentagemConcluido = dados[i]['PORCENTAGEM_ETAPA'] != '' ? dados[i]['PORCENTAGEM_ETAPA'] : 0;
                var corPorcentagem = 'bg-danger';

                if(porcentagemConcluido >= 30 && porcentagemConcluido < 50){
                    corPorcentagem = 'bg-warning';
                } else if(porcentagemConcluido >= 50 && porcentagemConcluido < 70){
                    corPorcentagem = 'bg-info';
                } else if(porcentagemConcluido > 70){
                    corPorcentagem = 'bg-success';
                }

                var spanPorcentagemBarra = `<span class="progress-bar ${corPorcentagem}" style="width: ${porcentagemConcluido}%"></span>`
                var spanPorcentagem = `<span class="badge ${corPorcentagem}">${porcentagemConcluido} %</span>`
                
                htmlTabela += `
                    <tr id="tableRowdados" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['ID']}</td>
                        <td class="tdTexto">${dados[i]['TITULO']}</td>
                        <td class="progress progress-sm mt-2">
                            ${spanPorcentagemBarra}
                        </td>
                        <td class="tdTexto">
                            ${spanPorcentagem}                            
                        </td>
                    </tr>

                    <tr class="d-lg-none">
                        <td class="row d-flex ">
                            <div class="col-12 d-flex justify-content-center">
                                <span> ${dados[i]['ID']} - <b>${dados[i]['TITULO']}</b></span>
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                ${spanPorcentagem}
                            </div>
                        </td>
                    </tr>
                `;
            }
            $('#tabelaObrasAndamento').html(htmlTabela);
        }

        /* Matheus 02/04/2025 22:24:47 - GRAFICO */
            function inicializarGrafico() {
                const ctx = document.getElementById('gastosChart').getContext('2d');
                return new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: []
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                stacked: false,
                            },
                            y: {
                                stacked: false,
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'R$ ' + value.toLocaleString('pt-BR');
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += 'R$ ' + context.raw.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function carregarDadosGrafico(ano) {
                $.ajax({
                    type: 'POST',
                    url: '{{route('dashboard.buscar.grafico.valores')}}',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        ANO: ano
                    },
                    success: function(response) {
                        if (window.gastosChart) {
                            window.gastosChart.data.labels = response.labels;
                            window.gastosChart.data.datasets = response.datasets;
                            window.gastosChart.update();
                        }
                    },
                    error:err=>{exibirErro(err)}
                });
            }
        /* Matheus 02/04/2025 22:24:47 - FIM */

        $(document).ready(function() {
            window.gastosChart = inicializarGrafico();
            
            // Carrega os dados para o ano atual
            carregarDadosGrafico(new Date().getFullYear());
            
            // Atualiza ao mudar o ano
            $('#selectAno').change(function() {
                carregarDadosGrafico($(this).val());
            });

            buscarDadosProjetosValores();
            buscarDadosProjetos();
            buscarCards();
        })
    </script>
@stop