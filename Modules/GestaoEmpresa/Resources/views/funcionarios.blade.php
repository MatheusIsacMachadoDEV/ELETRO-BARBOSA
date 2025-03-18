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
                    <div class="col-12 col-md-6">
                        <input type="text" class="form-control form-control-border" id="inputFiltro" placeholder="Filtro" maxlength="8" onkeyup="buscarDados()">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-xs">
                    <thead>
                        <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">Nome</th>
                        <th class="d-none d-lg-table-cell"><center>Documento</center></th>
                        <th class="d-none d-lg-table-cell"><center>Telefone</center></th>
                        <th class="d-none d-lg-table-cell"><center>Obras</center></th>
                        <th class="d-none d-lg-table-cell"><center>Diária</center></th>
                        <th class="d-none d-lg-table-cell"><center>Ações</center></th>
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
                            <table class="table table-responsive-xs">
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

                        <div class="form-group col-6">
                            <input type="text" class="form-control form-control-border" maxlength="16" id="inputTelefone" oninput="mascaraTelefone(this)" placeholder="Telefone">
                        </div>
                        <div class="form-group col-12">
                            <input type="text" class="form-control form-control-border" maxlength="48" id="inputEmail" placeholder="Email">
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <input type="date"  class="form-control form-control-border" id="inputDataNascimento" placeholder="Data de Nascimento">
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

        $('#inputValor').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputQtde').mask('000000000');

        function exibirModalCadastro(){
            resetarCampos();
            buscarMarca();
            $('#modal-cadastro').modal('show');
        }

        function cadastrarPessoa(){
            inserindoPessoa = true;

            $('#inputNome').val('');
            $('#inputDocumento').val('');
            $('#inputTelefone').val('');
            $('#inputDataNascimento').val('');
            $('#inputEmail').val('');
            $('#selectTipoPessoa').val('2');

            $('#modal-cadastro').modal('show');
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
                        'ID_TIPO': $('#selectTipoPessoa').val()
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
                        'ID_TIPO': $('#selectTipoPessoa').val()
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

                btnContasBancarias = `<li class="dropdown-item" onclick="exibirContasBancarias(${dados[i]['ID']})"><span class="btn"><i class="fas fa-university"></i></span> Contas Bancárias</li>`;
                btnApontamentos = `<li class="dropdown-item" onclick="exibirApontamentos(${dados[i]['ID']})"><span class="btn"><i class="fas fa-clock"></i></span> Apontamentos</li>`;
                btnEquipamentos = `<li class="dropdown-item" onclick="exibirEquipamentos(${dados[i]['ID']})"><span class="btn"><i class="fas fa-hard-hat"></i></span> Equipamentos</li>`;
                btnUniformes = `<li class="dropdown-item" onclick="exibirUniformesEPI(${dados[i]['ID']})"><span class="btn"><i class="fas fa-tshirt"></i></span> Uniformes/EPI</li>`;
                btnArquivos = `<li class="dropdown-item" onclick="cadastarDocumento(${dados[i]['ID']})"><span class="btn"><i class="fas fa-file-alt"></i></span> Arquivos</li>`;

                var btnOpcoes = ` <div class="input-group-prepend show justify-content-center" style="text-align: center">
                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                Ações
                            </button>
                            <ul class="dropdown-menu ">
                                ${btnContasBancarias}
                                ${btnApontamentos}                                       
                                ${btnEquipamentos}                                       
                                ${btnUniformes}                                       
                                ${btnArquivos}                                            
                            </ul>
                        </div>
                    `;
                htmlTabela += `
                    <tr id="tableRow${dados}" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['NOME']}</td>
                        <td class="tdTexto"><center>${formatCPForCNPJ(dados[i]['DOCUMENTO'])}</center></td>
                        <td class="tdTexto"><center>${formatarTelefone(dados[i]['TELEFONE'])}</center></td>
                        <td class="tdTexto"><center><span class="badge bg-dark">${dados[i]['QTDE_OBRAS']} Obras</span></center></td>
                        <td class="tdTexto"><center><span class="badge bg-dark">${dados[i]['SITUACAO_OBRA']} Obras</span></center></td>
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

        $('#btnNovaPessoa').click(() => {
            cadastrarPessoa();
        })

        $('#btnConfirmar').click(() => {
            inserirPessoa();
        });

        $(document).ready(function() {
            buscarTipoPessoa(); 
            buscarDados();
        })
    </script>
@stop