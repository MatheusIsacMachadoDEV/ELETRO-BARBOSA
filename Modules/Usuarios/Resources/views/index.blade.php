@extends('adminlte::page')

@section('title', 'Usuários | GSSoftware')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Usuários</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <div class="col-12 col-md-6 row d-flex d-flex justify-content-end ">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-block btn-warning" id="btnNovo">
                            <i class="fas fa-user"></i>
                            <span class="ml-1">Cadastrar</span>
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
                        <input type="text" class="form-control form-control-border col" id="inputFiltro" placeholder="Usuário" onkeyup="buscarDados()">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-xs">
                    <thead>
                        <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">ID</th>
                        <th class="d-none d-lg-table-cell"><center>Nome</center></th>
                        <th class="d-none d-lg-table-cell"><center>Email</center></th>
                        <th class="d-none d-lg-table-cell"><center>Administrador</center></th>
                        <th class="d-none d-lg-table-cell"><center>Ações</center></th>
                    </thead>
                    <tbody id="tableBodyDados">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modal-cadastro" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Cadastro de Usuário</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <div class="row d-flex p-0 m-0">                        
                        <input type="hidden" id="inputUsuarioID">
                        <div class="col-12">
                            <input type="text" class="form-control form-control-border" placeholder="Nome" id="inputUsuarioNome" >
                        </div>
                        <div class="col-12">
                            <input type="text" class="form-control form-control-border" placeholder="Email" id="inputUsuarioEmail" >
                        </div>
                        <div class="col-12">
                            <input type="password" class="form-control form-control-border" placeholder="Senha" id="inputUsuarioSenha" >
                        </div>
                        <div class="col-12">
                            <input type="password" class="form-control form-control-border" placeholder="Confirmar Senha" id="inputUsuarioSenhaConfirmacao" >
                        </div>
                        <div class="col-12">
                            <input type="checkbox" placeholder="Administrador" id="inputUsuarioAdministrador" > Adminsitrador
                        </div>
                    </div>
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
                    <button type="button" class="btn btn-primary" id="btnUsuarioSalvar">Salvar</button> 
                </div> 
            </div> 
        </div> 
    </div> 

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog ">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Documentação dados <span id="titleDocumento"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="">
                                <input type="hidden" id="inputPlacaDocumentacao">
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

    <div class="modal fade" id="modalMenuUsuario" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vincular Menus ao Usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="inputIDUsuario">
                    
                    <!-- Formulário de cadastro -->
                    <div class="row mb-3">
                        <div class="col-md-10">
                            <select class="form-control" id="selectMenu">
                                <option value="">Selecione um menu</option>
                                <!-- Opções serão preenchidas via JavaScript -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" id="btnAdicionarMenu">
                                <i class="fas fa-plus"></i> Adicionar
                            </button>
                        </div>
                    </div>
                    
                    <!-- Tabela de menus vinculados -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th width="100px">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="tableMenuUsuario">
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
            resetarCampos();
            $('#modal-cadastro').modal('show');
        }

        function buscarDados(){
            editar = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'FILTRO_BUSCA': $('#inputFiltro').val()
                },
                url:"{{route('usuarios.buscar')}}",
                success:function(r){
                    popularListaDados(r.dados);
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

                var spanAdministrador = `<span class="badge ${dados[i]['ADMINISTRADOR'] == 1 ? 'bg-success' : 'bg-dark'}"> ${dados[i]['ADMINISTRADOR'] == 1 ? 'Administrador' : 'Usuário'}</span>`

                btnEditar = `<li class="dropdown-item" onclick="alterarUsuario(${dados[i]['ID']})"><span class="btn"><i class="fas fa-pen"></i></span> Alterar</li>`;
                btnMenus = `<li class="dropdown-item" onclick="abrirModalMenuUsuario(${dados[i]['ID']})"><span class="btn"><i class="fas fa-list"></i></span> Menus</li>`;
                btnInativar = `<li class="dropdown-item" onclick="inativarUsuario(${dados[i]['ID']})"><span class="btn"><i class="fas fa-trash"></i></span> Inativar</li>`;

                var btnOpcoes = ` <div class="input-group-prepend show justify-content-center" style="text-align: center">
                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                Ações
                            </button>
                            <ul class="dropdown-menu ">
                                ${btnMenus}
                                ${btnEditar}
                                ${btnInativar}
                            </ul>
                        </div>
                    `;
                htmlTabela += `
                    <tr id="tableRowdados" class="d-none d-lg-table-row">
                        <td style="padding-left: 5px!important">${dados[i]['ID']}</td>
                        <td><center>${dados[i]['NAME']}</center></td>
                        <td><center>${dados[i]['EMAIL']}</center></td>
                        <td><center>${spanAdministrador}</center></td>
                        <td>
                            <center>
                                ${btnOpcoes}
                            </center>
                        </td>
                    </tr>
                
                    <tr id="tableRow${dados[i]['ID']}" class="d-table-row d-lg-none">
                        <td>
                            <div class="col-12">
                                <center>
                                    <b>${dados[i]['ID']} - ${dados[i]['NAME']} (${spanAdministrador})</b>
                                </center>
                            </div>
                            <div class="col-12" style="font-size: 4vw">
                                <center>
                                    <span class="badge bg-info">${dados[i]['EMAIL']}</span>
                                </center>
                            </div>
                            <div class="col-12">
                                <center>
                                    ${btnOpcoes}
                                </center>
                            </div>
                        </td>
                    </tr>`;
            }
            $('#tableBodyDados').html(htmlTabela);
        }

        function inserirUsuario() {
            validacao = true;
            var mensagemErro = 'Valide os campos obrigatórios.';

            var inputIDs = ['inputUsuarioNome', 'inputUsuarioEmail', 'inputUsuarioSenha', 'inputUsuarioSenhaConfirmacao'];

            for (var i = 0; i < inputIDs.length; i++) {
                var inputID = inputIDs[i];
                var input = $('#' + inputID);
                
                if (input.val() === '' ) {
                    input.addClass('is-invalid');
                    validacao = false;
                } else {
                    input.removeClass('is-invalid');
                }
            }

            if($('#inputUsuarioSenha').val() != $('#inputUsuarioSenhaConfirmacao').val()){
                mensagemErro = 'As senhas não são iguais';
                $('#inputUsuarioSenha').addClass('is-invalid');
                $('#inputUsuarioSenhaConfirmacao').addClass('is-invalid');

                validacao = false;
            }

            if( !(/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($('#inputUsuarioEmail').val()))){
                mensagemErro = 'Insira um email válido';
                validacao = false;
            }

            if(validacao){
                var nome = $('#inputUsuarioNome').val();
                var email = $('#inputUsuarioEmail').val();
                var senha = $('#inputUsuarioSenha').val();

                if($('#inputUsuarioID').val() == '0'){
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'nome': nome,
                            'email': email,
                            'senha': senha,
                            'ADMINISTADOR': $('#inputUsuarioAdministrador').prop('checked') ? 'S' : 'N'
                        },
                        url:"{{route('usuarios.inserir')}}",
                        success:function(r){
                            if(r.SITUACAO == 'SUCESSO'){
                                buscarDados();
    
                                dispararAlerta('success', 'Usuario inserido com sucesso!')
    
                                $('#modal-cadastro').modal('hide');
                            } else {
                                dispararAlerta('warning', r.MENSAGEM);
                            }
                        },
                        error:err=>{exibirErro(err)}
                    });
                } else {
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'ID': $('#inputUsuarioID').val(),
                            'nome': nome,
                            'email': email,
                            'senha': senha,
                            'ADMINISTADOR': $('#inputUsuarioAdministrador').prop('checked') ? '1' : '0'
                        },
                        url:"{{route('usuarios.alterar')}}",
                        success:function(r){
                            if(r.SITUACAO == 'SUCESSO'){
                                buscarDados();
    
                                dispararAlerta('success', 'Usuario inserido com sucesso!')
    
                                $('#modal-cadastro').modal('hide');
                            } else {
                                dispararAlerta('warning', r.MENSAGEM);
                            }
                        },
                        error:err=>{exibirErro(err)}
                    });
                }
            } else {
                dispararAlerta('warning', mensagemErro)
            }
            
        }

        function alterarUsuario(id){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'ID': id
                },
                url:"{{route('usuarios.buscar')}}",
                success:function(r){
                    var dadosUsuario = r.dados[0];
                    $('#inputUsuarioID').val(id)
                    $('#inputUsuarioNome').val(dadosUsuario['NAME'])
                    $('#inputUsuarioEmail').val(dadosUsuario['EMAIL'])
                    $('#inputUsuarioSenha').val('');
                    $('#inputUsuarioSenhaConfirmacao').val('');
                    $('#inputUsuarioAdministrador').prop('checked',dadosUsuario['ADMINISTRADOR'] == 1 ? true : false)

                    $('#modal-cadastro').modal('show');
                },
                error:err=>{exibirErro(err)}
            })
        }

        function inativarUsuario(id){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar o usuário?',
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
                            'ID': id
                        },
                        url:"{{route('usuarios.inativar')}}",
                        success:function(r){
                            dispararAlerta('success', 'Usuário inativado com sucesso.');

                            buscarDados();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        function resetarCampos(){
            $('#inputUsuarioID').val('0')
            $('#inputUsuarioNome').val('')
            $('#inputUsuarioEmail').val('')
            $('#inputUsuarioSenha').val('')
            $('#inputUsuarioSenhaConfirmacao').val('')
            $('#inputUsuarioAdministrador').prop('checked', false);
        }

        /* Matheus 29/03/2025 23:52:41 - MENUS */
            // Função para abrir o modal
            function abrirModalMenuUsuario(idUsuario) {
                $('#inputIDUsuario').val(idUsuario);
                carregarMenusUsuario(idUsuario);
                carregarOpcoesMenus();
                $('#modalMenuUsuario').modal('show');
            }

            // Carrega as opções de menus disponíveis
            function carregarOpcoesMenus() {
                $.ajax({
                    type: 'post',
                    url: "{{ route('menu.opcoes') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'ID_USUARIO': $('#inputIDUsuario').val()
                    },
                    success: function(response) {
                        let options = '<option value="">Selecione um menu</option>';
                        response.forEach(menu => {
                            options += `<option value="${menu.ID}">${menu.NOME}</option>`;
                        });
                        $('#selectMenu').html(options);
                    },
                    error:err=>{exibirErro(err)}
                });
            }

            // Carrega os menus vinculados ao usuário
            function carregarMenusUsuario(idUsuario) {
                $.ajax({
                    type: 'post',
                    url: "{{ route('menu.usuario.buscar') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'ID_USUARIO': idUsuario
                    },
                    success: function(response) {
                        let html = '';
                        response.dados.forEach(item => {
                            html += `
                                <tr>
                                    <td>${item.NOME}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" onclick="removerMenuUsuario(${item.ID})">
                                            <i class="fas fa-trash"></i> Remover
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#tableMenuUsuario').html(html);
                    },
                    error:err=>{exibirErro(err)}
                });
            }

            // Remove menu do usuário
            function removerMenuUsuario(id) {
                Swal.fire({
                    title: 'Confirmação',
                    text: 'Deseja realmente desvincular este menu?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'post',
                            url: "{{ route('menu.usuario.remover') }}",
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'ID': id
                            },
                            success: function() {
                                const idUsuario = $('#inputIDUsuario').val();
                                carregarMenusUsuario(idUsuario);
                                carregarOpcoesMenus();
                            },
                            error:err=>{exibirErro(err)}
                        });
                    }
                });
            }

            // Adiciona novo menu ao usuário
            $('#btnAdicionarMenu').click(function() {
                const idUsuario = $('#inputIDUsuario').val();
                const idMenu = $('#selectMenu').val();
                
                if(!idMenu) {
                    alert('Selecione um menu');
                    return;
                }

                $.ajax({
                    type: 'post',
                    url: "{{ route('menu.usuario.inserir') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'ID_USUARIO': idUsuario,
                        'ID_MENU': idMenu
                    },
                    success: function(response) {
                        if(response.error) {
                            alert(response.error);
                            return;
                        }
                        $('#selectMenu').val('');
                        carregarMenusUsuario(idUsuario);
                    },
                    error:err=>{exibirErro(err)}
                });
            });

        /* Matheus 29/03/2025 23:52:49 - MENUS */

        $('#btnNovo').click(() => {
            exibirModalCadastro();
        });

        $('#btnUsuarioSalvar').click(() => {
            inserirUsuario();
        });

        $(document).ready(function() {
            buscarDados();
        })
    </script>
@stop