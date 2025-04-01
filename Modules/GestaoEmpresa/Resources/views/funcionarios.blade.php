@extends('adminlte::page')

@section('title', 'Funcionários')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Funcionários</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <button class="col-md-4 btn btn-block btn-warning" id="btnNovaPessoa">
                    <i class="fas fa-user-edit"></i>
                    <span class="ml-1">Novo</span>
                </button>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex m-0 p-0">
                    <div class="col-12">
                        <input type="text" class="form-control form-control-border" id="inputFiltro" placeholder="Filtro" maxlength="8" onkeyup="buscarDados()">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-xs">
                    <thead>
                        <th style="padding-left: 5px!important">Nome</th>
                        <th><center>Documento</center></th>
                        <th><center>Telefone</center></th>
                        <th><center>Obras</center></th>
                        <th><center>Diária em Aberto</center></th>
                        <th><center>Ações</center></th>
                    </thead>
                    <tbody id="tableBodyDadosdados">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Documentos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div>
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

    <div class="modal fade" id="modal-cadastro">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Cadastro de Funcionário</h5>
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
                            <select class="form-control form-control-border col-12 selectTipoPessoa" id="selectTipoPessoa" disabled>
                                <option value="0">Tipo Cliente</option>
                            </select>
                        </div>

                        <div class="form-group col-12" id="divUsuario">
                            <input type="text" class="form-control form-control-border" id="inputUsuario" placeholder="Usuário">
                            <input type="hidden" id="inputIDUsuario">
                            <div class="btnLimparUsuario d-none">
                                <button id="btnLimparUsuario" class="btn btn-sm btn-danger mt-2"><i class="fas fa-eraser"></i> LIMPAR</button>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <input type="text" class="form-control form-control-border" maxlength="16" id="inputTelefone" oninput="mascaraTelefone(this)" placeholder="Telefone">
                        </div>
                        <div class="form-group col-12">
                            <input type="text" class="form-control form-control-border" maxlength="48" id="inputEmail" placeholder="Email">
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <input type="date"  class="form-control form-control-border" id="inputDataNascimento" placeholder="Data de Nascimento">
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <input type="text"  class="form-control form-control-border" id="inputSalarioBase" placeholder="Salario Base">
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <input type="text"  class="form-control form-control-border" id="inputhorasMensais" placeholder="Horas Mensais">
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <input type="text"  class="form-control form-control-border" id="inputCargo" placeholder="Cargo">
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
                                    <th style="padding-left: 5px!important">Banco</th>
                                    <th>Agência</th>
                                    <th>Conta</th>
                                    <th>Pix</th>
                                    <th><center>Ações</center></th>
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

    <div class="modal fade" id="modal-apontamento" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog modal-xl"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Apontamentos</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <div class="col-12 row d-flex">
                        <div class="form-group col-12 col-md-5">
                            <input id="inputFiltroDataInicio" type="date" class="form-control form-control-border" value="{{date('Y-m-d')}}">
                        </div>
                        <label class="col-2">Até</label>
                        <div class="form-group col-12 col-md-5">
                            <input id="inputFiltroDataFim" type="date" class="form-control form-control-border" value="{{date('Y-m-d')}}">
                        </div>
                    </div>

                    <div class="col-12">
                        <table class="table table-responsive-xs">
                            <thead>
                                <th style="padding-left: 5px!important">Usuário</th>
                                <th><center>Entrada</center></th>
                                <th><center>Saída</center></th>
                                <th><center>Decorrido</center></th>
                                <th><center>Localização Entrada</center></th>
                                <th><center>Localização Saída</center></th>
                            </thead>
                            <tbody id="tableBodyDadosApontamento">
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div> 
            </div> 
        </div> 
    </div> 

    <div class="modal fade" id="modal-material" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog modal-xl"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Materiais</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body">
                    <div class="col-12 col-md-6">
                        <input type="text" class="form-control form-control-border col" id="inputFiltroMaterial" placeholder="Material/Responsável">
                    </div>

                    <div class="col-12">
                        <table class="table table-responsive-xs">
                            <thead>
                                <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">Material</th>
                                <th class="d-none d-lg-table-cell"><center>Responsável</center></th>
                                <th class="d-none d-lg-table-cell"><center>Data</center></th>
                                <th class="d-none d-lg-table-cell"><center>Tipo</center></th>
                            </thead>
                            <tbody id="tableBodyDadosMaterial">    
                            </tbody>
                        </table>
                    </div>
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
                    <button type="button" class="btn btn-primary">Salvar</button> 
                </div> 
            </div> 
        </div> 
    </div> 

    <div class="modal fade" id="modalUniformeUsuario" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Uniformes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <!-- Formulário de cadastro -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <select class="form-control" id="selectUniforme">
                                <option value="">Selecione um uniforme</option>
                                <!-- Opções serão preenchidas via JavaScript -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="inputQtde" placeholder="Qtd" min="1" value="1">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" id="btnAdicionarUniforme">
                                <i class="fas fa-plus"></i> Adicionar
                            </button>
                        </div>
                    </div>
                    
                    <!-- Tabela de uniformes -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Uniforme</th>
                                    <th width="100px">Quantidade</th>
                                    <th width="100px">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="tableUniformeUsuario">
                                <!-- Dados serão carregados aqui -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
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
        inserindoPessoa = false;
        var idUsuarioSelecionado = 0;

        $('#inputValor').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputSalarioBase').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputQtde').mask('000000000');
        $('#inputContaBancariaNumero').mask('0000000000');
        $('#inputhorasMensais').mask('0000000000');
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

            var inputIDs = ['inputNome', 'inputTelefone', 'selectTipoPessoa', 'inputUsuario', 'inputIDUsuario', 'inputhorasMensais', 'inputCargo', 'inputSalarioBase'];

            for (var i = 0; i < inputIDs.length; i++) {
                var inputID = inputIDs[i];
                var $input = $('#' + inputID);
                
                if ($input.val() === '' || $input == 'inputIDUsuario' && $input.val() == '0' || $input == 'inputSalarioBase' && limparMascaraFinanceira($input.val()) == 0) {
                    if($input == 'inputIDUsuario' && $input.val() == '0'){
                        $('#inputUsuario').addClass('is-invalid');
                    } else {
                        $input.addClass('is-invalid');
                    }
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
                        'data_nascimento': $('#inputDataNascimento').val(),
                        'ID_TIPO': $('#selectTipoPessoa').val(),
                        'ID_USUARIO': $('#inputIDUsuario').val(),
                        'SALARIO_BASE': limparMascaraFinanceira($('#inputSalarioBase').val()),
                        'CARGO': $('#inputCargo').val(),
                        'HORAS_MENSAIS': $('#inputhorasMensais').val(),
                        },
                        url:"{{route('pessoa.inserir')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-cadastro').modal('hide');

                            buscarDados();
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
                        'SALARIO_BASE': limparMascaraFinanceira($('#inputSalarioBase').val()),
                        'CARGO': $('#inputCargo').val(),
                        'HORAS_MENSAIS': $('#inputhorasMensais').val(),
                        },
                        url:"{{route('pessoa.alterar')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-cadastro').modal('hide');

                            buscarDados();
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

        function editarPessoa(idPessoa){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                '_token':'{{csrf_token()}}',
                'id': idPessoa
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
                    $('#inputIDUsuario').val(r[0]['ID_USUARIO']);
                    $('#inputUsuario').val(r[0]['USUARIO']);
                    $('#inputhorasMensais').val(r[0]['HORAS_MENSAIS']);
                    $('#inputCargo').val(r[0]['CARGO']);
                    $('#inputSalarioBase').val(mascaraFinanceira(r[0]['SALARIO_BASE']));


                    if(r[0]['ID_USUARIO'] > 0){
                        $('#inputUsuario').attr('disabled', true);
                        $('.btnLimparUsuario').removeClass('d-none');
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
                            buscarDados();

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

        function buscarDados(){
            editar = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'nome': $('#inputFiltro').val(),
                    'ID_TIPO': 2
                },
                url:"{{route('pessoa.buscar')}}",
                success:function(r){
                    popularListaDados(r);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularListaDados(dados){
            var htmlTabela = "";
            for(i=0; i< dados.length; i++){
                var Keys = Object.keys(dados[i]);
                for(j=0;j<Keys.length;j++){
                    if(dados[i][Keys[j]] == null){
                        dados[i][Keys[j]] = "";
                    }
                }

                btnInativar = `<li class="dropdown-item" onclick="inativarPessoa(${dados[i]['ID']})"><span class="btn"><i class="fas fa-trash"></i></span> Inativar</li>`;
                btnEditar = `<li class="dropdown-item" onclick="editarPessoa(${dados[i]['ID']})"><span class="btn"><i class="fas fa-pen"></i></span> Editar</li>`;
                btnContasBancarias = `<li class="dropdown-item" onclick="buscarContaBancaria(${dados[i]['ID']})"><span class="btn"><i class="fas fa-university"></i></span> Contas Bancárias</li>`;
                btnApontamentos = `<li class="dropdown-item" onclick="buscarDadosApontamento(${dados[i]['ID']})"><span class="btn"><i class="fas fa-clock"></i></span> Apontamentos</li>`;
                btnEquipamentos = `<li class="dropdown-item" onclick="buscarDadosMaterial(${dados[i]['ID']})"><span class="btn"><i class="fas fa-hard-hat"></i></span> Materiais Retirados</li>`;
                btnUniformes = `<li class="dropdown-item" onclick="abrirModalUniformeUsuario(${dados[i]['ID']})"><span class="btn"><i class="fas fa-tshirt"></i></span> Uniformes/EPI</li>`;
                btnArquivos = `<li class="dropdown-item" onclick="cadastarDocumento(${dados[i]['ID']})"><span class="btn"><i class="fas fa-file-alt"></i></span> Arquivos</li>`;

                var btnOpcoes = ` <div class="input-group-prepend show justify-content-center" style="text-align: center">
                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                Ações
                            </button>
                            <ul class="dropdown-menu ">
                                ${btnEditar}
                                ${btnContasBancarias}
                                ${btnApontamentos}                                       
                                ${btnEquipamentos}                                       
                                ${btnUniformes}                                       
                                ${btnArquivos}                                            
                                ${btnInativar}
                            </ul>
                        </div>
                    `;
                htmlTabela += `
                    <tr id="tableRow${dados}" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['NOME']}</td>
                        <td class="tdTexto"><center>${formatCPForCNPJ(dados[i]['DOCUMENTO'])}</center></td>
                        <td class="tdTexto"><center>${formatarTelefone(dados[i]['TELEFONE'])}</center></td>
                        <td class="tdTexto"><center><span class="badge bg-dark">${dados[i]['TOTAL_PROJETO']} Projeto(s)</span></center></td>
                        <td class="tdTexto"><center><span class="badge bg-dark">${dados[i]['TOTAL_DIARIA']} Diária(s)</span></center></td>
                        <td>
                            <center>
                                ${btnOpcoes}
                            </center>
                        </td>
                    </tr>
                `;
            }
            $('#tableBodyDadosdados').html(htmlTabela);
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
            var htmlTabela = `<option value="0">Tipo Cliente</option>`;

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

        function buscarDadosApontamento(idFuncionario){
            $('#inputFiltroDataInicio').val(moment().format('YYYY-MM-DD'));
            $('#inputFiltroDataFim').val(moment().format('YYYY-MM-DD'));
            idUsuarioSelecionado = idFuncionario;

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'DATA_INICIO': $('#inputFiltroDataInicio').val(),
                    'DATA_TERMINO': $('#inputFiltroDataFim').val(),
                    'ID_FUNCIONARIO': idFuncionario
                },
                url:"{{route('controle.ponto.buscar')}}",
                success:function(r){
                    $('#modal-apontamento').modal('show');

                    popularListaDadosApontamento(r.dados);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularListaDadosApontamento(dados){
            var htmlTabela = "";
            var tempoApontamentoAberto = 0;

            for(i=0; i< dados.length; i++){
                var Keys = Object.keys(dados[i]);
                for(j=0;j<Keys.length;j++){
                    if(dados[i][Keys[j]] == null){
                        dados[i][Keys[j]] = "";
                    }
                }
                var spanLocalizacaoEntrada = ``;
                var spanLocalizacaoSaida = `-`;
                var tempoApontamento = '-';
                var dataSaida = '-';

                if(dados[i]['LATITUDE_ENTRADA'] != '' && dados[i]['LONGITUDE_ENTRADA'] != '' ){
                    spanLocalizacaoEntrada = `<span style="cursor: pointer;font-weight: bold" onclick="abrirLocalizacao('${dados[i]['LATITUDE_ENTRADA']}', '${dados[i]['LONGITUDE_ENTRADA']}')">${dados[i]['LATITUDE_ENTRADA']},${dados[i]['LONGITUDE_ENTRADA']}</span>`;
                }   

                if(dados[i]['LATITUDE_SAIDA'] != '' && dados[i]['LONGITUDE_SAIDA'] != '' ){
                    spanLocalizacaoSaida = `<span style="cursor: pointer;font-weight: bold" onclick="abrirLocalizacao('${dados[i]['LATITUDE_SAIDA']}', '${dados[i]['LONGITUDE_SAIDA']}')">${dados[i]['LATITUDE_SAIDA']},${dados[i]['LONGITUDE_SAIDA']}</span>`;
                }

                if(dados[i]['DATA_SAIDA'] != '') {
                    dataSaida = moment(dados[i]['DATA_SAIDA']).format('DD/MM/YYYY HH:mm:ss');

                    let diff = moment.duration(moment(dados[i]['DATA_SAIDA']).diff(moment(dados[i]['DATA_ENTRADA'])));
                    let horas = String(Math.floor(diff.asHours())).padStart(2, '0');
                    let minutos = String(diff.minutes()).padStart(2, '0');
                    let segundos = String(diff.seconds()).padStart(2, '0');
                    tempoApontamento =  `${horas}:${minutos}:${segundos}`;
                } else {
                    tempoApontamentoAberto = moment.duration(moment().diff(moment(dados[i]['DATA_ENTRADA']))).asSeconds();

                    let diff = moment.duration(moment().diff(moment(dados[i]['DATA_ENTRADA'])));
                    let horas = String(Math.floor(diff.asHours())).padStart(2, '0');
                    let minutos = String(diff.minutes()).padStart(2, '0');
                    let segundos = String(diff.seconds()).padStart(2, '0');
                    tempoApontamento =  `${horas}:${minutos}:${segundos}`;
                }

                htmlTabela += `
                    <tr id="tableRow${dados[i]['ID']}" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['NOME_USUARIO']}</td>
                        <td class="tdTexto"><center>${moment(dados[i]['DATA_ENTRADA']).format('DD/MM/YYYY HH:mm:ss')}</center></td>
                        <td class="tdTexto"><center>${dataSaida}</center></td>
                        <td class="tdTexto"><center>${tempoApontamento}</center></td>
                        <td class="tdTexto"><center>${spanLocalizacaoEntrada}</center></td>
                        <td class="tdTexto"><center>${spanLocalizacaoSaida}</center></td>
                    </tr>
                `;
            }

            
            $('#tableBodyDadosApontamento').html(htmlTabela);
        }

        function buscarDadosMaterial(idFuncionario, limparBusca = true){
            idUsuarioSelecionado = idFuncionario;

            if(limparBusca){
                $('#inputFiltroMaterial').val('');
            }

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'FILTRO_BUSCA': $('#inputFiltroMaterial').val(),
                    'ID_FUNCIONARIO': idUsuarioSelecionado
                },
                url:"{{route('material.buscar.movimento')}}",
                success:function(r){
                    $('#modal-material').modal('show');

                    popularListaDadosMaterial(r.dados);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularListaDadosMaterial(dados){
            var htmlTabela = "";
            for(i=0; i< dados.length; i++){
                var Keys = Object.keys(dados[i]);
                for(j=0;j<Keys.length;j++){
                    if(dados[i][Keys[j]] == null){
                        dados[i][Keys[j]] = "";
                    }
                }

                var tipoMovimento = 'Retirada';
                var classeBadgeTipoMovimento = 'bg-warning';

                if(dados[i]['TIPO_MOVIMENTO'] == 1){
                    tipoMovimento = 'Devolução';
                    classeBadgeTipoMovimento = 'bg-success';
                }

                htmlTabela += `
                    <tr id="tableRowdados" class="d-none d-lg-table-row">
                        <td style="padding-left: 5px!important">${dados[i]['EQUIPAMENTO']}</td>
                        <td><center>${dados[i]['PESSOA']}</center></td>
                        <td><center>${moment(dados[i]['DATA']).format('DD/MM/YYYY HH:mm')}</center></td>
                        <td>
                            <center>
                                <span class="badge ${classeBadgeTipoMovimento}">${tipoMovimento}</span>
                            </center>
                        </td>
                    </tr>
                
                    <tr id="tableRow${dados[i]['ID']}" class="d-table-row d-lg-none">
                        <td>
                            <div class="col-12">
                                <center>
                                    <b>${dados[i]['EQUIPAMENTO']} = ${dados[i]['PESSOA']}</b>
                                </center>
                            </div>
                            <div class="col-12" style="font-size: 4vw">
                                <center>
                                    <span class="badge bg-info">${moment(dados[i]['DATA']).format('DD/MM/YYYY HH:mm')}</span>
                                    <span class="badge ${classeBadgeTipoMovimento}">${tipoMovimento}</span>
                                </center>
                            </div>
                        </td>
                    </tr>`;
            }
            $('#tableBodyDadosMaterial').html(htmlTabela);
        }

        function abrirLocalizacao(latitude, longitude){
            var url = `https://www.google.com/maps?q=${latitude},${longitude}`;
            window.open(url, '_blank')
        }

        function resetarCamposCadastro(){
            $('#inputNome').val('');
            $('#inputDocumento').val('');
            $('#inputTelefone').val('');
            $('#inputDataNascimento').val('');
            $('#inputEmail').val('');
            $('#selectTipoPessoa').val('2');

            limparCampo('inputUsuario', 'inputIDUsuario', 'btnLimparUsuario');
        }

        /* Matheus 29/03/2025 21:20:49 - UNIFORME */
            // Função para abrir o modal
            function abrirModalUniformeUsuario(idUsuario) {
                idUsuarioSelecionado = idUsuario;

                carregarUniformesUsuario(idUsuario);
                carregarOpcoesUniformes();
                $('#modalUniformeUsuario').modal('show');
            }

            // Carrega as opções de uniformes
            function carregarOpcoesUniformes() {

                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                    '_token':'{{csrf_token()}}',
                    'FILTRO_ADICIONAL' : 'AND QUANTIDADE > 0'
                    },
                    url:"{{route('uniforme.buscar')}}",
                    success:function(r){
                        var response = r.dados;
                        var options = '<option value="">Selecione um uniforme</option>';

                        response.forEach(uniforme => {
                            options += `<option value="${uniforme.ID}">${uniforme.DESCRICAO}</option>`;
                        });
                        $('#selectUniforme').html(options);
                    },
                    error:err=>{exibirErro(err)}
                })
            }

            // Carrega os uniformes do usuário
            function carregarUniformesUsuario(idUsuario) {
                $.ajax({
                    type: 'post',
                    url: "{{ route('uniforme.usuario.buscar') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'ID_USUARIO': idUsuario
                    },
                    success: function(response) {
                        let html = '';
                        response.dados.forEach(item => {
                            html += `
                                <tr>
                                    <td>${item.DESCRICAO}</td>
                                    <td>${item.QTDE}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" onclick="inativarUniformeUsuario(${item.ID})">
                                            <i class="fas fa-trash"></i> Remover
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#tableUniformeUsuario').html(html);
                    },
                    error:err=>{exibirErro(err)}
                });
            }

            // Remove uniforme do usuário
            function inativarUniformeUsuario(id) {

                Swal.fire({
                    title: 'Confirmação',
                    text: 'Deseja remover o vínculo de usuário com uniforme?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'post',
                            url: "{{ route('uniforme.usuario.remover') }}",
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'ID': id
                            },
                            success: function() {
                                const idUsuario = idUsuarioSelecionado
                                carregarUniformesUsuario(idUsuario);
                                carregarOpcoesUniformes();
            
                                dispararAlerta('success', 'Vínculo de usuário com uniforme removido com sucesso.')
                            },
                            error:err=>{exibirErro(err)}
                        });
                    }
                });
            }

            $('#btnAdicionarUniforme').click(function() {
                const idUsuario = idUsuarioSelecionado;
                const idUniforme = $('#selectUniforme').val();
                const qtde = $('#inputQtde').val();
                
                if(!idUniforme) {
                    dispararAlerta('warning', 'Selecione um uniforme');
                    return;
                }
    
                $.ajax({
                    type: 'post',
                    url: "{{ route('uniforme.usuario.inserir') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'ID_USUARIO': idUsuario,
                        'ID_UNIFORME': idUniforme,
                        'QTDE': qtde
                    },
                    success: function(r) {
                        if(r['situacao'] == 'sucesso'){
                            $('#selectUniforme').val('');
                            $('#inputQtde').val('1');
                            carregarUniformesUsuario(idUsuario);
                            carregarOpcoesUniformes();
                        } else {
                            dispararAlerta('warning', r['mensagem']);
                        }
                    },
                    error:err=>{exibirErro(err)}
                });
            });
        /* Matheus 29/03/2025 21:20:57 - FIM */

        /* Matheus 29/03/2025 21:25:23 - DOCUMENTO */
            function verDocumento(caminhoDocumento){
                url = "{{env('APP_URL')}}/"+caminhoDocumento;

                window.open(url, '_blank');
            }

            function fecharCadastroDocumento(){
                $('#modal-documentacao').modal('hide');
            }

            function cadastarDocumento(ID){
                idUsuarioSelecionado = ID;

                buscarDocumentos();

                $('#modal-documentacao').modal('show');
            }

            function buscarDocumentos(){
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                        '_token':'{{csrf_token()}}',                    
                        'idPessoa': idUsuarioSelecionado,
                    },
                    url:"{{route('pessoa.buscar.documento')}}",
                    success:function(r){
                        popularListaDocumentos(r);
                    },
                    error:err=>{exibirErro(err)}
                })
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

            function uploadArquivo(){
                var dataAnexo = new FormData();
                anexoCaminho = "";
                idPessoa = idUsuarioSelecionado;
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

                                    dispararAlerta('success', 'Documento salvo com sucesso.')
                                },
                                error:err=>{exibirErro(err)}
                            })  
                        }else{
                            console.log(r)
                            dispararAlerta('warning', 'Erro ao enviar Anexo.');
                        }
                    },
                    error: err=>{exibirErro(err)}
                });
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

            function validaDocumento(){
                if ($("#inputArquivoDocumentacao")[0].files.length > 0) {
                    $('#labelInputArquivoDocumentacao').html($("#inputArquivoDocumentacao")[0].files[0].name);
                } else {
                    $('#labelInputArquivoDocumentacao').html('Selecionar Arquivos');
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
        /* Matheus 29/03/2025 21:25:23 - FIM */

        $('#btnNovaPessoa').click(() => {
            cadastrarPessoa();
        })

        $('#btnConfirmar').click(() => {
            inserirPessoa();
        });

        $('#btnCadastrarContaBancaria').click(() => {
            cadastrarContaBancaria();
        });

        $('#btnSalvarContaBancaria').click(() => {
            inserirContaBancaria();
        });

        $('#btnLimparUsuario').click(function() {
            limparCampo('inputUsuario', 'inputIDUsuario', 'btnLimparUsuario');
        });

        $('#inputFiltroMaterial').on('keyup', () => {
            buscarDadosMaterial(idUsuarioSelecionado, false);
        });

        $('#inputFiltroDataInicio').on('change', () => {
            buscarDados(idUsuarioSelecionado);
        });

        $('#inputFiltroDataFim').on('change', () => {
            buscarDados(idUsuarioSelecionado);
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

        $(document).ready(function() {
            buscarTipoPessoa(); 
            buscarDados();
        })
    </script>
@stop