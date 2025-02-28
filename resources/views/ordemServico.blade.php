@extends('adminlte::page')

@section('title', 'Ordem de Servico')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Ordem de Serviço</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <button class="col-md-4 btn btn-block btn-warning" id="btnNovaOrdem">
                    <i class="fas fa-money-check-alt"></i>
                    <span class="ml-1">Nova</span>
                </button>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="input-group col-md-6 col-sm-12 p-0">
                        <div class="input-group col-md-5 col-sm-12">
                            <input type="date" class="form-control" id="inputDataInicio" onchange="filtroBuscarOrdem()">
                        </div>
                        <label class="col-2 display-content-center">Até</label>
                        <div class="input-group col-md-5 col-sm-6">
                            <input type="date" class="form-control" id="inputDataFim" onchange="filtroBuscarOrdem()">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-md">
                    <thead>
                        <tr>
                            <th>Data de Ordem</th>
                            <th><center>Cliente</center></th>
                            <th><center>Valor Total</center></th>
                            <th><center>Pagamento</center></th>
                            <th><center>Ações</center></th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyDadosOrdem">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-Ordem">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Ordem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        
                        <div class="form-group col-xs-6 col-md-6">
                            <label>Data Ordem</label>
                            <input type="date"  class="form-control" id="inputDataOrdem">
                        </div>                        
                        
                        <div class="form-group col-xs-2 col-md-6">
                            <label>Tipo Pagamento</label>
                            <select type="text" class="form-control" id="inputPagamentoOrdem">
                                <option value="1">Dinheiro</option>
                                <option value="2">Cartão</option>
                                <option value="3">Pix</option>
                                <option value="4">Fiado</option>
                            </select>
                        </div>

                        <div class="form-group col-12 d-none" id="divPessoaFiado">
                            <label>Pessoa Fiado</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-3 d-none" id="inputCodPessoa">
                                <input type="text" class="form-control col-9" id="inputNomePessoa">
                                <button id="btnLimparPessoa" class="btn btn-danger d-none col-3"><i class="fas fa-eraser"></i>LIMPAR</button>
                            </div>
                        </div>

                        <div class="form-group col-12">
                            <label>Cliente</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-9" id="inputComprador">
                                <input type="hidden" class="form-control col-9" id="inputCompradorID">
                                <input type="text" class="form-control col-3" id="inputCompradorDocumento" disabled>
                            </div>
                            <button id="btnLimparComprador" class="btn btn-default d-none">LIMPAR</button>
                        </div>

                        <div class="form-group col-12">
                            <label>Serviço</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control d-none" id="inputCodProdID">
                                <input type="text" class="form-control col-4" id="inputCodProd">
                                <input type="text" class="form-control col-6" id="inputProdDesc" disabled>
                                <button id="btnLimparPlaca" class="btn btn-danger d-none col-2"><i class="fas fa-eraser"></i>LIMPAR</button>
                            </div>
                        </div>

                        <div class="form-group col-xs-6 col-md">
                            <label>Valor Custo</label>
                            <input type="text"  class="form-control" id="inputValorCustoItem">
                        </div>  

                        <div class="form-group col-xs-6 col-md">
                            <label>Valor Gasto</label>
                            <input type="text"  class="form-control" id="inputValorGastoItem">
                        </div>  

                        <div class="form-group col-xs-6 col-md">
                            <label>% Venda</label>
                            <input type="text"  class="form-control" id="inputPorcentagemVenda">
                        </div>  

                        <div class="form-group col-xs-6 col-md">
                            <label>Valor Venda(Unitario)</label>
                            <input type="text"  class="form-control" id="inputValorUnitarioItem">
                        </div>  
                        
                        <div class="form-group col-xs-6 col-md">
                            <label>Quantidade</label>
                            <input type="text"  class="form-control" id="inputQtdeItem">
                        </div>  
                        
                        {{-- <label>Valor Mínimo <span id="spanValorMinimo"></span></label> --}}

                        <div class="col-12">
                            <button class="btn btn-block btn-info col-12" id="btnAddItem"><i class="fas fa-hammer"></i>Adicionar Serviço</button>
                        </div>

                        <div class="col-12">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th>Serviço</th>
                                        <th><center>Quantidade</center></th>
                                        <th><center>Valor Unitario</center></th>
                                        <th><center>Valor Total</center></th>
                                        <th><center>Ações</center></th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyDadosItem">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="badge bg-success" id="spanValorTotalOrdem" style="font-size: 20px">R$ 0,00</span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmar">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-itens-Ordem">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title">Itens da Ordem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th><center>Valor Unitário</center></th>
                                        <th><center>Valor Custo</center></th>
                                        <th><center>Valor Gasto</center></th>
                                        <th><center>Valor Total</center></th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyItemOrdem">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" >Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog ">
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
                                <div class="">
                                    <input type="hidden" id="inputPlacaDocumentacao">
                                    <input type="hidden" id="inputidOrdemServicoDocumentacao">
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

        .ui-autocomplete{
            z-index: 1050;
        }

        .table td{
            padding: 0px;
        }

        table tr:hover {
            color: #251991;
            cursor: pointer;
        }

        .table td, .table th {
            padding: 5px;
        }

        table tr:nth-child(even) /* CSS3 */ {
            background: #e0a80041;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{env('APP_URL')}}/main.js"></script>
    <script>
        var timeoutFiltro;
        var dadosItemPedido = [];
        var valorTotalPedido = 9;

        $('#inputValorUnitarioItem').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', allowZero: true });
        $('#inputValorCustoItem').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', allowZero: true });
        $('#inputValorGastoItem').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', allowZero: true });
        $('#inputPorcentagemVenda').maskMoney({ prefix: '', allowNegative: true, thousands: '', decimal: '.', allowZero: true });
        $('#inputQtdeItem').mask("00000");

        function cadastarOrdem(){
            dadosItemPedido = [];
            valorTotalPedido = 0;

            $('#modal-Ordem').modal('show');
            $('#btnConfirmar').removeClass('d-none');

            $('#inputDataOrdem').val(moment().format('YYYY-MM-DD'));
            $('#divPessoaFiado').addClass('d-none');

            $('#inputPagamentoOrdem').val('1');

            popularListaItem();

            calcularValorTotal();

            limparOrdem();
            limparFiado();
        }

        function fecharCadastroDocumento(){
            $('#modal-documentacao').modal('hide');
        }        

        function limparOrdem(){
            $('#inputCodProdID').val('');
            $('#inputCodProd').val('');
            $('#inputProdDesc').val('');
            $('#inputValorUnitarioItem').val('');
            $('#inputQtdeItem').val('');
            $('#inputQuantidadeOrdem').val('');
            $('#inputValorGastoItem').val('');
            $('#inputValorCustoItem').val('');
            $('#inputPorcentagemVenda').val('');

            $('#inputCodProdID').addClass('d-none');
            $('#inputCodProd').removeClass('d-none');
            $('#btnLimparPlaca').addClass('d-none');
        } 

        function limparFiado(){
            $('#inputCodPessoa').val('0');
            $('#inputNomePessoa').val('');
            $('#inputNomePessoa').attr('disabled', false); 
            $('#btnLimparPessoa').addClass('d-none');
        }           

        function inserirOrdem() {
            validacao = true;

            var inputIDs = [
                ,'inputDataOrdem'
            ];

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

            if(dadosItemPedido.length == 0 ){
                Swal.fire('Atenção!', 'Insira ao menos um item, ou verifique os campos obrigatórios.', 'warning')
                validacao = false;
            }

            if($('#inputPagamentoOrdem').val() == '4'){
                if($('#inputNomePessoa').val().length == 0 || $('#inputCodPessoa').val() == 0 || $('#inputCodPessoa').val() == ""){
                    $('#inputNomePessoa').addClass('is-invalid');
                    validacao = false;
                } else {
                    $('#inputNomePessoa').removeClass('is-invalid');
                }
            }

            if(validacao){

                // if(inserindoVeiculo){                    
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'dataOrdem': $('#inputDataOrdem').val(),
                        'pagamento': $('#inputPagamentoOrdem').val(),
                        'dadosItemPedido': dadosItemPedido,
                        'valorTotal': valorTotalPedido,
                        'idPessoaFiado': $('#inputCodPessoa').val(),
                        'idCliente': $('#inputCompradorID').val(),
                        'nomeCliente': $('#inputComprador').val()
                        },
                        url:"{{route('ordem.inserir')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-Ordem').modal('hide');
                            buscarOrdem();

                            Swal.fire(
                                'Sucesso!',
                                'Ordem inserida com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                // } else {
                //     $.ajax({
                //         type:'post',
                //         datatype:'json',
                //         data:{
                //         '_token':'{{csrf_token()}}',
                //         'modelo': $('#inputModelo').val(),
                //         'renavam': $('#inputRenavam').val(),
                //         'chassis': $('#inputChassis').val(),
                //         'cor': $('#inputCor').val(),
                //         'ano': $('#inputAno').val(),
                //         'km': $('#inputKM').val(),
                //         'dataCompra': $('#inputDataCompra').val(),
                //         'valorCompra': valorCompra,
                //         'ID': $('#inputID').val()
                //         },
                //         url:"{{route('veiculo.alterar')}}",
                //         success:function(r){
                //             console.log(r);
                //             $('#modal-cadastro').modal('hide');
                //             buscarVeiculo();

                //             Swal.fire(
                //                 'Sucesso!',
                //                 'Veículo alterado com sucesso.',
                //                 'success',
                //             )
                //         },
                //         error:err=>{exibirErro(err)}
                //     })
                // }
            }
            
        }           

        function inserirItemOrdem() {
            var descricaoItem = $('#inputProdDesc').val();
            var idServico = $('#inputCodProdID').val();

            if($('#inputCodProdID').val() == 0){
                descricaoItem = $('#inputCodProd').val();
                idServico = 0;
            }

            var dadoItemSelecionado = {
                idItem : idServico,
                descItem : descricaoItem,
                valorUnitario : limparMascaraFinanceira($('#inputValorUnitarioItem').val()),
                valorCusto : limparMascaraFinanceira($('#inputValorCustoItem').val()),
                valorGasto : limparMascaraFinanceira($('#inputValorGastoItem').val()),
                valorTotal : parseFloat(limparMascaraFinanceira($('#inputValorUnitarioItem').val())) * $('#inputQtdeItem').val(),
                porcentagem : parseFloat($('#inputPorcentagemVenda').val()),
                qtde : $('#inputQtdeItem').val(),
                randomId : Math.random().toString(36).substring(2, 7)
            }

            dadosItemPedido.push(dadoItemSelecionado);  
            
            popularListaItem();

            calcularValorTotal();

            limparOrdem();
        }

        function inativarOrdem(idOrdemServico){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar a Ordem?',
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
                            'idOrdemServico': idOrdemServico
                        },
                        url:"{{route('ordem.inativar')}}",
                        success:function(r){
                            Swal.fire('Sucesso!'
                                    , 'Ordem inativada com sucesso.'
                                    , 'success');
                            buscarOrdem();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        function removerItemOrdem(randomID){
            let index = dadosItemPedido.findIndex(
                (el)=>{                      
                    return el.randomId == randomID;
                }
            );

            dadosItemPedido.splice(index,1);

            popularListaItem();
            calcularValorTotal();
        }

        function verOrdem(idOrdemServico){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'placa': '',
                    'idOrdemServico': idOrdemServico,
                },
                url:"{{route('ordem.buscar')}}",
                success:function(r){
                    $('#inputPlaca').val(r[0]['PLACA']);
                    $('#inputVeiculoDesc').val(r[0]['MODELO']);
                    $('#inputPlaca').prop('disabled', true);
                    $('#inputVendedor').val(r[0]['VENDEDOR']);
                    $('#inputVendedor').prop('disabled', true);
                    $('#inputVendedorDocumento').val(mascaraDocumento(r[0]['VENDEDOR_DOCUMENTO']));
                    $('#inputComprador').val(r[0]['COMPRADOR']);
                    $('#inputComprador').prop('disabled', true);
                    $('#inputCompradorDocumento').val(mascaraDocumento(r[0]['COMPRADOR_DOCUMENTO']));
                    $('#inputDataOrdem').val(r[0]['DATA']);
                    $('#inputDataOrdem').prop('disabled', true);
                    $('#inputValorOrdem').val(mascaraFinanceira(r[0]['VALOR']));
                    $('#inputValorOrdem').prop('disabled', true);
                    $('#inputPagamento').val(r[0]['PAGAMENTO']);
                    $('#inputPagamento').prop('disabled', true);
                    $('#inputCompradorID').val(r[0]['ID_CLIENTE']);
                    $('#inputCompradorID').prop('disabled', true);
                    $('#inputComprador').val(r[0]['NOME_CLIENTE'])
                    $('#inputComprador').prop('disabled', true);

                    
                    $('#btnLimparComprador').addClass('d-none');
                    $('#btnLimparVendedor').addClass('d-none');
                    $('#btnLimparPlaca').addClass('d-none');

                    $('#btnConfirmar').addClass('d-none');                    

                    $('#modal-Ordem').modal('show');
                },
                error:err=>{exibirErro(err)}
            })
        }

        function verItemOrdem(idOrdemServico){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'idOrdemServico': idOrdemServico,
                },
                url:"{{route('ordem.buscar.item')}}",
                success:function(r){
                    popularListaItemVisualizacao(r)

                    $('#modal-itens-Ordem').modal('show');
                },
                error:err=>{exibirErro(err)}
            })
        }

        function buscarOrdem(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'dataInicio': $('#inputDataInicio').val(),
                    'dataTermino': $('#inputDataFim').val(),
                },
                url:"{{route('ordem.buscar')}}",
                success:function(r){
                    popularListaOrdem(r);
                },
                error:err=>{exibirErro(err)}
            })
        }
        
        function filtroBuscarOrdem(){
            clearTimeout(timeoutFiltro);
            timeoutFiltro = setTimeout(() => {
                buscarOrdem();
            }, 1500);
        }

        function popularListaOrdem(Ordem){
            var htmlOrdem = "";
            var totalOrdems = 0;

            for(i=0; i< Ordem.length; i++){
                var OrdemKeys = Object.keys(Ordem[i]);
                for(j=0;j<OrdemKeys.length;j++){
                    if(Ordem[i][OrdemKeys[j]] == null){
                        Ordem[i][OrdemKeys[j]] = "";
                    }
                }

                var tipoPagamento = '';

                if(Ordem[i]['PAGAMENTO'] == 1){
                    tipoPagamento = 'Dinheiro';
                } else if(Ordem[i]['PAGAMENTO'] == 2){
                    tipoPagamento = 'Cartão';
                } else if(Ordem[i]['PAGAMENTO'] == 3){
                    tipoPagamento = 'PIX';
                } else if(Ordem[i]['PAGAMENTO'] == 4){
                    tipoPagamento = 'FIADO';
                } 

                htmlOrdem += `
                    <tr id="tableRow${Ordem[i]['ID']}">
                        <td class="tdTexto">${moment(Ordem[i]['DATA']).format('DD/MM/YYYY')}</td>
                        <td class="tdTexto"><center>${Ordem[i]['NOME_CLIENTE']}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(Ordem[i]['VALOR_TOTAL'])}</center></td>
                        <td class="tdTexto"><center>${tipoPagamento}</center></td>
                        <td>\
                            <center>\                               
                                <button class="btn" onclick="verItemOrdem(${Ordem[i]['ID']})"><i class="fas fa-eye"></i></button>\
                                <button class="btn" onclick="imprimirOrdem(${Ordem[i]['ID']})"><i class="fas fa-print"></i></button>\
                                <button class="btn" onclick="inativarOrdem(${Ordem[i]['ID']})"><i class="fas fa-trash"></i></button>\
                            </center>\
                        </td>\                      
                    </tr>`;

                
                    totalOrdems = totalOrdems + Ordem[i]['VALOR_TOTAL'];
            }

            htmlOrdem += `
                    <tr id="tableRowTOTAL">
                        <td></td>
                        <td class="tdTexto"><center>Total em Ordems</center></td>
                        <td class="tdTexto"><center><span class="badge bg-success">${mascaraFinanceira(totalOrdems)}</span></center></td>
                        <td></td>
                        <td></td>\                      
                    </tr>`;
            $('#tableBodyDadosOrdem').html(htmlOrdem)
        }

        function popularListaItem(){
            var htmldadosItemPedido = "";

            for(i=0; i< dadosItemPedido.length; i++){
                var dadosItemPedidoKeys = Object.keys(dadosItemPedido[i]);
                for(j=0;j<dadosItemPedidoKeys.length;j++){
                    if(dadosItemPedido[i][dadosItemPedidoKeys[j]] == null){
                        dadosItemPedido[i][dadosItemPedidoKeys[j]] = "";
                    }
                }

                htmldadosItemPedido += `
                    <tr id="tableRow">
                        <td class="tdTexto">${dadosItemPedido[i]['descItem']}</td>
                        <td class="tdTexto"><center>${dadosItemPedido[i]['qtde']}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(dadosItemPedido[i]['valorUnitario'])}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(dadosItemPedido[i]['valorTotal'])}</center></td>
                        <td>\
                            <center>\                            
                                <button class="btn" onclick="removerItemOrdem('${dadosItemPedido[i]['randomId']}')"><i class="fas fa-trash"></i></button>\
                            </center>\
                        </td>\                      
                    </tr>`;
            }
            $('#tableBodyDadosItem').html(htmldadosItemPedido)
        }

        function popularListaItemVisualizacao(itens){
            var htmlitensPedido = "";

            for(i=0; i< itens.length; i++){
                var itensPedidoKeys = Object.keys(itens[i]);
                for(j=0;j<itensPedidoKeys.length;j++){
                    if(itens[i][itensPedidoKeys[j]] == null){
                        itens[i][itensPedidoKeys[j]] = "";
                    }
                }

                htmlitensPedido += `
                    <tr id="tableRow">
                        <td class="tdTexto">${itens[i]['PRODUTO']}</td>
                        <td class="tdTexto"><center>${itens[i]['QUANTIDADE']}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(itens[i]['VALOR_UNITARIO'])}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(itens[i]['VALOR_CUSTO'])}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(itens[i]['VALOR_GASTO'])}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(itens[i]['VALOR_TOTAL'])}</center></td>                  
                    </tr>`;
            }
            $('#tableBodyItemOrdem').html(htmlitensPedido)
        }

        function validaDocumento(){
            if ($("#inputArquivoDocumentacao")[0].files.length > 0) {
                $('#labelInputArquivoDocumentacao').html($("#inputArquivoDocumentacao")[0].files[0].name);
            } else {
                $('#labelInputArquivoDocumentacao').html('Selecionar Arquivos');
            }
        }
        
        function limparComprador(){
            $('#inputComprador').val('');
            $('#btnLimparComprador').addClass('d-none');
            $('#inputComprador').attr('disabled', false); 
            $('#inputCompradorDocumento').val('') ;
            $('#inputCompradorID').val(0);
        }  

        function calcularValorTotal(){
            var valorTotal = 0;
            for(i=0; i< dadosItemPedido.length; i++){
                valorItemAutal = parseFloat(dadosItemPedido[i]['valorTotal']);
                valorTotal = (valorTotal + valorItemAutal);
            }

            valorTotalPedido = valorTotal;

            $('#spanValorTotalOrdem').html(mascaraFinanceira(valorTotal));
        }
        
        function calcularValorItem(){
            var valorUnitario = 0, valorCusto = 0, valorGasto = 0, qtde = 0, porcentagemVenda = 0;

            if($('#inputValorUnitarioItem').val() != ''){
                valorUnitario =  parseFloat(limparMascaraFinanceira($('#inputValorUnitarioItem').val()));
            }
            if($('#inputValorGastoItem').val() != ''){
                valorGasto = parseFloat(limparMascaraFinanceira($('#inputValorGastoItem').val()));
            }
            if($('#inputValorCustoItem').val() != ''){
                valorCusto = parseFloat(limparMascaraFinanceira($('#inputValorCustoItem').val()));
            }
            if($('#inputQtdeItem').val() != ''){
                qtde = $('#inputQtdeItem').val();
            }
            if($('#inputPorcentagemVenda').val() != ''){
                porcentagemVenda = $('#inputPorcentagemVenda').val();
            }

            if(porcentagemVenda > 0){
                valorUnitario = (valorGasto + valorCusto) + (((valorGasto + valorCusto) / 100 ) * porcentagemVenda)
            } else {
                valorUnitario = valorGasto + valorCusto;
            }


            $('#inputValorUnitarioItem').val(mascaraFinanceira(valorUnitario));
        }

        function imprimirOrdem($id){
            window.open('{{env('APP_URL')}}ordemServico/impresso/'+$id, '_blank')
        }

        $("#inputCodProd").autocomplete({
            source: function(request, cb){
                param = request.term;
                campoBuscado = param;
                $.ajax({
                    url:"{{route('produto.buscar')}}",
                    method: 'post',
                    data:{
                        '_token': '{{csrf_token()}}',
                        'filtro': param,
                        'tipo': 2
                    },
                    dataType: 'json',
                    success: function(r){
                        result = $.map(r, function(obj){
                            return {
                                label: obj.info,
                                value: obj.DESCRICAO,
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
                    $('#inputProdDesc').val(selectedData.item.data.DESCRICAO);
                    $('#inputCodProdID').val(selectedData.item.data.ID);
                    $('#inputValorUnitarioItem').val(mascaraFinanceira(selectedData.item.data.VALOR));
                    $('#inputValorGastoItem').val('0');
                    $('#inputValorCustoItem').val(mascaraFinanceira(selectedData.item.data.VALOR_CUSTO));
                    $('#inputPorcentagemVenda').val(parseFloat(selectedData.item.data.PORCENTAGEM_VENDA));
                    $('#inputQtdeItem').val('1');
                    $('#inputCodProd').addClass('d-none');
                    $('#inputCodProdID').removeClass('d-none');
                    $('#inputCodProdID').prop('disabled', true);
                    $('#btnLimparPlaca').removeClass('d-none');
                    $('#inputPlaca').attr('disabled', true); 

                    calcularValorItem();
                } else {
                    $('#inputVeiculoDesc').val('')
                }
            }
        });      
        
        $("#inputNomePessoa").autocomplete({
            source: function(request, cb){
                param = request.term;
                campoBuscado = param;
                $.ajax({
                    url:"{{route('pessoa.buscar')}}",
                    method: 'post',
                    data:{
                        '_token': '{{csrf_token()}}',
                        'nome': param
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
                if (selectedData.item.label != 'Nenhuma Veículo Encontrado.'){
                    $('#inputCodPessoa').val(selectedData.item.data.ID);
                    $('#btnLimparPessoa').removeClass('d-none');
                    $('#inputNomePessoa').attr('disabled', true); 
                } else {
                    $('#inputNomePessoa').val('')
                }
            }
        });

        $('#inputQuantidadeOrdem').on('change', () => {
            calcularValorTotal();
        });

        $('#inputValorOrdem').on('change', () => {
            calcularValorTotal();
        });

        $('#inputValorCustoItem').on('change', () => {
            calcularValorItem();
        });

        $('#inputValorGastoItem').on('change', () => {
            calcularValorItem();
        });

        $('#inputPorcentagemVenda').on('change', () => {
            calcularValorItem();
        });

        $('#inputPagamentoOrdem').on('change', () => {
            if($('#inputPagamentoOrdem').val() == '4'){
                $('#divPessoaFiado').removeClass('d-none');
            } else {
                $('#divPessoaFiado').addClass('d-none');
            }
        });

        $('#btnLimparPlaca').click(() => {
            limparOrdem();
        });

        $("#inputComprador").autocomplete({
            source: function(request, cb){
                param = request.term;
                campoBuscado = param;
                $.ajax({
                    url:"{{route('pessoa.buscar')}}",
                    method: 'post',
                    data:{
                        '_token': '{{csrf_token()}}',
                        'nome': param
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
                if (selectedData.item.label != 'Nenhum Comprador Encontrado.'){
                    $('#inputCompradorDocumento').val(mascaraDocumento(selectedData.item.data.DOCUMENTO));
                    $('#inputCompradorID').val(selectedData.item.data.ID);
                    $('#btnLimparComprador').removeClass('d-none');
                    $('#inputComprador').attr('disabled', true); 
                } else {
                    $('#inputCompradorDocumento').val('')
                }
            }
        });  

        $('#btnLimparPessoa').click(() => {
            limparFiado();
        });

        $('#btnNovaOrdem').click(() => {
            cadastarOrdem();
        });

        $('#btnAddItem').click(() => {
            if($('#inputQtdeItem').val().length < 1 || $('#inputQtdeItem').val() == 0){
                $('#inputQtdeItem').addClass('is-invalid');
            } else if(limparMascaraFinanceira($('#inputValorUnitarioItem').val()).length < 1 || limparMascaraFinanceira($('#inputValorUnitarioItem').val()) == 0){
                $('#inputValorUnitarioItem').addClass('is-invalid');
            } else {
                $('#inputCodProdID').removeClass('is-invalid');
                $('#inputValorUnitarioItem').removeClass('is-invalid');
                $('#inputQtdeItem').removeClass('is-invalid');
                inserirItemOrdem();
            }
        });

        $('#btnConfirmar').click(() => {
            inserirOrdem();
        });

        $('#btnLimparComprador').click(() => {
            limparComprador();
        });
        
        $(document).ready(() => {
            $('#inputDataInicio').val(moment().format('YYYY-MM-DD')),
            buscarOrdem();

        })
    </script>
@stop