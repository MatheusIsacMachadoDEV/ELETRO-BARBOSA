@extends('adminlte::page')

@section('title', 'Compras | GSSoftware')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Compras</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <div class="col-12 col-md-6 row d-flex d-flex justify-content-end ">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-block btn-warning" id="btnNovo">
                            <i class="fas fa-shopping-basket"></i>
                            <span class="ml-1">Nova Compra</span>
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
                        <input type="text" class="form-control form-control-border" id="inputFiltro" placeholder="Filtro" maxlength="8" onkeyup="buscarDados()">
                    </div>
                    <div class="col-12 col-md-6">
                        <select id="selectFiltroSituacao" class="form-control form-control-borde">
                            <option value="0">Todas as Situações</option>   
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-xs">
                    <thead>
                        <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">Responsável</th>
                        <th class="d-none d-lg-table-cell"><center>Data</center></th>
                        <th class="d-none d-lg-table-cell"><center>Valor</center></th>
                        <th class="d-none d-lg-table-cell"><center>Situação</center></th>
                        <th class="d-none d-lg-table-cell"><center>Aprovação</center></th>
                        <th class="d-none d-lg-table-cell"><center>Observação</center></th>
                        <th class="d-none d-lg-table-cell"><center>Ações</center></th>
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
                    <h4 class="modal-title">Cadastro de Ordem de Compra</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <div class="row d-flex">
                        <input type="hidden" class="form-control form-control-border" id="inputCadastroID" disabled>
                        <div class="form-group col-12 col-md-6">
                            <input type="datetime-local" class="form-control form-control-border" id="inputCadastroData" value="{{date('Y-m-d H:i')}}">
                        </div>
                        
                        <div class="form-group col-12 col-md-6">
                            <input type="text" class="form-control form-control-border" placeholder="Valor Total Ordem" id="inputCadastroValorTotal">
                        </div>

                        <div class="col-12 row d-flex p-0 m-0">
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
                            <input type="text" class="form-control form-control-border" placeholder="Item: Valor Total" id="inputCadastroItemValorTotal">
                        </div>

                        <div class="form-group col-12">
                            <textarea class="form-control form-control-border" placeholder="Item: Observação" id="inputCadastroItemObs"></textarea>
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

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog ">
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
        var valorTotalOrdem = 0;
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

        function buscarDados(){
            editar = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'filtro': $('#inputFiltro').val(),
                    'ID_SITUACAO': $('#selectFiltroSituacao').val()
                },
                url:"{{route('compras.buscar.ordem')}}",
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
                    'TIPO': 'ORDEM_COMPRA'
                },
                url:"{{route('buscar.situacoes')}}",
                success:function(r){
                    popularSelectSituacoes(r.dados);
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
                var btnAprovavaco = '';
                var btnImprimir = `<button class="btn" onclick="gerarImpresso(${dados[i]['ID']}, 1)"><i class="fas fa-print"></i></button>`;
                var classeBadgeSituacao = 'bg-warning';

                if(dados[i]['ID_USUARIO_APROVACAO'] != null && dados[i]['ID_USUARIO_APROVACAO'] > 0){
                    situacaoAprovacao = `<span class="badge bg-info">${dados[i]['USUARIO_APROVACAO']} - ${moment(dados[i]['DATA_APROVACAO']).format('DD/MM/YYYY H:m')}</span>`;
                }

                if(dados[i]['ID_SITUACAO'] == 1){
                    classeBadgeSituacao = 'bg-success';
                } else if(dados[i]['ID_SITUACAO'] == 2){
                    classeBadgeSituacao = 'bg-danger';
                } else if(dados[i]['STATUS'] == 'A' && dados[i]['ID_SITUACAO'] == 3){
                    btnAprovavaco = `<button class="btn" onclick="alterarSituacaoOrdem(${dados[i]['ID']}, 1)"><i class="fas fa-check" style="color: #63E6BE;"></i></button>
                                     <button class="btn" onclick="alterarSituacaoOrdem(${dados[i]['ID']}, 2)"><i class="fas fa-times" style="color: #ff0000;"></i></button>`;
                }

                if(dados[i]['STATUS'] == 'A' && dados[i]['ID_SITUACAO'] == 3){
                    btnAcoes = `<button class="btn" onclick="exibirModalEdicao(${dados[i]['ID']})"><i class="fas fa-pen"></i></button>
                                <button class="btn" onclick="inativar(${dados[i]['ID']})"><i class="fas fa-trash"></i></button>`;
                }
                
                htmlTabela += `
                    <tr id="tableRow${dados[i]['ID']}" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['USUARIO']}</td>
                        <td class="tdTexto"><center>${moment(dados[i]['DATA_CADASTRO']).format('DD/MM/YYYY H:m')}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(dados[i]['VALOR'])}</center></td>
                        <td class="tdTexto"><center><span class="right badge ${classeBadgeSituacao}">${dados[i]['SITUACAO']}</span></center></td>
                        <td class="tdTexto"><center>${situacaoAprovacao}</center></td>
                        <td class="tdTexto"><center>${dados[i]['OBSERVACAO'].substr(0, 20)}</center></td>
                        <td>
                            <center>
                                ${btnAprovavaco}
                                ${btnAcoes}
                                ${btnImprimir}
                            </center>
                        </td>
                    </tr>
                `;
            }

            $('#tableBodyDados').html(htmlTabela);
        }

        function popularListaItens(){
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
        
        function inserirOrdemServico() {
            validacao = true;

            var inputIDs = ['inputCadastroData', 'inputCadastroValorTotal'];

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
                    'Insira ao menos um item para a ordem de compra!',
                    'warning'
                );
                validacao = false;
            }

            if(validacao){
                var cadastroID = $('#inputCadastroID').val();
                var data = $('#inputCadastroData').val();
                var valorTotal = limparMascaraFinanceira($('#inputCadastroValorTotal').val());
                var projeto = $('#inputCadastroProjeto').val();
                var idProjeto = $('#inputCadastroIDProjeto').val();
                var observacao = $('#inputCadastroObservacao').val();
            
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
                        },
                        url:"{{route('compras.inserir.ordem')}}",
                        success:function(r){
                            $('#modal-cadastro').modal('hide');

                            buscarDados();

                            Swal.fire(
                                'Sucesso!',
                                'Ordem de Compra cadastrada com sucesso.',
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
                        },
                        url:"{{route('material.alterar')}}",
                        success:function(r){
                            $('#modal-cadastro').modal('hide');

                            buscarDados();

                            Swal.fire(
                                'Sucesso!',
                                'Ordem de Compra alterada com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            }
            
        }

        function inserirItemOrdem() {
            var validacao = true;

            if($('#inputCadastroItem').val().trim().length == 0){
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

        function alterarSituacaoOrdem(idOrdem, situacao){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja alterar a situação da ordem de compra?',
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
                            'ID': idOrdem,
                            'ID_SITUACAO' : situacao
                        },
                        url:"{{route('compras.alterar.situacao')}}",
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

        function calcularValorTotal(){
            var valorTotal = 0;
            for(i=0; i< dadosItens.length; i++){
                valorItemAutal = parseFloat(dadosItens[i]['VALOR_TOTAL']);
                valorTotal = (valorTotal + valorItemAutal);
            }

            valorTotalOrdem = valorTotal;

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
            $('#inputCadastroData').val(moment().format('YYYY-MM-DD H:m'))
            $('#inputCadastroValorTotal').val('')
            $('#inputCadastroValorTotal').val('')
            $('#inputCadastroItem').val('')
            $('#inputCadastroItemID').val('0')
            $('#inputCadastroItemQTDE').val('')
            $('#inputCadastroItemValorUnitario').val('')
            $('#inputCadastroItemValorTotal').val('')

            dadosItens = [];
            valorTotalOrdem = 0;

            popularListaItens();
        }

        function gerarImpresso(idOrdem){
            window.open(`{{env('APP_URL')}}/compras/imprimir/ordem/${idOrdem}`)
        }

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

        $('#btnCadastroSalvar').on('click', () => {
            inserirOrdemServico();
        });

        $('#btnCadastroAdicionarItem').on('click', () => {
            inserirItemOrdem();
        });

        $('#btnNovo').on('click', () => {
            exibirModalCadastro();
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

        $(document).ready(function() {
            buscarSituacoes();
            buscarDados();
        })
    </script>
@stop