@extends('adminlte::page')

@section('title', 'Gastos')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Gastos</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <button class="col-md-4 btn btn-block btn-warning" id="btnNovoGasto">
                    <i class="fas fa-money-check-alt"></i>
                    <span class="ml-1">Novo</span>
                </button>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="form-group col-12 d-flex row">
                        <input type="text" class="form-control col-12 col-md-6" id="inputFiltroGasto" placeholder="Descrição Gasto" maxlength="120" onkeyup="buscarGastos()">

                        <div class="col-12 col-md-6 d-flex align-content-between row m-0 p-0">
                            <input type="date" class="form-control col-12 col-md-5" id="inputFiltroDataInicio" onchange="buscarGastos()">
                            <label class="col-12 col-md-2">Até</label>
                            <input type="date" class="form-control col-12 col-md-5" id="inputFiltroDataFim" onchange="buscarGastos()">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-md">
                    <thead>
                        <tr>
                            <th>Gasto</th>
                            <th><center>Valor</center></th>
                            <th><center>Data despesa</center></th>
                            <th><center>Ações</center></th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyDadosGasto">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-gasto">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Gastos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="form-group col-12">
                            <label>Descrição do Gasto</label>
                            <input type="hidden" class="form-control" id="inputCodGasto">
                            <input type="text" class="form-control col-12" id="inputGastoDesc">
                        </div>
                        
                        <div class="form-group col-xs-6 col-md-2">
                            <label>Data Gasto</label>
                            <input type="date"  class="form-control" id="inputDataGasto">
                        </div>

                        <div class="form-group col-xs-2 col-md-2">
                            <label>Valor Gasto</label>
                            <input type="text" inputmode="numeric" class="form-control" id="inputValorGasto">
                        </div>
                        
                        <div class="form-group col-12">
                            <label>Observação</label>
                            <textarea maxlength="999" rows="3" class="form-control" id="inputObservacaoGasto"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarGasto">Confirmar</button>
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
                                    <input type="hidden" id="inputIDDespesa">
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
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Despesa</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $('#inputValorGasto').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' });


        function buscarGastos(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'filtro': $('#inputFiltroGasto').val(),
                    'dataInicio': $('#inputFiltroDataInicio').val(),
                    'dataTermino': $('#inputFiltroDataFim').val(),
                },
                url:"{{route('despesa.buscar')}}",
                success:function(r){
                    popularListaGasto(r);
                },
                error:err=>{exibirErro(err)}
            })
        }    

        function popularListaGasto(Gasto){
            var htmlGasto = "";
            var totalCPG = 0;

            for(i=0; i< Gasto.length; i++){
                var GastoKeys = Object.keys(Gasto[i]);
                for(j=0;j<GastoKeys.length;j++){
                    if(Gasto[i][GastoKeys[j]] == null){
                        Gasto[i][GastoKeys[j]] = "";
                    }
                }
                htmlGasto += `
                    <tr id="tableRow${Gasto[i]['ID']}">
                        <td class="tdTexto">${Gasto[i]['DESCRICAO']}</td>
                        <td class="tdTexto"><center>${mascaraFinanceira(Gasto[i]['VALOR'])}</center></td>
                        <td class="tdTexto"><center>${moment(Gasto[i]['DATA']).format('DD/MM/YYYY')}</center></td>
                        <td>\
                            <center>\
                                <button class="btn btn-sm btn-warning" onclick="editarGasto(${Gasto[i]['ID']})"><i class="fas fa-pen"></i></button>\
                                <button class="btn btn-sm btn-info" onclick="cadastarDocumento(${Gasto[i]['ID']}, '${Gasto[i]['DESCRICAO']}')"><i class="fas fa-file-alt"></i></button>\
                                <button class="btn btn-sm btn-danger" onclick="inativarGasto(${Gasto[i]['ID']})"><i class="fas fa-trash"></i></button>\
                            </center>\
                        </td>\                      
                    </tr>`;

                totalCPG = totalCPG + Gasto[i]['VALOR'];
            }

            htmlGasto += `
                    <tr id="tableRowTOTAL">
                        <td class="tdTexto"><center>Total:</center></td>
                        <td class="tdTexto"><center><span class="badge bg-success">${mascaraFinanceira(totalCPG)}</span></center></td>
                        <td class="tdTexto"><center></center></td>
                        <td></td>\                      
                    </tr>`;
            $('#tableBodyDadosGasto').html(htmlGasto)
        }

        function cadastrarGasto(){
            $('#modal-gasto').modal('show');

            $('#inputGastoDesc').val('');
            $('#inputObservacaoGasto').val('');
            $('#inputDataGasto').val(moment().format('YYYY-MM-DD'));
            $('#inputValorGasto').val(mascaraFinanceira('0'));
            $('#inputCodGasto').val('0');
        }

        function fecharCadastroDocumento(){
            $('#modal-documentacao').modal('hide');
        }

        function inserirGasto(){
            validacao = true;

            var inputIDs = ['inputGastoDesc', 'inputDataGasto', 'inputValorGasto'];

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

            if(validacao){
                valorGasto = $('#inputValorGasto').val();
                valorGasto = valorGasto.replace('.', '').replace('R$','').replace(' ','').replace(',', '.');

                if($('#inputCodGasto').val() == null || $('#inputCodGasto').val() == 0){                    
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'valor': valorGasto,
                        'data': $('#inputDataGasto').val(),
                        'descricao': $('#inputGastoDesc').val(),
                        'observacao': $('#inputObservacaoGasto').val(),
                        },
                        url:"{{route('despesa.inserir')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-gasto').modal('hide');
                            buscarGastos();

                            Swal.fire(
                                'Sucesso!',
                                'Gasto inserido com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                } else {
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'valor': valorGasto,
                        'data': $('#inputDataGasto').val(),
                        'descricao': $('#inputGastoDesc').val(),
                        'observacao': $('#inputObservacaoGasto').val(),
                        'idCodigo': $('#inputCodGasto').val(),
                        },
                        url:"{{route('despesa.alterar')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-gasto').modal('hide');
                            buscarGastos();

                            Swal.fire(
                                'Sucesso!',
                                'Gasto alterado com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            }
            
        }

        function editarGasto(idGasto){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'idGasto': idGasto,
                },
                url:"{{route('despesa.buscar')}}",
                success:function(r){
                    $('#inputGastoDesc').val(r[0]['DESCRICAO']);
                    $('#inputObservacaoGasto').val(r[0]['OBSERVACAO']);
                    $('#inputDataGasto').val(moment(r[0]['DATA']).format('YYYY-MM-DD'));
                    $('#inputValorGasto').val(mascaraFinanceira(r[0]['VALOR']));
                    $('#inputCodGasto').val(idGasto);

                    $('#modal-gasto').modal('show');
                },
                error:err=>{exibirErro(err)}
            })
        }      

        function inativarGasto(idGasto, placa){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar o gasto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'idGasto': idGasto
                        },
                        url:"{{route('despesa.inativar')}}",
                        success:function(r){
                            Swal.fire('Sucesso!'
                                    , 'Gasto inativado com sucesso.'
                                    , 'success');
                            buscarGastos();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        // DOCUMENTOS
            function buscarDocumentos(){
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                        '_token':'{{csrf_token()}}',                    
                        'idDespesa': $('#inputIDDespesa').val(),
                    },
                    url:"{{route('despesa.buscar.documento')}}",
                    success:function(r){
                        popularListaDocumentos(r);
                    },
                    error:err=>{exibirErro(err)}
                })
            }  

            function cadastarDocumento(idGasto, descricaoGasto){
                $('#titleDocumento').text(idGasto +' - '+descricaoGasto);
                $('#inputIDDespesa').val(idGasto);

                buscarDocumentos();

                $('#modal-documentacao').modal('show');
            }  

            function inativarDocumento(idDocumento){
                Swal.fire({
                    title: 'Confirmação',
                    text: 'Deseja inativar o documento?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type:'post',
                            datatype:'json',
                            data:{
                                '_token':'{{csrf_token()}}',
                                'idDocumento': idDocumento
                            },
                            url:"{{route('despesa.inativar.documento')}}",
                            success:function(r){
                                Swal.fire('Sucesso!'
                                        , 'Documento inativado com sucesso.'
                                        , 'success');
                                buscarDocumentos();
                            },
                            error:err=>{exibirErro(err)}
                        })
                    }
                });
            }

            function verDocumento(caminhoDocumento){
                url = "{{env('APP_URL')}}/"+caminhoDocumento;

                window.open(url, '_blank');
            }

            function popularListaDocumentos(Documento){
                var htmlDocumento = "";

                for(i=0; i< Documento.length; i++){
                    var DocumentoKeys = Object.keys(Documento[i]);
                    for(j=0;j<DocumentoKeys.length;j++){
                        if(Documento[i][DocumentoKeys[j]] == null){
                            Documento[i][DocumentoKeys[j]] = "";
                            }
                        }
                    
                    documentoCaminho = Documento[i]['CAMINHO_DOCUMENTO'];
                    documentoCaminho = documentoCaminho.split('/')[3];
                    
                    htmlDocumento += `
                        <tr id="tableRow${Documento[i]['ID']}">
                            <td class="tdTexto">${Documento[i]['DESCRICAO_DESPESA']}</td>
                            <td class="tdTexto"><span style="text-decoration: underline; cursor: pointer;" onclick="verDocumento('${Documento[i]['CAMINHO_DOCUMENTO']}')">${documentoCaminho}</span></td>
                                <td>\
                                    <center>\
                                    <button class="btn" onclick="inativarDocumento(${Documento[i]['ID']})"><i class="fas fa-trash"></i></button>\
                                    </center>\
                                </td>\                      
                            </tr>`;
                    }
                $('#tableBodyDocumentos').html(htmlDocumento)
            }  

            function validaDocumento(){
                if ($("#inputArquivoDocumentacao")[0].files.length > 0) {
                    $('#labelInputArquivoDocumentacao').html($("#inputArquivoDocumentacao")[0].files[0].name);
                } else {
                    $('#labelInputArquivoDocumentacao').html('Selecionar Arquivos');
                }
            }

            function salvarDocumento(){
                if($("#inputArquivoDocumentacao")[0].files.length > 0){
                    uploadArquivo();
                } else {
                    Swal.fire('Atenção!'
                            , 'Selecione um documento para vincular à venda.'
                            , 'error');
                }
            }

            function uploadArquivo(){
                var dataAnexo = new FormData();
                anexoCaminho = "";
                idGasto = $('#inputIDDespesa').val();
                dataAnexo.append('meuArquivo', document.getElementById('inputArquivoDocumentacao').files[0]);
                dataAnexo.append('ID', idGasto);

                $.ajax({
                    processData: false,
                    contentType: false,
                    type : 'POST',
                    data : dataAnexo,
                    url : "{{env('APP_URL')}}/salvarDocumentacao.php",
                    success : function(resultUpload) {
                        if(resultUpload != "error"){
                            anexoCaminho = resultUpload;
                            $.ajax({
                                type:'post',
                                datatype:'json',
                                data:{
                                    '_token':'{{csrf_token()}}',
                                    'caminhoArquivo': resultUpload,
                                    'idGasto': idGasto,
                                    'caminho': anexoCaminho
                                },
                                url:"{{route('despesa.inserir.documento')}}",
                                success:function(resultInsert){

                                    buscarDocumentos();                                
                                    $("#inputArquivoDocumentacao").val('');
                                    validaDocumento();

                                    Swal.fire('Sucesso!'
                                            , 'Documento salvo com sucesso.'
                                            , 'success');
                                },
                                error:err=>{exibirErro(err)}
                            })  
                        }else{
                            console.log(r)
                            Swal.fire(
                                'Atenção!',
                                'Erro ao enviar Anexo.',
                                'error'
                            )
                        }
                    },
                    error: err=>{exibirErro(err)}
                });
            }
        // FIM

        $('#btnConfirmarGasto').click(() => {
            inserirGasto();
        });

        $('#btnNovoGasto').click(() => {
            cadastrarGasto();
        })

        $('#btnComprovantes').click(() => {
            cadastarDocumento();
        })

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
            console.log(err)
        }
        
        $(document).ready(() => {
            buscarGastos();

            $('#inputFiltroDataInicio').val(moment().subtract(30, 'days').format('YYYY-MM-DD'))
        })
    </script>
@stop