@extends('adminlte::page')

@section('title', 'Produtos')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Produtos</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <button class="col-md-4 btn btn-block btn-warning" id="btnNovoProduto">
                    <i class="fas fa-shopping-basket"></i>
                    <span class="ml-1">Novo</span>
                </button>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="form-group col-12 d-flex">
                        <input type="text" class="form-control" id="inputProdutoBuscar" placeholder="Produto" maxlength="8" onkeyup="buscarProduto()">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-xs">
                    <thead>
                        <th class="d-none d-lg-table-cell">Código</th>
                        <th class="d-none d-lg-table-cell">Produto</th>
                        <th class="d-none d-lg-table-cell"><center>Valor de venda</center></th>
                        <th class="d-none d-lg-table-cell"><center>Valor mínimo</center></th>
                        <th class="d-none d-lg-table-cell"><center>Valor de custo</center></th>
                        <th class="d-none d-lg-table-cell"><center>QTDE</center></th>
                        <th class="d-none d-lg-table-cell"><center>Tipo</center></th>
                        <th class="d-none d-lg-table-cell"><center>Classe</center></th>
                        <th class="d-none d-lg-table-cell"><center>Ações</center></th>
                    </thead>
                    <tbody id="tableBodyDadosproduto">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-cadastro-produto">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title">Produto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="form-group col-12">
                            <label>Titulo</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-12" id="inputProduto">
                                <input type="hidden" class="form-control col-12" id="inputCodProduto">
                            </div>
                        </div>
                        <div class="row d-flex col-12">
                            <div class="col-3">
                                <label>Produto</label>
                                <input type="radio" class="col-3" name="radioTipo" value="1"  id="radioProduto" checked>
                            </div>
                            <div class="col-3">
                                <label>Serviço</label>
                                <input type="radio" class="col-3" name="radioTipo" value="2" id="radioServico">                        
                            </div>
                            <div class="col-3">
                                <label>Cardápio</label>
                                <input type="radio" class="col-3" name="radioTipo" value="3" id="radioCardapio">                        
                            </div>
                        </div>
                        <div class="col-6">
                            <label>Controla Estoque</label>
                            <div class="col-12 d-flex justify-content-start">
                                <input type="checkbox" value="4" id="radioControlaEstoque">
                            </div>
                        </div>
                        <div class="col-6">                  
                            <label>Classe</label>
                            <select id="selectClasseProduto" class="form-control">
                                <option value="0">Selecionar Classe</option>
                            </select>
                        </div>
                        <div class="form-group col-xs-2 col-md-2">
                            <label>Quantidade:</label>
                            <input type="text" inputmode="numeric" class="form-control" id="inputQTDEProd">
                        </div>
                        <div class="form-group col-xs-2 col-md-2">
                            <label>Valor Custo</label>
                            <input type="text" inputmode="numeric" class="form-control" id="inputValorCustoProd">
                        </div>
                        <div class="form-group col-xs-2 col-md-2">
                            <label>% Mínima</label>
                            <input type="text" inputmode="numeric" class="form-control" id="inputPorcentagemMinima">
                        </div>
                        <div class="form-group col-xs-2 col-md-2">
                            <label>Valor Mínimo</label>
                            <input type="text" inputmode="numeric" class="form-control" id="inputValorMinimo">
                        </div>
                        <div class="form-group col-xs-2 col-md-2">
                            <label>% Venda</label>
                            <input type="text" inputmode="numeric" class="form-control" id="inputPorcentagemVenda">
                        </div>
                        <div class="form-group col-xs-2 col-md-2">
                            <label>Valor Venda</label>
                            <input type="text" inputmode="numeric" class="form-control" id="inputValorProd">
                        </div>
                        <div class="form-group col-12">
                            <label>Codigo de Barras</label>
                            <input type="text" class="form-control" id="inputProdutoCodBarras">
                        </div>
                        <div class="form-group col-12">
                            <label>Detalhes</label>
                            <textarea id="textareaProdutoDetalhes" class="col-12 form-control" rows="5"></textarea>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        var editarProduto = false;
        $('#inputValorProd').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputValorCustoProd').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputValorMinimo').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputPorcentagemMinima').maskMoney({ prefix: '', allowNegative: true, thousands: '', decimal: '.' , allowZero: true});
        $('#inputPorcentagemVenda').maskMoney({ prefix: '', allowNegative: true, thousands: '', decimal: '.' , allowZero: true});

        function exibirModalCadastroProduto(){
            $('#modal-cadastro-produto').modal('show');
        }

        function exibirModalEdicaoProduto(idCodigo, descricao, valor, tipo, codBarras, QTDE, valorCusto, porcentagemMinima, porcentagemVenda, valorMinimo, detalhes, controlaEstoque, classe){
            $('#modal-cadastro-produto').modal('show');
            $('#inputCodProduto').val(idCodigo);
            $('#inputProduto').val(descricao);
            $('#inputProdutoCodBarras').val(codBarras);
            $('#inputQTDEProd').val(QTDE);
            $('#inputValorMinimo').val(valorMinimo);
            $('#inputPorcentagemMinima').val(parseFloat(porcentagemMinima));
            $('#inputPorcentagemVenda').val(parseFloat(porcentagemVenda));
            $('#textareaProdutoDetalhes').val(detalhes);
            $('#selectClasseProduto').val(classe);

            if(tipo == 1){
                $('#radioProduto').prop('checked', true);
                $('#radioServico').prop('checked', false);
                $('#radioCardapio').prop('checked', false);
            } else if(tipo == 2) {
                $('#radioServico').prop('checked', true);
                $('#radioProduto').prop('checked', false);
                $('#radioCardapio').prop('checked', false);
            } else {
                $('#radioCardapio').prop('checked', true);
                $('#radioServico').prop('checked', false);
                $('#radioProduto').prop('checked', false);
            }
            
            if(controlaEstoque == 'S'){
                $('#radioControlaEstoque').prop('checked', true);
            } else {
                $('#radioControlaEstoque').prop('checked', false);
            }
            
            $('#inputValorProd').val(mascaraFinanceira(valor));
            $('#inputValorCustoProd').val(mascaraFinanceira(valorCusto));
            editarProduto = true;
        }

        function inserirproduto() {
            validacao = true;

            var inputIDs = ['inputProduto', 'inputValorProd', 'inputQTDEProd'];

            for (var i = 0; i < inputIDs.length; i++) {
                var inputID = inputIDs[i];
                var $input = $('#' + inputID);
                
                if ($input.val() === '') {
                    $input.addClass('is-invalid');
                    validacao = false;
                } else {
                    $input.removeClass('is-invalid');
                }
            }

            if(validacao){
                valorProduto = $('#inputValorProd').val().replace('R$', '').replace('.', '').replace(',', '.').replace(' ', '') ;
                descricaoProduto = $('#inputProduto').val();
                codBarras = $('#inputProdutoCodBarras').val();
                tipoProduto = $('input[name=radioTipo]:checked').val();
                QTDEProd = $('#inputQTDEProd').val();
                valorCusto = $('#inputValorCustoProd').val().replace('R$', '').replace('.', '').replace(',', '.').replace(' ', '');
                valorMinimo= $('#inputValorMinimo').val().replace('R$', '').replace('.', '').replace(',', '.').replace(' ', '');
                porcentagemMinima= $('#inputPorcentagemMinima').val();
                porcentagemVenda = $('#inputPorcentagemVenda').val();
                radioControlaEstoque = $('#radioControlaEstoque').prop('checked') ? 'S' : 'N';
                detalhes = $('#textareaProdutoDetalhes').val();
                classe = $('#selectClasseProduto').val();
               
                if(!editarProduto){                    
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'valor': valorProduto,
                        'descricao': descricaoProduto,
                        'tipoProduto': tipoProduto,
                        'QTDE': QTDEProd,
                        'codBarras': codBarras,
                        'VALOR_CUSTO': valorCusto,
                        'VALOR_MINIMO': valorMinimo,
                        'PORCENTAGEM_MINIMA': porcentagemMinima,
                        'PORCENTAGEM_VENDA': porcentagemVenda,
                        'CONTROLA_ESTOQUE': radioControlaEstoque,
                        'DETALHES': detalhes,
                        'CLASSE': classe,
                        },
                        url:"{{route('produto.inserir')}}",
                        success:function(r){
                            $('#modal-cadastro-produto').modal('hide');
                            buscarProduto();

                            Swal.fire(
                                'Sucesso!',
                                'Produto inserido com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                } else {
                    codigoProduto = $('#inputCodProduto').val();

                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'valor': valorProduto,
                        'descricao': descricaoProduto,
                        'tipoProduto': tipoProduto,
                        'id': codigoProduto,
                        'codBarras': codBarras,
                        'QTDE': QTDEProd,
                        'VALOR_CUSTO': valorCusto,
                        'VALOR_MINIMO': valorMinimo,
                        'PORCENTAGEM_MINIMA': porcentagemMinima,
                        'CONTROLA_ESTOQUE': radioControlaEstoque,
                        'DETALHES': detalhes,
                        'CLASSE': classe,
                        },
                        url:"{{route('produto.alterar')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-cadastro-produto').modal('hide');
                            buscarProduto();

                            Swal.fire(
                                'Sucesso!',
                                'Produto alterado com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            }
            
        }

        function inativarProduto(idProduto){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar o produto?',
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
                            'id': idProduto
                        },
                        url:"{{route('produto.inativar')}}",
                        success:function(r){
                            Swal.fire('Sucesso!'
                                    , 'Produto inativado com sucesso.'
                                    , 'success');
                            buscarProduto();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        function buscarProduto(){
            editarProduto = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'filtro': $('#inputProdutoBuscar').val()
                },
                url:"{{route('produto.buscar')}}",
                success:function(r){
                    popularListaProduto(r);
                },
                error:err=>{exibirErro(err)}
            })
        }  

        function popularListaProduto(produto){
            var htmlproduto = "";

            for(i=0; i< produto.length; i++){
                var produtoKeys = Object.keys(produto[i]);
                for(j=0;j<produtoKeys.length;j++){
                    if(produto[i][produtoKeys[j]] == null){
                        produto[i][produtoKeys[j]] = "";
                    }
                }

                var classeProdutoEstoque = "";

                // VALIDA SE É PRODUTO OU SERVIÇO
                if(produto[i]['TIPO'] == 1){
                    tipoProduto = 'PRODUTO';
                }  else if(produto[i]['TIPO'] == 2) {
                    tipoProduto = 'SERVIÇO';
                } else {
                    tipoProduto = 'CARDÁPIO';
                }

                // VALIDA SE O PRODUTO ESTÁ COM ESTOQUE BAIXO
                if(produto[i]['QTDE'] <= 5 && produto[i]['CONTROLA_ESTOQUE'] == 'S'){
                    classeProdutoEstoque = "estoque-baixo";
                }

                btnAcoes = ` <button class="btn" onclick="exibirModalEdicaoProduto(${produto[i]['ID']},  '${produto[i]['DESCRICAO']}' , ${produto[i]['VALOR']}, ${produto[i]['TIPO']}, '${produto[i]['CODBARRAS']}', ${produto[i]['QTDE']}, ${produto[i]['VALOR_CUSTO']}, '${produto[i]['PORCENTAGEM_MINIMA']}', '${produto[i]['PORCENTAGEM_VENDA']}', '${produto[i]['VALOR_MINIMO']}', '${produto[i]['DETALHES']}', '${produto[i]['CONTROLA_ESTOQUE']}', ${produto[i]['ID_ITEM_PDV_CLASSE']})"><i class="fas fa-pen"></i></button>
                             <button class="btn" onclick="inativarProduto(${produto[i]['ID']})"><i class="fas fa-trash"></i></button>`;

                htmlproduto += `
                    <tr id="tableRow${produto[i]['ID']}" class="${classeProdutoEstoque} d-none d-lg-table-row">
                        <td class="tdTexto">${produto[i]['ID']}</td>
                        <td class="tdTexto">${produto[i]['DESCRICAO']}</td>
                        <td class="tdTexto"><center>${mascaraFinanceira(produto[i]['VALOR'])}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(produto[i]['VALOR_MINIMO'])}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(produto[i]['VALOR_CUSTO'])}</center></td>
                        <td class="tdTexto"><center>${produto[i]['QTDE']}</center></td>
                        <td class="tdTexto"><center>${tipoProduto}</center></td>
                        <td class="tdTexto"><center>${produto[i]['CLASSE']}</center></td>
                        <td>\
                            <center>\
                                ${btnAcoes}
                            </center>\
                        </td>\                      
                    </tr>
                    
                    <tr id="tableRow${produto[i]['ID']}" class="${classeProdutoEstoque} d-table-row d-lg-none">
                        <td>
                            <div class="col-12">
                                <center>
                                    <b>${produto[i]['ID']}-${produto[i]['DESCRICAO']} ${mascaraFinanceira(produto[i]['VALOR'])}</b>
                                </center>
                            </div>
                            <div class="col-12" style="font-size: 4vw">
                                <center>
                                    ${produto[i]['CLASSE']} - ${tipoProduto}
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
            $('#tableBodyDadosproduto').html(htmlproduto)
        }

        function buscarClasseProduto(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}'
                },
                url:"{{route('produto.buscar.classe')}}",
                success:function(r){
                    popularSelectClasseProduto(r.dados);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularSelectClasseProduto(Dados) {
            var htmlDados = '<option value="0">Selecionar Classe</option>';
        
            for (i = 0; i < Dados.length; i++) {
                var DadosKeys = Object.keys(Dados[i]);
                for (j = 0; j < DadosKeys.length; j++) {
                    if (Dados[i][DadosKeys[j]] == null) {
                        Dados[i][DadosKeys[j]] = '';
                    }
                }
        
                htmlDados += `
                    <option value="${Dados[i]['ID']}">${Dados[i]['DESCRICAO']}</option>`;
            }
        
            $('#selectClasseProduto').html(htmlDados);
        }

        $('#btnConfirmar').click(() => {
            inserirproduto();
        });

        $('#btnNovoProduto').click(() => {
            exibirModalCadastroProduto();
        })

        function exibirErro(err){
            errorMessage =
            "<p><b>Exception: </b>"+err.responseJSON.exception+"<p></br>"
            +"<p><b>File: </b>"+err.responseJSON.file+"<p></br>"
            +"<p><b>Line: </b>"+err.responseJSON.line+"<p></br>"
            +"<p><b>Message: </b>"+err.responseJSON.message+"<p></br>";

            Swal.fire(
                'Request exception',
                errorMessage,
                'error'
            )
            console.log(err)
        }

        function mascaraFinanceira(valor){
            return (valor-0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }
        
        $(document).ready(() => {
            buscarProduto();

            buscarClasseProduto();
        })
    </script>
@stop