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
                            <i class="fas fa-plug"></i>
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
                        <th class="d-none d-lg-table-cell"><center>Ações</center></th>
                    </thead>
                    <tbody id="tableBodyDados">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-cadastro" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog modal-lg"> 
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
                            <textarea class="form-control form-control-border" placeholder="Observações" id="inputCadastroValorTotal" maxlength="200"></textarea>
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
                            <input type="text" class="form-control form-control-border" placeholder="Quantidade" id="inputCadastroItemQTDE">
                        </div>

                        <div class="form-group col-4">
                            <input type="text" class="form-control form-control-border" placeholder="Valor Unitário" id="inputCadastroItemValorUnitario">
                        </div>

                        <div class="form-group col-4">
                            <input type="text" class="form-control form-control-border" placeholder="Valor Total" id="inputCadastroItemValorTotal">
                        </div>

                        <div class="form-group col-12">
                            <textarea class="form-control form-control-border" placeholder="Observação do Item" id="inputCadastroItemObs"></textarea>
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

        $('#inputCadastroValorTotal').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputCadastroItemValorTotal').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputCadastroItemValorUnitario').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
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
                    'filtro': $('#inputDescricaoFiltro').val()
                },
                url:"{{route('compras.buscar.ordem')}}",
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
                var situacaoAprovacao = '-';
                var btnAcoes = '-';
                var classeBadgeSituacao = 'bg-warning';

                if(dado[i]['ID_USUARIO_APROVACAO'] != null && dado[i]['ID_USUARIO_APROVACAO'] != '0'){
                    situacaoAprovacao = `${dados['USUARIO_APROVACAO']} - ${moment(dados[i]['DATA_APROVACAO']).format('DD/MM/YYYY HH:mi')}`;
                }

                if(dados[i]['ID_SITUACAO'] == 2){
                    classeBadgeSituacao = 'bg-success';
                } else if(dados[i]['ID_SITUACAO'] == 3){
                    classeBadgeSituacao = 'bg-danger';
                }

                if(dados[i]['SITUACAO'] == 'A'){
                    btnAcoes = ` <button class="btn" onclick="exibirModalEdicao(${dados[i]['ID']})"><i class="fas fa-pen"></i></button>
                                 <button class="btn" onclick="inativar(${dados[i]['ID']})"><i class="fas fa-trash"></i></button>`;
                }
                
                htmlTabela += `
                    <tr id="tableRow${dados[i]['ID']}" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['USUARIO']}</td>
                        <td class="tdTexto">${moment(dados[i]['DATA_CADASTRO']).format('DD/MM/YYYY HH:mi')}</td>
                        <td class="tdTexto">${mascaraFinanceira(dados[i]['VALOR_TOTAL'])}</td>
                        <td class="tdTexto"><span class="right badge ${classeBadgeSituacao}">${dados[i]['SITUACAO']}</span></td>
                        <td class="tdTexto">${situacaoAprovacao}</td>
                        <td>
                            <center>
                            ${btnAcoes}
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

        function inserirItemOrdem() {
            var validacao = true;

            if(validacao){
                var descItem = '';

                var dadoItemSelecionado = {
                    ID_ITEM : $('#inputCadastroItem').val(),
                    ITEM : $('#inputCadastroItemID').val(),
                    VALOR_UNITARIO : limparMascaraFinanceira($('#inputCadastroItemValorUnitario').val()),
                    QTDE : $('#inputCadastroItemQTDE').val(),
                    VALOR_TOTAL : limparMascaraFinanceira($('#inputCadastroItemValorTotal').val()),
                    OBSERVACAO : $('#inputCadastroItemObs').val(),
                    ID_UNICO : dadosItens.length+1
                }
    
                dadosItens.push(dadoItemSelecionado);  
                
                popularListaItens();
    
                calcularValorTotal();
    
                resetarCamposItem();
            }
        }

        function calcularValorTotal(){
            var valorTotal = 0;
            for(i=0; i< dadosItens.length; i++){
                valorItemAutal = parseFloat(dadosItens[i]['VALOR_TOTAL']);
                valorTotal = (valorTotal + valorItemAutal);
            }

            valorTotalOrdem = valorTotal;

            $('#inputCadastroValorTotal').html(mascaraFinanceira(valorTotal));
        }

        function removerItem(ID_UNICO){
            let index = dadosItens.findIndex(
                (el)=>{                      
                    return el.ID_UNICO == ID_UNICO;
                }
            );

            dadosItens.splice(index,1);

            popularListaItem();
            calcularValorTotal();
        }

        function resetarCampos(){
            $('#inputCadastroID').val('0')
            $('#inputCadastroData').val(moment().format('YYYY-MM-DD DD:mi'))
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

        function resetarCamposItem(){
            $('#inputCadastroItem').val('')
            $('#inputCadastroItemID').val('0')
            $('#inputCadastroItemQTDE').val('')
            $('#inputCadastroItemValorUnitario').val('')
            $('#inputCadastroItemValorTotal').val('')
            $('#inputCadastroItemObs').val('')
        }

        $('#btnCadastroSalvar').on('click', () => {
            inserirOrdemServico();
        });

        $('#btnCadastroAdicionarItem').on('click', () => {
            inserirItemOrdem();
        });

        $('#btnNovo').on('click', () => {
            exibirModalCadastro();
        });

        $(document).ready(function() {
            buscarDados();
        })
    </script>
@stop