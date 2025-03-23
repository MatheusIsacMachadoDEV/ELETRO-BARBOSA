@extends('adminlte::page')

@section('title', 'GSSoftware')

@section('content')

    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>GSSoftware</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <div class="col-12 col-md-6 row d-flex d-flex justify-content-end ">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-block btn-warning" id="btnNovo">
                            <i class="fas fa-plug"></i>
                            <span class="ml-1">Novo</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex m-0 p-0">
                    <div class="col-12 col-md-6">
                        <input type="text" class="form-control form-control-border" id="inputFiltro" placeholder="Filtro" maxlength="8" onkeyup="buscarDados()">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Funcionário</th>
                            <th>Data Início</th>
                            <th>Data Fim</th>
                            <th>Tempo (Dias)</th>
                            <th>Valor por Dia</th>
                            <th>Valor Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyDiarias">
                        <!-- Dados das diárias serão preenchidos aqui -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Documentação da dados <span id="titleDocumento"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="">
                                <input type="hidden" id="inputIDdadosDocumentacao">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="inputArquivoDocumentacao" onchange="validaDocumento()">
                                        <label class="custom-file-label" for="inputArquivoDocumentacao" id="labelInputArquivoDocumentacao">Selecionar Arquivos</label>
                                    </div>
                                    <div class="input-group-append">
                                        <button class="input-group-text" onclick="salvarDocumento()">Enviar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th><center>Ações</center></th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyDocumentos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onclick="fecharCadastroDocumento()">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modal-diaria" tabindex="-1" role="dialog" aria-labelledby="modalDiariaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDiariaLabel">Cadastrar Diária</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Autocomplete de Funcionário -->
                    <div class="form-group">
                        <label>Funcionário</label>
                        <input type="text" class="form-control" id="inputFuncionario" placeholder="Funcionário">
                        <input type="hidden" id="inputIDFuncionario">
                        <div class="btnLimparFuncionario d-none">
                            <button id="btnLimparFuncionario" class="btn btn-sm btn-danger mt-2"><i class="fas fa-eraser"></i> LIMPAR</button>
                        </div>
                    </div>

                    <!-- Data Início e Data Fim -->
                    <div class="form-group">
                        <label>Data Início</label>
                        <input type="date" class="form-control" id="inputDataInicio">
                    </div>
                    <div class="form-group">
                        <label>Data Fim</label>
                        <input type="date" class="form-control" id="inputDataFim">
                    </div>

                    <!-- Valor por Dia -->
                    <div class="form-group">
                        <label>Valor por Dia</label>
                        <input type="number" class="form-control" id="inputValorDia">
                    </div>

                    <!-- Valor Total (calculado automaticamente) -->
                    <div class="form-group">
                        <label>Valor Total</label>
                        <input type="number" class="form-control" id="inputValorTotal" readonly>
                    </div>

                    <input type="hidden" id="inputIDDiaria" value="0">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarDiaria">Salvar</button>
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

        function exibirModalCadastro(){
            $('#modal-diaria').modal('show');
        }
    
        // Buscar Diárias
        function buscarDados() {
            $.ajax({
                type: 'post',
                url: "{{route('diaria.buscar')}}",
                data: {
                    '_token': '{{csrf_token()}}',
                    'filtro': $('#inputFiltroBusca').val()
                },
                success: function(r) {
                    popularTabelaDados(r.dados);
                },
                error: err => {
                    console.log(err);
                }
            });
        }
    
        // Popular lista de diárias
        function popularTabelaDados(diarias) {
            let html = '';
            diarias.forEach(diaria => {
                html += `
                    <tr>
                        <td>${diaria.NOME_USUARIO}</td>
                        <td>${diaria.DATA_INICIO}</td>
                        <td>${diaria.DATA_FIM}</td>
                        <td>${diaria.TEMPO_DIAS} dias</td>
                        <td>R$ ${diaria.VALOR_DIA}</td>
                        <td>R$ ${diaria.VALOR_TOTAL}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editarDiaria(${diaria.ID})">Editar</button>
                            <button class="btn btn-sm btn-danger" onclick="inativarDiaria(${diaria.ID})">Inativar</button>
                        </td>
                    </tr>
                `;
            });
            $('#tableBodyDiarias').html(html);
        }
    
        // Editar Diária
        function editarDiaria(idDiaria) {
            $.ajax({
                type: 'post',
                url: "{{route('diaria.buscar')}}",
                data: {
                    '_token': '{{csrf_token()}}',
                    'ID': idDiaria
                },
                success: function(r) {
                    const diaria = r.dados[0];
                    $('#inputIDDiaria').val(diaria.ID);
                    $('#inputFuncionario').val(diaria.NOME_USUARIO);
                    $('#inputIDFuncionario').val(diaria.ID_USUARIO);
                    $('#inputDataInicio').val(diaria.DATA_INICIO);
                    $('#inputDataFim').val(diaria.DATA_FIM);
                    $('#inputValorDia').val(diaria.VALOR_DIA);
                    $('#inputValorTotal').val(diaria.VALOR_TOTAL);
                    $('#modal-diaria').modal('show');
                },
                error: err => {
                    console.log(err);
                }
            });
        }
    
        // Inativar Diária
        function inativarDiaria(idDiaria) {
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar esta diária?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: "{{route('diaria.inativar')}}",
                        data: {
                            '_token': '{{csrf_token()}}',
                            'ID': idDiaria
                        },
                        success: function(r) {
                            Swal.fire('Sucesso!', 'Diária inativada com sucesso.', 'success');
                            buscarDados();
                        },
                        error: err => {
                            console.log(err);
                        }
                    });
                }
            });
        }

        // Limpar campo de funcionário
        $('#btnLimparFuncionario').click(function() {
            limparCampo('inputFuncionario', 'inputIDFuncionario', 'btnLimparFuncionario');
        });
    
        // Calcular valor total
        $('#inputDataFim, #inputValorDia').on('change', function() {
            const dataInicio = new Date($('#inputDataInicio').val());
            const dataFim = new Date($('#inputDataFim').val());
            const valorDia = parseFloat($('#inputValorDia').val());

            if (dataInicio && dataFim && valorDia) {
                const tempoDias = (dataFim - dataInicio) / (1000 * 60 * 60 * 24);
                const valorTotal = tempoDias * valorDia;
                $('#inputValorTotal').val(valorTotal.toFixed(2));
            }
        });

        // Inserir/Editar Diária
        $('#btnConfirmarDiaria').click(function() {
            const idDiaria = $('#inputIDDiaria').val();
            const idUsuario = $('#inputIDFuncionario').val();
            const dataInicio = $('#inputDataInicio').val();
            const dataFim = $('#inputDataFim').val();
            const valorDia = $('#inputValorDia').val();
            const valorTotal = $('#inputValorTotal').val();

            if (!idUsuario || !dataInicio || !dataFim || !valorDia) {
                alert('Preencha todos os campos!');
                return;
            }

            const url = idDiaria == 0 ? "{{route('diaria.inserir')}}" : "{{route('diaria.alterar')}}";
            const data = {
                '_token': '{{csrf_token()}}',
                'ID': idDiaria,
                'ID_USUARIO': idUsuario,
                'DATA_INICIO': dataInicio,
                'DATA_FIM': dataFim,
                'VALOR_DIA': valorDia
            };

            $.ajax({
                type: 'post',
                url: url,
                data: data,
                success: function(r) {
                    $('#modal-diaria').modal('hide');
                    buscarDados();
                    Swal.fire('Sucesso!', 'Diária salva com sucesso.', 'success');
                },
                error: err => {
                    console.log(err);
                    Swal.fire('Erro!', 'Ocorreu um erro ao salvar a diária.', 'error');
                }
            });
        });

        // Autocomplete de Funcionário
        $("#inputFuncionario").autocomplete({
            source: function(request, cb) {
                param = request.term;
                $.ajax({
                    url: "{{route('pessoa.buscar')}}",
                    method: 'post',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'nome': param,
                        'ID_TIPO': 2
                    },
                    dataType: 'json',
                    success: function(r) {
                        result = $.map(r, function(obj) {
                            return {
                                label: obj.info,
                                value: obj.NOME,
                                data: obj
                            };
                        });
                        cb(result);
                    },
                    error: err => {
                        console.log(err);
                    }
                });
            },
            select: function(e, selectedData) {
                if (selectedData.item.label != 'Nenhum Funcionário Encontrado.') {
                    $('#inputFuncionario').val(selectedData.item.data.NOME);
                    $('#inputIDFuncionario').val(selectedData.item.data.ID);
                    $('#inputFuncionario').attr('disabled', true);
                    $('.btnLimparFuncionario').removeClass('d-none');
                } else {
                    limparCampo('inputFuncionario', 'inputIDFuncionario', 'btnLimparFuncionario');
                }
            }
        });

        $('#btnNovo').on('click',  () => {
            exibirModalCadastro();
        });

        $(document).ready(function() {
            buscarDados();
        })
    </script>
@stop