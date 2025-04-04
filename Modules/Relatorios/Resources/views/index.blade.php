@extends('adminlte::page')

@section('title', 'Relatórios')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Relatórios</h1>
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
                        <button type="button" class="btn btn-block btn-info">Filtrar</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-xs">
                    <thead>
                        <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">Coluna</th>
                        <th class="d-none d-lg-table-cell"><center>Coluna 2</center></th>
                        <th class="d-none d-lg-table-cell"><center>Ações</center></th>
                    </thead>
                    <tbody id="tableBodyDadosdados">
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

        function gerarRelatorioPDF(){
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
                    window.open("{{env('APP_URL')}}/consultas/impresso/itens", "_blank");
                } else if (result.dismiss == 'cancel') {
                    personalizarRelatorioPDF(dadosLista);
                }
            })
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
                    url:"",
                    success:function(r){
                        window.open("{{env('APP_URL')}}/consultas/impresso/customizavel/itens", "_blank");
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
    </script>
@stop