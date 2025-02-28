@extends('adminlte::page')

@section('title', 'DASHBOARD')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Dashboard Lucro</h1>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row d-flex">
            <div class="card card-info col-12 col-md-6">
                <div class="card-header">
                    <h3 class="card-title">Lucro Mensal</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" id="divGraficoMensal">
                        <canvas id="graficoMensal" width="400" height="400" ></canvas>
                    </div>
                </div>            
            </div>
            <div class="card card-info col-6 d-none d-md-block">
                <div class="card-header">
                    <h3 class="card-title">Lucro Anual</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container"><iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px; height: 0px; margin: 0px; position: absolute; inset: 0px;"></iframe>
                        <canvas id="graficoAnual" width="1318" height="659" style="width: 1318px; height: 659px; max-height: 750px;"></canvas>
                    </div>
                </div>            
            </div>        
        </div>

        <div class="card">
            <div class="card-header">
                <div class="row d-flex p-0">
                    <div class="input-group col-md-6 col-sm-12 p-0">
                        <div class="input-group col-md-5 col-sm-12">
                            <input type="date" class="form-control" id="inputDataInicio" onchange="buscarFaturamento();buscarGraficoMensal()">
                        </div>
                        <label class="col-2 display-content-center">Até</label>
                        <div class="input-group col-md-5 col-sm-6">
                            <input type="date" class="form-control" id="inputDataFim" onchange="buscarFaturamento();buscarGraficoMensal()">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-xs ">
                    <thead>
                        <th class="d-none d-lg-table-cell">ID</th>
                        <th class="d-none d-lg-table-cell">Data Venda</th>
                        <th class="d-none d-lg-table-cell">Valor Venda</th>
                        <th class="d-none d-lg-table-cell">Valor Custo Itens</th>
                        <th class="d-none d-lg-table-cell">Lucro</th>
                    </thead>
                    <tbody ID="tableBodyDadosFaturamento">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-cadastro">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Cadastro de Pessoa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">

                        <div class="form-group col-12">
                            <label>Nome</label>
                            <input type="text" class="form-control col-4" id="">
                        </div>
                        <div class="form-group col-12">
                            <label>CPF/CNPJ</label>
                            <input type="text" class="form-control" id="">
                        </div>
                        <div class="form-group col-12">
                            <label>Telefone</label>
                            <input type="text" class="form-control" id="">
                        </div>
                        <div class="form-group col-12">
                            <label>Email</label>
                            <input type="text" class="form-control" id="">
                        </div>
                        <div class="form-group col-2">
                            <label>Data de Nascimento</label>
                            <input type="date"  class="form-control" id="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </div>
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
    <link rel="stylesheet" href="{{env('APP_URL')}}/main.css">
    <style>
        .table{
            background-color: #f8f9fa;
        }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        var datasetLucroAnual = [];
        var datasetGastosAnual = [];

        var dadosLucroAnual = {
            "labels": [
                "Jan",
                "Fev",
                "Mar",
                "Abr",
                "Mai",
                "Jun",
                "Jul",
                "Ago",
                "Set",
                "Out",
                "Nov",
                "Dez"
            ],
            "datasets": [
                {
                "label": "Vendas",
                "backgroundColor": "#00FF7F",
                "fill": true,
                "data": datasetLucroAnual,
                "borderColor": "#000000",
                "borderWidth": "1"
                },
                {
                "label": "Gastos",
                "backgroundColor": "#aa2c2c",
                "fill": true,
                "data": datasetGastosAnual,
                "borderColor": "#000000",
                "borderWidth": "1"
                }
            ]
        };

        function buscarFaturamento(){
            dataInicio = $('#inputDataInicio').val();
            dataFim = $('#inputDataFim').val();

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'dataInicio':dataInicio,
                    'dataFim': dataFim
                },
                url:"{{route('dashboard.buscar.lista')}}",
                success:function(r){
                    popularListaFaturamento(r);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularListaFaturamento(dados){
            Faturamento = dados.dados;

            var htmlFaturamento = "",
                valorTotalVenda = 0,
                valorTotalCusto = 0,
                valorTotalLucro = 0;

            for(i=0; i< Faturamento.length; i++){
                var FaturamentoKeys = Object.keys(Faturamento[i]);
                for(j=0;j<FaturamentoKeys.length;j++){
                    if(Faturamento[i][FaturamentoKeys[j]] == null){
                        Faturamento[i][FaturamentoKeys[j]] = "";
                    }
                }

                var lucroTotal = Faturamento[i]['VALOR_TOTAL_VENDA'] - Faturamento[i]['VALOR_TOTAL_CUSTO'];

                if(lucroTotal <= 0){
                    spanBG = "bg-warning";
                }  else {
                    spanBG = "bg-success";
                }

                htmlFaturamento += `
                    <tr id="tableRow${Faturamento[i]['ID']}" class="d-none d-lg-table-row">
                        <td class="tdTexto">${Faturamento[i]['ID']}</td>
                        <td class="tdTexto">${moment(Faturamento[i]['DATA']).format('DD/MM/YYYY')}</td>
                        <td class="tdTexto">${mascaraFinanceira(Faturamento[i]['VALOR_TOTAL_VENDA'])}</td>
                        <td class="tdTexto">${mascaraFinanceira(Faturamento[i]['VALOR_TOTAL_CUSTO'])}</td>
                        <td class="tdTexto"><span class="badge ${spanBG}">${mascaraFinanceira(lucroTotal)}</span></td>                    
                    </tr>
                    
                    <tr id="tableRow${Faturamento[i]['ID']}" class="d-table-row d-lg-none">
                        <td>
                            <div class="col-12">
                                <center>
                                    <b>${Faturamento[i]['ID']}-${moment(Faturamento[i]['DATA']).format('DD/MM/YYYY')}</b>
                                </center>
                            </div>
                            <div class="col-12" style="font-size: 4vw">
                                <center>
                                    <b>Valor venda: ${mascaraFinanceira(Faturamento[i]['VALOR_TOTAL_VENDA'])}</b>
                                </center>
                            </div>
                            <div class="col-12" style="font-size: 4vw">
                                <center>
                                    <b>Valor Custo: ${mascaraFinanceira(Faturamento[i]['VALOR_TOTAL_CUSTO'])}</b>
                                </center>
                            </div>
                            <div class="col-12">
                                <center>
                                    <span class="badge ${spanBG}">${mascaraFinanceira(lucroTotal)}</span>
                                </center>
                            </div>
                        </td>
                    </tr>`;

                    valorTotalVenda = valorTotalVenda + Faturamento[i]['VALOR_TOTAL_VENDA'];
                    valorTotalCusto = valorTotalCusto + Faturamento[i]['VALOR_TOTAL_CUSTO'];
                    valorTotalLucro = valorTotalLucro + lucroTotal;

            }

            htmlFaturamento += `
                    <tr id="tableRowTotal" class="d-none d-lg-table-row">\
                        <td ></td>\
                        <td>TOTAL:</td>\
                        <td><span class="badge bg-info">${mascaraFinanceira(valorTotalVenda)}</span></td>\
                        <td><span class="badge bg-warning">${mascaraFinanceira(valorTotalCusto)}</span></td>\
                        <td><span class="badge bg-success">${mascaraFinanceira(valorTotalLucro)}</span></td>\
                    </tr>`;

            $('#tableBodyDadosFaturamento').html(htmlFaturamento)
        }

        function buscarGraficoMensal(){
            dataInicio = $('#inputDataInicio').val();
            dataFim = $('#inputDataFim').val();
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'dataInicio':dataInicio,
                    'dataFim': dataFim
                },
                url:"{{route('dashboard.buscar.mensal')}}",
                success:function(r){
                    dadosLucroMensal = {
                        "labels": [
                            "LUCRO",
                            "GASTOS"
                        ],
                        "datasets": [
                            {
                            "label": "VENDAS X GASTOS X LUCRO",
                            "backgroundColor": ["#00FF7F" , "#A52A2A", "#007bff"],
                            "fill": false,
                            "data": [""+(r.VENDAS - r.GASTOS)+""
                                         ,""+r.GASTOS+""
                                        ],
                            "borderColor": "#000000",
                            "borderWidth": "1"
                            }
                        ]
                    };

                    criarGraficoMensal(dadosLucroMensal);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function criarGraficoMensal(dadosLucroMensal){        
            var data = dadosLucroMensal ;
            $('#graficoMensal').remove();
            
            var novoCanvas = $("<canvas>").attr({
                id: "graficoMensal",
                width: 400,
                height: 300
            });
            $("#divGraficoMensal").append(novoCanvas);

            var contexto = novoCanvas[0].getContext("2d");

            // Execute operações de desenho aqui
            contexto.fillStyle = "blue";
            contexto.fillRect(50, 50, 100, 100);

            var options = {                
                "responsive": true,
                "maintainAspectRatio": true,
                "title": {
                    "display": true,
                    "text": "Processos em andamento",
                    "position": "top",
                    "fontFamily": "roboto",
                    "fontSize": 20,
                    "fontStyle": "bold",
                    "fontColor": "#000000",
                    "fullWidth": true
                },
                "legend": {
                    "display": false,
                    "fullWidth": false,
                    "labels": {
                        "fontFamily": "roboto",
                        "fontStyle": "bold",
                        "boxWidth": 20,
                        "padding": 15
                    }
                },
                "animation": {
                    "duration": "3000"
                },
                "elements": {
                    "arc": {
                    "borderColor": "#ffffff"
                    }
                }
            };

            var myChart = new Chart(contexto, {
                type: 'doughnut',
                data: data,
                options: options
            });

            $('#graficoMensal').css('max-height', '300px');
        }

        function buscarGraficoAnual(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}'
                },
                url:"{{route('dashboard.buscar.anual')}}",
                success:function(r){
                    datasetGastosAnual.push(r.GASTOS[0]['JAN']);
                    datasetGastosAnual.push(r.GASTOS[0]['FEV']);
                    datasetGastosAnual.push(r.GASTOS[0]['MAR']);
                    datasetGastosAnual.push(r.GASTOS[0]['ABR']);
                    datasetGastosAnual.push(r.GASTOS[0]['MAI']);
                    datasetGastosAnual.push(r.GASTOS[0]['JUN']);
                    datasetGastosAnual.push(r.GASTOS[0]['JUL']);
                    datasetGastosAnual.push(r.GASTOS[0]['AGO']);
                    datasetGastosAnual.push(r.GASTOS[0]['SETEM']);
                    datasetGastosAnual.push(r.GASTOS[0]['OUTUB']);
                    datasetGastosAnual.push(r.GASTOS[0]['NOV']);
                    datasetGastosAnual.push(r.GASTOS[0]['DEZ']);
                    
                    datasetLucroAnual.push(r.LUCRO[0]['JAN']);
                    datasetLucroAnual.push(r.LUCRO[0]['FEV']);
                    datasetLucroAnual.push(r.LUCRO[0]['MAR']);
                    datasetLucroAnual.push(r.LUCRO[0]['ABR']);
                    datasetLucroAnual.push(r.LUCRO[0]['MAI']);
                    datasetLucroAnual.push(r.LUCRO[0]['JUN']);
                    datasetLucroAnual.push(r.LUCRO[0]['JUL']);
                    datasetLucroAnual.push(r.LUCRO[0]['AGO']);
                    datasetLucroAnual.push(r.LUCRO[0]['SETEM']);
                    datasetLucroAnual.push(r.LUCRO[0]['OUTUB']);
                    datasetLucroAnual.push(r.LUCRO[0]['NOV']);
                    datasetLucroAnual.push(r.LUCRO[0]['DEZ']);

                    criarGraficoAnual();
                },
                error:err=>{exibirErro(err)}
            })
        }

        function criarGraficoAnual(){        
            var ctx = document.getElementById('graficoAnual').getContext('2d');
            var data = dadosLucroAnual ;

            var options = {
                "title": {
                    "display": false,
                    "text": "ven",
                    "fullWidth": true,
                    "fontSize": 30
                },
                "legend": {
                    "display": true,
                    "position": "top",
                    "fullWidth": true,
                    "labels": {
                    "boxWidth": 10
                    }
                },
                "scales": {
                    "yAxes": [
                    {
                        "ticks": {
                        "beginAtZero": true
                        }
                    }
                    ]
                },
                "tooltips": {
                    "enabled": true,
                    "mode": "label",
                    "titleFontFamily": "roboto",
                    "bodyFontFamily": "roboto",
                    "bodyFontSize": 12,
                    "titleFontSize": 15,
                    "cornerRadius": 1,
                    "backgroundColor": "#000000",
                    "footerFontFamily": "roboto",
                    "footerFontSize": 10,
                    "footerFontStyle": "normal",
                    "footerSpacing": 1,
                    "footerMarginTop": 1,
                    "xPadding": 10,
                    "yPadding": 10,
                    "caretSize": 1,
                    "bodyFontStyle": "bold"
                },
                "animation": {
                    "duration": "3000"
                }
            }

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: options
            });

            $('#graficoAnual').css('max-height', '750px');
        }

        // // Função para ajustar o tamanho do canvas
        // function resizeChart() {
        //     var ctx = document.getElementById("graficoVendas").getContext("2d");
        //     var container = document.getElementById("graficoVendas").parentNode;
        //     var width = container.offsetWidth;
        //     var height = container.offsetHeight;
        //     ctx.canvas.width = width;
        //     ctx.canvas.height = height;
        // }

        function mascaraFinanceira(valor){
            return (valor-0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }

        function mascaraDocumento(numero) {
            numero = numero.replace(/\D/g, '');

            if (numero.length === 11) {
                return numero.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            } else if (numero.length === 14) {
                return numero.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
            } else {
                return numero;
            }
        }

        function exibirErro(err){
            console.log(err)

            errorMessage =
            "<p><b>Exception: </b>"+err.responseJSON.exception+"<p></br>"
            +"<p><b>File: </b>"+err.responseJSON.file+"<p></br>"
            +"<p><b>Line: </b>"+err.responseJSON.line+"<p></br>"
            +"<p><b>Message: </b>"+err.responseJSON.message+"<p></br>";

            Swal.fire(
                'Request exception',
                errorMessage,
                'error'
            )
        }

        // window.addEventListener("resize", resizeChart);

        $(document).ready(() => {
            $('#inputDataInicio').val(moment().startOf('month').format('YYYY-MM-DD'));
            $('#inputDataFim').val(moment().endOf('month').format('YYYY-MM-DD'));  

            buscarGraficoAnual();
            buscarGraficoMensal();

            buscarFaturamento(); 
        })
    </script>
@stop