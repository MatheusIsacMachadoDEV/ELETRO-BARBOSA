@extends('adminlte::page')

@section('title', 'Pessoas')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Pessoas</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <button class="col-md-4 btn btn-block btn-warning" id="btnNovaPessoa">
                    <i class="fas fa-user-edit"></i>
                    <span class="ml-1">Nova</span>
                </button>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="input-group col-12 col-md-8">
                        <input type="text" class="form-control form-control-border" placeholder="Nome" id="inputNomeFiltro" onkeyup="buscarPessoa()">
                    </div>
                    <div class="col-12 col-md-4">
                        <select class="form-control form-control-border selectTipoPessoa" id="selectFiltroTipoPessoa" onchange="buscarPessoa()">
                            <option value="0">Tipo Pessoa</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>CPF/CNPJ</th>
                            <th>Contato</th>
                            <th>Email</th>
                            <th class="d-none">Valor em Aberto</th>
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
                            <input type="text" class="form-control form-control-border col-12" id="inputNome" placeholder="Nome">
                            <input type="hidden" class="form-control col-4" id="inputIDPessoa">
                        </div>
                        <div class="form-group col-6">
                            <input type="text" class="form-control form-control-border" maxlength="18" id="inputDocumento" oninput="mascararDocumento(this)" placeholder="CPF/CNPJ">
                        </div>

                        <div class="col-12 col-md-4 ">
                            <select class="form-control form-control-border col-12 selectTipoPessoa" id="selectTipoPessoa">
                                <option value="0">Tipo Pessoa</option>
                            </select>
                        </div>

                        <div class="form-group col-12 d-none" id="divUsuario">
                            <input type="text" class="form-control form-control-border" id="inputUsuario" placeholder="Usuário">
                            <input type="hidden" id="inputIDUsuario">
                            <div class="btnLimparUsuario d-none">
                                <button id="btnLimparUsuario" class="btn btn-sm btn-danger mt-2"><i class="fas fa-eraser"></i> LIMPAR</button>
                            </div>
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <input type="text" class="form-control form-control-border" maxlength="16" id="inputTelefone" oninput="mascaraTelefone(this)" placeholder="Telefone">
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <input type="text" class="form-control form-control-border" maxlength="48" id="inputEmail" placeholder="Email">
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <input type="date"  class="form-control form-control-border" id="inputDataNascimento" placeholder="Data de Nascimento">
                        </div>
                        <div class="form-group col-12 col-md-2">
                            <input type="text"  class="form-control form-control-border" id="inputCEP" placeholder="CEP">
                        </div>
                        <div class="form-group col-12 col-md-8">
                            <input type="text"  class="form-control form-control-border" id="inputRua" placeholder="Rua">
                        </div>
                        <div class="form-group col-12 col-md-2">
                            <input type="text"  class="form-control form-control-border" id="inputNumero" placeholder="Nº">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <input type="text"  class="form-control form-control-border" id="inputCidade" placeholder="Cidade">
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <input type="text"  class="form-control form-control-border" id="inputBairro" placeholder="Bairro">
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <input type="text"  class="form-control form-control-border" id="inputEstado" placeholder="Estado">
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
        <div class="modal-dialog modal-lg">
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
                                        <input class="input-control d-none" type="checkbox" id="checkDocPendente" onchange="validaDocPendente()">
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
                            <table class="table table-responsive-xs">
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

    <div class="modal fade" id="modal-lista-contas-bancarias" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog modal-lg"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Contas Bancárias</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <div class="row d-flex">
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-sm btn-info col-12 col-md-4" id="btnCadastrarContaBancaria">
                                <i class="fas fa-university"></i>
                                <span class="ml-1">Cadastrar</span>
                            </button>
                        </div>
                        <div class="col-12">
                            <table class="table table-responsive-xs">
                                <thead>
                                    <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">Banco</th>
                                    <th class="d-none d-lg-table-cell">Agência</th>
                                    <th class="d-none d-lg-table-cell">Conta</th>
                                    <th class="d-none d-lg-table-cell">Pix</th>
                                    <th class="d-none d-lg-table-cell"><center>Ações</center></th>
                                </thead>
                                <tbody id="tableBodyDadosContaBancaria">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
                </div> 
            </div> 
        </div> 
    </div> 

    <div class="modal fade" id="modal-cadastro-contas-bancarias" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Cadastro de Conta Bancária</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body">
                    <div class="col-12">
                        <input type="hidden" id="inputContaBancariaIDPessoa">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-border" maxlength="60"  placeholder="Banco" id="inputContaBancariaBanco">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-border" placeholder="Agência" id="inputContaBancariaAgencia">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-border" placeholder="Número (Com Dígito)" id="inputContaBancariaNumero">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-border" maxlength="50" placeholder="Chave PIX" id="inputContaBancariaPix">
                        </div>
                    </div>
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
                    <button type="button" class="btn btn-primary" id="btnSalvarContaBancaria">Salvar</button> 
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
        inserindoPessoa = false;
        idUsuarioPagamentoFiado = 0;

        $('#inputCEP').mask('00000-000');
        $('#inputContaBancariaNumero').mask('0000000000');
        $('#inputContaBancariaAgencia').mask('0000');
        
        function cadastrarPessoa(){
            inserindoPessoa = true;

            resetarCamposCadastro();

            $('#modal-cadastro').modal('show');
        }        

        function cadastrarContaBancaria(){

            $('#inputContaBancariaNumero').val('');
            $('#inputContaBancariaAgencia').val('');
            $('#inputContaBancariaBanco').val('');
            $('#inputContaBancariaPix').val('');
            
            $('#modal-lista-contas-bancarias').modal('hide');
            $('#modal-cadastro-contas-bancarias').modal('show');
        }        

        function inserirPessoa() {
            validacao = true;

            var inputIDs = ['inputNome', 'inputTelefone', 'selectTipoPessoa'];

            for (var i = 0; i < inputIDs.length; i++) {
                var inputID = inputIDs[i];
                var $input = $('#' + inputID);
                
                if ($input.val() === '' || $input == 'ID_TIPO' && $input.val() == '0') {
                    $input.addClass('is-invalid');
                    validacao = false;
                } else {
                    $input.removeClass('is-invalid');
                }
            }

            if($('#selectTipoPessoa').val() == 2 && ($('#inputIDUsuario').val() == '0' || $('#inputIDUsuario').val() == '')){
                $('#inputUsuario').addClass('is-invalid');
                validacao = false;
            } else {
                $('#inputUsuario').removeClass('is-invalid');
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
                        'data_nascimento': $('#inputDataNascimento').val(),
                        'ID_TIPO': $('#selectTipoPessoa').val(),
                        'ID_USUARIO': $('#inputIDUsuario').val(),
                        'ESTADO': $('#inputEstado').val(),
                        'CIDADE': $('#inputCidade').val(),
                        'NUMERO': $('#inputNumero').val(),
                        'RUA': $('#inputRua').val(),
                        'BAIRRO': $('#inputBairro').val(),
                        'CEP': $('#inputCEP').val(),
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
                        'data_nascimento': $('#inputDataNascimento').val(),
                        'ID_TIPO': $('#selectTipoPessoa').val(),
                        'ID_USUARIO': $('#inputIDUsuario').val(),
                        'ESTADO': $('#inputEstado').val(),
                        'CIDADE': $('#inputCidade').val(),
                        'NUMERO': $('#inputNumero').val(),
                        'RUA': $('#inputRua').val(),
                        'BAIRRO': $('#inputBairro').val(),
                        'CEP': $('#inputCEP').val(),
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

        function inserirContaBancaria() {
            validacao = true;

            var inputIDs = ['inputContaBancariaBanco', 'inputContaBancariaAgencia', 'inputContaBancariaNumero'];

            for (var i = 0; i < inputIDs.length; i++) {
                var inputID = inputIDs[i];
                var $input = $('#' + inputID);
                
                if ($input.val() === '' || $input == 'ID_TIPO' && $input.val() == '0') {
                    $input.addClass('is-invalid');
                    validacao = false;
                } else {
                    $input.removeClass('is-invalid');
                }
            }

            if(validacao){
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                    '_token':'{{csrf_token()}}',
                    'BANCO': $('#inputContaBancariaBanco').val(),
                    'AGENCIA': $('#inputContaBancariaAgencia').val(),
                    'NUMERO': $('#inputContaBancariaNumero').val(),
                    'PIX': $('#inputContaBancariaPix').val(),
                    'ID_PESSOA': $('#inputContaBancariaIDPessoa').val()
                    },
                    url:"{{route('financeiro.cc.inserir')}}",
                    success:function(r){
                        $('#modal-cadastro-contas-bancarias').modal('hide');

                        buscarContaBancaria($('#inputContaBancariaIDPessoa').val());
                        Swal.fire(
                            'Sucesso!',
                            'Conta bancária inserida com sucesso.',
                            'success',
                        )
                    },
                    error:err=>{exibirErro(err)}
                })
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
                    $('#selectTipoPessoa').val(r[0]['ID_TIPO']);
                    $('#inputEstado').val(r[0]['ESTADO']);
                    $('#inputCidade').val(r[0]['CIDADE']);
                    $('#inputNumero').val(r[0]['NUMERO']);
                    $('#inputRua').val(r[0]['RUA']);
                    $('#inputCEP').val(r[0]['BAIRRO']);
                    $('#inputBairro').val(r[0]['CEP']);

                    if($('#selectTipoPessoa').val() == 2){
                        $('#divUsuario').removeClass('d-none');

                        $('#inputIDUsuario').val(r[0]['ID_USUARIO']);
                        $('#inputUsuario').val(r[0]['USUARIO']);


                        if(r[0]['ID_USUARIO'] > 0){
                            $('#inputUsuario').attr('disabled', true);
                            $('.btnLimparUsuario').removeClass('d-none');
                        } else {                            
                            limparCampo('inputUsuario', 'inputIDUsuario', 'btnLimparUsuario');
                        }
                    } else {
                        $('#divUsuario').addClass('d-none');
                        limparCampo('inputUsuario', 'inputIDUsuario', 'btnLimparUsuario');
                    }

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

        function inativarContaBancaria(idContaBancaria){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar a conta bancária?',
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
                        'id': idContaBancaria
                        },
                        url:"{{route('financeiro.cc.inativar')}}",
                        success:function(r){
                            buscarContaBancaria($('#inputContaBancariaIDPessoa').val());

                            Swal.fire('Sucesso!'
                                    , 'Conta Bancária inativada com sucesso.'
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
                'nome': $('#inputNomeFiltro').val(),
                'ID_TIPO': $('#selectFiltroTipoPessoa').val()
                },
                url:"{{route('pessoa.buscar')}}",
                success:function(r){
                    popularListaPessoas(r);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function buscarContaBancaria(idPessoa){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                '_token':'{{csrf_token()}}',
                'id': idPessoa,
                },
                url:"{{route('financeiro.cc.buscar')}}",
                success:function(r){
                    $('#inputContaBancariaIDPessoa').val(idPessoa);
                    popularListaContaBancaria(r);
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

                if(Pessoa[i]['ID_TIPO'] == 1){
                    classeBadgeTipPessoa = 'bg-dark';
                } else if(Pessoa[i]['ID_TIPO'] == 2){
                    classeBadgeTipPessoa = 'bg-indigo color-palette';
                } else {
                    classeBadgeTipPessoa = 'bg-primary';
                }

                htmlPessoa += `
                    <tr id="tableRow${Pessoa[i]['ID']}">
                        <td class="tdTexto">${Pessoa[i]['ID']}</td>
                        <td class="tdTexto">${Pessoa[i]['NOME']}</td>
                        <td class="tdTexto"><span class="badge ${classeBadgeTipPessoa}">${Pessoa[i]['TIPO_PESSOA']}</span></td>
                        <td class="tdTexto">${formatCPForCNPJ(Pessoa[i]['DOCUMENTO'])}</td>
                        <td class="tdTexto">${formatarTelefone(Pessoa[i]['TELEFONE'].toString())}</td>
                        <td class="tdTexto">${Pessoa[i]['EMAIL']}</td>
                        <td class="tdTexto d-none"><span class="badge ${classeBagdeValorAberto}">${mascaraFinanceira(valorEmAberto)}</span></td>
                        <td>\
                            <center>\
                                <button class="btn d-none" onclick="verVenda(${Pessoa[i]['ID']})"><i class="fas fa-money-check-alt"></i></button>\
                                <button class="btn d-none" onclick="verPagamentos(${Pessoa[i]['ID']})"><i class="fas fa-hand-holding-usd"></i></button>\
                                <button class="btn" onclick="editarPessoa(${Pessoa[i]['ID']})"><i class="fas fa-pen"></i></button>\
                                <button class="btn" onclick="buscarContaBancaria(${Pessoa[i]['ID']})"><i class="fas fa-university"></i></button>\
                                <button class="btn" onclick="cadastarDocumento(${Pessoa[i]['ID']}, '${Pessoa[i]['NOME']}', '${Pessoa[i]['DOC_PENDENTE']}')"><i class="fas fa-file-alt"></i></button>\
                                <button class="btn" onclick="inativarPessoa(${Pessoa[i]['ID']})"><i class="fas fa-trash"></i></button>\
                            </center>\
                        </td>\                        
                    </tr>`;
            }
            $('#tableBodyDadosPessoa').html(htmlPessoa)
        }

        function popularListaContaBancaria(Dado){
            var htmlDado = "";

            for(i=0; i< Dado.length; i++){
                var DadoKeys = Object.keys(Dado[i]);
                for(j=0;j<DadoKeys.length;j++){
                    if(Dado[i][DadoKeys[j]] == null){
                        Dado[i][DadoKeys[j]] = "";
                    }
                }

                htmlDado += `
                    <tr id="tableRow${Dado[i]['ID']}">
                        <td class="tdTexto">${Dado[i]['BANCO']}</td>
                        <td class="tdTexto">${Dado[i]['AGENCIA']}</td>
                        <td class="tdTexto">${Dado[i]['NUMERO']}</td>
                        <td class="tdTexto">${Dado[i]['PIX']}</td>
                        <td>\
                            <center>
                                <button class="btn" onclick="inativarContaBancaria(${Dado[i]['ID']})"><i class="fas fa-trash"></i></button>
                            </center>
                        </td>\                        
                    </tr>`;
            }
            $('#tableBodyDadosContaBancaria').html(htmlDado);

            $('#modal-lista-contas-bancarias').modal('show');
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

        function buscarTipoPessoa(){
            editarMaterial = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'TIPO': 'PESSOA_TIPO'
                },
                url:"{{route('buscar.situacoes')}}",
                success:function(r){
                    popularTipoPessoa(r.dados);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularTipoPessoa(dados){
            var htmlTabela = `<option value="0">Tipo Pessoa</option>`;

            for(i=0; i< dados.length; i++){
                var materialKeys = Object.keys(dados[i]);
                for(j=0;j<materialKeys.length;j++){
                    if(dados[i][materialKeys[j]] == null){
                        dados[i][materialKeys[j]] = "";
                    }
                }

                htmlTabela += `
                    <option value="${dados[i]['ID_ITEM']}">${dados[i]['VALOR']}</option>`;
            }
            $('.selectTipoPessoa').html(htmlTabela)
        }

        function resetarCamposCadastro(){
            $('#inputNome').val('');
            $('#inputDocumento').val('');
            $('#inputTelefone').val('');
            $('#inputDataNascimento').val('');
            $('#inputCEP').val('');
            $('#inputBairro').val('');
            $('#inputEmail').val('');
            $('#selectTipoPessoa').val('0');

            limparCampo('inputUsuario', 'inputIDUsuario', 'btnLimparUsuario');
        }

        $('#btnConfirmar').click(() => {
            inserirPessoa();
        });

        $('#btnCadastrarContaBancaria').click(() => {
            cadastrarContaBancaria();
        });

        $('#btnSalvarContaBancaria').click(() => {
            inserirContaBancaria();
        });

        $('#btnNovaPessoa').click(() => {
            cadastrarPessoa();
        })

        $('#selectTipoPessoa').on('change', () => {
            if($('#selectTipoPessoa').val() == 2){
                $('#divUsuario').removeClass('d-none');
            } else {
                $('#divUsuario').addClass('d-none');
                limparCampo('inputUsuario', 'inputIDUsuario', 'btnLimparUsuario');
            }
        })

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
        });

        $("#inputUsuario").autocomplete({
            source: function(request, cb) {
                param = request.term;
                $.ajax({
                    url: "{{route('usuarios.buscar')}}",
                    method: 'post',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'FILTRO_BUSCA': param,
                        'FILTRO_ADICIONAL': 'SEM_FUNCIONARIO'
                    },
                    dataType: 'json',
                    success: function(r) {
                        result = $.map(r.dados, function(obj) {
                            return {
                                label: obj.info,
                                value: obj.NAME,
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
                    $('#inputUsuario').val(selectedData.item.data.NAME);
                    $('#inputIDUsuario').val(selectedData.item.data.ID);
                    $('#inputUsuario').attr('disabled', true);
                    $('.btnLimparUsuario').removeClass('d-none');
                } else {
                    limparCampo('inputUsuario', 'inputIDUsuario', 'btnLimparUsuario');
                }
            }
        });

        $('#btnLimparUsuario').click(function() {
            limparCampo('inputUsuario', 'inputIDUsuario', 'btnLimparUsuario');
        });

        $('#modal-cadastro-contas-bancarias').on('hidden.bs.modal', function () {
            
            $('#modal-lista-contas-bancarias').modal('show');
        });
        
        $(document).ready(() => {
            buscarTipoPessoa(); 
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