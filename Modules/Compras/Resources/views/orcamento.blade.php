@php
    date_default_timezone_set('America/Sao_Paulo');
@endphp

@extends('adminlte::page')

@section('title', 'Orçamento')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Orçamento</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <div class="col-12 col-md-6 row d-flex d-flex justify-content-end ">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-block btn-warning" id="btnNovo">
                            <i class="fas fa-file-alt"></i>
                            <span class="ml-1">Novo Orçamento</span>
                        </button>
                    </div>
                    <div class="col-12 col-md-6">
                        <button class="btn btn-block btn-info" id="btnNovoMaterial">
                            <i class="fas fa-plug"></i>
                            <span class="ml-1">Novo Material</span>
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
                    <div class="col-12 col-md-4">
                        <select id="selectFiltroSituacao" class="form-control form-control-border">
                            <option value="0">Todas as Situações</option>   
                        </select>
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
                        <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">Nº</th>
                        <th class="d-none d-lg-table-cell"><center>Título</center></th>
                        <th class="d-none d-lg-table-cell"><center>Projeto</center></th>
                        <th class="d-none d-lg-table-cell"><center>Cliente</center></th>
                        <th class="d-none d-lg-table-cell"><center>Emissão</center></th>
                        <th class="d-none d-lg-table-cell"><center>Total</center></th>
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
                    <h4 class="modal-title">Cadastro de Orçamento</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <div class="row d-flex">
                        <input type="hidden" class="form-control form-control-border" id="inputCadastroID" disabled>
                        <div class="form-group col-12 col-md-6">
                            <input type="text" class="form-control form-control-border" placeholder="Titulo" id="inputCadastroTitulo">
                        </div>
                        <div class="form-group col-12 col-md-6 d-none">
                            <input type="datetime-local" class="form-control form-control-border" id="inputCadastroData" value="{{date('Y-m-d H:i')}}">
                        </div>
                        
                        <div class="form-group col-12 col-md-6">
                            <input type="text" class="form-control form-control-border" placeholder="Valor Total Orçamento" id="inputCadastroValorTotal" disabled>
                        </div>

                        <div class="col-12 col-md-6 row d-flex p-0 m-0">
                            <div class="col">
                                <input type="text" class="form-control form-control-border col-12" placeholder="Cliente/Fornecedor" id="inputCadastroCliente">
                                <input type="hidden" id="inputCadastroIDCliente">
                            </div>
                            <div class="col btnLimparCadastroCliente d-none">
                                <button id="btnLimparCadastroCliente" class="btnLimparCadastroCliente btn btn-sm btn-danger d-none col-12"><i class="fas fa-eraser"></i>LIMPAR</button>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 row d-flex p-0 m-0">
                            <div class="col">
                                <input type="text" class="form-control form-control-border col-12" placeholder="Projeto" id="inputCadastroProjeto">
                                <input type="hidden" id="inputCadastroIDProjeto">
                            </div>
                            <div class="col btnLimparProjeto d-none">
                                <button id="btnLimparProjeto" class="btnLimparProjeto btn btn-sm btn-danger d-none col-12"><i class="fas fa-eraser"></i>LIMPAR</button>
                            </div>
                        </div>

                        <div class="form-group col-12">
                            <textarea class="form-control form-control-border" placeholder="Observações" id="inputCadastroObservacao" maxlength="200"></textarea>
                        </div>

                        <div class="col-12 row d-flex" id="divItens">
                            <div class="col-12 d-flex justify-content-start">
                                <span class="right badge badge-info">Itens</span>
                            </div>
    
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
                            
                            <div class="form-group col-4">
                                <input type="text" class="form-control form-control-border" placeholder="Item: Quantidade" id="inputCadastroItemQTDE">
                            </div>
    
                            <div class="form-group col-4">
                                <input type="text" class="form-control form-control-border" placeholder="Item: Valor Unitário" id="inputCadastroItemValorUnitario">
                            </div>
    
                            <div class="form-group col-4">
                                <input type="text" class="form-control form-control-border" placeholder="Item: Valor Total" id="inputCadastroItemValorTotal" disabled>
                            </div>
    
                            <div class="form-group col-12">
                                <textarea class="form-control form-control-border" placeholder="Item: Especificação" id="inputCadastroItemObs"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <table class="table table-responsive-xs">
                                <thead>
                                    <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">Item</th>
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

    <div class="modal fade" id="modal-cadastro-material">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title">Cadastro de Material</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="form-group col-12 col-md-4">
                            <label>Material</label>
                            <input type="text" class="form-control form-control-border  col-12" id="inputMaterial" placeholder="Material">
                            <input type="hidden" class="form-control form-control-border  col-12" id="inputCodMaterial">
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <label>Marca</label>
                            <select id="selectMaterialMarca" class="form-control  form-control-border">
                                <option value="0">Selecionar Marca</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4 row d-flex p-0 m-0">
                            <div class="col">
                                <label>Fornecedor</label>
                                <input type="text" class="form-control form-control-border col-12" placeholder="Fornecedor" id="inputFornecedor">
                                <input type="hidden" id="inputIDFornecedor">
                                <div class="col btnLimparFornecedor d-none p-0 m-0">
                                    <button id="btnLimparFornecedor" class="btnLimparFornecedor btn btn-sm btn-danger d-none col-12"><i class="fas fa-eraser"></i>LIMPAR</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-4 col-md-2">
                            <label>Valor</label>
                            <input type="text" class="form-control form-control-border  col-12" id="inputMaterialValor" placeholder="Valor">
                        </div>
                        <div class="form-group col-4 col-md-2">
                            <label>Quantidade</label>
                            <input type="text" class="form-control form-control-border  col-12" id="inputMaterialQtde" placeholder="Quantidade">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarMaterial">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Documentação da Orçamento <span id="titleDocumento"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="">
                                <input type="hidden" id="inputIDOrçamentoCompra">
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
        var valorTotalOrçamento = 0;
        var timeoutFiltro = 0;
        let inputsAdicionaisItemCadastro = ['inputCadastroItemQTDE', 'inputCadastroItemValorUnitario', 'inputCadastroItemValorTotal', 'inputCadastroItemObs'];

        $('#inputCadastroValorTotal').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero:false});
        $('#inputCadastroItemValorTotal').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero:false});
        $('#inputCadastroItemValorUnitario').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero:false});
        $('#inputCadastroItemQTDE').mask('000000000');

        function exibirModalCadastro(){
            resetarCampos();
            buscarDados();
            $('#modal-cadastro').modal('show');
        }

        function exibirModalEdicao(idOrçamento){
            resetarCampos();

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                   '_token':'{{csrf_token()}}',
                   'ID': idOrçamento
                },
                url:"{{route('compras.buscar.orcamento')}}",
                success:function(r){
                    var dados = r.dados[0];

                    $('#inputCadastroID').val(idOrçamento)
                    $('#inputCadastroData').val(dados['DATA_CADASTRO'])
                    $('#inputCadastroObservacao').val(dados['OBSERVACAO'])
                    $('#inputCadastroValorTotal').val(mascaraFinanceira(dados['VALOR']))

                    $('#inputCadastroProjeto').val(dados['PROJETO']);
                    $('#inputCadastroIDProjeto').val(dados['ID_PROJETO']);

                    if(dados['ID_PROJETO'] > 0){                        
                        $('#inputCadastroProjeto').attr('disabled', true);
                        $('.btnLimparProjeto').removeClass('d-none');
                    }

                    $('#inputCadastroCliente').val(dados['NOME_CLIENTE']);
                    $('#inputCadastroIDCliente').val(dados['ID_PESSOA']);
                    
                    if(dados['ID_PESSOA'] > 0){                        
                        $('#inputCadastroCliente').attr('disabled', true);
                        $('.btnLimparCadastroCliente').removeClass('d-none');
                    }

                    $('#inputCadastroTitulo').val(dados['TITULO']);
                    
                    dadosItens = dados['ITENS'];

                    popularListaItens();
                    $('#modal-cadastro').modal('show');
                },
                error:err=>{exibirErroAJAX(err)}
            })
        }

        function exibirModalVisualizacao(idOrçamento){
            resetarCampos();

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                   '_token':'{{csrf_token()}}',
                   'ID': idOrçamento
                },
                url:"{{route('compras.buscar.orcamento')}}",
                success:function(r){
                    var dados = r.dados[0];
                    $('#divItens').addClass('d-none');
                    $('#btnCadastroSalvar').addClass('d-none')
                    $('#divItens').removeClass('d-flex');

                    $('#inputCadastroID').val(idOrçamento)
                    $('#inputCadastroData').val(dados['DATA_CADASTRO'])
                    $('#inputCadastroObservacao').val(dados['OBSERVACAO'])
                    $('#inputCadastroValorTotal').val(mascaraFinanceira(dados['VALOR']))

                    $('#inputCadastroID').prop('disabled', true)
                    $('#inputCadastroData').prop('disabled', true)
                    $('#inputCadastroObservacao').prop('disabled', true)
                    $('#inputCadastroValorTotal').prop('disabled', true)
                    $('#inputCadastroProjeto').prop('disabled', true)
                    $('#inputCadastroTitulo').prop('disabled', true)
                    $('#inputCadastroCliente').attr('disabled', true)

                    $('#inputCadastroProjeto').val(dados['PROJETO']);
                    $('#inputCadastroIDProjeto').val(dados['ID_PROJETO']);

                    if(dados['ID_PROJETO'] > 0){                        
                        $('#inputCadastroProjeto').attr('disabled', true);
                    }

                    $('#inputCadastroCliente').val(dados['NOME_CLIENTE']);
                    $('#inputCadastroIDCliente').val(dados['ID_PESSOA']);
                    $('#inputCadastroTitulo').val(dados['TITULO']);

                    dadosItens = dados['ITENS'];

                    popularListaItens(false);
                    $('#modal-cadastro').modal('show');
                },
                error:err=>{exibirErroAJAX(err)}
            })
        }

        function exibirModalCadastroMaterial(){
            resetarCamposMaterial();
            buscarMarca();
            $('#modal-cadastro-material').modal('show');
        }

        function buscarDados(){
            editar = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'filtro': $('#inputFiltro').val(),
                    'ID_SITUACAO': $('#selectFiltroSituacao').val(),
                    'DATA_INICIO': $('#inputFiltroDataInicio').val(),
                    'DATA_FIM': $('#inputFiltroDataFim').val(),
                },
                url:"{{route('compras.buscar.orcamento')}}",
                success:function(r){
                    popularListaDados(r.dados);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function buscarSituacoes(){
            editar = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'TIPO': 'ORCAMENTO'
                },
                url:"{{route('buscar.situacoes')}}",
                success:function(r){
                    popularSelectSituacoes(r.dados);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function buscarMarca(abrirModalMarca = false){
            editarMaterial = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}'
                },
                url:"{{route('material.busca.marca')}}",
                success:function(r){
                    if(abrirModalMarca){
                        popularListaMarca(r);
                    } else {
                        popularSelectMarca(r);
                    }
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
                var situacaoAprovacao = '-';
                var btnAcoes = '';
                var btnOpcoes = '';
                var btnAprovavaco = '';
                var btnArquivos = '';
                var btnImprimir = `<li class="dropdown-item" onclick="gerarImpresso(${dados[i]['ID']}, 1)"><span class="btn"><i class="fas fa-print"></i> Imprimir</span></li>`;
                var btnVisualizar = `<li class="dropdown-item" onclick="exibirModalVisualizacao(${dados[i]['ID']})"><span class="btn"><i class="fas fa-eye"></i> Visualizar</span></li>`;
                var classeBadgeSituacao = 'bg-warning';
                var dataFormatada = moment(dados[i]['DATA_CADASTRO']).format('DD/MM/YYYY HH:mm');

                if(dados[i]['ID_USUARIO_APROVACAO'] != null && dados[i]['ID_USUARIO_APROVACAO'] > 0){
                    situacaoAprovacao = `<span class="badge bg-info">${dados[i]['USUARIO_APROVACAO']} - ${moment(dados[i]['DATA_APROVACAO']).format('DD/MM/YYYY HH:mm')}</span>`;
                }

                if(dados[i]['ID_SITUACAO'] == 1){
                    classeBadgeSituacao = 'bg-success';
                } else if(dados[i]['ID_SITUACAO'] == 2){
                    classeBadgeSituacao = 'bg-danger';
                } else if(dados[i]['STATUS'] == 'A' && dados[i]['ID_SITUACAO'] == 3){

                    @CAN('GESTAO_COMPRAS')
                    btnAprovavaco = `<li class="dropdown-item" onclick="alterarSituacaoOrçamento(${dados[i]['ID']}, 1)"><span class="btn"><i class="fas fa-check" style="color: #63E6BE;"></i></span> Aprovar</li>
                                     <li class="dropdown-item" onclick="alterarSituacaoOrçamento(${dados[i]['ID']}, 2)"><span class="btn"><i class="fas fa-times" style="color: #ff0000;"></i></span> Reprovar</li>`;
                    @ENDCAN
                }

                if(dados[i]['STATUS'] == 'A' && dados[i]['ID_SITUACAO'] == 3){
                    btnAcoes = `<li class="dropdown-item" onclick="exibirModalEdicao(${dados[i]['ID']})"><span class="btn"><i class="fas fa-pen"></i> Editar</span></li>
                                <li class="dropdown-item" onclick="inativarOrçamento(${dados[i]['ID']})"><span class="btn"><i class="fas fa-trash"></i> Inativar</span></li>`;
                }

                btnArquivos = `<li class="dropdown-item" onclick="cadastarDocumento(${dados[i]['ID']}, '${dataFormatada}')"><span class="btn"><i class="fas fa-file-alt"></i></span> Arquivos</li>`;

                var btnOpcoes = ` <div class="input-group-prepend show justify-content-center" style="text-align: center">
                                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                Ações
                                            </button>
                                            <ul class="dropdown-menu ">
                                                ${btnAprovavaco}
                                                ${btnAcoes}                                            
                                                ${btnVisualizar}                                            
                                                ${btnImprimir}                                            
                                                ${btnArquivos}                                            
                                            </ul>
                                        </div>
                                    `;

                var badgeSituacao = `<span class="right badge ${classeBadgeSituacao}">${dados[i]['SITUACAO']}</span>`;

                htmlTabela += `
                    <tr id="tableRow${dados[i]['ID']}" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['ID']}</td>
                        <td class="tdTexto"><center>${dados[i]['TITULO']}</center></td>
                        <td class="tdTexto"><center>${dados[i]['PROJETO'] ?? '-'}</center></td>
                        <td class="tdTexto"><center>${dados[i]['NOME_CLIENTE']}</center></td>
                        <td class="tdTexto"><center>${dataFormatada}</center></td>
                        <td class="tdTexto"><center><span class="badge bg-success">${mascaraFinanceira(dados[i]['VALOR'])}</span></center></td>
                        <td class="tdTexto"><center>${badgeSituacao}</center></td>
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
                                <span><b>${dados[i]['ID']} - ${dados[i]['USUARIO']}</b></span>
                                <span><b>${badgeSituacao}</b></span>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <span><b>${dataFormatada}</b></span>
                                <span><b>${mascaraFinanceira(dados[i]['VALOR'])}</b></span>
                                <span><b>${situacaoAprovacao}</b></span>
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                <span><b>${dados[i]['PROJETO'] ?? '-'}</b></span>
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                <span><b>${dados[i]['OBSERVACAO'].substr(0, 20)}</b></span>
                            </div>
                        </td>
                    </tr>
                `;
            }

            $('#tableBodyDados').html(htmlTabela);
        }

        function popularListaItens(editarItens = true){
            var htmlTabela = "";
            var dados = dadosItens;

            for(i=0; i< dados.length; i++){
                var Keys = Object.keys(dados[i]);
                for(j=0;j<Keys.length;j++){
                    if(dados[i][Keys[j]] == null){
                        dados[i][Keys[j]] = "";
                    }
                }

                btnAcoes = `<button class="btn" onclick="removerItem(${dados[i]['ID_UNICO']})"><i class="fas fa-trash"></i></button>`;

                if(!editarItens){
                    btnAcoes = '';
                }
                
                htmlTabela += `
                    <tr id="tableRow${dados[i]['ID_UNICO']}" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['ITEM']}</td>
                        <td class="tdTexto"><center>${mascaraFinanceira(dados[i]['VALOR_UNITARIO'])}</center></td>
                        <td class="tdTexto"><center>${dados[i]['QTDE']}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(dados[i]['VALOR_TOTAL'])}</center></td>
                        <td>
                            <center>
                                ${btnAcoes}
                            </center>
                        </td>
                    </tr>
                `;
            }

            $('#tableBodyDadosItens').html(htmlTabela);
        }

        function popularSelectSituacoes(dados){
            var htmlTabela = `<option value="0">Todas as Situações</option>`;

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
            $('#selectFiltroSituacao').html(htmlTabela)
        }

        function popularSelectMarca(dados){
            var htmlTabela = `<option value="0">Selecionar Marca</option>`;

            for(i=0; i< dados.length; i++){
                var materialKeys = Object.keys(dados[i]);
                for(j=0;j<materialKeys.length;j++){
                    if(dados[i][materialKeys[j]] == null){
                        dados[i][materialKeys[j]] = "";
                    }
                }

                htmlTabela += `
                    <option value="${dados[i]['ID']}">${dados[i]['DESCRICAO']}</option>`;
            }
            $('#selectMaterialMarca').html(htmlTabela)
        }
        
        function inserirOrçamentoServico() {
            validacao = true;

            var inputIDs = ['inputCadastroData', 'inputCadastroValorTotal', 'inputCadastroTitulo'];

            for (var i = 0; i < inputIDs.length; i++) {
                var inputID = inputIDs[i];
                var input = $('#' + inputID);
                
                if (input.val() === '' || input.val() == '0' && inputID == 'inputCadastroValorTotal') {
                    input.addClass('is-invalid');
                    validacao = false;
                } else {
                    input.removeClass('is-invalid');
                }
            }

            if(dadosItens.length == 0){
                Swal.fire(
                    'Atenção!',
                    'Insira ao menos um item para a orçamento!',
                    'warning'
                );
                validacao = false;
            }

            if($('#inputCadastroIDProjeto').val() == 0 && $('#inputCadastroProjeto').val().length > 0){
                validacao = false;
                $('#inputCadastroProjeto').addClass('is-invalid')
                dispararAlerta('warning', 'Projeto inválido!')
            } else {
                $('#inputCadastroProjeto').removeClass('is-invalid');
            }

            if(validacao){
                var cadastroID = $('#inputCadastroID').val();
                var data = $('#inputCadastroData').val();
                var valorTotal = limparMascaraFinanceira($('#inputCadastroValorTotal').val());
                var projeto = $('#inputCadastroProjeto').val();
                var idProjeto = $('#inputCadastroIDProjeto').val();
                var observacao = $('#inputCadastroObservacao').val();
                var cliente = $('#inputCadastroCliente').val();
                var idCliente = $('#inputCadastroIDCliente').val();
                var titulo = $('#inputCadastroTitulo').val();
            
                if(cadastroID == 0){
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'data': data,
                            'valorTotal': valorTotal,
                            'idProjeto': idProjeto,
                            'observacao': observacao,
                            'dadosItens': dadosItens,
                            'cliente': cliente,
                            'idCliente': idCliente,
                            'titulo': titulo,
                        },
                        url:"{{route('compras.inserir.orcamento')}}",
                        success:function(r){
                            $('#modal-cadastro').modal('hide');

                            buscarDados();

                            Swal.fire(
                                'Sucesso!',
                                'Orçamento cadastrado com sucesso.',
                                'success',
                            );
                        },
                        error:err=>{exibirErro(err)}
                    })
                } else {
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'data': data,
                            'valorTotal': valorTotal,
                            'idProjeto': idProjeto,
                            'observacao': observacao,
                            'dadosItens': dadosItens,
                            'ID': cadastroID,
                            'cliente': cliente,
                            'idCliente': idCliente,
                            'titulo': titulo,
                        },
                        url:"{{route('compras.alterar.orcamento')}}",
                        success:function(r){
                            $('#modal-cadastro').modal('hide');

                            buscarDados();

                            Swal.fire(
                                'Sucesso!',
                                'Orçamento alterado com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            }
            
        }

        function inserirItemOrçamento() {
            var validacao = true;

            if($('#inputCadastroItem').val().trim().length == 0 || $('#inputCadastroItem').val() == ''){
                Swal.fire(
                    'Atenção!',
                    'Informe ao menos um item para adicionar!',
                    'warning'
                );
                validacao = false;
            }

            if(validacao){
                var descItem = '';

                var dadoItemSelecionado = {
                    ID_ITEM : $('#inputCadastroItemID').val(),
                    ITEM : $('#inputCadastroItem').val(),
                    VALOR_UNITARIO : limparMascaraFinanceira($('#inputCadastroItemValorUnitario').val()),
                    QTDE : $('#inputCadastroItemQTDE').val(),
                    VALOR_TOTAL : limparMascaraFinanceira($('#inputCadastroItemValorTotal').val()),
                    OBSERVACAO : $('#inputCadastroItemObs').val(),
                    ID_UNICO : dadosItens.length+1
                }
    
                dadosItens.push(dadoItemSelecionado);  
                
                popularListaItens();
    
                calcularValorTotal();

                limparCampo('inputCadastroItem', 'inputCadastroItemID', 'btnLimparCadastroItem', inputsAdicionaisItemCadastro);
            }
        }

        function inserirMaterial() {
            validacao = true;

            var inputIDs = ['inputMaterial'];

            for (var i = 0; i < inputIDs.length; i++) {
                var inputID = inputIDs[i];
                var input = $('#' + inputID);
                
                if (input.val() === '') {
                    input.addClass('is-invalid');
                    validacao = false;
                } else {
                    input.removeClass('is-invalid');
                }
            }

            if(validacao){
                var codMaterial = $('#inputCodMaterial').val();
                var valorMaterial = $('#inputMaterialValor').val().replace('R$', '').replace('.', '').replace(',', '.').replace(' ', '') ;
                var material = $('#inputMaterial').val();
                var marca = $('#selectMaterialMarca').val();
                var QTDE = $('#inputMaterialQtde').val();
                var disponivel = 1;
                var ultimaRetirada = moment().format('YYYY-MM-DD HH:mm');
                var tipoMaterial = 4;
                var idFornecedor = $('#inputIDFornecedor').val();
            
                if(codMaterial == 0){
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'valor': valorMaterial,
                        'material': material,
                        'marca': marca,
                        'QTDE': QTDE,
                        'disponivel': disponivel,
                        'ultimaRetirada': ultimaRetirada,
                        'TIPO_MATERIAL': tipoMaterial,
                        'ID_FORNECEDOR': idFornecedor
                        },
                        url:"{{route('material.inserir')}}",
                        success:function(r){
                            $('#modal-cadastro-material').modal('hide');

                            dispararAlerta('success', 'Material cadastrado com sucesso.')
                        },
                        error:err=>{exibirErro(err)}
                    })
                } else {
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'valor': valorMaterial,
                        'material': material,
                        'marca': marca,
                        'QTDE': QTDE,
                        'disponivel': disponivel,
                        'ultimaRetirada': ultimaRetirada,
                        'TIPO_MATERIAL': tipoMaterial,
                        'ID': codMaterial,
                        'ID_FORNECEDOR': idFornecedor
                        },
                        url:"{{route('material.alterar')}}",
                        success:function(r){
                            $('#modal-cadastro-material').modal('hide');
                            buscarMaterial();

                            Swal.fire(
                                'Sucesso!',
                                'Material alterado com sucesso.',
                                'success'
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            }
            
        }

        function alterarSituacaoOrçamento(idOrçamento, situacao){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja alterar a situação do orçamento?',
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
                            'ID': idOrçamento,
                            'ID_SITUACAO' : situacao
                        },
                        url:"{{route('compras.alterar.situacao.orcamento')}}",
                        success:function(r){
                            Swal.fire('Sucesso!'
                                    , 'Situação alterada com sucesso.'
                                    , 'success');
                            buscarDados();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        function inativarOrçamento(idMovimentacao){
            Swal.fire({
                title: 'Confirmação',
                text: `Deseja inativar o orçamento ${idMovimentacao}?`,
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
                            'ID': idMovimentacao
                        },
                        url:"{{route('compras.inativar.orcamento')}}",
                        success:function(r){
                            Swal.fire('Sucesso!'
                                    , 'orçamento inativada com sucesso.'
                                    , 'success');
                            buscarDados();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        function calcularValorTotal(){
            var valorTotal = 0;
            for(i=0; i< dadosItens.length; i++){
                valorItemAutal = parseFloat(dadosItens[i]['VALOR_TOTAL']);
                valorTotal = (valorTotal + valorItemAutal);
            }

            valorTotalOrçamento = valorTotal;

            $('#inputCadastroValorTotal').val(mascaraFinanceira(valorTotal));
        }

        function calcularValorItem(){
            var valorUnitario = 0, valorTotalItem = 0, qtde = 0;

            if($('#inputCadastroItemValorUnitario').val() != ''){
                valorUnitario =  parseFloat(limparMascaraFinanceira($('#inputCadastroItemValorUnitario').val()));
            }

            if($('#inputCadastroItemQTDE').val() != ''){
                qtde = $('#inputCadastroItemQTDE').val();
            }

            valorTotalItem = valorUnitario * qtde;

            $('#inputCadastroItemValorTotal').val(mascaraFinanceira(valorTotalItem));
        }

        function removerItem(ID_UNICO){
            let index = dadosItens.findIndex(
                (el)=>{                      
                    return el.ID_UNICO == ID_UNICO;
                }
            );

            dadosItens.splice(index,1);

            popularListaItens();
            calcularValorTotal();
        }

        function resetarCampos(){
            $('#inputCadastroID').val('0')
            $('#inputCadastroData').val(moment().format('YYYY-MM-DD HH:mm'))
            $('#inputCadastroObservacao').val('')
            $('#inputCadastroValorTotal').val('')
            $('#inputCadastroValorTotal').val('')
            $('#inputCadastroItem').val('')
            $('#inputCadastroItemID').val('0')
            $('#inputCadastroItemQTDE').val('')
            $('#inputCadastroItemValorUnitario').val('')
            $('#inputCadastroItemValorTotal').val('')
            $('#inputCadastroTitulo').val('')

            $('#divItens').removeClass('d-none');
            $('#btnCadastroSalvar').removeClass('d-none')

            $('#divItens').addClass('d-flex');
            

            $('#inputCadastroID').prop('disabled', false)
            $('#inputCadastroData').prop('disabled', false)
            $('#inputCadastroObservacao').prop('disabled', false)
            $('#inputCadastroProjeto').prop('disabled', false)
            $('#inputCadastroTitulo').prop('disabled', false)

            limparCampo('inputCadastroProjeto', 'inputCadastroIDProjeto', 'btnLimparProjeto');
            limparCampo('inputCadastroCliente', 'inputCadastroIDCliente', 'btnLimparCadastroCliente');

            dadosItens = [];
            valorTotalOrçamento = 0;

            popularListaItens();
        }

        function resetarCamposMaterial(){
            $('#inputCodMaterial').val('0')
            $('#inputMaterial').val('')
            $('#selectMaterialMarca').val('0')
            $('#inputMaterialValor').val('')
            $('#inputMaterialQtde').val('')

            limparCampo('inputFornecedor', 'inputIDFornecedor', 'btnLimparFornecedor');
        }

        function gerarImpresso(idOrçamento){
            window.open(`{{env('APP_URL')}}/compras/imprimir/orcamento/${idOrçamento}`)
        }

        // DOCUMENTOS
            function cadastarDocumento(idDocumento, descricaoDocumento){
                $('#titleDocumento').text(idDocumento +' - '+descricaoDocumento);
                $('#inputIDOrçamentoCompra').val(idDocumento);

                buscarDocumento();

                $('#modal-documentacao').modal('show');
            }

            function buscarDocumento(){
                var idOrçamento = $('#inputIDOrçamentoCompra').val();
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                        '_token':'{{csrf_token()}}',                    
                        'ID_ORDEM': idOrçamento,
                    },
                    url:"{{route('compras.buscar.documento.orcamento')}}",
                    success:function(r){
                        popularListaDocumentos(r.dados);
                    },
                    error:err=>{exibirErro(err)}
                })
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
                            url:"{{route('compras.inativar.documento.orcamento')}}",
                            success:function(r){
                                Swal.fire('Sucesso!'
                                        , 'Documento inativado com sucesso.'
                                        , 'success');
                                buscarDocumento();
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
                idOrçamentoCompra = $('#inputIDOrçamentoCompra').val();
                dataAnexo.append('meuArquivo', document.getElementById('inputArquivoDocumentacao').files[0]);
                dataAnexo.append('ID', idOrçamentoCompra);

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
                                    'ID_ORDEM': idOrçamentoCompra
                                },
                                url:"{{route('compras.inserir.documento.orcamento')}}",
                                success:function(resultInsert){                               
                                    $("#inputArquivoDocumentacao").val('');
                                    validaDocumento();

                                    Swal.fire('Sucesso!'
                                            , 'Documento salvo com sucesso.'
                                            , 'success');
                                    buscarDocumento();
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

        $("#inputCadastroItem").autocomplete({
            source: function(request, cb){
                param = request.term;
                campoBuscado = param;
                $.ajax({
                    url:"{{route('material.busca')}}",
                    method: 'post',
                    data:{
                        '_token': '{{csrf_token()}}',
                        'filtro': param
                    },
                    dataType: 'json',
                    success: function(r){
                        result = $.map(r.dados, function(obj){
                            return {
                                label: obj.info,
                                value: obj.MATERIAL,
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
                    $('#inputCadastroItem').val(selectedData.item.data.MATERIAL);
                    $('#inputCadastroItem').attr('disabled', true); 
                    $('#inputCadastroItemID').val(selectedData.item.data.ID);
                    $('#inputCadastroItemQTDE').val('1');
                    $('#inputCadastroItemValorUnitario').val(mascaraFinanceira(selectedData.item.data.VALOR));
                    $('#inputCadastroItemValorTotal').val(mascaraFinanceira(selectedData.item.data.VALOR));
                    $('#inputCadastroItemObs').val('');
                    $('.btnLimparCadastroItem').removeClass('d-none');
                    $('#btnQRCODE').addClass('d-none');
                } else {
                    limparCampo('inputCadastroItem', 'inputCadastroItemID', 'btnLimparCadastroItem', inputsAdicionaisItemCadastro);
                }
            }
        });

        $("#inputCadastroCliente").autocomplete({
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
                    $('#inputCadastroCliente').val(selectedData.item.data.NOME);
                    $('#inputCadastroIDCliente').val(selectedData.item.data.ID);
                    $('#inputCadastroCliente').attr('disabled', true);
                    $('.btnLimparCadastroCliente').removeClass('d-none');
                } else {
                    limparCampo('inputCadastroCliente', 'inputCadastroIDCliente', 'btnLimparCadastroCliente');
                }
            }
        });

        $('#btnCadastroSalvar').on('click', () => {
            inserirOrçamentoServico();
        });

        $('#btnCadastroAdicionarItem').on('click', () => {
            inserirItemOrçamento();
        });

        $('#btnNovo').on('click', () => {
            exibirModalCadastro();
        });

        $('#btnNovoMaterial').click(() => {
            exibirModalCadastroMaterial();
        });

        $('#btnLimparCadastroItem').on('click', () => {
            limparCampo('inputCadastroItem', 'inputCadastroItemID', 'btnLimparCadastroItem', inputsAdicionaisItemCadastro);
        });

        $('#selectFiltroSituacao').on('change', () => {
            buscarDados();
        });

        $('#inputCadastroItemQTDE').on('change', () => {
            calcularValorItem();
        });

        $('#inputCadastroItemValorUnitario').on('change', () => {
            calcularValorItem();
        });

        $('#inputFiltroDataInicio').on('change', () => {
            clearTimeout(timeoutFiltro);

            timeoutFiltro = setTimeout(() => {
                buscarDados();
            }, 1500);
        });

        $('#inputFiltroDataFim').on('change', () => {
            clearTimeout(timeoutFiltro);

            timeoutFiltro = setTimeout(() => {
                buscarDados();
            }, 1500);
        })

        $("#inputCadastroProjeto").autocomplete({
            source: function(request, cb) {
                param = request.term;
                $.ajax({
                    url: "{{route('projeto.buscar')}}",
                    method: 'post',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'filtro': param,
                        'BUSCAR_TODOS': 'S'
                    },
                    dataType: 'json',
                    success: function(r) {
                        result = $.map(r.dados, function(obj) {
                            return {
                                label: obj.info,
                                value: obj.TITULO,
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
                    $('#inputCadastroProjeto').val(selectedData.item.data.TITULO);
                    $('#inputCadastroIDProjeto').val(selectedData.item.data.ID);
                    $('#inputCadastroProjeto').attr('disabled', true);
                    $('.btnLimparProjeto').removeClass('d-none');
                } else {
                    limparCampo('inputCadastroProjeto', 'inputCadastroIDProjeto', 'btnLimparProjeto');
                }
            }
        });

        $("#inputFornecedor").autocomplete({
            source: function(request, cb){
                param = request.term;
                campoBuscado = param;
                $.ajax({
                    url:"{{route('pessoa.buscar')}}",
                    method: 'post',
                    data:{
                        '_token': '{{csrf_token()}}',
                        'nome': param,
                        'ID_TIPO': 3
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
                    $('#inputFornecedor').val(selectedData.item.data.NOME);
                    $('#inputIDFornecedor').val(selectedData.item.data.ID);
                    $('#inputFornecedor').attr('disabled', true); 
                    $('.btnLimparFornecedor').removeClass('d-none');
                } else {
                    limparCampo('inputFornecedor', 'inputIDFornecedor', 'btnLimparFornecedor');
                }
            }
        });

        $('#btnLimparProjeto').click(function() {
            limparCampo('inputCadastroProjeto', 'inputCadastroIDProjeto', 'btnLimparProjeto');
        });

        $('#btnLimparCadastroCliente').click(function() {
            limparCampo('inputCadastroCliente', 'inputCadastroIDCliente', 'btnLimparCadastroCliente');
        });

        $('#btnConfirmarMaterial').click(() => {
            inserirMaterial();
        });

        $(document).ready(function() {
            buscarSituacoes();
            buscarDados();
        })
    </script>
@stop