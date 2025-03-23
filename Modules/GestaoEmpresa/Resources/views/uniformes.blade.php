@extends('adminlte::page')

@section('title', 'Uniforme/EPI')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Uniforme/EPI</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <button class="col-md-4 btn btn-block btn-warning" id="btnNovoUniforme">
                    <i class="fas fa-tshirt"></i>
                    <span class="ml-1">Nova</span>
                </button>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="form-group col-12 d-flex row p-0 m-0">
                        <input type="text" class="form-control form-control-border col-12" id="inputFiltroBusca" placeholder="Buscar Uniforme/EPI" maxlength="120" onkeyup="buscarCPG()">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th><center>Código</center></th>
                            <th><center>Quantidade</center></th>
                            <th><center>Ações</center></th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyDadosUniforme">
                        <!-- Dados dos uniformes serão preenchidos aqui -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-uniforme" tabindex="-1" role="dialog" aria-labelledby="modalUniformeLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUniformeLabel">Cadastrar Uniforme</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="inputDescricaoUniforme">Descrição</label>
                            <input type="text" class="form-control" id="inputDescricaoUniforme">
                        </div>
                        <div class="form-group">
                            <label for="inputCodigoUniforme">Código</label>
                            <input type="text" class="form-control" id="inputCodigoUniforme">
                        </div>
                        <div class="form-group">
                            <label for="inputQuantidadeUniforme">Quantidade</label>
                            <input type="number" class="form-control" id="inputQuantidadeUniforme">
                        </div>
                        <input type="hidden" id="inputCodUniforme" value="0">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarUniforme">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog ">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Documentação da Gasto <span id="titleDocumento"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="">
                                <div class="">
                                    <input type="hidden" id="inputIDConta">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="inputArquivoDocumentacao" onchange="validaDocumento()">
                                            <label class="custom-file-label" for="inputArquivoDocumentacao" id="labelInputArquivoDocumentacao">Selecionar Arquivos</label>
                                        </div>
                                        <div class="input-group-append">
                                            <button class="input-group-text" onclick="salvarDocumento()">Enviar</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-responsive-xs">
                                <thead>
                                    <tr>
                                        <th>Conta</th>
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
@stop

