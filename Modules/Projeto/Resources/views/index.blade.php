@extends('adminlte::page')

@section('title', 'GSSoftware - Projetos')

@section('content')

    <div id="divLista">
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
                        <div class="col-12">
                            <input type="text" class="form-control form-control-border" id="inputFiltro" placeholder="Projeto / Cliente / Ano do Projeto" >
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th><center>Cliente</center></th>
                                <th><center>Início</center></th>
                                <th><center>Fim</center></th>
                                <th><center>Valor</center></th>
                                <th><center>Pagamento</center></th>
                                <th><center>Conclusão</center></th>
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
    </div>

    <div class="card d-none" id="divDetalhesProjeto">
        <div class="card-header">
            <h3 class="card-title">Detalhes do Projeto</h3>

            <div class="card-tools">
            <button type="button" class="btn btn-tool" id="btnFecharModalDetalhes">
                <i class="fas fa-times"></i>
            </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row p-2">
            <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                <div class="row">
                <div class="col-12 col-sm-3">
                    <div class="info-box bg-light">
                        <div class="info-box-content" id="divSpanValorProjeto">
                            <span class="info-box-text text-center text-muted">Valor Projeto</span>
                            <span class="info-box-number text-center text-muted mb-0 bg-info" id="spanValorProjeto">R$ 10.000,00</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="info-box bg-light">
                        <div class="info-box-content" id="divSpanGastosProjeto">
                            <span class="info-box-text text-center text-muted">Gastos do Projeto</span>
                            <span class="info-box-number text-center text-muted mb-0 bg-danger" id="spanGastosProjeto">R$ 5.000,00</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="info-box bg-light">
                    <div class="info-box-content" id="divSpanLucroProjeto">
                        <span class="info-box-text text-center text-muted">Lucro do Projeto</span>
                        <span class="info-box-number text-center text-muted mb-0 bg-success" id="spanLucroProjeto">R$ 5.000,00</span>
                    </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="info-box bg-light">
                    <div class="info-box-content" id="divSpanDataEntregaProjeto">
                        <span class="info-box-text text-center text-muted">Data de Entrega</span>
                        <span class="info-box-number text-center text-muted mb-0" id="spanDataEntregaProjeto">20/05/2025</span>
                    </div>
                    </div>
                </div>
                </div>
                <div class="row">
                    <div class="col-12 m-2">
                        <h4>Gastos Recentes</h4>
                        <div id="divGastosRecente">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">

                <h3 class="text-primary"><i class="fas fa-project-diagram"></i> <span id="spanTituloProjeto">Titulo do Projeto</span></h3>
                <p class="text-muted" id="spanDescricaoProjeto">Descrição do projeto</p>
                <br>
                <div class="text-muted">
                    <p class="text-sm">Cliente
                        <b class="d-block" id="spanClienteProjeto">Eletro Barbosa</b>
                    </p>
                    <p class="text-sm">Responsável
                        <b class="d-block" id="spanUsuarioProjeto">Usuário</b>
                    </p>
                </div>

                <div class="text-center mt-5 mb-3" id="divBotoesProjeto">
                    <button class="btn btn-sm btn-danger" id="btnAdicionarGastos">Adicionar Gastos</button>
                    <button class="btn btn-sm btn-dark" id="btnAdicionarArquivos">Adicionar Arquivos</button>
                    <button class="btn btn-sm btn-primary" id="concluirProjeto">Concluir Projeto</button>
                </div>
            </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>

    <!-- Modal de Cadastro/Edição de Projeto -->
    <div class="modal fade" id="modal-projeto" tabindex="-1" role="dialog">
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
                        <div class="col-12 row d-flex p-0 m-0">
                            <label class="col-12 m-0 p-0">Cliente</label>
                            <div class="col  m-0 p-0">
                                <input type="text" class="form-control form-control-border col-12" placeholder="Cliente" id="inputCliente">
                                <input type="hidden" id="inputIDCliente">
                            </div>
                            <div class="col btnLimparCliente d-none m-0 p-0">
                                <button id="btnLimparCliente" class="btnLimparCliente btn btn-sm btn-danger d-none col-12"><i class="fas fa-eraser"></i>LIMPAR</button>
                            </div>
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

    <div class="modal fade" id="modal-gasto" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Adicionar Gasto</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <div class="form-group">
                        <label>Titulo</label>
                        <input type="text" class="form-control form-control-border" placeholder="Titulo" id="inpuGastoTitulo">
                    </div> 
                    <div class="form-group">
                        <label>Valor</label>
                        <input type="text" class="form-control form-control-border" placeholder="R$ 0,00" id="inpuGastoValor">
                    </div> 
                    <div class="form-group">
                        <label>Vencimento</label>
                        <input type="date" class="form-control form-control-border" id="inputGastoVencimento" value="{{date('Y-m-d')}}">
                    </div> 
                    <div class="form-group">
                        <label>Descrição</label>
                        <textarea class="form-control form-control-border" id="inputGastoDescricao" placeholder="Descrição" rows="4"></textarea>
                    </div> 
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
                    <button type="button" class="btn btn-primary" id="btnSalvarGasto">Salvar</button> 
                </div> 
            </div> 
        </div> 
    </div> 

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Documentos do Projeto <span id="titleDocumento"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body m-0 p-0">
                    <div class="card m-0 p-0">
                        <div class="card-header">
                            <div class="row d-flex">
                                <div class="col-12 d-flex justify-content-end">
                                    <button class="btn btn-info col-3" id="btnAdicionarArquivoProjeto">
                                        <i class="fas fa-plus"></i> Arquivo
                                    </button>
                                    <button class="btn btn-primary col-3" id="btnAdicionarPasta">
                                        <i class="fas fa-plus"></i> Pasta
                                    </button>
                                </div>

                                {{-- Matheus 04/05/2025 20:08:06 - CAMINHO DA DOCUMENTACAO --}}
                                <ol class="breadcrumb float-sm-right" id="olTipoArquivo">                                    
                                </ol>
                                <div class="col-12 d-none">
                                    <input type="hidden" id="inputIDProjeto">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="inputArquivoDocumentacao" onchange="validaDocumento()">
                                            <label class="custom-file-label" for="inputArquivoDocumentacao" id="labelInputArquivoDocumentacao">Selecionar Arquivos</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body m-0 p-0">
                            <div class="col-12">
                                <table class="table table-responsive-xs">
                                    <tbody id="tableBodyDocumentos">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-pessoa" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog modal-lg"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Participantes do Projeto</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body">
                    <div class="row d-flex">
                        <input type="hidden" id="inputIdProjetoPessoa">
                        <div class="form-group col-6 col-md-8">
                            <select id="selectPessoa" class="form-control form-control-border">
                                <option value="0">Selecionar Participante</option>
                            </select>
                        </div>

                        <div class="col-6 col-md-4">
                            <button type="button" class="btn btn-block btn-info" id="btnSalvarPessoa"><i class="fas fa-plus"></i></button>
                        </div>

                        <div class="col-12">
                            <table class="table table-responsive-xs">
                                <thead>
                                    <th>Nome</th>
                                    <th><center>Açoes</center></th>
                                </thead>
                                <tbody id="tbodyDadosPessoa">                                    
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

    <div class="modal fade" id="modalEtapasProjeto" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Etapas do Projeto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="inputIDProjeto">
                    
                    <!-- Formulário de cadastro -->
                    <div class="row mb-3">
                        <div class="col-10">
                            <input type="text" class="form-control" id="inputDescricaoEtapa" placeholder="Descreva a nova etapa">
                        </div>
                        <div class="col-2">
                            <button class="btn btn-primary w-100" id="btnSalvarEtapa">Salvar</button>
                        </div>
                    </div>
                    
                    <!-- Lista de etapas -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="60%">Descrição</th>
                                <th width="20%">Situação</th>
                                <th width="20%">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tableEtapasProjeto">
                            <!-- Dados serão carregados aqui -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

       <div class="modal fade" id="modal-pasta" style="display: none;" aria-hidden="true"> 
           <div class="modal-dialog"> 
               <div class="modal-content"> 
                   <div class="modal-header"> 
                       <h4 class="modal-title">Adicionar Pasta</h4> 
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                           <span aria-hidden="true">×</span> 
                       </button> 
                   </div> 
                   <div class="modal-body"> 
                        <div class="form-group col-12">
                            <label>Nome da Pasta</label>
                            <input type="text" id="inputNomePasta" class="form-control col-12" placeholder="Nome da Pasta">
                        </div> 
                   </div> 
                   <div class="modal-footer justify-content-between"> 
                       <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
                       <button type="button" class="btn btn-primary" id="btnSalvarPasta">Salvar</button> 
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

        .progress {
            border-radius: 10px;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
        }

        .progress-bar {
            font-size: 12px;
            line-height: 20px;
            font-weight: bold;
            transition: width 0.6s ease;
        }

        .modal-xl{
            max-width: 100%!important;
            padding: 0px!important;
            margin: 0px!important;
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
        $('#inpuGastoValor').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' });

        let idProjetoSelecionado = 0;
        var buscarDetalhesProjetoSelecionado = false;
        var timeoutFiltro = 0;
        var tipoArquivoBusca = 0;
        var idPastaAtual = 0;

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
                    exibirErro(err);
                }
            });
        }

        function buscarPessoaProjeto(idProjeto) {
            $.ajax({
                type: 'post',
                url: "{{route('projeto.buscar.pessoa')}}",
                data: {
                    '_token': '{{csrf_token()}}',
                    'ID_PROJETO': idProjeto
                },
                success: function(r) {
                    $('#inputIdProjetoPessoa').val(idProjeto);
    
                    buscarPessoaDisponivelProjeto(idProjeto);

                    popularTabelaPessoaProjeto(r.dados);

                    $('#modal-pessoa').modal('show');
                },
                error: err => {
                    exibirErro(err);
                }
            });
        }

        function buscarPessoaDisponivelProjeto(idProjeto) {
            $.ajax({
                type: 'post',
                url: "{{route('pessoa.buscar')}}",
                data: {
                    '_token': '{{csrf_token()}}',
                    'ID_TIPO': 2,
                    'ID_PROJETO': idProjeto
                },
                success: function(r) {
                    popularSelectPessoaDisponivelProjeto(r);
                },
                error: err => {
                    exibirErro(err);
                }
            });
        }

        function buscarArquivoTipo(tipoArquivo){
            tipoArquivoBusca = tipoArquivo;
            buscarDocumentos();
        }

        // Popular lista de projetos
        function popularSelectPessoaDisponivelProjeto(dados) {
            let htmlTabela = '<option value="0">Selecionar Participante </option>';
            var contador = 0;
            dados.forEach(pessoa => {
                htmlTabela += `
                    <option value="${pessoa.ID}">
                        ${pessoa.NOME}
                    </option>
                `;
            });
            $('#selectPessoa').html(htmlTabela);
        }

        function popularTabelaPessoaProjeto(dados) {
            let htmlTabela = '';
            dados.forEach(projeto => {
                htmlTabela += `
                    <tr>
                        <td>${projeto.PESSOA}</td>
                        <td>
                            <center>
                               <button class="btn" onclick="inativarPessoaProjeto(${projeto.ID})"><i class="fas fa-trash"></i></button>
                            </center>
                        </td>
                    </tr>
                `;
            });
            $('#tbodyDadosPessoa').html(htmlTabela);
        }

        function popularTabelaDados(dados) {
            let htmlTabela = '';
            dados.forEach(projeto => {
                const dataInicio = moment(projeto.DATA_INICIO).format('DD/MM/YYYY');
                const dataFim = moment(projeto.DATA_FIM).format('DD/MM/YYYY');
                const pagamento = projeto.PAGAMENTO_REALIZADO === 'S' ? 'Pago' : 'Pendente';
                const porcentagem = projeto.PORCENTAGEM_ETAPA == null ? 0 : projeto.PORCENTAGEM_ETAPA;
                
                // Criação do gráfico de progresso
                const progressoHTML = `
                    <div class="progress" style="height: 20px; width: 100px; margin: 0 auto;">
                        <div class="progress-bar ${porcentagem == 100 ? 'bg-success' : 'bg-info'}" 
                            role="progressbar" 
                            style="width: ${porcentagem}%" 
                            aria-valuenow="${porcentagem}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                            ${porcentagem}%
                        </div>
                    </div>
                `;

                btnEditar = `<li class="dropdown-item" onclick="editarProjeto(${projeto.ID})"><span class="btn"><i class="fas fa-pen"></i></span> Editar</li>`;
                btnArquivos = `<li class="dropdown-item" onclick="cadastarDocumento(${projeto.ID}, '${projeto.TITULO}')"><span class="btn"><i class="fas fa-file"></i></span> Arquivos</li>`;
                btnPessoas = `<li class="dropdown-item" onclick="buscarPessoaProjeto(${projeto.ID}, '${projeto.TITULO}')"><span class="btn"><i class="fas fa-user"></i></span> Participantes</li>`;
                btnEtapas = `<li class="dropdown-item" onclick="abrirModalEtapas(${projeto.ID})"><span class="btn"><i class="fas fa-tasks"></i></span> Etapas</li>`;
                btnVisualizar = `<li class="dropdown-item" onclick="detalhesProjeto(${projeto.ID})"><span class="btn"><i class="fas fa-cogs"></i></span> Gestão do Projeto</li>`;
                btnInativar = `<li class="dropdown-item" onclick="inativarProjeto(${projeto.ID})"><span class="btn"><i class="fas fa-trash"></i></span> Inativar</li>`;

                var btnOpcoes = ` <div class="input-group-prepend show justify-content-center" style="text-align: center">
                                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                Ações
                                            </button>
                                            <ul class="dropdown-menu">
                                                ${btnVisualizar}
                                                ${btnEditar}
                                                ${btnArquivos}
                                                ${btnPessoas}
                                                ${btnEtapas}
                                                ${btnInativar}
                                            </ul>
                                        </div>`;

                htmlTabela += `
                    <tr>
                        <td>${projeto.TITULO}</td>
                        <td><center>${projeto.CLIENTE}</center></td>
                        <td><center>${dataInicio}</center></td>
                        <td><center>${dataFim}</center></td>
                        <td><center>${mascaraFinanceira(projeto.VALOR)}</center></td>
                        <td><center>${pagamento}</center></td>
                        <td><center>${progressoHTML}</center></td>
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

        function concluirProjeto(){
            Swal.fire({
                title: 'Deseja realmente concluir o projeto?',
                text: 'Será inserido um contas a receber referente ao valor do projeto.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: "{{route('projeto.concluir')}}",
                        data: {
                            '_token': '{{csrf_token()}}',
                            'ID_PROJETO': idProjetoSelecionado
                        },
                        success: function(r) {
                            dispararAlerta('success', 'Projeto concluido com sucesso!');

                            detalhesProjeto(idProjetoSelecionado);
                        },
                        error: err => {
                            exibirErro(err)
                        }
                    });
                }
            });
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

            if($('#inputIDCliente').val() == 0){
                $('#inputCliente').addClass('is-invalid');
                validacao = false;
            } else {
                $('#inputCliente').removeClass('is-invalid');
            }

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
            const idCliente = $('#inputIDCliente').val();

            // Define a URL e os dados com base no ID do projeto
            const url = idProjeto == 0 ? "{{route('projeto.inserir')}}" : "{{route('projeto.alterar')}}";
            const dados = {
                '_token': '{{csrf_token()}}',
                'ID': idProjeto,
                'TITULO': titulo,
                'DESCRICAO': descricao,
                'DATA_INICIO': dataInicio,
                'DATA_FIM': dataFim,
                'VALOR': valor,
                'ID_CLIENTE': idCliente
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

        function salvarGasto(){
            var validacao = true;

            inputs = [
                'inpuGastoTitulo',
                'inputGastoDescricao',
                'inputGastoVencimento'
            ]
            
            for(i = 0; i< inputs.length; i++){
                if($('#'+inputs[i]).val() == ''){
                    $('#'+inputs[i]).addClass('is-invalid');
                    validacao = false;
                    
                }else{
                    $('#'+inputs[i]).removeClass('is-invalid');
                };
            }

            if(limparMascaraFinanceira($('#inpuGastoValor').val()) == 0){
                validacao = false;
                $('#inpuGastoValor').addClass('is-invalid')
            } else {
                $('#inpuGastoValor').removeClass('is-invalid')
            }

            if(validacao){
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                    '_token':'{{csrf_token()}}',
                    'valor': limparMascaraFinanceira($('#inpuGastoValor').val()),
                    'dataVencimento': $('#inputGastoVencimento').val(),
                    'descricao': $('#inpuGastoTitulo').val(),
                    'observacao': $('#inputGastoDescricao').val(),
                    'ID_ORIGEM': 3,
                    'ID_PROJETO': idProjetoSelecionado
                    },
                    url:"{{route('contaspagar.inserir')}}",
                    success:function(r){
                        $('#modal-gasto').modal('hide');

                        detalhesProjeto(idProjetoSelecionado);
                        dispararAlerta('success', 'Gasto registrado com suceso!');
                    },
                    error:err=>{exibirErro(err)}
                })
            } else {
                dispararAlerta('warning', 'Verifique os campos obrigatórios!');
            }
        }

        function salvarPessoa(){
            var idPessoa = $('#selectPessoa').val();
            var idProjeto = $('#inputIdProjetoPessoa').val();
            var validacao = true;

            if(idPessoa == 0){
                dispararAlerta('warning', 'Informe ao menos uma pessoa!');
                validacao = false;
            }

            if(validacao){
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                       '_token':'{{csrf_token()}}',
                       'ID_PROJETO': idProjeto,
                       'ID_PESSOA': idPessoa
                    },
                    url:"{{route('projeto.inserir.pessoa')}}",
                    success:function(r){
                        dispararAlerta('success', 'Participante vinculado com sucesso!');
    
                        buscarPessoaDisponivelProjeto(idProjeto);
    
                        buscarPessoaProjeto(idProjeto);
                    },
                    error:err=>{exibirErro(err)}
                })
            }
        }

        function salvarPasta(){
            var validacao = true;
            var nomePasta = $('#inputNomePasta').val();

            if(nomePasta.length == 0){
                dispararAlerta('warning', 'Informe um nome para a pasta!');
                validacao = false;
            }

            if(validacao){
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                       '_token':'{{csrf_token()}}',
                       'NOME': nomePasta,
                       'ID_PASTA_PAI': idPastaAtual,
                       'ID_PROJETO': $('#inputIDProjeto').val(),
                    },
                    url:"{{route('projeto.inserir.pasta')}}",
                    success:function(r){
                        buscarCaminhoAtual();

                        dispararAlerta('success', 'Pasta salva com sucesso!');

                        $('#modal-pasta').modal('hide');

                        buscarDocumentos();
                    },
                    error:err=>{exibirErro(err)}
                })
            }
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
                    $('#inputIDCliente').val(projeto.ID_CLIENTE);
                    $('#inputCliente').val(projeto.CLIENTE);

                    $('#inputCliente').attr('disabled', true);
                    $('.btnLimparCliente').removeClass('d-none');

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

        function inativarPessoa(idPessoa) {
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja remover o participante do projeto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: "{{route('projeto.inativar.pessoa')}}",
                        data: {
                            '_token': '{{csrf_token()}}',
                            'ID': idPessoa
                        },
                        success: function(r) {
                            var idProjeto = $('#inputIdProjetoPessoa').val();

                            dispararAlerta('success', 'Participante removido com sucesso!');
        
                            buscarPessoaDisponivelProjeto(idProjeto);
        
                            buscarPessoaProjeto(idProjeto);
                        },
                        error: err => {
                            console.log(err);
                            Swal.fire('Erro!', 'Ocorreu um erro ao inativar o projeto.', 'error');
                        }
                    });
                }
            });
        }

        function inativarEtapa(idEtapa, idProjeto) {
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar a etapa do projeto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: "{{route('projeto.inativar.etapa')}}",
                        data: {
                            '_token': '{{csrf_token()}}',
                            'ID_ETAPA': idEtapa
                        },
                        success: function(r) {
                            dispararAlerta('success', 'Etapa inativada com sucesso!');
                            
                            carregarEtapas($('#inputIDProjeto').val());
                        },
                        error: err => {
                            console.log(err);
                            Swal.fire('Erro!', 'Ocorreu um erro ao inativar o projeto.', 'error');
                        }
                    });
                }
            });
        }

        function detalhesProjeto(idProjeto){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                   '_token':'{{csrf_token()}}',
                   'ID': idProjeto
                },
                url:"{{route('projeto.buscar')}}",
                success:function(r){
                    var dadosProjeto = r.dados[0];
                    var htmlGastos = '';
                    var htmlDocumento = '';
                    var badgeSituacao = 'bg-warning'
                    var situacaoProjeto = 'EM ANDAMENTO';

                    idProjetoSelecionado = idProjeto;

                    if(dadosProjeto.PAGAMENTO_REALIZADO == 'S'){
                        $('#divBotoesProjeto').addClass('d-none');
                        badgeSituacao = 'bg-success';
                        situacaoProjeto = 'CONCLUÍDO';
                    } else {
                        $('#divBotoesProjeto').removeClass('d-none');
                    }

                    $('#spanValorProjeto').html(mascaraFinanceira(dadosProjeto['VALOR']));
                    $('#spanGastosProjeto').html(mascaraFinanceira(dadosProjeto['VALOR_GASTO']));
                    $('#spanLucroProjeto').html(mascaraFinanceira(dadosProjeto['VALOR'] - dadosProjeto['VALOR_GASTO']));
                    $('#spanDataEntregaProjeto').html(moment(dadosProjeto['DATA_FIM']).format('DD/MM/YYYY'));
                    $('#spanTituloProjeto').html(`${dadosProjeto['TITULO']} <span class="badge ${badgeSituacao}">${situacaoProjeto}</span>`);
                    $('#spanDescricaoProjeto').html(dadosProjeto['DESCRICAO']);
                    $('#spanClienteProjeto').html(dadosProjeto['CLIENTE']);
                    $('#spanUsuarioProjeto').html(dadosProjeto['NOME_USUARIO']);
                    $('#spanGastosProjeto').html(mascaraFinanceira(dadosProjeto['VALOR_GASTO']));

                    dadosProjeto.GASTOS_PROJETO.forEach(projeto => {
                        htmlGastos += `
                            <div class="post">

                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="{{env('APP_URL')}}/vendor/adminlte/dist/img/dinheiro.jpg" alt="user image">
                                    <span class="username">
                                        <span>${projeto['DESCRICAO']} - Inserido em: ${mascaraData(projeto['DATA_INSERCAO'])}</span>
                                    </span>
                                    <span class="description">${mascaraFinanceira(projeto['VALOR'])}</span>
                                </div>

                                <p class="pl-3">
                                    ${projeto['OBSERVACAO']}
                                    <br>
                                    ${projeto['DATA_PAGAMENTO'] != null ? `Pago em: ${moment(projeto['DATA_PAGAMENTO']).format('DD/MM/YYYY')}` : ''}
                                    Inserido por: ${projeto['NOME_USUARIO']}
                                </p>
                            </div>`;

                    });

                    $('#divGastosRecente').html(htmlGastos);

                    $('#divLista').slideUp();

                    $('#divDetalhesProjeto').removeClass('d-none');
                    $('#divDetalhesProjeto').slideDown();

                },
                error:err=>{exibirErro(err)}
            })
        }

        function adicionarGasto(){
            $('#inpuGastoTitulo').val('');
            $('#inpuGastoValor').val('');
            $('#inputGastoDescricao').val('');
            $('#inputGastoVencimento').val(moment().format('YYYY-MM-DD'));

            $('#modal-gasto').modal('show');
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

            limparCampo('inputCliente', 'inputIDCliente', 'btnLimparCliente');
        }

        // MODAL ETAPAS
            function abrirModalEtapas(idProjeto) {
                $('#inputIDProjeto').val(idProjeto);
                carregarEtapas(idProjeto);
                $('#modalEtapasProjeto').modal('show');
            }

            // Função para carregar as etapas
            function carregarEtapas(idProjeto) {
                $.ajax({
                    type: 'post',
                    url: "{{ route('projeto.etapa.buscar') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'ID_PROJETO': idProjeto
                    },
                    success: function(response) {
                        let html = '';
                        response.dados.forEach(etapa => {
                            html += `
                                <tr>
                                    <td>${etapa.DESCRICAO}</td>
                                    <td><span class="badge ${etapa.SITUACAO === 'Pendente' ? 'bg-warning' : 'bg-success'}">${etapa.SITUACAO}</span></td>
                                    <td>
                                        ${etapa.SITUACAO === 'Pendente' ? 
                                            `<i onclick="concluirEtapa(${etapa.ID})" class="fas fa-check"></i>` :
                                            ``}
                                        <i class="fas fa-trash" onclick="inativarEtapa(${etapa.ID})"></i>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#tableEtapasProjeto').html(html);
                    }
                });
            }

            // Função para salvar nova etapa
            $('#btnSalvarEtapa').click(function() {
                const idProjeto = $('#inputIDProjeto').val();
                const descricao = $('#inputDescricaoEtapa').val();
                
                if(!descricao) {
                    alert('Informe a descrição da etapa');
                    return;
                }

                $.ajax({
                    type: 'post',
                    url: "{{ route('projeto.etapa.inserir') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'ID_PROJETO': idProjeto,
                        'DESCRICAO': descricao
                    },
                    success: function() {
                        $('#inputDescricaoEtapa').val('');

                        dispararAlerta('success', 'Etapa cadastrada com sucesso!');

                        carregarEtapas(idProjeto);
                    }
                });
            });

            // Função para concluir etapa
            function concluirEtapa(idEtapa) {
                Swal.fire({
                    title: 'Confirmação',
                    text: 'Deseja concluir a etapa do projeto?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    $.ajax({
                        type: 'post',
                        url: "{{ route('projeto.etapa.concluir') }}",
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'ID': idEtapa
                        },
                        success: function() {
                            const idProjeto = $('#inputIDProjeto').val();
                            carregarEtapas(idProjeto);
                        }
                    });
                });
            }
        // FIM

        // DOCUMENTOS
            function buscarCaminhoAtual(){
                editar = false;
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                        '_token':'{{csrf_token()}}',
                        'ID_PASTA_ATUAL': idPastaAtual,
                        'ID_PROJETO': $('#inputIDProjeto').val(),
                    },
                    url:"{{route('projeto.buscar.caminho')}}",
                    success:function(r){
                        popularCaminhoAtual(r.dados);
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
                        'ID_PROJETO': $('#inputIDProjeto').val(),
                        'ID_TIPO': tipoArquivoBusca,
                        'ID_PASTA_ATUAL': idPastaAtual
                    },
                    url:"{{route('projeto.buscar.documento')}}",
                    success:function(r){
                        popularListaDocumentos(r.dados);
                    },
                    error:err=>{exibirErro(err)}
                })
            }  

            function popularCaminhoAtual(dados){
                var htmlTabela = ``;
                var olTipoArquivo = ``;

                if(idPastaAtual > 0){
                    olTipoArquivo = `<li class="breadcrumb-item">
                                        <span style="cursor: pointer; text-decoration: underline" onclick="acessarPasta(0)">Geral</span>
                                    </li>`;
                }

                for(i=0; i< dados.length; i++){
                    var materialKeys = Object.keys(dados[i]);
                    for(j=0;j<materialKeys.length;j++){
                        if(dados[i][materialKeys[j]] == null){
                            dados[i][materialKeys[j]] = "";
                        }
                    }
                    var styleMenu = 'cursor: pointer; text-decoration: underline';
                    var funcaoMenu = `acessarPasta(${dados[i]['ID']})`;

                    if((i+1) == dados.length ){
                        styleMenu = '';
                        funcaoMenu = '';
                    }

                    olTipoArquivo += ` <li class="breadcrumb-item">
                                            <span style="${styleMenu}" onclick="${funcaoMenu}">${dados[i]['NOME']}</span>
                                        </li>`;

                }

                $('#olTipoArquivo').html(olTipoArquivo);
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
                    
                    var icone = 'fas fa-file';
                    var documentoCaminho = Documento[i]['CAMINHO_DOCUMENTO'].split('/')[3];
                    var funcao = `verDocumento('${Documento[i]['CAMINHO_DOCUMENTO']}')`;
                    var funcaoInativar = `inativarDocumento(${Documento[i]['ID']})`;
                    var classes = `text-decoration: underline;`;
                    var classeIcone = '';

                    if(Documento[i]['TIPO'] == 1){
                        icone = 'fas fa-folder-open';
                        documentoCaminho = Documento[i]['CAMINHO_DOCUMENTO'];
                        funcao = `acessarPasta(${Documento[i]['ID']})`;
                        funcaoInativar = `inativarPasta(${Documento[i]['ID']})`;
                        classes = '';
                        classeIcone = 'color: #f3c625;';
                    }

                    htmlDocumento += `
                        <tr id="tableRow${Documento[i]['ID']}">
                            <td class="tdTexto"><i onclick="${funcao}" style="${classeIcone}" class="${icone}"></i></td>
                            <td class="tdTexto">
                                <span style="${classes} cursor: pointer;" onclick="${funcao}">${documentoCaminho}</span>
                            </td>
                            <td>\
                                <center>\
                                    <button class="btn" onclick="${funcaoInativar}"><i class="fas fa-trash"></i></button>\
                                </center>\
                            </td>\                      
                        </tr>`;
                    }
                $('#tableBodyDocumentos').html(htmlDocumento)
            }

            function cadastarDocumento(idProjeto, descricaoProjeto, buscar = false){
                idPastaAtual = 0;
                buscarDetalhesProjetoSelecionado = buscar;

                $('#titleDocumento').text(idProjeto);
                $('#inputIDProjeto').val(idProjeto);

                buscarCaminhoAtual();
                buscarDocumentos();

                $('#modal-documentacao').modal('show');
                
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
                idProjeto = $('#inputIDProjeto').val();
                dataAnexo.append('meuArquivo', document.getElementById('inputArquivoDocumentacao').files[0]);
                dataAnexo.append('ID', idProjeto);

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
                                    'ID_PROJETO': idProjeto,
                                    'caminho': anexoCaminho,
                                    'ID_TIPO': idPastaAtual
                                },
                                url:"{{route('projeto.inserir.documento')}}",
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

            function verDocumento(caminhoDocumento){
                url = "{{env('APP_URL')}}/"+caminhoDocumento;

                window.open(url, '_blank');
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
                            url:"{{route('projeto.inativar.documento')}}",
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

            function inativarPasta(idPasta){
                Swal.fire({
                    title: 'Deseja realmente inativar a pasta?',
                    text: 'TODOS os arquivos e pasta dentro dela serão inativados também.',
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
                                'idPasta': idPasta
                            },
                            url:"{{route('projeto.inativar.pasta')}}",
                            success:function(r){
                                dispararAlerta('success', 'Pasta inativada com sucesso!');
                                buscarDocumentos();
                            },
                            error:err=>{exibirErro(err)}
                        })
                    }
                });
            }  

            function adicionarPasta(){
                $('#modal-documentacao').modal('hide');

                $('#inputNomePasta').val('');

                $('#modal-pasta').modal('show');
            }

            function acessarPasta(idPasta){
                idPastaAtual = idPasta;
                buscarDocumentos();
                buscarCaminhoAtual();
            }

            $('#btnAdicionarArquivoProjeto').on('click', () => {
                $('#inputArquivoDocumentacao').click();
            });

            $('#inputArquivoDocumentacao').on('change', () => {
                uploadArquivo();
            })

            $('#modal-pasta').on('hidden.bs.modal', function () {
                $('#modal-documentacao').modal('show');
            }); 
        // FIM

        // Abrir modal de cadastro
        $('#btnNovo').click(function() {
            resetarCamposCadastro();

            $('#modal-projeto').modal('show');
        });

        // Salvar Projeto
        $('#btnConfirmarProjeto').click(function() {
            salvarProjeto();
        });

        $("#inputCliente").autocomplete({
            source: function(request, cb) {
                param = request.term;
                $.ajax({
                    url: "{{route('pessoa.buscar')}}",
                    method: 'post',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'FILTRO_BUSCA': param,
                        'ID_TIPO': 1
                    },
                    dataType: 'json',
                    success: function(r) {
                        result = $.map(r, function(obj) {
                            return {
                                label: obj.info,
                                value: obj.NOME,
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
                    $('#inputCliente').val(selectedData.item.data.NOME);
                    $('#inputIDCliente').val(selectedData.item.data.ID);
                    $('#inputCliente').attr('disabled', true);
                    $('.btnLimparCliente').removeClass('d-none');
                } else {
                    limparCampo('inputCliente', 'inputIDCliente', 'btnLimparCliente');
                }
            }
        });

        $('#inputFiltro').on('keyup', () => {
            clearTimeout(timeoutFiltro);

            timeoutFiltro = setTimeout(() => {
                buscarDados();
            }, 1500);
        })

        $('#btnLimparCliente').on('click', () => {
            limparCampo('inputCliente', 'inputIDCliente', 'btnLimparCliente');
        })

        $('#btnAdicionarGastos').on('click', () => {
            adicionarGasto();
        })

        $('#btnSalvarGasto').on('click', () => {
            salvarGasto();
        });

        $('#btnAdicionarArquivos').on('click', () => {
            cadastarDocumento(idProjetoSelecionado, idProjetoSelecionado, true);
        })

        $('#btnFecharModalDetalhes').on('click', () => {
            $('#divLista').slideDown();

            $('#divDetalhesProjeto').slideUp();
        });

        $('#btnSalvarPessoa').on('click', () => {
            salvarPessoa();
        });

        $('#concluirProjeto').on('click', () => {
            concluirProjeto();
        });

        $('#btnAdicionarPasta').on('click', () => {
            adicionarPasta();
        });

        $('#btnSalvarPasta').on('click', () => {
            salvarPasta();
        });

        $('#modalEtapasProjeto').on('hidden.bs.modal', function () {
            buscarDados();
        }); 

        $('#modal-documentacao').on('hidden.bs.modal', function () {
            if(buscarDetalhesProjetoSelecionado){
                detalhesProjeto(idProjetoSelecionado);
            }
        }); 

        $(document).ready(function() {
            buscarDados();
        });
    </script>
@stop