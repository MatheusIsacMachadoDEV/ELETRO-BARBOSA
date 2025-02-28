@extends('adminlte::page')

@section('title', 'Venda')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Venda</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <button class="col-md-4 btn btn-block btn-warning" id="btnNovaVenda">
                    <i class="fas fa-money-check-alt"></i>
                    <span class="ml-1">Nova</span>
                </button>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="form-group col-12 d-flex">
                        <input type="text" class="form-control" id="inputPlacaBuscar" placeholder="Placa" maxlength="8" onkeyup="buscarVenda()">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-md">
                    <thead>
                        <tr>
                            <th>Placa</th>
                            <th>Data de Venda</th>
                            <th>Valor de Venda</th>
                            <th><center>Comprador</center></th>
                            <th><center>Vendedor</center></th>
                            <th><center>Ações</center></th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyDadosVenda">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-venda">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Venda de Veículo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="form-group col-12">
                            <label>Veículo</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-3" id="inputPlaca">
                                <input type="text" class="form-control col-9" id="inputVeiculoDesc" disabled>
                            </div>
                            <button id="btnLimparPlaca" class="btn btn-default d-none">LIMPAR</button>
                        </div>
                        <div class="form-group col-12">
                            <label>Vendedor</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-9" id="inputVendedor">
                                <input type="hidden" class="form-control col-9" id="inputVendedorID">
                                <input type="text" class="form-control col-3" id="inputVendedorDocumento" disabled>
                            </div>
                            <button id="btnLimparVendedor" class="btn btn-default d-none">LIMPAR</button>
                        </div>
                        <div class="form-group col-12">
                            <label>Comprador</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-9" id="inputComprador">
                                <input type="hidden" class="form-control col-9" id="inputCompradorID">
                                <input type="text" class="form-control col-3" id="inputCompradorDocumento" disabled>
                            </div>
                            <button id="btnLimparComprador" class="btn btn-default d-none">LIMPAR</button>
                        </div>
                        <div class="form-group col-xs-6 col-md-2">
                            <label>Data Venda</label>
                            <input type="date"  class="form-control" id="inputDataVenda">
                        </div>
                        <div class="form-group col-xs-2 col-md-2">
                            <label>Valor Venda</label>
                            <input type="text" class="form-control" id="inputValorVenda">
                        </div>
                        <div class="form-group col-12 d-none">
                            <button class="btn btn-success" id="btnComprovantes">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <span>Comprovantes</span>
                            </button>
                        </div>
                        <div class="form-group col-12">
                            <label>Forma de Pagamento</label>
                            <textarea maxlength="150" class="form-control" id="inputPagamento"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmar">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog ">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Documentação da Venda <span id="titleDocumento"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="">
                                <div class="">
                                    <input type="hidden" id="inputPlacaDocumentacao">
                                    <input type="hidden" id="inputIDVendaDocumentacao">
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
                                        <th>Veiculo</th>
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

        .ui-autocomplete{
            z-index: 1050;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $('#inputValorVenda').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' });

        function cadastarVenda(){
            $('#modal-venda').modal('show');
            $('#btnConfirmar').removeClass('d-none');
        }

        function cadastarDocumento(idVenda, placa){
            $('#titleDocumento').text(idVenda +' - '+placa);
            $('#inputPlacaDocumentacao').val(placa);
            $('#inputIDVendaDocumentacao').val(idVenda);

            buscarDocumentos();

            $('#modal-documentacao').modal('show');
        }

        function fecharCadastroDocumento(){
            $('#modal-documentacao').modal('hide');
        }        

        function limparPlaca(){
            $('#inputPlaca').val('');
            $('#btnLimparPlaca').addClass('d-none');
            $('#inputPlaca').attr('disabled', false); 
            $('#inputVeiculoDesc').val('');
        }     

        function limparComprador(){
            $('#inputComprador').val('');
            $('#btnLimparComprador').addClass('d-none');
            $('#inputComprador').attr('disabled', false); 
            $('#inputCompradorDocumento').val('') ;
            $('#inputCompradorID').val(0);
        }     

        function limparVendedor(){
            $('#inputVendedor').val('');
            $('#btnLimparVendedor').addClass('d-none');
            $('#inputVendedor').attr('disabled', false); 
            $('#inputVendedorDocumento').val('');
            $('#inputVendedorID').val(0);
        }

        function inserirVenda() {
            validacao = true;

            var inputIDs = ['inputVendedor', 'inputVendedorDocumento', 'inputPlaca', 'inputVeiculoDesc','inputComprador', 'inputCompradorDocumento', 'inputDataVenda', 'inputValorVenda'];

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

            if($('#inputVendedorID').val() == 0 || $('#inputVendedorID').val() == null){
                $('#inputVendedorID').addClass('is-invalid');
            } else {
                $('#inputVendedorID').removeClass('is-invalid');
            }

            if($('#inputCompradorID').val() == 0 || $('#inputCompradorID').val() == null){
                $('#inputCompradorID').addClass('is-invalid');
            } else {
                $('#inputCompradorID').removeClass('is-invalid');
            }

            if(validacao){
                valorVenda = $('#inputValorVenda').val();
                valorVenda = valorVenda.replace('.', '').replace('R$','').replace(' ','').replace(',', '.');

                // if(inserindoVeiculo){                    
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'placa': $('#inputPlaca').val(),
                        'idComprador': $('#inputCompradorID').val(),
                        'idVendedor': $('#inputVendedorID').val(),
                        'dataVenda': $('#inputDataVenda').val(),
                        'valorVenda': valorVenda,
                        'pagamento': $('#inputPagamento').val(),
                        },
                        url:"{{route('venda.inserir')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-venda').modal('hide');
                            buscarVenda();

                            Swal.fire(
                                'Sucesso!',
                                'Venda inserida com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                // } else {
                //     $.ajax({
                //         type:'post',
                //         datatype:'json',
                //         data:{
                //         '_token':'{{csrf_token()}}',
                //         'modelo': $('#inputModelo').val(),
                //         'renavam': $('#inputRenavam').val(),
                //         'chassis': $('#inputChassis').val(),
                //         'cor': $('#inputCor').val(),
                //         'ano': $('#inputAno').val(),
                //         'km': $('#inputKM').val(),
                //         'dataCompra': $('#inputDataCompra').val(),
                //         'valorCompra': valorCompra,
                //         'ID': $('#inputID').val()
                //         },
                //         url:"{{route('veiculo.alterar')}}",
                //         success:function(r){
                //             console.log(r);
                //             $('#modal-cadastro').modal('hide');
                //             buscarVeiculo();

                //             Swal.fire(
                //                 'Sucesso!',
                //                 'Veículo alterado com sucesso.',
                //                 'success',
                //             )
                //         },
                //         error:err=>{exibirErro(err)}
                //     })
                // }
            }
            
        }

        function inativarVenda(idVenda, placa){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar a venda?',
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
                            'idVenda': idVenda,
                            'placa': placa
                        },
                        url:"{{route('venda.inativar')}}",
                        success:function(r){
                            Swal.fire('Sucesso!'
                                    , 'Venda inativada com sucesso.'
                                    , 'success');
                            buscarVenda();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
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
                        url:"{{route('venda.documento.inativar')}}",
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

        function verVenda(idVenda){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'placa': '',
                    'idVenda': idVenda,
                },
                url:"{{route('venda.buscar')}}",
                success:function(r){
                    $('#inputPlaca').val(r[0]['PLACA']);
                    $('#inputVeiculoDesc').val(r[0]['MODELO']);
                    $('#inputPlaca').prop('disabled', true);
                    $('#inputVendedor').val(r[0]['VENDEDOR']);
                    $('#inputVendedor').prop('disabled', true);
                    $('#inputVendedorDocumento').val(mascaraDocumento(r[0]['VENDEDOR_DOCUMENTO']));
                    $('#inputComprador').val(r[0]['COMPRADOR']);
                    $('#inputComprador').prop('disabled', true);
                    $('#inputCompradorDocumento').val(mascaraDocumento(r[0]['COMPRADOR_DOCUMENTO']));
                    $('#inputDataVenda').val(r[0]['DATA']);
                    $('#inputDataVenda').prop('disabled', true);
                    $('#inputValorVenda').val(mascaraFinanceira(r[0]['VALOR']));
                    $('#inputValorVenda').prop('disabled', true);
                    $('#inputPagamento').val(r[0]['PAGAMENTO']);
                    $('#inputPagamento').prop('disabled', true);

                    
                    $('#btnLimparComprador').addClass('d-none');
                    $('#btnLimparVendedor').addClass('d-none');
                    $('#btnLimparPlaca').addClass('d-none');

                    $('#btnConfirmar').addClass('d-none');                    

                    $('#modal-venda').modal('show');
                },
                error:err=>{exibirErro(err)}
            })
        }

        function verDocumento(caminhoDocumento){
            url = "{{env('APP_URL')}}/"+caminhoDocumento;

            window.open(url, '_blank');
        }

        function buscarVenda(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'placa': $('#inputPlacaBuscar').val(),
                },
                url:"{{route('venda.buscar')}}",
                success:function(r){
                    popularListaVenda(r);
                },
                error:err=>{exibirErro(err)}
            })
        }    

        function buscarDocumentos(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',                    
                    'idVenda': $('#inputIDVendaDocumentacao').val(),
                },
                url:"{{route('venda.buscar.documentos')}}",
                success:function(r){
                    popularListaDocumentos(r);
                },
                error:err=>{exibirErro(err)}
            })
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
                        <td class="tdTexto">${Documento[i]['PLACA']}</td>
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

        function popularListaVenda(Venda){
            var htmlVenda = "";

            for(i=0; i< Venda.length; i++){
                var VendaKeys = Object.keys(Venda[i]);
                for(j=0;j<VendaKeys.length;j++){
                    if(Venda[i][VendaKeys[j]] == null){
                        Venda[i][VendaKeys[j]] = "";
                    }
                }
                htmlVenda += `
                    <tr id="tableRow${Venda[i]['ID']}">
                        <td class="tdTexto">${Venda[i]['PLACA']}</td>
                        <td class="tdTexto">${moment(Venda[i]['DATA']).format('DD/MM/YYYY')}</td>
                        <td class="tdTexto">${mascaraFinanceira(Venda[i]['VALOR'])}</td>
                        <td class="tdTexto"><center>${Venda[i]['COMPRADOR']}</center></td>
                        <td class="tdTexto"><center>${Venda[i]['VENDEDOR']}</center></td>
                        <td>\
                            <center>\
                                <button class="btn" onclick="cadastarDocumento(${Venda[i]['ID']}, '${Venda[i]['PLACA']}')"><i class="fas fa-file-alt"></i></button>\
                                <button class="btn" onclick="verVenda(${Venda[i]['ID']})"><i class="fas fa-eye"></i></button>\
                                <button class="btn" onclick="inativarVenda(${Venda[i]['ID']}, '${Venda[i]['PLACA']}')"><i class="fas fa-trash"></i></button>\
                            </center>\
                        </td>\                      
                    </tr>`;
            }
            $('#tableBodyDadosVenda').html(htmlVenda)
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
            idVenda = $('#inputIDVendaDocumentacao').val();
            placa = $('#inputPlacaDocumentacao').val();
            dataAnexo.append('meuArquivo', document.getElementById('inputArquivoDocumentacao').files[0]);
            dataAnexo.append('ID', idVenda);

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
                                'idVenda': idVenda,
                                'placa': placa,
                                'caminho': anexoCaminho
                            },
                            url:"{{route('venda.inserir.documento')}}",
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

        $("#inputPlaca").autocomplete({
            source: function(request, cb){
                param = request.term;
                campoBuscado = param;
                $.ajax({
                    url:"{{route('venda.buscar.veiculo')}}",
                    method: 'post',
                    data:{
                        '_token': '{{csrf_token()}}',
                        'placa': param,
                        'situacao':''
                    },
                    dataType: 'json',
                    success: function(r){
                        result = $.map(r, function(obj){
                            return {
                                label: obj.info,
                                value: obj.PLACA,
                                data : obj
                            };
                        });
                        cb(result);
                    },
                    error: err=>{
                        console.log(err)
                    }
                });
            },
            select:function(e, selectedData) {
                if (selectedData.item.label != 'Nenhum Veículo Encontrado.'){
                    $('#inputVeiculoDesc').val(selectedData.item.data.MODELO);
                    $('#btnLimparPlaca').removeClass('d-none');
                    $('#inputPlaca').attr('disabled', true); 
                } else {
                    $('#inputVeiculoDesc').val('')
                }
            }
        });

        $("#inputComprador").autocomplete({
            source: function(request, cb){
                param = request.term;
                campoBuscado = param;
                $.ajax({
                    url:"{{route('pessoa.buscar')}}",
                    method: 'post',
                    data:{
                        '_token': '{{csrf_token()}}',
                        'nome': param
                    },
                    dataType: 'json',
                    success: function(r){
                        result = $.map(r, function(obj){
                            return {
                                label: obj.info,
                                value: obj.NOME,
                                data : obj
                            };
                        });
                        cb(result);
                    },
                    error: err=>{
                        console.log(err)
                    }
                });
            },
            select:function(e, selectedData) {
                if (selectedData.item.label != 'Nenhum Veículo Encontrado.'){
                    $('#inputCompradorDocumento').val(mascaraDocumento(selectedData.item.data.DOCUMENTO));
                    $('#inputCompradorID').val(selectedData.item.data.ID);
                    $('#btnLimparComprador').removeClass('d-none');
                    $('#inputComprador').attr('disabled', true); 
                } else {
                    $('#inputCompradorDocumento').val('')
                }
            }
        });        

        $("#inputVendedor").autocomplete({
            source: function(request, cb){
                param = request.term;
                campoBuscado = param;
                $.ajax({
                    url:"{{route('pessoa.buscar')}}",
                    method: 'post',
                    data:{
                        '_token': '{{csrf_token()}}',
                        'nome': param
                    },
                    dataType: 'json',
                    success: function(r){
                        result = $.map(r, function(obj){
                            return {
                                label: obj.info,
                                value: obj.NOME,
                                data : obj
                            };
                        });
                        cb(result);
                    },
                    error: err=>{
                        console.log(err)
                    }
                });
            },
            select:function(e, selectedData) {
                $('#inputVendedorDocumento').val(mascaraDocumento(selectedData.item.data.DOCUMENTO));
                $('#inputVendedorID').val(selectedData.item.data.ID);
                $('#btnLimparVendedor').removeClass('d-none');
                $('#inputVendedor').attr('disabled', true);                
            }
        });

        $('#btnLimparPlaca').click(() => {
            limparPlaca();
        });

        $('#btnLimparVendedor').click(() => {
            limparVendedor();
        });

        $('#btnLimparComprador').click(() => {
            limparComprador();
        });

        $('#btnConfirmar').click(() => {
            inserirVenda();
        });

        $('#btnNovaVenda').click(() => {
            cadastarVenda();
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
            buscarVenda();

            $('#modal-venda').on('hidden.bs.modal', function () {
                $('#inputPlaca').val('');
                $('#inputPlaca').prop('disabled', false);
                $('#inputPlaca').removeClass('is-invalid');
                $('#inputVeiculoDesc').removeClass('is-invalid');
                $('#inputVendedor').val('');
                $('#inputVendedor').removeClass('is-invalid');
                $('#inputVendedor').prop('disabled', false);
                $('#inputVendedorDocumento').val('');
                $('#inputVendedorDocumento').removeClass('is-invalid');
                $('#inputComprador').val('');
                $('#inputComprador').removeClass('is-invalid');
                $('#inputComprador').prop('disabled', false);
                $('#inputCompradorDocumento').val('');
                $('#inputCompradorDocumento').removeClass('is-invalid');
                $('#inputDataVenda').val('');
                $('#inputDataVenda').removeClass('is-invalid');
                $('#inputDataVenda').prop('disabled', false);
                $('#inputValorVenda').val('');
                $('#inputValorVenda').removeClass('is-invalid');
                $('#inputValorVenda').prop('disabled', false);
                $('#inputPagamento').val('');
                $('#inputPagamento').removeClass('is-invalid');
                $('#inputPagamento').prop('disabled', false);
                    
                $('#btnLimparComprador').addClass('d-none');
                $('#btnLimparVendedor').addClass('d-none');
                $('#btnLimparPlaca').addClass('d-none');

                buscarVenda();
            });
        })
    </script>
@stop