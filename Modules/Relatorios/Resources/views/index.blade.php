@extends('adminlte::page')

@section('title', 'Relatórios')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-4 d-none d-md-block">
                <h1>Relatórios</h1>
            </div>
            <div class="col-12 col-md-8 d-flex justify-content-end">
                <button class="btn  btn-dark" id="btnImprimirRelatorio"><i class="far fa-file-pdf"></i> Gerar PDF</button>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex m-0 p-0">
                    <div class="col-8">
                        <select class="form-control" id="selectModeloRelatorio">
                            <option value="0">Selecionar Relatorio</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-block btn-info" id="btnGerarRelatorio"><i class="fa fa-print"></i> Gerar Relatorio</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-xs">
                    <thead id="tableHeadRelatorio">
                    </thead>
                    <tbody id="tableBodyRelatorio">
                    </tbody>
                </table>
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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{env('APP_URL')}}/main.css">
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
    </style>
@stop

@section('js')
    <script src="{{env('APP_URL')}}/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        var dadosRelatorio = null;
        var codigoRelatorio = '{{$codigo}}'

        function buscarSelectRelatorio() {
        
            $.ajax({
                type: 'post',
                datatype: 'json',
                data: {
                    '_token': '{{csrf_token()}}',
                    'TIPO': codigoRelatorio
                },
                url: "{{route('relatorio.buscar.modelo')}}",
                success: function (r) {
                    var dadosModeloRelatorio = r.dados;

                    popularSelectRelatorio(dadosModeloRelatorio);
                },
                error: function (e) {
                    console.log(e)
                    informarErro('Não foi possivel obter dados, favor entrar em contato com o suporte.')
                }
            })
        }

        function popularSelectRelatorio(dados){
            var htmlTabela = `<option value="0">Selecionar Relatorio</option>`;

            for(i=0; i< dados.length; i++){
                var materialKeys = Object.keys(dados[i]);
                for(j=0;j<materialKeys.length;j++){
                    if(dados[i][materialKeys[j]] == null){
                        dados[i][materialKeys[j]] = "";
                    }
                }

                htmlTabela += `
                    <option value="${dados[i]['CODIGO']}">${dados[i]['NOME']}</option>`;
            }
            $('#selectModeloRelatorio').html(htmlTabela)
        }

        function buscarDados() {
            if($('#selectModeloRelatorio').val() == '0'){
                dispararAlerta('warning', 'Selecione um relatório para poder gerar.')
            } else {
        
                $.ajax({
                    type: 'post',
                    datatype: 'json',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'CODIGO': $('#selectModeloRelatorio').val()
                    },
                    url: "{{route('relatorio.buscar.dados')}}",
                    success: function (r) {
                        dadosRelatorio = r.dados;
        
                        popularTabelaDados(dadosRelatorio);
                    },
                    error: function (e) {
                        console.log(e)
                        informarErro('Não foi possivel obter dados, favor entrar em contato com o suporte.')
                    }
                })
            }
        }
        
        function popularTabelaDados(Dados) {
            // Verifica se há dados
            if (Dados.length === 0) return;
            
            // Pega as chaves do primeiro objeto para criar o cabeçalho
            var DadosKeys = Object.keys(Dados[0]);
            var htmlHead = '<tr>';
            var htmlDados = '';
            
            // Cria o cabeçalho da tabela
            for (j = 0; j < DadosKeys.length; j++) {
                htmlHead += `<th><center>${DadosKeys[j]}</center></th>`;
            }
            htmlHead += '</tr>';
            $('#tableHeadRelatorio').html(htmlHead);
            
            // Preenche as linhas da tabela
            for (i = 0; i < Dados.length; i++) {
                htmlDados += '<tr>';
                for (j = 0; j < DadosKeys.length; j++) {
                    var valor = Dados[i][DadosKeys[j]] === null ? '' : Dados[i][DadosKeys[j]];
                    htmlDados += `<td><center>${valor}</center></td>`;
                }
                htmlDados += '</tr>';
            }
            
            $('#tableBodyRelatorio').html(htmlDados);
        }

        function gerarRelatorioPDF(){
            if(dadosRelatorio == null){
                dispararAlerta('warning', 'Gere um relatório para poder imprimir')
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Selecione o relatório para impressão:',
                    showCloseButton: false,
                    showConfirmButton: true,
                    confirmButtonText:
                        'Relatório Padrão',
                    showCancelButton: true,
                    cancelButtonText:
                        'Customizar Relatório'
                }).then((result) => {
                    if (result.value) {
                        window.open("{{env('APP_URL')}}/relatorios/impresso", "_blank");
                    } else if (result.dismiss == 'cancel') {
                        personalizarRelatorioPDF(dadosRelatorio);
                    }
                })
            }
        }

        function personalizarRelatorioPDF(data) {
            if(data.length > 0){
                const columns = Object.keys(data[0]);
    
                let checkboxHtml = columns.map(column => {
                    return `<label><input type="checkbox" class="column-checkbox" value="${column}" checked> ${column}</label><br>`;
                }).join('');
    
                Swal.fire({
                    title: 'Selecione as colunas que serão exibidas no relatório',
                    html: checkboxHtml,
                    showCancelButton: true,
                    confirmButtonText: 'Gerar Relatório',
                    cancelButtonText: 'Cancelar',
                    preConfirm: () => {
                        let selectedColumns = [];
                        $('.column-checkbox:checked').each(function() {
                            selectedColumns.push($(this).val());
                        });
                        return selectedColumns;
                    }
                }).then((result) => {
                    if (result.value) {
                        filtrarArray(data, result.value);
                    }
                });
            } else {
                Swal.fire(
                    'Atenção!',
                    'Nenhum registro disponível para geração do relatório.',
                    'warning'
                )
            }
        }

        // Função para filtrar os dados com base nas colunas selecionadas
        function filtrarArray(data, colunasSelecionadas) {
            let dadosFiltrados = {
                dados: data.map(linha => {
                    let linhaFiltrada = {};
                    colunasSelecionadas.forEach(coluna => {
                        linhaFiltrada[coluna] = linha[coluna];
                    });
                    return linhaFiltrada;
                })
            };

            if(colunasSelecionadas.length > 0){
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                       '_token':'{{csrf_token()}}',
                       'DADOS_CACHE': dadosFiltrados.dados
                    },
                    url:"{{route('relatorio.cache')}}",
                    success:function(r){
                        window.open("{{env('APP_URL')}}/relatorios/impresso", "_blank");
                    },
                    error:err=>{exibirErroAJAX(err)}
                });
            } else {
                Swal.fire(
                    'Relatório não gerado!',
                    'Selecione ao menos uma coluna para gerar o relatório.',
                    'error'
                )
            }
        }

        $('#btnGerarRelatorio').on('click', () => {
            buscarDados();
        })

        $('#btnImprimirRelatorio').on('click', () => {
            gerarRelatorioPDF();
        })

        $(document).ready(function() {
            buscarSelectRelatorio();
        })
    </script>
@stop