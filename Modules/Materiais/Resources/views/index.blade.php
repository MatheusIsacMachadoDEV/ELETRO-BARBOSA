@extends('adminlte::page')

@section('title', 'Materiais')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Materiais</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <div class="col-12 col-md-6 row d-flex">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-block btn-info" id="btnListaMarca">
                            <i class="fas fa-tools"></i>
                            <span class="ml-1">Marca</span>
                        </button>

                    </div>
                    <div class="col-12 col-md-6">
                        <button class="btn btn-block btn-warning" id="btnNovoMaterial">
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
            <div class="card-header">
                <div class="row d-flex m-0 p-0">
                    <div class="col-12 col-md-6">
                        <input type="text" class="form-control form-control-border" id="inputMaterialDescricaoFiltro" placeholder="Material/Marca" onkeyup="buscarMaterial()">
                    </div>
                    <div class="col-12 col-md-3">
                        <select id="selectFiltroTipo" class="selectTipoMaterial form-control form-control-border">
                            <option value="0">Tipo de Material</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <select id="selectFiltroSituacao" class="form-control form-control-border">
                            <option value="0">Todos as Situações</option>
                            <option value="1">Disponivel</option>
                            <option value="2">Retirado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-xs">
                    <thead>
                        <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">ID</th>
                        <th class="d-none d-lg-table-cell">Material</th>
                        <th class="d-none d-lg-table-cell"><center>Marca</center></th>
                        <th class="d-none d-lg-table-cell"><center>Valor</center></th>
                        <th class="d-none d-lg-table-cell"><center>QTDE</center></th>
                        <th class="d-none d-lg-table-cell"><center>Tipo</center></th>
                        <th class="d-none d-lg-table-cell"><center>Ultima Retirada</center></th>
                        <th class="d-none d-lg-table-cell"><center>Situação</center></th>
                        <th class="d-none d-lg-table-cell"><center>Ações</center></th>
                    </thead>
                    <tbody id="tableBodyDadosproduto">
                    </tbody>
                </table>
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
                        <div class="form-group col-4 col-md-2">
                            <label>Tipo de Material</label>
                            <select id="selectMaterialTipo" class="selectTipoMaterial form-control  form-control-border">
                            </select>
                        </div>
                        <div class="form-group col-4 col-md-3">
                            <label>Disponível?</label>
                            <select id="selectMaterialDisponivel" class="form-control  form-control-border">
                                <option value="1">Sim, disponível</option>
                                <option value="2">Não, retirado</option>
                            </select>
                        </div>
                        <div class="form-group col-4 col-md-3">
                            <label>Ultima Retirada</label>
                            <input type="datetime-local" class="form-control form-control-border  col-12" id="inputMaterialUltimaRetirada"> 
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
                    <h5 class="modal-title" >Documentação da produto <span id="titleDocumento"></span></h5>
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
                                    <input type="hidden" id="inputIDprodutoDocumentacao">
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

    <div class="modal fade" id="modal-lista-marca" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog modal-lg"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Marcas</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <div class="row d-flex">
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-sm btn-info col-12 col-md-4" id="btnCadastrarMarca">
                                <i class="fas fa-tools"></i>
                                <span class="ml-1">Cadastrar</span>
                            </button>
                        </div>
                        <div class="col-12">
                            <table class="table table-responsive-xs">
                                <thead>
                                    <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">ID</th>
                                    <th class="d-none d-lg-table-cell">Marca</th>
                                    <th class="d-none d-lg-table-cell"><center>Ações</center></th>
                                </thead>
                                <tbody id="tableBodyDadosMarca">
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

    <div class="modal fade" id="modal-cadastro-marca" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Cadastro de Marca</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Marca</label>
                            <input type="text" class="form-control form-control-border" placeholder="Nome da Marca" id="inputMarcaDescricao">
                        </div>
                    </div>
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
                    <button type="button" class="btn btn-primary" id="btnSalvarMarca">Salvar</button> 
                </div> 
            </div> 
        </div> 
    </div> 

    <div class="modal fade" id="modal-kardex" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog modal-xl"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Lista KARDEX</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <div class="col-12">
                        <table class="table table-responsive-xs">
                            <thead>
                                <th style="padding-left: 5px!important">Material</th>
                                <th><center>Data Movimentação</center></th>
                                <th><center>Usuário</center></th>
                                <th><center>Tipo</center></th>
                                <th><center>Valor</center></th>
                                <th><center>Origem</center></th>
                            </thead>
                            <tbody id="tableBodyKardex">
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
        var editarMaterial = false;
        $('#inputMaterialValor').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputMaterialQtde').mask('000000000');

        function exibirModalCadastroMaterial(){
            resetarCampos();
            buscarMarca();
            $('#modal-cadastro-material').modal('show');
        }

        function exibirModalEdicaoMaterial(idMaterial){
            resetarCampos();

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                   '_token':'{{csrf_token()}}',
                   'ID': idMaterial
                },
                url:"{{route('material.busca')}}",
                success:function(r){
                    var dadosMaterial = r.dados[0];

                    $('#inputCodMaterial').val(dadosMaterial['ID']);
                    $('#inputMaterialValor').val(dadosMaterial['VALOR']);
                    $('#inputMaterial').val(dadosMaterial['MATERIAL']);
                    $('#selectMaterialMarca').val(dadosMaterial['ID_MARCA']);
                    $('#selectMaterialTipo').val(dadosMaterial['TIPO_MATERIAL']);
                    $('#inputMaterialQtde').val(dadosMaterial['QTDE']);
                    $('#selectMaterialDisponivel').val(dadosMaterial['SITUACAO']);
                    $('#inputMaterialUltimaRetirada').val(dadosMaterial['DATA_ULTIMA_RETIRADA']);

                    if(dadosMaterial['ID_FORNECEDOR'] > 0){
                        $('#inputIDFornecedor').val(dadosMaterial['ID_FORNECEDOR']);
                        $('#inputFornecedor').val(dadosMaterial['FORNECEDOR']);

                        $('#inputFornecedor').attr('disabled', true); 
                        $('.btnLimparFornecedor').removeClass('d-none');
                    }

                    $('#modal-cadastro-material').modal('show');
                },
                error:err=>{exibirErroAJAX(err)}
            })
        }

        function exibirModalListaMarca(){
            buscarMarca(true);
            $('#modal-lista-marca').modal('show');
        }

        function exibirModalCadastroMarca(){
            $('#inputMarcaDescricao').val('');

            $('#modal-lista-marca').modal('hide');
            $('#modal-cadastro-marca').modal('show');
        }

        function exibirKardex(idMaterial){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                   '_token':'{{csrf_token()}}',
                   'ID_MATERIAL': idMaterial
                },
                url:"{{route('material.buscar.kardex')}}",
                success:function(r){
                    var dadosKardex = r.dados;

                    popularTabelaKardex(dadosKardex);

                    $('#modal-kardex').modal('show');
                },
                error:err=>{exibirErroAJAX(err)}
            })
        }

        function buscarMaterial(){
            editarMaterial = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'tipo_material': $('#selectFiltroTipo').val(),
                    'situacao': $('#selectFiltroSituacao').val(),
                    'filtro': $('#inputMaterialDescricaoFiltro').val()
                },
                url:"{{route('material.busca')}}",
                success:function(r){
                    popularListaMaterial(r.dados);
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

        function buscarTipoMaterial(){
            editarMaterial = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'TIPO': 'MATERIAL'
                },
                url:"{{route('buscar.situacoes')}}",
                success:function(r){
                    popularTipoMaterial(r.dados);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularTipoMaterial(dados){
            var htmlTabela = `<option value="0">Tipo de Material</option>`;

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
            $('.selectTipoMaterial').html(htmlTabela)
        }

        function popularListaMaterial(produto){
            var htmlTabela = "";

            for(i=0; i< produto.length; i++){
                var materialKeys = Object.keys(produto[i]);
                for(j=0;j<materialKeys.length;j++){
                    if(produto[i][materialKeys[j]] == null){
                        produto[i][materialKeys[j]] = "";
                    }
                }

                var tipoMaterial = produto[i]['NOME_TIPO_MATERIAL'];
                var situacaoMaterial = 'Disponível';
                var classeSituacao = 'bg-success';
                var dataUltimaRetirada = produto[i]['DATA_ULTIMA_RETIRADA'].length > 0 ? moment(produto[i]['DATA_ULTIMA_RETIRADA']).format('DD/MM/YYYYY') : '-';

                if(produto[i]['SITUACAO'] == 2){
                    situacaoMaterial = 'Retirado';
                    classeSituacao = 'bg-warning';
                }

                btnEtiqueta = `<li class="dropdown-item" onclick="gerarEtiqueta(${produto[i]['ID']})"><span class="btn"><i class="fas fa-tag"></i> Gerar Etiqueta</span></li>`;
                btnEditar = `<li class="dropdown-item" onclick="exibirModalEdicaoMaterial(${produto[i]['ID']})"><span class="btn"><i class="fas fa-pen"></i> Editar</span></li>`;
                btnKardex = `<li class="dropdown-item" onclick="exibirKardex(${produto[i]['ID']})"><span class="btn"><i class="fas fa-clipboard-list"></i> Relatório KARDEX</span></li>`;
                btnGerarInativar = `<li class="dropdown-item" onclick="inativarMaterial(${produto[i]['ID']})"><span class="btn"><i class="fas fa-trash"></i> Inativar</span></li>`;

                btnAcoes = `<div class="input-group-prepend show justify-content-center" style="text-align: center">
                                <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    Ações
                                </button>
                                <ul class="dropdown-menu">
                                    ${btnEtiqueta}
                                    ${btnEditar}
                                    ${btnKardex}
                                    ${btnGerarInativar}
                                </ul>
                            </div>`;

                htmlTabela += `
                    <tr id="tableRow${produto[i]['ID']}" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${produto[i]['ID']}</td>
                        <td class="tdTexto">${produto[i]['MATERIAL']}</td>
                        <td class="tdTexto"><center>${produto[i]['MARCA']}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(produto[i]['VALOR'])}</center></td>
                        <td class="tdTexto"><center>${produto[i]['QTDE']}</center></td>
                        <td class="tdTexto"><center><span class="badge bg-dark">${tipoMaterial}</span></center></td>
                        <td class="tdTexto"><center>${dataUltimaRetirada}</center></td>
                        <td class="tdTexto"><center><span class="badge ${classeSituacao}">${situacaoMaterial}</span></center></td>
                        <td>
                            <center>
                                ${btnAcoes}
                            </center>
                        </td>                      
                    </tr>
                    
                    <tr id="tableRow${produto[i]['ID']}" class="d-table-row d-lg-none">
                        <td>
                            <div class="col-12">
                                <center>
                                    <b>${produto[i]['ID']}-${produto[i]['MATERIAL']}(${produto[i]['MARCA']}) </b>
                                </center>
                            </div>
                            <div class="col-12" style="font-size: 4vw">
                                <center>
                                    <span class="badge ${classeSituacao}">${situacaoMaterial}</span> - <b>${mascaraFinanceira(produto[i]['VALOR'])}</b>
                                </center>
                            </div>
                            <div class="col-12" style="font-size: 4vw">
                                <center>
                                    <b>Qtde: ${produto[i]['QTDE']}</b>
                                </center>
                            </div>
                            <div class="col-12">
                                <center>
                                    ${btnAcoes}
                                </center>
                            </div>
                        </td>
                    </tr>`;
            }
            $('#tableBodyDadosproduto').html(htmlTabela)
        }

        function popularListaMarca(dados){
            var htmlTabela = "";

            for(i=0; i< dados.length; i++){
                var materialKeys = Object.keys(dados[i]);
                for(j=0;j<materialKeys.length;j++){
                    if(dados[i][materialKeys[j]] == null){
                        dados[i][materialKeys[j]] = "";
                    }
                }
                btnAcoes = ` <button class="btn" onclick="inativarMarca(${dados[i]['ID']})"><i class="fas fa-trash"></i></button>`;

                htmlTabela += `
                    <tr id="tableRow${dados[i]['ID']}" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['ID']}</td>
                        <td class="tdTexto">${dados[i]['DESCRICAO']}</td>
                        <td>
                            <center>
                                ${btnAcoes}
                            </center>
                        </td>                      
                    </tr>
                    
                    <tr id="tableRow${dados[i]['ID']}" class="d-table-row d-lg-none">
                        <td>
                            <div class="col-12">
                                <center>
                                    <b>${dados[i]['ID']}-${dados[i]['DESCRICAO']} </b>
                                </center>
                            </div>
                            <div class="col-12">
                                <center>
                                    ${btnAcoes}
                                </center>
                            </div>
                        </td>
                    </tr>`;
            }
            $('#tableBodyDadosMarca').html(htmlTabela)
        }
        
        function popularTabelaKardex(dados){
            var htmlTabela = "";

            for(i=0; i< dados.length; i++){
                var materialKeys = Object.keys(dados[i]);
                for(j=0;j<materialKeys.length;j++){
                    if(dados[i][materialKeys[j]] == null){
                        dados[i][materialKeys[j]] = "";
                    }
                }

                var dataMovimentacao = moment(dados[i]['DATA_CADASTRO']).format('DD/MM/YYYY HH:mm');

                htmlTabela += `
                    <tr id="tableRow${dados[i]['ID']}" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['MATERIAL']}</td>
                        <td class="tdTexto"><center>${dataMovimentacao}</center></td>
                        <td class="tdTexto"><center>${dados[i]['USUARIO']}</center></td>
                        <td class="tdTexto"><center>${dados[i]['TIPO_MOVIMENTACAO']}</center></td>
                        <td class="tdTexto"><center>${dados[i]['VALOR']}</center></td>
                        <td class="tdTexto"><center>${dados[i]['ORIGEM']}</center></td>
                    </tr>
                    
                    <tr id="tableRow${dados[i]['ID']}" class="d-table-row d-lg-none">
                        <td>
                            <div class="col-12">
                                <center>
                                    <b>${dados[i]['MATERIAL']} </b>
                                </center>
                            </div>
                            <div class="col-12 row d-flex justify-content-between">
                                <div class="col-4">
                                    <b>Origem: </b>${dados[i]['ORIGEM']}
                                </div>
                                <div class="col-4">
                                    <b>Tipo: </b>${dados[i]['TIPO_MOVIMENTACAO']}
                                </div>
                                <div class="col-4">
                                    <b>Valor: </b>${dados[i]['VALOR']}
                                </div>
                            </div>
                            <div class="col-12 row d-flex justify-content-between">
                                <div class="col-6">
                                    ${dataMovimentacao}
                                </div>
                                <div class="col-6">
                                    ${dados[i]['USUARIO']}
                                </div>
                            </div>
                        </td>
                    </tr>`;
            }
            $('#tableBodyKardex').html(htmlTabela)
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
                var disponivel = $('#selectMaterialDisponivel').val();
                var ultimaRetirada = $('#inputMaterialUltimaRetirada').val();
                var tipoMaterial = $('#selectMaterialTipo').val();
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

                            buscarMaterial();

                            Swal.fire(
                                'Sucesso!',
                                'Material cadastrado com sucesso.',
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

        function inserirMarca() {
            validacao = true;

            var inputIDs = ['inputMarcaDescricao'];

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
                marca = $('#inputMarcaDescricao').val();

                $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'MARCA': marca
                        },
                        url:"{{route('material.inserir.marca')}}",
                        success:function(r){
                            $('#modal-cadastro-marca').modal('hide');

                            Swal.fire(
                                'Sucesso!',
                                'Marca cadastrada com sucesso.',
                                'success',
                            );
                        },
                        error:err=>{exibirErro(err)}
                    })
            }
            
        }

        function inativarMaterial(idMaterial){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar o material?',
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
                            'ID': idMaterial
                        },
                        url:"{{route('material.inativar')}}",
                        success:function(r){
                            Swal.fire('Sucesso!'
                                    , 'Material inativado com sucesso.'
                                    , 'success');
                            buscarMaterial();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        function inativarMarca(idMarca){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar a marca?',
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
                            'ID': idMarca
                        },
                        url:"{{route('material.inativar.marca')}}",
                        success:function(r){
                            Swal.fire('Sucesso!'
                                    , 'Marca inativado com sucesso.'
                                    , 'success');
                            buscarMarca(true);
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        function resetarCampos(){
            $('#inputCodMaterial').val('0')
            $('#inputMaterial').val('')
            $('#selectMaterialMarca').val('0')
            $('#inputMaterialValor').val('')
            $('#inputMaterialQtde').val('')
            $('#selectMaterialDisponivel').val('1')
            $('#inputMaterialUltimaRetirada').val('')

            limparCampo('inputFornecedor', 'inputIDFornecedor', 'btnLimparFornecedor');
        }

        function gerarEtiqueta(idMaterial){
            window.open(`{{env('APP_URL')}}/materiais/etiqueta/${idMaterial}`)
        }

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

        $('#modal-cadastro-marca').on('hidden.bs.modal', function () {
            exibirModalListaMarca();
        });

        $('#btnConfirmar').click(() => {
            inserirMaterial();
        });
        
        $('#btnSalvarMarca').click(() => {
            inserirMarca();
        });

        $('#btnNovoMaterial').click(() => {
            exibirModalCadastroMaterial();
        });

        $('#btnListaMarca').click(() => {
            exibirModalListaMarca();
        });

        $('#btnCadastrarMarca').click(() => {
            exibirModalCadastroMarca();
        });

        $('#btnLimparFornecedor').click(() => {
            limparCampo('inputFornecedor', 'inputIDFornecedor', 'btnLimparFornecedor');
        });

        $('#selectFiltroTipo').on('change', () => {
            buscarMaterial();
        });

        $('#selectFiltroSituacao').on('change', () => {
            buscarMaterial();
        });
        
        $(document).ready(() => {
            buscarTipoMaterial();
            buscarMaterial();
            buscarMarca();
        })
    </script>
@stop