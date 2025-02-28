@extends('adminlte::page')

@section('title', 'Pessoas')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Pessoas</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <button class="col-md-4 btn btn-block btn-warning" id="btnNovoVeiculo">
                    <i class="fas fa-user-edit"></i>
                    <span class="ml-1">Nova</span>
                </button>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Nome" id="inputNomeFiltro" onkeyup="buscarPessoa()">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CPF/CNPJ</th>
                            <th>Contato</th>
                            <th>Email</th>
                            <th>Valor em Aberto</th>
                            <th><center>Ações<center></th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyDadosPessoa">
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
                            <input type="text" class="form-control col-12" id="inputNome">
                            <input type="hidden" class="form-control col-4" id="inputIDPessoa">
                        </div>
                        <div class="form-group col-6">
                            <label>CPF/CNPJ</label>
                            <input type="text" class="form-control" maxlength="18" id="inputDocumento" oninput="mascararDocumento(this)">
                        </div>
                        <div class="form-group col-6">
                            <label>Telefone</label> 
                            <input type="text" class="form-control" maxlength="16" id="inputTelefone" oninput="mascaraTelefone(this)">
                        </div>
                        <div class="form-group col-12">
                            <label>Email</label>
                            <input type="text" class="form-control" maxlength="48" id="inputEmail">
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <label>Data de Nascimento</label>
                            <input type="date"  class="form-control" id="inputDataNascimento">
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
                    <h5 class="modal-title" >Documentação da Pessoa <span id="titleDocumento"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="">
                                <div class="">
                                    <input type="hidden" id="inputIDPessoaDocumento">
                                    <div class="input-control col-12">
                                        <input class="input-control" type="checkbox" id="checkDocPendente" onchange="validaDocPendente()">
                                        <label>Documento Pendente?</label>
                                    </div>
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
                                        <th>Pessoa</th>
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

    <div class="modal fade" id="modal-itens-venda">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title">Vendas Fiado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th><center>Data</center></th>
                                        <th><center>Valor Total</center></th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyItemVenda">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" >Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-venda-fiado-pagamento">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title">Pagamentos de Fiado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-12 row d-flex justify-content-end">
                                <button class="col-md-4 btn btn-block btn-warning" id="btnNovoPagamentoFiado">
                                    <i class="fas fa-dollar-sign"></i>
                                    <span class="ml-1">Novo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th><center>Data do Pagamento</center></th>
                                        <th><center>Valor Pagamento</center></th>
                                        <th><center>Ações</center></th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyPagamentoFiado">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" >Fechar</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        inserindoPessoa = false;
        idUsuarioPagamentoFiado = 0;
        
        function cadastrarPessoa(){
            inserindoPessoa = true;

            $('#inputNome').val('');
            $('#inputDocumento').val('');
            $('#inputTelefone').val('');
            $('#inputDataNascimento').val('');
            $('#inputEmail').val('');

            $('#modal-cadastro').modal('show');
        }        

        function inserirPessoa() {
            validacao = true;

            var inputIDs = ['inputNome', 'inputTelefone'];

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

                if(inserindoPessoa){                    
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'nome': $('#inputNome').val(),
                        'documento': $('#inputDocumento').val().replace('.','').replace('.', '').replace('-','').replace('/', ''),
                        'telefone': $('#inputTelefone').val().replace(new RegExp(' ', 'g'), '').replace(')', '').replace('(', '').replace('-', ''),
                        'email': $('#inputEmail').val(),
                        'data_nascimento': $('#inputDataNascimento').val()
                        },
                        url:"{{route('pessoa.inserir')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-cadastro').modal('hide');

                            buscarPessoa();
                            Swal.fire(
                                'Sucesso!',
                                'Pessoa inserida com sucesso.',
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
                        'id': $('#inputIDPessoa').val(),
                        'nome': $('#inputNome').val(),
                        'documento': $('#inputDocumento').val().replace('.', '').replace('.','').replace('-','').replace('/', ''),
                        'telefone': $('#inputTelefone').val().replace(new RegExp(' ', 'g'), '').replace(')', '').replace('(', '').replace('-', ''),
                        'email': $('#inputEmail').val(),
                        'data_nascimento': $('#inputDataNascimento').val()
                        },
                        url:"{{route('pessoa.alterar')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-cadastro').modal('hide');

                            buscarPessoa();
                            Swal.fire(
                                'Sucesso!',
                                'Pessoa alterada com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            }
            
        }

        function cadastarDocumento(ID, nome, docPendente){
            $('#titleDocumento').text(ID +' - '+nome);
            $('#inputIDPessoaDocumento').val(ID);

            if(docPendente == 'S'){
                $('#checkDocPendente').prop('checked', true);
            } else {
                $('#checkDocPendente').prop('checked', false);
            }

            buscarDocumentos();

            $('#modal-documentacao').modal('show');
        }

        function editarPessoa(idPessoa){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                '_token':'{{csrf_token()}}',
                'id': idPessoa,
                'nome': ''
                },
                url:"{{route('pessoa.buscar')}}",
                success:function(r){
                    inserindoPessoa = false;

                    $('#inputIDPessoa').val(r[0]['ID']);
                    $('#inputNome').val(r[0]['NOME']);
                    $('#inputDocumento').val(formatCPForCNPJ(r[0]['DOCUMENTO']));
                    $('#inputTelefone').val(formatarTelefone(r[0]['TELEFONE']));
                    $('#inputEmail').val(r[0]['EMAIL']);
                    $('#inputDataNascimento').val(r[0]['DATA_NASCIMENTO']);

                    $('#modal-cadastro').modal('show')
                },
                error:err=>{exibirErro(err)}
            })
        }

        function inativarPessoa(idPessoa){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar a pessoa?',
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
                        'id': idPessoa
                        },
                        url:"{{route('pessoa.inativar')}}",
                        success:function(r){
                            buscarPessoa();

                            Swal.fire('Sucesso!'
                                    , 'Pessoa inativada com sucesso.'
                                    , 'success');
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        function buscarPessoa(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                '_token':'{{csrf_token()}}',
                'nome': $('#inputNomeFiltro').val()
                },
                url:"{{route('pessoa.buscar')}}",
                success:function(r){
                    popularListaPessoas(r);
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
                    'idPessoa': $('#inputIDPessoaDocumento').val(),
                },
                url:"{{route('pessoa.buscar.documento')}}",
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
                        <td class="tdTexto">${Documento[i]['NOME_PESSOA']}</td>
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

        function popularListaPessoas(Pessoa){
            var htmlPessoa = "";

            for(i=0; i< Pessoa.length; i++){
                var PessoaKeys = Object.keys(Pessoa[i]);
                for(j=0;j<PessoaKeys.length;j++){
                    if(Pessoa[i][PessoaKeys[j]] == null){
                        Pessoa[i][PessoaKeys[j]] = "";
                    }
                }

                if(Pessoa[i]['DOC_PENDENTE'] == 'S'){
                    spanBG = "bg-danger";
                } else {
                    spanBG = "bg-success";
                }

                valorEmAberto = Pessoa[i]['VALOR_FIADO'] - Pessoa[i]['VALOR_PAGAMENTO'];

                if(valorEmAberto == 0){
                    classeBagdeValorAberto = 'bg-warning';
                } else if(valorEmAberto > 0){
                    classeBagdeValorAberto = 'bg-danger';
                } else {
                    classeBagdeValorAberto = 'bg-success';
                }

                htmlPessoa += `
                    <tr id="tableRow${Pessoa[i]['ID']}">
                        <td class="tdTexto">${Pessoa[i]['ID']}</td>
                        <td class="tdTexto">${Pessoa[i]['NOME']}</td>
                        <td class="tdTexto">${formatCPForCNPJ(Pessoa[i]['DOCUMENTO'])}</td>
                        <td class="tdTexto">${formatarTelefone(Pessoa[i]['TELEFONE'].toString())}</td>
                        <td class="tdTexto">${Pessoa[i]['EMAIL']}</td>
                        <td class="tdTexto"><span class="badge ${classeBagdeValorAberto}">${mascaraFinanceira(valorEmAberto)}</span></td>
                        <td>\
                            <center>\
                                <button class="btn" onclick="verVenda(${Pessoa[i]['ID']})"><i class="fas fa-money-check-alt"></i></button>\
                                <button class="btn" onclick="verPagamentos(${Pessoa[i]['ID']})"><i class="fas fa-hand-holding-usd"></i></button>\
                                <button class="btn" onclick="cadastarDocumento(${Pessoa[i]['ID']}, '${Pessoa[i]['NOME']}', '${Pessoa[i]['DOC_PENDENTE']}')"><i class="fas fa-file-alt"></i></button>\
                                <button class="btn" onclick="editarPessoa(${Pessoa[i]['ID']})"><i class="fas fa-pen"></i></button>\
                                <button class="btn" onclick="inativarPessoa(${Pessoa[i]['ID']})"><i class="fas fa-trash"></i></button>\
                            </center>\
                        </td>\                        
                    </tr>`;
            }
            $('#tableBodyDadosPessoa').html(htmlPessoa)
        }

        function popularListaVendaFiado(itens){
            var htmlitensPedido = "";

            for(i=0; i< itens.length; i++){
                var itensPedidoKeys = Object.keys(itens[i]);
                for(j=0;j<itensPedidoKeys.length;j++){
                    if(itens[i][itensPedidoKeys[j]] == null){
                        itens[i][itensPedidoKeys[j]] = "";
                    }
                }

                htmlitensPedido += `
                    <tr id="tableRow">
                        <td class="tdTexto"><center>${moment(itens[i]['DATA']).format('DD/MM/YYYY')}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(itens[i]['VALOR_TOTAL'])}</center></td>                  
                    </tr>`;
            }
            $('#tableBodyItemVenda').html(htmlitensPedido)
        }
        
        function popularListaVendaFiadoPagamento(itens){
            var htmlitensPedido = "";

            for(i=0; i< itens.length; i++){
                var itensPedidoKeys = Object.keys(itens[i]);
                for(j=0;j<itensPedidoKeys.length;j++){
                    if(itens[i][itensPedidoKeys[j]] == null){
                        itens[i][itensPedidoKeys[j]] = "";
                    }
                }

                htmlitensPedido += `
                    <tr id="tableRow">
                        <td class="tdTexto"><center>${moment(itens[i]['DATA']).format('DD/MM/YYYY')}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(itens[i]['VALOR_PAGAMENTO'])}</center></td>                  
                        <td><center><button class="btn" onclick="inativarPagamento(${itens[i]['ID']})"><i class="fas fa-trash"></i></button></center></td>
                    </tr>`;
            }
            $('#tableBodyPagamentoFiado').html(htmlitensPedido)
        }

        function salvarDocumento(){
            if($("#inputArquivoDocumentacao")[0].files.length > 0){
                uploadArquivo();
            } else {
                Swal.fire('Atenção!'
                        , 'Selecione um documento para vincular à pessoa.'
                        , 'error');
            }
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
                        url:"{{route('pessoa.inativar.documento')}}",
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

        function uploadArquivo(){
            var dataAnexo = new FormData();
            anexoCaminho = "";
            idPessoa = $('#inputIDPessoaDocumento').val();
            dataAnexo.append('meuArquivo', document.getElementById('inputArquivoDocumentacao').files[0]);
            dataAnexo.append('ID', idPessoa);

            $.ajax({
                processData: false,
                contentType: false,
                type : 'POST',
                data : dataAnexo,
                url : "{{env('APP_URL')}}/salvarDocumentacaoPessoa.php",
                success : function(resultUpload) {
                    if(resultUpload != "error"){
                        anexoCaminho = resultUpload;
                        $.ajax({
                            type:'post',
                            datatype:'json',
                            data:{
                                '_token':'{{csrf_token()}}',
                                'idPessoa': idPessoa,
                                'caminho': anexoCaminho
                            },
                            url:"{{route('pessoa.inserir.documento')}}",
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

        function validaDocumento(){
            if ($("#inputArquivoDocumentacao")[0].files.length > 0) {
                $('#labelInputArquivoDocumentacao').html($("#inputArquivoDocumentacao")[0].files[0].name);
            } else {
                $('#labelInputArquivoDocumentacao').html('Selecionar Arquivos');
            }
        }

        function validaDocPendente(){
            idPessoa = $('#inputIDPessoaDocumento').val();

            if($('#checkDocPendente').prop('checked')){
                docPendente = 'S';
            } else {
                docPendente = 'N';
            }

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'idPessoa': idPessoa
                },
                url:"{{route('pessoa.alterar.docPendente')}}",
                success:function(r){
                    buscarPessoa();
                    Swal.fire('Sucesso!', 'Pendência ajustada com sucesso.', 'success');
                },
                error:err=>{exibirErro(err)}
            })
        }

        function fecharCadastroDocumento(){
            $('#modal-documentacao').modal('hide');
        }

        function verDocumento(caminhoDocumento){
            url = "{{env('APP_URL')}}/"+caminhoDocumento;

            window.open(url, '_blank');
        }

        function verVenda(idUsuario){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'idUsuario': idUsuario,
                },
                url:"{{route('pdv.buscar.venda.fiado')}}",
                success:function(r){
                    popularListaVendaFiado(r)

                    $('#modal-itens-venda').modal('show');
                },
                error:err=>{exibirErro(err)}
            })
        }

        function verPagamentos(idUsuario){
            idUsuarioPagamentoFiado = idUsuario;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'idUsuario': idUsuario,
                },
                url:"{{route('pdv.buscar.venda.fiado.pagamento')}}",
                success:function(r){
                    popularListaVendaFiadoPagamento(r);

                    $('#modal-venda-fiado-pagamento').modal('show');
                },
                error:err=>{exibirErro(err)}
            })      
        }

        function inativarPagamento(idPagamento){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar o pagamento?',
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
                            'idPagamento': idPagamento
                        },
                        url:"{{route('pdv.inativar.venda.fiado.pagamento')}}",
                        success:function(r){
                            Swal.fire('Sucesso!'
                                    , 'Pagamento inativado com sucesso.'
                                    , 'success');

                            verPagamentos(idUsuarioPagamentoFiado);
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        $('#btnConfirmar').click(() => {
            inserirPessoa();
        });

        $('#btnNovoVeiculo').click(() => {
            cadastrarPessoa();
        })

        function formatCPForCNPJ(document) {
            // Remove caracteres não numéricos
            document = document.replace(/\D/g, '');

            if (document.length === 11) { // Se o documento tem 11 dígitos, é um CPF
                // Adicione a máscara de CPF
                document = document.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, '$1.$2.$3-$4');
            } else if (document.length === 14) { // Se o documento tem 14 dígitos, é um CNPJ
                // Adicione a máscara de CNPJ
                document = document.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
            }
            
            return document;
        }

        function formatarTelefone(numero) {
            if (typeof numero !== 'string') {
                return numero;
            }

            // Remove todos os caracteres não numéricos do número
            const numeros = numero.replace(/\D/g, '');

            // Verifica se o número tem 10 ou 11 dígitos
            if (numeros.length === 10) {
                return `(${numeros.slice(0, 2)}) ${numeros.slice(2, 6)}-${numeros.slice(6, 10)}`;
            } else if (numeros.length === 11) {
                return `(${numeros.slice(0, 2)}) ${numeros.slice(2, 7)}-${numeros.slice(7, 11)}`;
            } else {
                return numero;
            }
        }

        function mascaraTelefone(input) {
            // Remove qualquer caractere que não seja número do valor do input
            let numero = input.value.replace(/\D/g, '');

            // Verifica o tamanho do número inserido e formata de acordo
            if (numero.length === 11) {
                input.value = numero.replace(/(\d{2})(\d{1})(\d{4})(\d{4})/, '($1) $2 $3-$4');
            } else if (numero.length === 10) {
                input.value = numero.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            } else {
                // Se o número não se encaixar em nenhum dos formatos, deixe-o inalterado
                input.value = numero;
            }
        }

        function mascararDocumento(input) {
            // Remove todos os caracteres não numéricos
            const valorLimpo = input.value.replace(/\D/g, '');

            if (valorLimpo.length <= 11) {
                // Formatando como CPF (###.###.###-##)
                input.value = valorLimpo.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            } else {
                // Formatando como CNPJ (##.###.###/####-##)
                input.value = valorLimpo.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
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

        function mascaraFinanceira(valor){
            return (valor-0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }

        $('#btnNovoPagamentoFiado').on('click', function(){
            Swal.fire({
                title: 'Qual valor do pagamento?',
                html: '<input type="number" id="valorInput" placeholder="Digite o valor" class="form-control">',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                // Função que é chamada quando o botão "Confirmar" é clicado
                preConfirm: () => {
                    // Retornar o valor do input
                    return document.getElementById('valorInput').value;
                }
            }).then((result) => {
                // Verificar se o botão "Confirmar" foi clicado
                if (result.isConfirmed) {
                    if(idUsuarioPagamentoFiado > 0){
                        $.ajax({
                            type:'post',
                            datatype:'json',
                            data:{
                                '_token':'{{csrf_token()}}',
                                'idUsuario': idUsuarioPagamentoFiado,
                                'valorPagamento': result.value
                            },
                            url:"{{route('pdv.inserir.venda.fiado.pagamento')}}",
                            success:function(r){
                                Swal.fire('Sucesso!', 'Pagamento inserido com sucesso.', 'success');
                                verPagamentos(idUsuarioPagamentoFiado);
                            },
                            error:err=>{exibirErro(err)}
                        })   
                    }
                }
            });
        })
        
        $(document).ready(() => {
            buscarPessoa();

            $('#modal-documentacao').on('hidden.bs.modal', function (){
                buscarPessoa();
            })

            $('#modal-itens-venda').on('hidden.bs.modal', function (){
                buscarPessoa();
            })

            $('#modal-venda-fiado-pagamento').on('hidden.bs.modal', function (){
                buscarPessoa();                
            })
        })
    </script>
@stop