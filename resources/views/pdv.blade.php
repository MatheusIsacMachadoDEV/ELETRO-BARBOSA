@extends('adminlte::page')

@section('title', 'Venda')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Venda</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <button class="col-md-4 btn btn-block btn-warning" id="btnNovaVenda">
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
                            <input type="date" class="form-control" id="inputDataInicio" onchange="filtroBuscarVenda()">
                        </div>
                        <label class="col-2 display-content-center">Até</label>
                        <div class="input-group col-md-5 col-sm-6">
                            <input type="date" class="form-control" id="inputDataFim" onchange="filtroBuscarVenda()">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <audio id="audioCampainha" src="{{env('APP_URL')}}/arquivos/VENDA/audio/campainha.mp3"></audio>

                <table class="table table-responsive-xs">
                    <thead>
                        <tr>
                            <th class="d-none d-lg-table-cell">Data de Venda</th>
                            <th class="d-none d-lg-table-cell"><center>Valor Total</center></th>
                            <th class="d-none d-lg-table-cell"><center>Cliente</center></th>
                            <th class="d-none d-lg-table-cell"><center>Telefone</center></th>
                            <th class="d-none d-lg-table-cell"><center>Retirada</center></th>
                            <th class="d-none d-lg-table-cell"><center>Endereço</center></th>
                            <th class="d-none d-lg-table-cell"><center>Pagamento</center></th>
                            <th class="d-none d-lg-table-cell"><center>Situacao</center></th>
                            <th class="d-none d-lg-table-cell"><center>Ações</center></th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyDadosVenda">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-venda">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Venda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <input type="hidden" id="inputIdVenda">
                        
                        <div class="form-group col-xs-6 col-md-6">
                            <label>Data Venda</label>
                            <input type="datetime-local"  class="form-control" id="inputDataVenda">
                        </div>                        
                        
                        <div class="form-group col-xs-2 col-md-6">
                            <label>Tipo Pagamento</label>
                            <select type="text" class="form-control" id="inputPagamentoVenda">
                                <option value="1">Dinheiro</option>
                                <option value="2">Cartão</option>
                                <option value="3">Pix</option>
                                <option value="4">Fiado</option>
                            </select>
                        </div>

                        <div class="form-group col-12" id="divPessoaFiado">
                            <label>Cliente</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col d-none" id="inputCodPessoa">
                                <input type="text" class="form-control col" id="inputNomePessoa">
                                <button id="btnLimparPessoa" class="btn btn-danger d-none col"><i class="fas fa-eraser"></i>LIMPAR</button>
                            </div>
                        </div>

                        <div class="form-group col-12">
                            <label>Produto</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-6" id="inputCodProd">
                                <input type="text" class="form-control col-3 d-none" id="inputCodProdID">
                                <input type="text" class="form-control col-3" id="inputProdDesc" disabled>
                                <button id="btnLimparPlaca" class="btn btn-danger d-none col-3"><i class="fas fa-eraser"></i>LIMPAR</button>
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

                        <div class="col-12">
                            <button class="btn btn-block btn-info col-12" id="btnAddItem"><i class="fas fa-cart-plus"></i>Adicionar Item</button>
                        </div>

                        <div class="col-12">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th class="d-none d-lg-block"><center>Quantidade</center></th>
                                        <th><center>Valor Unitário</center></th>
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
                    <span class="badge bg-success" id="spanValorTotalVenda" style="font-size: 20px">R$ 0,00</span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmar">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-itens-venda">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title">Itens da Venda</h5>
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
                                        <th><center>Observação</center></th>
                                        <th><center>Valor Unitário</center></th>
                                        <th><center>Valor Custo</center></th>
                                        <th><center>Valor Gasto</center></th>
                                        <th><center>Valor Total</center></th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyItemVenda">
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
                    <h5 class="modal-title" >Documentação da Venda <span id="titleDocumento"></span></h5>
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
                                    <input type="hidden" id="inputIDVendaDocumentacao">
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
    <link rel="stylesheet" href="{{env('APP_URL')}}/main.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
            padding: 0px!important;
        }


        .zebrado{
            background: #e0a80041!important;
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
        var qtdeProdutoSelecionado = 0;
        var timeoutAtualizador = 0;
        var htmlOptionsPagamento = '';

        $('#inputValorUnitarioItem').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' });
        $('#inputValorCustoItem').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', allowZero: true });
        $('#inputValorGastoItem').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', allowZero: true });
        $('#inputPorcentagemVenda').maskMoney({ prefix: '', allowNegative: true, thousands: '', decimal: '.', allowZero: true });
        $('#inputQtdeItem').mask("00000");

        function cadastarVenda(){
            dadosItemPedido = [];
            valorTotalPedido = 0;

            $('#inputIdVenda').val('0');

            $('#modal-venda').modal('show');
            $('#btnConfirmar').removeClass('d-none');

            $('#inputDataVenda').val(moment().format('YYYY-MM-DD H:m'));
            // $('#divPessoaFiado').addClass('d-none');

            $('#inputPagamentoVenda').val('1');

            popularListaItem();

            calcularValorTotal();

            limparVenda();
            limparFiado();
        }

        function fecharCadastroDocumento(){
            $('#modal-documentacao').modal('hide');
        }        

        function limparVenda(){
            $('#inputCodProdID').val('');
            $('#inputCodProd').val('');
            $('#inputProdDesc').val('');
            $('#inputValorUnitarioItem').val('');
            $('#inputQtdeItem').val('');
            $('#inputQuantidadeVenda').val('');
            $('#inputValorGastoItem').val('');
            $('#inputValorCustoItem').val('');

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

        function inserirVenda() {
            validacao = true;

            var inputIDs = [
                ,'inputDataVenda'
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

            if($('#inputPagamentoVenda').val() == '4'){
                if($('#inputNomePessoa').val().length == 0 || $('#inputCodPessoa').val() == 0 || $('#inputCodPessoa').val() == ""){
                    $('#inputNomePessoa').addClass('is-invalid');
                    validacao = false;
                } else {
                    $('#inputNomePessoa').removeClass('is-invalid');
                }
            }

            if(validacao){

                if($('#inputIdVenda').val() == 0){                    
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'dataVenda': $('#inputDataVenda').val(),
                        'pagamento': $('#inputPagamentoVenda').val(),
                        'dadosItemPedido': dadosItemPedido,
                        'valorTotal': valorTotalPedido,
                        'idPessoaFiado': $('#inputCodPessoa').val(),
                        'pessoa': $('#inputNomePessoa').val()
                        },
                        url:"{{route('pdv.inserir')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-venda').modal('hide');
                            buscarVenda();

                            Swal.fire(
                                'Sucesso!',
                                'Venda inserida com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                } else {
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'ID': $('#inputIdVenda').val(),
                            'dataVenda': $('#inputDataVenda').val(),
                            'pagamento': $('#inputPagamentoVenda').val(),
                            'dadosItemPedido': dadosItemPedido,
                            'valorTotal': valorTotalPedido,
                            'idPessoaFiado': $('#inputCodPessoa').val(),
                            'pessoa': $('#inputNomePessoa').val()
                        },
                        url:"{{route('pdv.alterar')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-venda').modal('hide');
                            buscarVenda();

                            Swal.fire(
                                'Sucesso!',
                                'Venda alterada com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            }
            
        }          

        function inserirItemVenda() {
            var validacao = true;

            if($('#inputCodProdID').val() != 0){
                if(qtdeProdutoSelecionado != -1){
                    if((qtdeProdutoSelecionado - parseFloat($('#inputQtdeItem').val())) < 0){
                        Swal.fire('Atenção!'
                                 , `A QTDE informada para o item (${$('#inputQtdeItem').val()}), ultrapassa a quantidade em estoque dísponível (${qtdeProdutoSelecionado}). Ajuste a quantidade em estoque do produto.`
                                 , 'warning');
                        validacao = false;
                    } else if((qtdeProdutoSelecionado - parseFloat($('#inputQtdeItem').val())) == 0){
                        Swal.fire('Atenção!'
                                 , `A venda atual irá zerar o estoque do produto, considere repôr o estoque do produto em questão.`
                                 , 'warning');
                    }
                }
            }

            for (let index = 0; index < dadosItemPedido.length; index++) {
                const element = dadosItemPedido[index];

                if(element['idItem'] == $('#inputCodProdID').val()){
                    Swal.fire('Atenção!'
                             , 'Produto já adicionado à venda.'
                             , 'warning');
                    validacao = false;
                }
                
            }

            if(validacao){
                var descItem = '';
                var idItem = 0;

                if($('#inputCodProdID').val() != 0){
                    descItem = $('#inputProdDesc').val();
                    idItem = $('#inputCodProdID').val();
                } else {
                    descItem = $('#inputCodProd').val();
                }

                var dadoItemSelecionado = {
                    idItem : idItem,
                    descItem : descItem,
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
    
                limparVenda();
            }
        }

        function inativarVenda(idVenda){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar a venda?',
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
                            'idVenda': idVenda
                        },
                        url:"{{route('pdv.inativar')}}",
                        success:function(r){
                            Swal.fire('Sucesso!'
                                    , 'Venda inativada com sucesso.'
                                    , 'success');
                            buscarVenda();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        function removerItemVenda(randomID){
            let index = dadosItemPedido.findIndex(
                (el)=>{                      
                    return el.randomId == randomID;
                }
            );

            dadosItemPedido.splice(index,1);

            popularListaItem();
            calcularValorTotal();
        }

        function verVenda(idVenda){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'placa': '',
                    'idVenda': idVenda,
                },
                url:"{{route('venda.buscar')}}",
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
                    $('#inputDataVenda').val(r[0]['DATA']);
                    $('#inputDataVenda').prop('disabled', true);
                    $('#inputValorVenda').val(mascaraFinanceira(r[0]['VALOR']));
                    $('#inputValorVenda').prop('disabled', true);
                    $('#inputPagamento').val(r[0]['PAGAMENTO']);
                    $('#inputPagamento').prop('disabled', true);

                    
                    $('#btnLimparComprador').addClass('d-none');
                    $('#btnLimparVendedor').addClass('d-none');
                    $('#btnLimparPlaca').addClass('d-none');

                    $('#btnConfirmar').addClass('d-none');                    

                    $('#modal-venda').modal('show');
                },
                error:err=>{exibirErro(err)}
            })
        }

        function verItemVenda(idVenda){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'idVenda': idVenda,
                },
                url:"{{route('pdv.buscar.item')}}",
                success:function(r){
                    popularListaItemVisualizacao(r)

                    $('#modal-itens-venda').modal('show');
                },
                error:err=>{exibirErro(err)}
            })
        }

        function buscarVenda(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'dataInicio': $('#inputDataInicio').val(),
                    'dataTermino': $('#inputDataFim').val(),
                },
                url:"{{route('pdv.buscar')}}",
                success:function(r){
                    popularListaVenda(r.dadosVenda);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function editarVenda(ID){
            dadosItemPedido = [];

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'ID_VENDA': ID
                },
                url:"{{route('pdv.buscar')}}",
                success:function(r){
                    var dados = r.dadosVenda[0];
                    $('#inputIdVenda').val(dados['ID']);
                    $('#inputDataVenda').val(dados['DATA']);
                    $('#inputPagamentoVenda').val(dados['PAGAMENTO']);
                    $('#inputNomePessoa').val(dados['CLIENTE_NOME']);
                    $('#inputDataVenda').val(moment(dados['DATA']).format('YYYY-MM-DD H:m'));

                    for (let index = 0; index < dados['ITEM'].length; index++) {
                        var item = dados['ITEM'][index];

                        var dadosItem = {
                            descItem: item['DESCRICAO_PRODUTO'],
                            idItem: item['ID_PRODUTO'],
                            porcentagem: item['PORCENTAGEM'],
                            qtde: item['QUANTIDADE'],
                            randomId: Math.random().toString(36).substring(2, 7),
                            valorCusto: item['VALOR_CUSTO'],
                            valorGasto: item['VALOR_GASTO'],
                            valorTotal: item['VALOR_TOTAL'],
                            valorUnitario: item['VALOR_UNITARIO']
                        }

                        dadosItemPedido.push(dadosItem);                        
                    }

                    popularListaItem();

                    $('#modal-venda').modal('show');
                },
                error:err=>{exibirErro(err)}
            })
        }

        function buscarFormaPagamento(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                   '_token':'{{csrf_token()}}',
                },
                url:"{{route('pdv.buscar.forma.pagamento')}}",
                success:function(r){
                   popularSelectFormaPagamento(r.dados);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularSelectFormaPagamento(Dados) {
            htmlOptionsPagamento = '<option value="0">Forma de Pagamento</option>';
        
            for (i = 0; i < Dados.length; i++) {
                var DadosKeys = Object.keys(Dados[i]);
                for (j = 0; j < DadosKeys.length; j++) {
                    if (Dados[i][DadosKeys[j]] == null) {
                        Dados[i][DadosKeys[j]] = '';
                    }
                }
        
                htmlOptionsPagamento += `
                    <option value="${Dados[i]['ID']}">${Dados[i]['DESCRICAO']}</option>`;
            }
        }
        
        function filtroBuscarVenda(){
            clearTimeout(timeoutFiltro);
            timeoutFiltro = setTimeout(() => {
                buscarVenda();
            }, 1500);
        }

        function popularListaVenda(Venda){
            var htmlVenda = "";
            var totalVendas = 0;
            var totalPedidosNovos = 0;
            var contagemDados = 0;

            for(i=0; i< Venda.length; i++){
                var VendaKeys = Object.keys(Venda[i]);
                for(j=0;j<VendaKeys.length;j++){
                    if(Venda[i][VendaKeys[j]] == null){
                        Venda[i][VendaKeys[j]] = "";
                    }
                }
                
                var endereco = ``;
                var retirada = Venda[i]['PEDIDO_RETIRADA'] == 'S' ? 'Sim' : 'Não';
                var classeBadgeSituacao = 'bg-info';

                var tipoPagamento = 'PAGAMENTO PENDENTE';

                if(Venda[i]['PAGAMENTO'] == 1){
                    tipoPagamento = 'Dinheiro';
                } else if(Venda[i]['PAGAMENTO'] == 2){
                    tipoPagamento = 'Cartão';
                } else if(Venda[i]['PAGAMENTO'] == 3){
                    tipoPagamento = 'PIX';
                } else if(Venda[i]['PAGAMENTO'] == 4){
                    tipoPagamento = 'FIADO';
                } 

                if(Venda[i]['SITUACAO'] == 'CONFIRMADO'){
                    classeBadgeSituacao = 'bg-warning';
                } else if(Venda[i]['SITUACAO'] == 'FINALIZADO'){
                    classeBadgeSituacao = 'bg-success';
                } else if(Venda[i]['SITUACAO'] == 'CANCELADO'){
                    classeBadgeSituacao = 'bg-danger';
                } else if(Venda[i]['SITUACAO'] == 'ENTREGA INICIADA'){
                    classeBadgeSituacao = 'bg-dark';
                } else if(Venda[i]['SITUACAO'] == 'AGUARDANDO CONFIRMACAO'){
                    totalPedidosNovos = totalPedidosNovos + 1;
                }

                if(retirada == 'Sim'){
                    endereco = 'Pedido para retirada';
                } else if(Venda[i]['CLIENTE_NUMERO'].length > 0 && Venda[i]['CLIENTE_RUA'].length > 0 &&  Venda[i]['CLIENTE_CEP'].length > 0 && Venda[i]['CLIENTE_BAIRRO'].length > 0){
                    endereco = `${Venda[i]['CLIENTE_RUA']}, ${Venda[i]['CLIENTE_NUMERO']} - ${Venda[i]['CLIENTE_CEP']} - ${Venda[i]['CLIENTE_BAIRRO']}`;
                }

                if (contagemDados%2 == 0){
                    classelinhaZebrada = ''
                } else {
                    classelinhaZebrada = 'linhaZebrada'
                }

                var btnAcoes = `<button class="btn p-0" onclick="enviarMensagemWhatssApp('${Venda[i]['CLIENTE_TELEFONE'].replace('(','').replace(')', '').replace('-', '').replace(' ', '')}', 'Avulsa')"><i class="fab fa-whatsapp"></i></button>
                                <button class="btn p-0" onclick="verItemVenda(${Venda[i]['ID']})"><i class="fas fa-eye"></i></button>
                                <button class="btn p-0" onclick="editarVenda(${Venda[i]['ID']})"><i class="fas fa-pen"></i></button>
                                <button class="btn p-0" onclick="opcoesVenda(${Venda[i]['ID']}, '${Venda[i]['SITUACAO']}', '${Venda[i]['CLIENTE_TELEFONE'].replace('(','').replace(')', '').replace('-', '').replace(' ', '')}')"><i class="fas fa-cogs"></i></button>
                                <button class="btn p-0" onclick="imprimirVenda(${Venda[i]['ID']})"><i class="fas fa-print"></i></button>
                                <button class="btn p-0" onclick="inativarVenda(${Venda[i]['ID']})"><i class="fas fa-trash"></i></button>
                                `;


                htmlVenda += `
                    <tr id="tableRow${Venda[i]['ID']}" class="d-none d-lg-table-row ${classelinhaZebrada}">
                        <td class="tdTexto">${moment(Venda[i]['DATA']).format('DD/MM/YYYY HH:mm:ss')}</td>
                        <td class="tdTexto"><center>${mascaraFinanceira(Venda[i]['VALOR_TOTAL'])}</center></td>
                        <td class="tdTexto"><center>${Venda[i]['CLIENTE_NOME']}</center></td>
                        <td class="tdTexto"><center>${Venda[i]['CLIENTE_TELEFONE']}</center></td>
                        <td class="tdTexto"><center>${retirada}</center></td>
                        <td class="tdTexto"><center>${endereco}</center></td>
                        <td class="tdTexto"><center>${tipoPagamento}</center></td>
                        <td class="tdTexto"><center><span class="badge ${classeBadgeSituacao}">${Venda[i]['SITUACAO']}</span></center></td>
                        <td class="p-0">
                            <center>
                                ${btnAcoes}
                            </center>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr class="d-table-row d-lg-none ${classelinhaZebrada}">
                        <td>
                            <div class="col-12">
                                <center>
                                    <span class="badge ${classeBadgeSituacao}">${Venda[i]['SITUACAO']}</span>
                                    <span style="font-size: 4vw"><b>${tipoPagamento}</b></span>
                                </center>
                            </div>
                            <div class="col-12">
                                <center>
                                    <b>Nº ${Venda[i]['ID']} ${Venda[i]['CLIENTE_NOME']} ${Venda[i]['CLIENTE_TELEFONE']}</b>
                                </center>
                            </div>
                            <div class="col-12" style="font-size: 4vw">
                                <center>
                                    ${moment(Venda[i]['DATA']).format('DD/MM/YYYY HH:mm:ss')}
                                    <b>${mascaraFinanceira(Venda[i]['VALOR_TOTAL'])}</b>
                                </center>
                            </div>
                            <div class="col-12" style="font-size: 4vw">
                                <center>
                                    ${endereco}
                                </center>
                            </div>
                            <div class="col-12">
                                <center>
                                    ${btnAcoes}
                                </center>
                            </div>
                        </td>
                    </tr>`;

                
                    totalVendas = totalVendas + Venda[i]['VALOR_TOTAL'];
                    contagemDados++
            }

            htmlVenda += `
                    <tr id="tableRowTOTAL" class="d-none d-lg-table-row">
                        <td class="tdTexto"><center>Total em Vendas</center></td>
                        <td class="tdTexto"><center><span class="badge bg-success">${mascaraFinanceira(totalVendas)}</span></center></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>\                      
                    </tr>
                    
                     <tr class="d-table-row d-lg-none">
                        <td class="tdTexto"><center><span class="badge bg-success">Total: ${mascaraFinanceira(totalVendas)}</span></center></td>
                    </tr>`;
            $('#tableBodyDadosVenda').html(htmlVenda);

            console.log('totalPedidosNovos ', totalPedidosNovos);

            if(totalPedidosNovos > 0){
                $('#audioCampainha')[0].play();
            } else {
                $('#audioCampainha')[0].pause();
            }

            clearTimeout(timeoutAtualizador);

            timeoutAtualizador = setTimeout(() => {
                buscarVenda();
            }, 10000);
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
                                <button class="btn" onclick="removerItemVenda('${dadosItemPedido[i]['randomId']}')"><i class="fas fa-trash"></i></button>\
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
                        <td class="tdTexto"><center>${itens[i]['OBSERVACAO']}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(itens[i]['VALOR_UNITARIO'])}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(itens[i]['VALOR_CUSTO'])}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(itens[i]['VALOR_GASTO'])}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(itens[i]['VALOR_TOTAL'])}</center></td>                  
                    </tr>`;
            }
            $('#tableBodyItemVenda').html(htmlitensPedido)
        }

        function validaDocumento(){
            if ($("#inputArquivoDocumentacao")[0].files.length > 0) {
                $('#labelInputArquivoDocumentacao').html($("#inputArquivoDocumentacao")[0].files[0].name);
            } else {
                $('#labelInputArquivoDocumentacao').html('Selecionar Arquivos');
            }
        }

        function calcularValorTotal(){
            var valorTotal = 0;
            for(i=0; i< dadosItemPedido.length; i++){
                valorItemAutal = parseFloat(dadosItemPedido[i]['valorTotal']);
                valorTotal = (valorTotal + valorItemAutal);
            }

            valorTotalPedido = valorTotal;

            $('#spanValorTotalVenda').html(mascaraFinanceira(valorTotal));
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

        function opcoesVenda(idVenda, situacao, numero){
            var html = `
                <div class="col-12">
                    <button onclick="confirmarPedido(${idVenda}, '${situacao}', '${numero}')" class="col-12 btn btn-warning"><i class="fas fa-hamburger"></i> Confirmar Pedido</button>                    
                </div>
                <div class="col-12">
                    <button onclick="iniciarEntregaPedido(${idVenda}, '${situacao}', '${numero}')" class="col-12 btn btn-info"><i class="fas fa-motorcycle"></i> Inciar Entrega</button>                    
                </div>
                <div class="col-12">
                    <button onclick="finalizarPedido(${idVenda}, '${situacao}')" class="col-12 btn btn-success"><i class="fas fa-check"></i> Finalizar pedido</button>                    
                </div>
                <div class="col-12">
                    <button onclick="inserirPagamento(${idVenda}, '${situacao}')" class="col-12 btn btn-dark"><i class="fas fa-money-bill-alt"></i> Inserir Pagamento</button>                    
                </div>
                <div class="col-12">
                    <button onclick="cancelarPedido(${idVenda}, '${situacao}')" class="col-12 btn btn-danger"><i class="fas fa-times"></i> Cancelar Pedido</button>                    
                </div>
            `;

            Swal.fire({
                icon: 'warning',
                title: 'Selecione uma ação',
                html: html,
                showCloseButton: false,
                showConfirmButton: false,
                showCancelButton: false
            })
        }

        function confirmarPedido(idVenda, situacao, numero){
            if(situacao == 'AGUARDANDO CONFIRMACAO'){
                Swal.fire({
                    icon: 'warning',
                    title: 'Deseja confirmar o pedido?',
                    showCloseButton: false,
                    showConfirmButton: true,
                    confirmButtonText: 'Sim, confirmar',
                    showCancelButton: true,
                    cancelButtonText: 'Não, cancelar',
                }).then(function(r){
                    if(r.value){
                        $.ajax({
                            type:'post',
                            datatype:'json',
                            data:{
                               '_token':'{{csrf_token()}}',
                               'ID_VENDA': idVenda
                            },
                            url:"{{route('pdv.pedido.confirmar')}}",
                            success:function(r){
                                Swal.fire(
                                    'Sucesso!',
                                    'O pedido foi confirmado',
                                    'success'
                                );
                                
                                buscarVenda();

                                enviarMensagemWhatssApp(numero, 'CONFIRMACAO');

                                imprimirVenda(idVenda);
                            },
                            error:err=>{exibirErro(err)}
                        })
                    }
                })
            } else {
                Swal.fire(
                    'Atenção!',
                    'O pedido já foi confirmado',
                    'warning'
                );
            }
        }

        function finalizarPedido(idVenda, situacao){
            Swal.fire({
                icon: 'warning',
                title: 'Deseja finalizar o pedido?',
                showCloseButton: false,
                showConfirmButton: true,
                confirmButtonText: 'Sim, finalizar',
                showCancelButton: true,
                cancelButtonText: 'Não, cancelar',
            }).then(function(r){
                if(r.value){
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'ID_VENDA': idVenda
                        },
                        url:"{{route('pdv.pedido.finalizar')}}",
                        success:function(r){
                            Swal.fire(
                                'Sucesso!',
                                'O pedido foi finalizado',
                                'success'
                            );

                            buscarVenda();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            })
        }

        function cancelarPedido(idVenda, situacao){
            Swal.fire({
                icon: 'warning',
                title: 'Deseja cancelar o pedido?',
                showCloseButton: false,
                showConfirmButton: true,
                confirmButtonText: 'Sim, cancelar',
                showCancelButton: true,
                cancelButtonText: 'Não, voltar',
            }).then(function(r){
                if(r.value){
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'ID_VENDA': idVenda
                        },
                        url:"{{route('pdv.pedido.cancelar')}}",
                        success:function(r){
                            Swal.fire(
                                'Sucesso!',
                                'O pedido foi cancelado',
                                'success'
                            );

                            buscarVenda();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            })
        }

        function iniciarEntregaPedido(idVenda, situacao, numero){
            Swal.fire({
                icon: 'warning',
                title: 'Deseja iniciar entrega do pedido?',
                showCloseButton: false,
                showConfirmButton: true,
                confirmButtonText: 'Sim, iniciar',
                showCancelButton: true,
                cancelButtonText: 'Não, cancelar',
            }).then(function(r){
                if(r.value){
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'ID_VENDA': idVenda
                        },
                        url:"{{route('pdv.pedido.iniciar.entrega')}}",
                        success:function(r){
                            Swal.fire(
                                'Sucesso!',
                                'A entrega do pedido foi iniciada.',
                                'success'
                            );

                            buscarVenda();

                            enviarMensagemWhatssApp(numero, 'ENTREGA');
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            })
        }

        function inserirPagamento(idVenda){
            var html = `
                <div class="col-12">
                    <select class="form-control col-12" id="selectFormaPagamentoVenda">
                        ${htmlOptionsPagamento}
                    </select>
                </div>
            `;

            Swal.fire({
                icon: 'warning',
                title: 'Informe o método de pagamento:',
                html: html,
                showCloseButton: false,
                showConfirmButton: true,
                confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Confirmar!',
                showCancelButton: true,
                cancelButtonText:
                    '<i class="fa fa-thumbs-down"></i> Cancelar'
            }).then(function(result) {
                if (result.value) {
                    if($('#selectFormaPagamentoVenda').val() > 0){
                        $.ajax({
                            type:'post',
                            datatype:'json',
                            data:{
                               '_token':'{{csrf_token()}}',
                               'ID': idVenda,
                               'FORMA_PAGAMENTO': $('#selectFormaPagamentoVenda').val()
                            },
                            url:"{{route('pdv.venda.inserir.pagamento')}}",
                            success:function(r){
                                if(r.situacao == 'sucesso'){
                                    Swal.fire(
                                        'Sucesso!',
                                        'Forma de pagamento inserida.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Atenção!',
                                        r.mensagem,
                                        'error'
                                    ).then(() => {
                                        window.location.href  = "{{route('caixa')}}";
                                    });

                                }

                                buscarVenda();
                            },
                            error:err=>{exibirErro(err)}
                        })
                    } else {
                        Swal.fire(
                            'Atenção!',
                            'Selecione uma forma de pagamento válida.',
                            'warning'
                        );
                    }
                }
            })
        }

        function imprimirVenda($id){
            window.open('{{env('APP_URL')}}/pdv/venda/impresso/'+$id, '_blank')
        }

        function enviarMensagemWhatssApp(numero, tipoMensagem){
            var mensagem = 'Olá, Mady tem uma notícia para você: ';
            if(tipoMensagem == 'ENTREGA'){
                mensagem += 'O seu pedido saiu acaba de sair para entrega!'
            } else if(tipoMensagem == 'CONFIRMACAO'){                
                mensagem += 'O seu pedido acaba de ser confirmado, pode ficar tranquilo que iremos avisar quando sair para entrega!'
            }

            mensagem = mensagem.replace(' ', '%20');

            window.open(`https://wa.me/55${numero}?text=${mensagem}`);
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
                        'tipo': 1
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

                    if(selectedData.item.data.TIPO == 1){
                        qtdeProdutoSelecionado = selectedData.item.data.QTDE;
                    } else {
                        qtdeProdutoSelecionado = -1;
                    }
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

        $('#inputQuantidadeVenda').on('change', () => {
            calcularValorTotal();
        });

        $('#inputValorVenda').on('change', () => {
            calcularValorTotal();
        });

        $('#inputPagamentoVenda').on('change', () => {
            if($('#inputPagamentoVenda').val() == '4'){
                $('#divPessoaFiado').removeClass('d-none');
            } else {
                $('#divPessoaFiado').addClass('d-none');
            }
        });

        $('#inputValorGastoItem').on('change', () => {
            calcularValorItem();
        });

        $('#inputPorcentagemVenda').on('change', () => {
            calcularValorItem();
        });

        $('#btnLimparPlaca').click(() => {
            limparVenda();
        });

        $('#btnLimparPessoa').click(() => {
            limparFiado();
        });

        $('#btnNovaVenda').click(() => {
            cadastarVenda();
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
                inserirItemVenda();
            }
        });

        $('#btnConfirmar').click(() => {
            inserirVenda();
        });

        function mascaraFinanceira(valor){
            return (valor-0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }

        function mascaraDocumento(numero) {
            numero = numero.replace(/\D/g, '');

            if (numero.length === 11) {
                return numero.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            } else if (numero.length === 14) {
                return numero.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
            } else {
                return numero;
            }
        }

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
        
        $(document).ready(() => {
            $('#inputDataInicio').val(moment().format('YYYY-MM-DD')),
            buscarVenda();
            buscarFormaPagamento();
        })
    </script>
@stop