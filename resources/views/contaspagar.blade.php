@extends('adminlte::page')

@section('title', 'Contas a Pagar')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Contas a Pagar</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <button class="col-md-4 btn btn-block btn-warning" id="btnNovoCPG">
                    <i class="fas fa-barcode"></i>
                    <span class="ml-1">Nova</span>
                </button>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="form-group col-12 d-flex row">
                        <input type="text" class="form-control col-12 col-md-4" id="inputFiltroConta" placeholder="Descrição da Conta" maxlength="120" onkeyup="buscarCPG()">
                        
                        <div class="col-12 col-md-4 d-flex justify-content-between row m-0 p-0">
                            <select class="form-control col-12" id="selectFiltroSituacao" onchange="buscarCPG()">
                                <option value="T">Todas as Situações</option>
                                <option value="PAGA">Apenas Pagos</option>
                                <option value="PENDENTE">Apenas Pendentes</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4 d-flex justify-content-between row m-0 p-0">
                            <input type="date" class="form-control col-12 col-md-5" id="inputFiltroDataInicio" onchange="buscarCPG()">
                            <label class="col-12 col-md-2">Até</label>
                            <input type="date" class="form-control col-12 col-md-5" id="inputFiltroDataFim" onchange="buscarCPG()">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-md">
                    <thead>
                        <tr>
                            <th>Conta</th>
                            <th><center>Valor</center></th>
                            <th><center>Data de vencimento</center></th>
                            <th><center>Data de Pagamento</center></th>
                            <th><center>Situação</center></th>
                            <th><center>Ações</center></th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyDadosCPG">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-cpg">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Conta a Pagar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="form-group col-12">
                            <label>Conta</label>
                            <input type="hidden" class="form-control col-3" id="inputCodCPG">
                            <input type="text" class="form-control col-12" id="inputDescricaoCPG" >
                        </div>
                        
                        <div class="form-group col-xs-6 col-md-2">
                            <label>Data Vencimento</label>
                            <input type="date"  class="form-control" id="inputDataVencimentoCPG">
                        </div>

                        <div class="form-group col-xs-2 col-md-2">
                            <label>Valor Conta</label>
                            <input type="text" inputmode="numeric" class="form-control" id="inputValorCPG">
                        </div>
                        
                        <div class="form-group col-12">
                            <label>Observação</label>
                            <textarea maxlength="150" class="form-control" id="inputObservacaoCPG"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarCPG">Confirmar</button>
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
                            <table class="table table-responsive">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $('#inputValorCPG').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' });

        function buscarCPG(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'filtro': $('#inputFiltroConta').val(),
                    'filtroSituacao': $('#selectFiltroSituacao').val(),
                    'dataInicio': $('#inputFiltroDataInicio').val(),
                    'dataTermino': $('#inputFiltroDataFim').val(),
                },
                url:"{{route('contaspagar.buscar')}}",
                success:function(r){
                    popularListaCPG(r);
                },
                error:err=>{exibirErro(err)}
            })
        }    

        function cadastrarCPG(){
            $('#modal-cpg').modal('show');

            $('#inputDescricaoCPG').val('');
            $('#inputObservacaoCPG').val('');
            $('#inputDataVencimentoCPG').val(moment().format('YYYY-MM-DD'));
            $('#inputValorCPG').val(mascaraFinanceira('0'));
            $('#inputCodCPG').val('0');
        }

        function fecharCadastroDocumento(){
            $('#modal-documentacao').modal('hide');
        }

        function inserirCPG(){
            validacao = true;

            var inputIDs = ['inputDescricaoCPG', 'inputDataVencimentoCPG', 'inputValorCPG'];

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
                valorCPG = $('#inputValorCPG').val();
                valorCPG = valorCPG.replace('.', '').replace('R$','').replace(' ','').replace(',', '.');

                if($('#inputCodCPG').val() == null || $('#inputCodCPG').val() == 0){                    
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'valor': valorCPG,
                        'dataVencimento': $('#inputDataVencimentoCPG').val(),
                        'descricao': $('#inputDescricaoCPG').val(),
                        'observacao': $('#inputObservacaoCPG').val(),
                        },
                        url:"{{route('contaspagar.inserir')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-cpg').modal('hide');
                            buscarCPG();

                            Swal.fire(
                                'Sucesso!',
                                'CPG inserido com sucesso.',
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
                        'valor': valorCPG,
                        'dataVencimento': $('#inputDataVencimentoCPG').val(),
                        'descricao': $('#inputDescricaoCPG').val(),
                        'observacao': $('#inputObservacaoCPG').val(),
                        'idCodigo': $('#inputCodCPG').val(),
                        },
                        url:"{{route('contaspagar.alterar')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-cpg').modal('hide');
                            buscarCPG();

                            Swal.fire(
                                'Sucesso!',
                                'CPG alterado com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            }
            
        }

        function inserirPagamento(idCPG, descricaoCPG){
            var dataAtual = moment().format('YYYY-MM-DD');
            Swal.fire({
                title: 'Deseja realmente confirmar o pagamento '+descricaoCPG+'?',
                html: ' <div class="form-group">\
                            <input type="date" class="form-control" id="inputDataPagamento" >\
                        </div>',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                onOpen: () => {
                    $('#inputDataPagamento').val(dataAtual)
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const dataPagamento = $('#inputDataPagamento').val();
                    if (dataPagamento !== '') {
                        $.ajax({
                            type:'post',
                            datatype:'json',
                            data:{
                                '_token':'{{csrf_token()}}',
                                'dataPagamento': dataPagamento,
                                'idCPG': idCPG
                            },
                            url:"{{route('contaspagar.inserir.pagamento')}}",
                            success:function(r){
                                swal.fire('Sucesso!', 'Data de Pagamento inserida com sucesso.', 'success')
                                buscarCPG();
                            },
                            error:err=>{exibirErro(err)}
                        })
                    } else {
                        Swal.fire('Selecione uma data válida!');
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Pagamento cancelado');
                    // Ações se o usuário cancelar
                }
            });
        }

        function editarCPG(idCPG){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'idCPG': idCPG,
                },
                url:"{{route('contaspagar.buscar')}}",
                success:function(r){
                    $('#inputDescricaoCPG').val(r[0]['DESCRICAO']);
                    $('#inputObservacaoCPG').val(r[0]['OBSERVACAO']);
                    $('#inputDataVencimentoCPG').val(moment(r[0]['DATA_VENCIMENTO']).format('YYYY-MM-DD'));
                    $('#inputValorCPG').val(mascaraFinanceira(r[0]['VALOR']));
                    $('#inputCodCPG').val(idCPG);

                    $('#modal-cpg').modal('show');
                },
                error:err=>{exibirErro(err)}
            })
        }

        function inativarCPG(idCPG, placa){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar o CPG?',
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
                            'idCPG': idCPG
                        },
                        url:"{{route('contaspagar.inativar')}}",
                        success:function(r){
                            Swal.fire('Sucesso!'
                                    , 'CPG inativado com sucesso.'
                                    , 'success');
                            buscarCPG();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }        

        function popularListaCPG(CPG){
            var htmlCPG = "";
            var totalValorPago = 0;
            var totalValorAberto = 0;

            for(i=0; i< CPG.length; i++){
                var CPGKeys = Object.keys(CPG[i]);
                for(j=0;j<CPGKeys.length;j++){
                    if(CPG[i][CPGKeys[j]] == null){
                        CPG[i][CPGKeys[j]] = "";
                    }
                }
                var SituacaoCPG = "";

                if(CPG[i]['SITUACAO'] != 'PAGA'){
                    totalValorAberto = totalValorAberto + CPG[i]['VALOR'];
                    SituacaoCPG = `<span class="badge bg-danger">${CPG[i]['SITUACAO']}</span>`;
                } else {
                    totalValorPago = totalValorPago + CPG[i]['VALOR'] ;
                    SituacaoCPG = `<span class="badge bg-success"> ${CPG[i]['SITUACAO']}</span>`;
                }
                htmlCPG += `
                    <tr id="tableRow${CPG[i]['ID']}">
                        <td class="tdTexto">${CPG[i]['DESCRICAO']}</td>
                        <td class="tdTexto"><center>${mascaraFinanceira(CPG[i]['VALOR'])}</center></td>
                        <td class="tdTexto"><center>${moment(CPG[i]['DATA_VENCIMENTO']).format('DD/MM/YYYY')}</center></td>
                        <td class="tdTexto"><center>${(CPG[i]['DATA_PAGAMENTO'].length == 0 ? '-' : moment(CPG[i]['DATA_PAGAMENTO']).format('DD/MM/YYYY'))}</center></td>
                        <td class="tdTexto"><center>${SituacaoCPG}</center></td>
                        <td>\
                            <center>\
                                <button class="btn btn-sm btn-warning" onclick="editarCPG(${CPG[i]['ID']})"><i class="fas fa-pen"></i></button>\
                                <button class="btn btn-sm btn-success" onclick="inserirPagamento(${CPG[i]['ID']}, '${CPG[i]['DESCRICAO']}')"><i class="fas fa-check"></i></button>\
                                <button class="btn btn-sm btn-info" onclick="cadastarDocumento(${CPG[i]['ID']}, '${CPG[i]['DESCRICAO']}')"><i class="fas fa-file-alt"></i></button>\
                                <button class="btn btn-sm btn-danger" onclick="inativarCPG(${CPG[i]['ID']})"><i class="fas fa-trash"></i></button>\
                            </center>\
                        </td>\                      
                    </tr>`;
            }

            htmlCPG += `
                    <tr id="tableRowTOTAL">
                        <td class="tdTexto">Total Pago</td>
                        <td class="tdTexto"><center><span class="badge bg-success">${mascaraFinanceira(totalValorPago)}</span></center></td>
                        <td class="tdTexto"><center></center></td>
                        <td class="tdTexto"><center>Total Em Aberto</center></td>
                        <td class="tdTexto"><center><span class="badge bg-danger">${mascaraFinanceira(totalValorAberto)}</span></center></td>
                        <td></td>\                      
                    </tr>`;
            $('#tableBodyDadosCPG').html(htmlCPG)
        }
        
        // DOCUMENTOS
            function buscarDocumentos(){
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                        '_token':'{{csrf_token()}}',                    
                        'idConta': $('#inputIDConta').val(),
                    },
                    url:"{{route('contaspagar.buscar.documento')}}",
                    success:function(r){
                        popularListaDocumentos(r);
                    },
                    error:err=>{exibirErro(err)}
                })
            }  

            function cadastarDocumento(idCPG, descricaoCPG){
                $('#titleDocumento').text(idCPG +' - '+descricaoCPG);
                $('#inputIDConta').val(idCPG);

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
                            url:"{{route('contaspagar.inativar.documento')}}",
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
                            <td class="tdTexto">${Documento[i]['DESCRICAO_CPG']}</td>
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
                idCPG = $('#inputIDConta').val();
                dataAnexo.append('meuArquivo', document.getElementById('inputArquivoDocumentacao').files[0]);
                dataAnexo.append('ID', idCPG);

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
                                    'idCPG': idCPG,
                                    'caminho': anexoCaminho
                                },
                                url:"{{route('contaspagar.inserir.documento')}}",
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

        $('#btnConfirmarCPG').click(() => {
            inserirCPG();
        });

        $('#btnNovoCPG').click(() => {
            cadastrarCPG();
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
            buscarCPG();

            $('#inputFiltroDataInicio').val(moment().subtract(30, 'days').format('YYYY-MM-DD'))
        })
    </script>
@stop