@extends('adminlte::page')

@section('title', 'GSSoftware - Projetos')

@section('content')

    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Projetos</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <div class="col-12 col-md-6 row d-flex d-flex justify-content-end ">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-block btn-warning" id="btnNovo">
                            <i class="fas fa-plus"></i>
                            <span class="ml-1">Novo Projeto</span>
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
                        <input type="text" class="form-control form-control-border" id="inputFiltro" placeholder="Filtro" onkeyup="buscarDados()">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th><center>Descrição</center></th>
                            <th><center>Início</center></th>
                            <th><center>Fim</center></th>
                            <th><center>Valor</center></th>
                            <th><center>Pagamento</center></th>
                            <th><center>Ações</center></th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyProjetos">
                        <!-- Dados dos projetos serão preenchidos aqui -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Cadastro/Edição de Projeto -->
    <div class="modal fade" id="modal-projeto" tabindex="-1" role="dialog" aria-labelledby="modalProjetoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProjetoLabel">Cadastrar Projeto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" class="form-control form-control-border" id="inputTitulo" placeholder="Título do Projeto">
                        </div>
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea class="form-control form-control-border" id="inputDescricao" placeholder="Descrição do Projeto"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Data Início</label>
                            <input type="date" class="form-control form-control-border" id="inputDataInicio">
                        </div>
                        <div class="form-group">
                            <label>Data Fim</label>
                            <input type="date" class="form-control form-control-border" id="inputDataFim">
                        </div>
                        <div class="form-group">
                            <label>Valor</label>
                            <input type="number" class="form-control form-control-border" id="inputValor" placeholder="Valor do Projeto">
                        </div>
                        <input type="hidden" id="inputIDProjeto" value="0">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarProjeto">Salvar</button>
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
        .table {
            background-color: #f8f9fa;
        }
        .table td, .table th {
            padding: 0px !important;
        }
        table tr:nth-child(even) {
            background: #007bff18;
        }
        .ui-autocomplete {
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
        // Buscar Projetos
        function buscarDados() {
            $.ajax({
                type: 'post',
                url: "{{route('projeto.buscar')}}",
                data: {
                    '_token': '{{csrf_token()}}',
                    'filtro': $('#inputFiltro').val()
                },
                success: function(r) {
                    popularTabelaDados(r.dados);
                },
                error: err => {
                    console.log(err);
                }
            });
        }

        // Popular lista de projetos
        function popularTabelaDados(dados) {
            let htmlTabela = '';
            dados.forEach(projeto => {
                const dataInicio = moment(projeto.DATA_INICIO).format('DD/MM/YYYY');
                const dataFim = moment(projeto.DATA_FIM).format('DD/MM/YYYY');
                const pagamento = projeto.PAGAMENTO_REALIZADO === 'S' ? 'Pago' : 'Pendente';

                btnEditar = `<li class="dropdown-item" onclick="editarProjeto(${projeto.ID})"><span class="btn"><i class="fas fa-pen"></i></span> Editar</li>`;
                btnInativar = `<li class="dropdown-item" onclick="inativarProjeto(${projeto.ID})"><span class="btn"><i class="fas fa-trash"></i></span> Inativar</li>`;

                var btnOpcoes = ` <div class="input-group-prepend show justify-content-center" style="text-align: center">
                                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                Ações
                                            </button>
                                            <ul class="dropdown-menu">
                                                ${btnEditar}
                                                ${btnInativar}
                                            </ul>
                                        </div>`;

                htmlTabela += `
                    <tr>
                        <td>${projeto.TITULO}</td>
                        <td><center>${projeto.DESCRICAO}</center></td>
                        <td><center>${dataInicio}</center></td>
                        <td><center>${dataFim}</center></td>
                        <td><center>R$ ${projeto.VALOR.toFixed(2)}</center></td>
                        <td><center>${pagamento}</center></td>
                        <td>
                            <center>
                                ${btnOpcoes}
                            </center>
                        </td>
                    </tr>
                `;
            });
            $('#tableBodyProjetos').html(htmlTabela);
        }

        function salvarProjeto() {
            // Validação dos campos
            let validacao = true;
            const camposObrigatorios = ['inputTitulo', 'inputDescricao', 'inputDataInicio', 'inputDataFim', 'inputValor'];

            camposObrigatorios.forEach(campo => {
                if ($('#' + campo).val() === '') {
                    $('#' + campo).addClass('is-invalid'); // Adiciona classe de erro
                    validacao = false;
                } else {
                    $('#' + campo).removeClass('is-invalid'); // Remove classe de erro
                }
            });

            if (!validacao) {
                Swal.fire('Atenção!', 'Preencha todos os campos obrigatórios.', 'warning');
                return;
            }

            // Dados do formulário
            const idProjeto = $('#inputIDProjeto').val();
            const titulo = $('#inputTitulo').val();
            const descricao = $('#inputDescricao').val();
            const dataInicio = $('#inputDataInicio').val();
            const dataFim = $('#inputDataFim').val();
            const valor = $('#inputValor').val();

            // Define a URL e os dados com base no ID do projeto
            const url = idProjeto == 0 ? "{{route('projeto.inserir')}}" : "{{route('projeto.alterar')}}";
            const dados = {
                '_token': '{{csrf_token()}}',
                'ID': idProjeto,
                'TITULO': titulo,
                'DESCRICAO': descricao,
                'DATA_INICIO': dataInicio,
                'DATA_FIM': dataFim,
                'VALOR': valor
            };

            // Requisição AJAX
            $.ajax({
                type: 'post',
                url: url,
                data: dados,
                success: function(r) {
                    $('#modal-projeto').modal('hide'); // Fecha o modal
                    buscarDados(); // Atualiza a lista de projetos
                    Swal.fire('Sucesso!', 'Projeto salvo com sucesso.', 'success'); // Exibe mensagem de sucesso
                },
                error: err => {
                    console.log(err);
                    Swal.fire('Erro!', 'Ocorreu um erro ao salvar o projeto.', 'error'); // Exibe mensagem de erro
                }
            });
        }

        function editarProjeto(idProjeto) {
            $.ajax({
                type: 'post',
                url: "{{route('projeto.buscar')}}",
                data: {
                    '_token': '{{csrf_token()}}',
                    'ID': idProjeto
                },
                success: function(r) {
                    const projeto = r.dados[0];
                    $('#inputIDProjeto').val(projeto.ID);
                    $('#inputTitulo').val(projeto.TITULO);
                    $('#inputDescricao').val(projeto.DESCRICAO);
                    $('#inputDataInicio').val(projeto.DATA_INICIO);
                    $('#inputDataFim').val(projeto.DATA_FIM);
                    $('#inputValor').val(projeto.VALOR);
                    $('#modal-projeto').modal('show');
                },
                error: err => {
                    console.log(err);
                    Swal.fire('Erro!', 'Ocorreu um erro ao buscar os dados do projeto.', 'error');
                }
            });
        }

        function inativarProjeto(idProjeto) {
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar este projeto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: "{{route('projeto.inativar')}}",
                        data: {
                            '_token': '{{csrf_token()}}',
                            'ID': idProjeto
                        },
                        success: function(r) {
                            Swal.fire('Sucesso!', 'Projeto inativado com sucesso.', 'success');
                            buscarDados();
                        },
                        error: err => {
                            console.log(err);
                            Swal.fire('Erro!', 'Ocorreu um erro ao inativar o projeto.', 'error');
                        }
                    });
                }
            });
        }

        function resetarCamposCadastro() {
            // Limpa os campos do formulário
            $('#inputIDProjeto').val('0'); // Reseta o ID do projeto (0 indica novo projeto)
            $('#inputTitulo').val(''); // Limpa o campo de título
            $('#inputDescricao').val(''); // Limpa o campo de descrição
            $('#inputDataInicio').val(''); // Limpa o campo de data de início
            $('#inputDataFim').val(''); // Limpa o campo de data de fim
            $('#inputValor').val(''); // Limpa o campo de valor

            // Remove classes de erro (se houver)
            $('#inputTitulo').removeClass('is-invalid');
            $('#inputDescricao').removeClass('is-invalid');
            $('#inputDataInicio').removeClass('is-invalid');
            $('#inputDataFim').removeClass('is-invalid');
            $('#inputValor').removeClass('is-invalid');
        }

        // Abrir modal de cadastro
        $('#btnNovo').click(function() {
            resetarCamposCadastro();

            $('#modal-projeto').modal('show');
        });

        // Salvar Projeto
        $('#btnConfirmarProjeto').click(function() {
            salvarProjeto();
        });

        $(document).ready(function() {
            buscarDados();
        });
    </script>
@stop