@section('css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{env('APP_URL')}}/main.css">
    <style>
        .table{
            background-color: #f8f9fa;
        }

        .ui-autocomplete{
            z-index: 1050;
        }
    </style>
@stop

@section('footer')
    <center>
        <span id="spnVersao">
            Desenvolvido por <a href="https://gssoftware.app.br/" target="_blank">GSSoftware</a>
        </span>
    </center>
@stop

@section('js')
    <script src="{{env('APP_URL')}}/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        function buscarUniforme() {
            $.ajax({
                type: 'post',
                datatype: 'json',
                data: {
                    '_token': '{{csrf_token()}}',
                    'filtro': $('#inputFiltroBusca').val()
                },
                url: "{{route('uniforme.buscar')}}",
                success: function(r) {
                    popularListaUniforme(r.dados);
                },
                error: err => { exibirErro(err) }
            });
        }
    
        function cadastrarUniforme() {
            $('#modal-uniforme').modal('show');
    
            $('#inputDescricaoUniforme').val('');
            $('#inputCodigoUniforme').val('');
            $('#inputQuantidadeUniforme').val('');
            $('#inputCodUniforme').val('0');
        }
    
        function inserirUniforme() {
            validacao = true;
    
            var inputIDs = ['inputDescricaoUniforme', 'inputCodigoUniforme', 'inputQuantidadeUniforme'];
    
            for (var i = 0; i < inputIDs.length; i++) {
                var inputID = inputIDs[i];
                var $input = $('#' + inputID);
    
                if ($input.val() === '') {
                    $input.addClass('is-invalid');
                    validacao = false;
                } else {
                    $input.removeClass('is-invalid');
                }
            }
    
            if (validacao) {
                if ($('#inputCodUniforme').val() == null || $('#inputCodUniforme').val() == 0) {
                    $.ajax({
                        type: 'post',
                        datatype: 'json',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'DESCRICAO': $('#inputDescricaoUniforme').val(),
                            'CODIGO': $('#inputCodigoUniforme').val(),
                            'QUANTIDADE': $('#inputQuantidadeUniforme').val()
                        },
                        url: "{{route('uniforme.inserir')}}",
                        success: function(r) {
                            console.log(r);
                            $('#modal-uniforme').modal('hide');
                            buscarUniforme();
    
                            Swal.fire(
                                'Sucesso!',
                                'Uniforme inserido com sucesso.',
                                'success',
                            )
                        },
                        error: err => { exibirErro(err) }
                    })
                } else {
                    $.ajax({
                        type: 'post',
                        datatype: 'json',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'DESCRICAO': $('#inputDescricaoUniforme').val(),
                            'CODIGO': $('#inputCodigoUniforme').val(),
                            'QUANTIDADE': $('#inputQuantidadeUniforme').val(),
                            'ID': $('#inputCodUniforme').val()
                        },
                        url: "{{route('uniforme.alterar')}}",
                        success: function(r) {
                            console.log(r);
                            $('#modal-uniforme').modal('hide');
                            buscarUniforme();
    
                            Swal.fire(
                                'Sucesso!',
                                'Uniforme alterado com sucesso.',
                                'success',
                            )
                        },
                        error: err => { exibirErro(err) }
                    })
                }
            }
        }
    
        function editarUniforme(idUniforme) {
            $.ajax({
                type: 'post',
                datatype: 'json',
                data: {
                    '_token': '{{csrf_token()}}',
                    'ID': idUniforme
                },
                url: "{{route('uniforme.buscar')}}",
                success: function(r) {
                    $('#inputDescricaoUniforme').val(r.dados[0]['DESCRICAO']);
                    $('#inputCodigoUniforme').val(r.dados[0]['CODIGO']);
                    $('#inputQuantidadeUniforme').val(r.dados[0]['QUANTIDADE']);
                    $('#inputCodUniforme').val(idUniforme);
    
                    $('#modal-uniforme').modal('show');
                },
                error: err => { exibirErro(err) }
            })
        }
    
        function inativarUniforme(idUniforme) {
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar o uniforme?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        datatype: 'json',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'ID': idUniforme
                        },
                        url: "{{route('uniforme.inativar')}}",
                        success: function(r) {
                            Swal.fire('Sucesso!', 'Uniforme inativado com sucesso.', 'success');
                            buscarUniforme();
                        },
                        error: err => { exibirErro(err) }
                    })
                }
            });
        }
    
        function popularListaUniforme(uniformes) {
            var htmlUniforme = "";
    
            for (i = 0; i < uniformes.length; i++) {
                var uniformeKeys = Object.keys(uniformes[i]);
                for (j = 0; j < uniformeKeys.length; j++) {
                    if (uniformes[i][uniformeKeys[j]] == null) {
                        uniformes[i][uniformeKeys[j]] = "";
                    }
                }
    
                var btnAcoes = `
                    <div class="input-group-prepend show justify-content-center" style="text-align: center">
                        <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            Ações
                        </button>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item" onclick="editarUniforme(${uniformes[i]['ID']})"><span class="btn"><i class="fas fa-pen"></i> Editar</span></li>
                            <li class="dropdown-item" onclick="inativarUniforme(${uniformes[i]['ID']})"><span class="btn"><i class="fas fa-trash"></i> Inativar</span></li>
                        </ul>
                    </div>
                `;
    
                htmlUniforme += `
                    <tr id="tableRow${uniformes[i]['ID']}">
                        <td class="tdTexto">${uniformes[i]['DESCRICAO']}</td>
                        <td class="tdTexto"><center>${uniformes[i]['CODIGO']}</center></td>
                        <td class="tdTexto"><center>${uniformes[i]['QUANTIDADE']}</center></td>
                        <td>
                            <center>
                                ${btnAcoes}
                            </center>
                        </td>
                    </tr>
                `;
            }
            $('#tableBodyDadosUniforme').html(htmlUniforme);
        }
    
        $('#btnConfirmarUniforme').click(() => {
            inserirUniforme();
        });
    
        $('#btnNovoUniforme').click(() => {
            cadastrarUniforme();
        });

        $('#inputFiltroBusca').on('keyup', () => {
            buscarUniforme();
        });
    
        $(document).ready(() => {
            buscarUniforme();
        });
    </script>
@stop