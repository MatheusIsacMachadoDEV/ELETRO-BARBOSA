@php
    date_default_timezone_set('America/Sao_Paulo');
@endphp

@extends('adminlte::page')

@section('title', 'Lista de Materiais | GSSoftware')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Lista de Materiais</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <div class="col-12 col-md-6 row d-flex d-flex justify-content-end ">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-block btn-warning" id="btnNovo">
                            <i class="fas fa-list"></i>
                            <span class="ml-1">Nova Lista</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header m-0 p-0">
                <div class="row d-flex m-0 p-0">
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control form-control-border" id="inputFiltro" placeholder="Filtro" maxlength="8" onkeyup="buscarDados()">
                    </div>
                    <div class="col-12 col-md-4 row d-flex">
                        <div class="form-group col-12 col-md-5">
                            <input id="inputFiltroDataInicio" type="date" class="form-control form-control-border" value="{{date('Y-m-d')}}">
                        </div>
                        <label class="col-2">Até</label>
                        <div class="form-group col-12 col-md-5">
                            <input id="inputFiltroDataFim" type="date" class="form-control form-control-border">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-xs">
                    <thead>
                        <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">ID</th>
                        <th class="d-none d-lg-table-cell"><center>Data</center></th>
                        <th class="d-none d-lg-table-cell"><center>Valor</center></th>
                        <th class="d-none d-lg-table-cell"><center>Situação</center></th>
                        <th class="d-none d-lg-table-cell" style="width: 10vw"><center></center></th>
                    </thead>
                    <tbody id="tableBodyDados">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-cadastro" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog modal-xl"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Cadastro de Lista de Materiais</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <div class="row d-flex">
                        <input type="hidden" class="form-control form-control-border" id="inputCadastroID" disabled>

                        <div class="col-12 row d-flex p-0 m-0">
                            <div class="col">
                                <input type="text" class="form-control form-control-border col-12" placeholder="Fornecedor" id="inputCadastroItemFornecedor">
                                <input type="hidden" id="inputCadastroItemFornecedorID">
                            </div>
                            <div class="col btnLimparCadastroFornecedor d-none">
                                <button id="btnLimparCadastroFornecedor" class="btnLimparCadastroFornecedor btn btn-sm btn-danger d-none col-12"><i class="fas fa-eraser"></i>LIMPAR</button>
                            </div>                         
    
                            <div class="form-group col-4">
                                <input type="text" class="form-control form-control-border" placeholder="Item: Valor Total" id="inputCadastroValorTotal" disabled>
                            </div>
                        </div>

                        <div class="col-12 row d-flex" id="divItens">
    
                            <div class="col-6 row d-flex p-0 m-0">
                                <div class="col">
                                    <input type="text" class="form-control form-control-border col-12" placeholder="Item" id="inputCadastroItem">
                                    <input type="hidden" id="inputCadastroItemID">
                                </div>
                                <div class="col btnLimparCadastroItem d-none">
                                    <button id="btnLimparCadastroItem" class="btnLimparCadastroItem btn btn-sm btn-danger d-none col-12"><i class="fas fa-eraser"></i> LIMPAR</button>
                                </div>
                            </div>   
    
                            <div class="col-6">
                                <button type="button" class="btn btn-block btn-info" id="btnCadastroAdicionarItem"><i class="fas fa-plus"></i> Item</button>
                            </div>
                            
                            <div class="form-group col-6">
                                <input type="text" class="form-control form-control-border" placeholder="Item: Quantidade" id="inputCadastroItemQTDE">
                            </div>

                            <div class="form-group col-6">
                                <input type="text" class="form-control form-control-border" placeholder="Item: Valor Unitário" id="inputCadastroItemValor">
                            </div>
                        </div>

                        <div class="col-12">
                            <table class="table table-responsive-xs">
                                <thead>
                                    <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">Item</th>
                                    <th class="d-none d-lg-table-cell"><center>Fornecedor</center></th>
                                    <th class="d-none d-lg-table-cell"><center>Valor Unitário</center></th>
                                    <th class="d-none d-lg-table-cell"><center>Qtde</center></th>
                                    <th class="d-none d-lg-table-cell"><center>Valor Total</center></th>
                                    <th class="d-none d-lg-table-cell"><center>Ações</center></th>
                                </thead>
                                <tbody id="tableBodyDadosItens">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
                    <button type="button" class="btn btn-primary" id="btnCadastroSalvar">Salvar</button> 
                </div> 
            </div> 
        </div> 
    </div> 

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Documentação da Ordem <span id="titleDocumento"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="">
                                <input type="hidden" id="inputIDOrdemCompra">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
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
        var dadosItens = [];
        var valorTotalLista = 0;
        var timeoutFiltro = 0;
        let inputsAdicionaisItemCadastro = ['inputCadastroItemQTDE', 'inputCadastroItemValor'];
        
        $('#inputCadastroValorTotal').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', allowZero: false });
        $('#inputCadastroItemValor').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', allowZero: false });
        $('#inputCadastroItemQTDE').mask('000000000');
        
        function exibirModalCadastro() {
            resetarCampos();
            buscarDados();
            $('#modal-cadastro').modal('show');
        }
        
        function exibirModalEdicao(idLista) {
            resetarCampos();
        
            $.ajax({
                type: 'post',
                datatype: 'json',
                data: {
                    '_token': '{{csrf_token()}}',
                    'ID': idLista
                },
                url: "{{route('material.buscar.lista')}}",
                success: function(r) {
                    var dados = r.dados[0];
        
                    $('#inputCadastroID').val(idLista);
                    $('#inputCadastroValorTotal').val(mascaraFinanceira(dados['VALOR_TOTAL']));
                    
                    dadosItens = dados['ITENS'];
        
                    popularListaItens();
                    $('#modal-cadastro').modal('show');
                },
                error: err => { 
                    console.error(err);
                    Swal.fire('Erro!', 'Não foi possível carregar os dados da lista.', 'error');
                }
            })
        }
        
        function exibirModalVisualizacao(idLista) {
            resetarCampos();
        
            $.ajax({
                type: 'post',
                datatype: 'json',
                data: {
                    '_token': '{{csrf_token()}}',
                    'ID': idLista
                },
                url: "{{route('material.buscar.lista')}}",
                success: function(r) {
                    var dados = r.dados[0];
                    $('#divItens').addClass('d-none');
                    $('#btnCadastroSalvar').addClass('d-none');
                    $('#divItens').removeClass('d-flex');
        
                    $('#inputCadastroID').val(idLista);
                    $('#inputCadastroValorTotal').val(mascaraFinanceira(dados['VALOR_TOTAL']));
        
                    $('#inputCadastroID').prop('disabled', true);
                    $('#inputCadastroValorTotal').prop('disabled', true);

                    $('#inputCadastroItemFornecedor').addClass('d-none');
        
                    dadosItens = dados['ITENS'];
        
                    popularListaItens(false);
                    $('#modal-cadastro').modal('show');
                },
                error: err => { 
                    console.error(err);
                    Swal.fire('Erro!', 'Não foi possível carregar os dados da lista.', 'error');
                }
            })
        }
        
        function buscarDados() {
            $.ajax({
                type: 'post',
                datatype: 'json',
                data: {
                    '_token': '{{csrf_token()}}',
                    'filtro': $('#inputFiltro').val(),
                    'DATA_INICIO': $('#inputFiltroDataInicio').val(),
                    'DATA_FIM': $('#inputFiltroDataFim').val(),
                },
                url: "{{route('material.buscar.lista')}}",
                success: function(r) {
                    popularListaDados(r.dados);
                },
                error: err => { 
                    console.error(err);
                    Swal.fire('Erro!', 'Não foi possível carregar os dados.', 'error');
                }
            })
        }
        
        function popularListaDados(dados) {
            var htmlTabela = "";
        
            for (i = 0; i < dados.length; i++) {
                var Keys = Object.keys(dados[i]);
                for (j = 0; j < Keys.length; j++) {
                    if (dados[i][Keys[j]] == null) {
                        dados[i][Keys[j]] = "";
                    }
                }
        
                var btnAcoes = '';
                var btnOpcoes = '';
                var btnImprimir = `<li class="dropdown-item" onclick="gerarImpresso(${dados[i]['ID']})"><span class="btn"><i class="fas fa-print"></i> Imprimir</span></li>`;
                var btnVisualizar = `<li class="dropdown-item" onclick="exibirModalVisualizacao(${dados[i]['ID']})"><span class="btn"><i class="fas fa-eye"></i> Visualizar</span></li>`;
                var dataFormatada = moment(dados[i]['DATA_INSERCAO']).format('DD/MM/YYYY HH:mm');
                var situacao = dados[i]['SITUACAO'];
                var classeSituacao = 'bg-warning';

                if(situacao == 'APROVADO'){
                    classeSituacao = 'bg-success';
                } else if(situacao == 'REPROVADO'){
                    classeSituacao = 'bg-danger';
                }
        
                if (dados[i]['STATUS'] == 'A') {
                    btnAcoes = `<li class="dropdown-item" onclick="exibirModalEdicao(${dados[i]['ID']})"><span class="btn"><i class="fas fa-pen"></i> Editar</span></li>
                    <li class="dropdown-item" onclick="inativarLista(${dados[i]['ID']})"><span class="btn"><i class="fas fa-trash"></i> Inativar</span></li>`;
                }

                if(situacao == 'PENDENTE'){
                    btnAcoes = `${btnAcoes}
                                <li class="dropdown-item" onclick="alterarSituacao(${dados[i]['ID']}, 'APROVADO')"><span class="btn"><i style="color: green" class="fas fa-check"></i> Aprovar</span></li>
                                <li class="dropdown-item" onclick="alterarSituacao(${dados[i]['ID']}, 'REPROVADO')"><span class="btn"><i style="color: red" class="fas fa-times"></i> Reprovar</span></li>`
                }
        
                var usuarioCadastro = dados[i]['USUARIO_CADASTRO'];
                var usuarioInativacao = dados[i]['USUARIO_INATIVACAO'] ? ` - Inativado por: ${dados[i]['USUARIO_INATIVACAO']}` : '';
        
                var btnOpcoes = `<div class="input-group-prepend show justify-content-center" style="text-align: center">
                                    <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                        Ações
                                    </button>
                                    <ul class="dropdown-menu">
                                        ${btnAcoes}                                            
                                        ${btnVisualizar}                                            
                                        ${btnImprimir}                                            
                                    </ul>
                                </div>`;
        
                htmlTabela += `
                    <tr id="tableRow${dados[i]['ID']}" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['ID']} - ${usuarioCadastro}${usuarioInativacao}</td>
                        <td class="tdTexto"><center>${dataFormatada}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(dados[i]['VALOR_TOTAL'])}</center></td>
                        <td class="tdTexto"><center><span class="badge ${classeSituacao}">${situacao}</center></td>
                        <td>
                            <center>
                            ${btnOpcoes}
                            </center>
                        </td>
                    </tr>                   
                    <tr></tr>
                    <tr class="d-lg-none">
                        <td class="row d-flex ">
                            <div class="col-12 d-flex justify-content-center">
                                <span><b>${dados[i]['ID']} - ${usuarioCadastro}${usuarioInativacao}</b></span>
                                <span class="badge ${classeSituacao}">${situacao}</center>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <span><b>${dataFormatada}</b></span>
                                <span><b>${mascaraFinanceira(dados[i]['VALOR_TOTAL'])}</b></span>
                            </div>
                        </td>
                    </tr>
                `;
            }
        
            $('#tableBodyDados').html(htmlTabela);
        }
        
        function popularListaItens(editarItens = true) {
            var htmlTabela = "";
            var dados = dadosItens;
        
            for (i = 0; i < dados.length; i++) {
                var Keys = Object.keys(dados[i]);
                for (j = 0; j < Keys.length; j++) {
                    if (dados[i][Keys[j]] == null) {
                        dados[i][Keys[j]] = "";
                    }
                }
        
                btnAcoes = editarItens ? `<button class="btn btn-sm btn-danger" onclick="removerItem(${dados[i]['ID']})"><i class="fas fa-trash"></i></button>` : '';
                
                htmlTabela += `
                    <tr id="tableRow${dados[i]['ID']}" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['ITEM']}</td>
                        <td class="tdTexto"><center>${dados[i]['NOME_FORNECEDOR'] || '-'}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(dados[i]['VALOR_ITEM'])}</center></td>
                        <td class="tdTexto"><center>${dados[i]['QTDE']}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(parseFloat(dados[i]['VALOR_ITEM']) * parseInt(dados[i]['QTDE']))}</center></td>
                        <td>
                            <center>
                                ${btnAcoes}
                            </center>
                        </td>
                    </tr>
                `;
            }
        
            $('#tableBodyDadosItens').html(htmlTabela);
            calcularValorTotal();
        }
        
        function inserirMaterialLista() {
            let validacao = true;
        
            if (dadosItens.length == 0) {
                Swal.fire('Atenção!', 'Insira ao menos um item para a lista de materiais!', 'warning');
                validacao = false;
            }
        
            if (validacao) {
                const cadastroID = $('#inputCadastroID').val();
                const valorTotal = limparMascaraFinanceira($('#inputCadastroValorTotal').val());
                const url = cadastroID == 0 ? "{{route('material.inserir.lista')}}" : "{{route('material.alterar.lista')}}";
                
                const data = {
                    '_token': '{{csrf_token()}}',
                    'valorTotal': valorTotal,
                    'dadosItens': dadosItens
                };
                
                if (cadastroID != 0) {
                    data.ID = cadastroID;
                }
        
                $.ajax({
                    type: 'post',
                    datatype: 'json',
                    data: data,
                    url: url,
                    success: function(r) {
                        $('#modal-cadastro').modal('hide');
                        buscarDados();
                        Swal.fire('Sucesso!', `Lista de Materiais ${cadastroID == 0 ? 'cadastrada' : 'alterada'} com sucesso.`, 'success');
                    },
                    error: err => { 
                        console.error(err);
                        Swal.fire('Erro!', 'Ocorreu um erro ao salvar a lista.', 'error');
                    }
                });
            }
        }
        
        function inserirItemLista() {
            const item = $('#inputCadastroItem').val().trim();
            const qtde = $('#inputCadastroItemQTDE').val();
            const valor = limparMascaraFinanceira($('#inputCadastroItemValor').val());
            const fornecedor = $('#inputCadastroItemFornecedor').val().trim();
        
            // Validações
            if (!item) {
                Swal.fire('Atenção!', 'Informe um item válido!', 'warning');
                return;
            }
        
            if (!qtde || qtde <= 0) {
                Swal.fire('Atenção!', 'Informe uma quantidade válida!', 'warning');
                return;
            }
        
            if (!valor || valor <= 0) {
                Swal.fire('Atenção!', 'Informe um valor válido!', 'warning');
                return;
            }
        
            const novoItem = {
                ID_ITEM: $('#inputCadastroItemID').val(),
                ITEM: item,
                VALOR_ITEM: valor,
                QTDE: qtde,
                NOME_FORNECEDOR: fornecedor,
                ID_FORNECEDOR: $('#inputCadastroItemFornecedorID').val() || 0,
                ID: dadosItens.length > 0 ? Math.max(...dadosItens.map(item => item.ID)) + 1 : 1
            };
        
            dadosItens.push(novoItem);  
            popularListaItens();
            limparCamposItem();
        }
        
        function limparCamposItem() {
            $('#inputCadastroItem').val('').removeAttr('disabled');
            $('#inputCadastroItemID').val('0');
            $('#inputCadastroItemQTDE').val('1');
            $('#inputCadastroItemValor').val('');
            $('.btnLimparCadastroItem').addClass('d-none');
        }
        
        function limparCamposFornecedor() {
            $('#inputCadastroItemFornecedor').val('').removeAttr('disabled');
            $('#inputCadastroItemFornecedorID').val('0');
            $('.btnLimparCadastroFornecedor').addClass('d-none');
        }
        
        function inativarLista(idLista) {
            Swal.fire({
                title: 'Confirmação',
                text: `Deseja inativar a lista de materiais ${idLista}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        datatype: 'json',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'ID': idLista
                        },
                        url: "{{route('material.inativar.lista')}}",
                        success: function() {
                            Swal.fire('Sucesso!', 'Lista inativada com sucesso.', 'success');
                            buscarDados();
                        },
                        error: err => {
                            console.error(err);
                            Swal.fire('Erro!', 'Não foi possível inativar a lista.', 'error');
                        }
                    });
                }
            });
        }

        function alterarSituacao(idLista, tipo) {
            Swal.fire({
                title: 'Confirmação',
                text: `Deseja alterar a lista de materiais para : ${tipo}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        datatype: 'json',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'ID': idLista,
                            'SITUACAO': tipo
                        },
                        url: "{{route('material.situacao.lista')}}",
                        success: function() {
                            Swal.fire('Sucesso!', 'Lista alterada com sucesso.', 'success');
                            buscarDados();
                        },
                        error: err => {
                            console.error(err);
                            Swal.fire('Erro!', 'Não foi possível inativar a lista.', 'error');
                        }
                    });
                }
            });
        }
        
        function calcularValorTotal() {
            let valorTotal = 0;
            dadosItens.forEach(item => {
                valorTotal += parseFloat(item.VALOR_ITEM) * parseInt(item.QTDE);
            });
        
            valorTotalLista = valorTotal;
            $('#inputCadastroValorTotal').val(mascaraFinanceira(valorTotal));
        }
        
        function removerItem(ID) {
            dadosItens = dadosItens.filter(item => item.ID != ID);
            popularListaItens();
        }
        
        function resetarCampos() {
            $('#inputCadastroID').val('0').prop('disabled', false);
            
            limparCamposItem();
            limparCamposFornecedor();
        
            $('#divItens').removeClass('d-none').addClass('d-flex');
            $('#btnCadastroSalvar').removeClass('d-none');
            $('#inputCadastroItemFornecedor').removeClass('d-none');
        
            dadosItens = [];
            valorTotalLista = 0;
        
            popularListaItens();
        }
        
        function gerarImpresso(idLista) {
            window.open(`{{env('APP_URL')}}/materiais/lista/imprimir/${idLista}`, '_blank');
        }
        
        // Autocomplete para itens
        $("#inputCadastroItem").autocomplete({
            source: function(request, cb) {
                $.ajax({
                    url: "{{route('material.busca')}}",
                    method: 'post',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'filtro': request.term
                    },
                    dataType: 'json',
                    success: function(r) {
                        cb($.map(r.dados, function(obj) {
                            return {
                                label: obj.info,
                                value: obj.MATERIAL,
                                data: obj
                            };
                        }));
                    },
                    error: err => console.error(err)
                });
            },
            select: function(e, selectedData) {
                if (selectedData.item.label != 'Nenhum Material Encontrado.') {
                    const item = selectedData.item.data;
                    $('#inputCadastroItem').val(item.MATERIAL).attr('disabled', true);
                    $('#inputCadastroItemID').val(item.ID);
                    $('#inputCadastroItemQTDE').val('1');
                    $('#inputCadastroItemValor').val(mascaraFinanceira(item.VALOR || 0));
                    $('.btnLimparCadastroItem').removeClass('d-none');
                }
            }
        });
        
        // Autocomplete para fornecedores
        $("#inputCadastroItemFornecedor").autocomplete({
            source: function(request, cb) {
                $.ajax({
                    url: "{{route('pessoa.buscar')}}",
                    method: 'post',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'FILTRO_BUSCA': request.term,
                        'ID_TIPO': 2 // Fornecedores
                    },
                    dataType: 'json',
                    success: function(r) {
                        cb($.map(r, function(obj) {
                            return {
                                label: obj.info,
                                value: obj.NOME,
                                data: obj
                            };
                        }));
                    },
                    error: err => console.error(err)
                });
            },
            select: function(e, selectedData) {
                if (selectedData.item.label != 'Nenhum Fornecedor Encontrado.') {
                    $('#inputCadastroItemFornecedor').val(selectedData.item.data.NOME).attr('disabled', true);
                    $('#inputCadastroItemFornecedorID').val(selectedData.item.data.ID);
                    $('.btnLimparCadastroFornecedor').removeClass('d-none');
                }
            }
        });
        
        // Event Listeners
        $('#btnCadastroSalvar').click(inserirMaterialLista);
        $('#btnCadastroAdicionarItem').click(inserirItemLista);
        $('#btnNovo').click(exibirModalCadastro);
        
        $('#btnLimparCadastroItem').click(function() {
            limparCamposItem();
        });
        
        $('#btnLimparCadastroFornecedor').click(function() {
            limparCamposFornecedor();
        });
        
        $('#inputFiltroDataInicio, #inputFiltroDataFim').change(function() {
            clearTimeout(timeoutFiltro);
            timeoutFiltro = setTimeout(buscarDados, 1500);
        });
        
        $(document).ready(function() {
            buscarDados();
        });
    </script>
@stop