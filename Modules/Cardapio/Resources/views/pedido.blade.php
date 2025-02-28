@extends('adminlte::page')  

@section('layout_topnav', true)

@section('title', 'Pedido')

@section('content')
    <div class="content-header">
        <div class="row d-flex">
            <div class="col-12 d-flex justify-content-center ">
                <img src="{{env('APP_URL')}}/vendor/adminlte/dist/img/AdminLTELogo.jpeg" width="100">
            </div>
            <div class="col-12 d-flex justify-content-center">
                <i class="fas fa-utensils fa-lg mt-2"></i>
                <h1>MONTE SEU PEDIDO</h1>
            </div>
        </div>
    </div>

    <div class="content-body pb-2" id="divItensPedido">
        <div class="card w-100">
            <div class="card-header" >
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Ta com fome de que?" id="inputFiltro">
                </div>
            </div>

            <div class="card-body p-0">
                <div class="col-12 p-1 d-flex justify-content-center sticky" id="divBtnCarrinho">
                    <button class="btn btn-success col-12" id="btnCarrinho">
                        <i class="fas fa-cart-plus"></i> Carrinho
                    </button>
                </div>

                <div class="row d-flex justify-content-center" id="divListaItens">
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 d-none justify-content-center" id="divPedidoConfirmado">

        <span style="font-size: 20px; font-weight: bold">Seu pedido já foi encaminhado à cozinha, entraremos em contato em breve. </span>
        <br>
        <span style="font-size: 20px; font-weight: bold">Pode fechar essa janela e bon appetit.</span>
    </div>

    <div class="modal fade" id="modal-cadastro">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title">Carrinho</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="form-group col-12">
                            <label>Nome</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-12" id="inputCarrinhoNome">
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <label>Telefone</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-12" id="inputCarrinhoTelefone">
                            </div>
                        </div>
                        <div class="form-group col-6 col-md-3">
                            <label>CEP</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-12" id="inputCarrinhoCEP">
                            </div>
                        </div>
                        <div class="form-group col-6 col-md-3">
                            <label>Rua</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-12" id="inputCarrinhoRua">
                            </div>
                        </div>
                        <div class="form-group col-6 col-md-3">
                            <label>Número</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-12" id="inputCarrinhoNumero">
                            </div>
                        </div>
                        <div class="form-group col-6 col-md-3">
                            <label>Bairro</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-12" id="inputCarrinhoBairro">
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="checkbox" id="inputCarrinhoRetirada">
                            <label>Pedido para retirada?</label>
                        </div>

                        <div class="col-12" id="divTableItensCarrinho">
                            <table class="table table-responsive-sm" id="tableCarrinhoItens">
                                <thead>
                                    <th style="width:20vw">Item</th>
                                    <th style="width: 20vw"><center>Observacao</center></th>
                                    <th style="width: 10vw"><center>Valor</center></th>
                                    <th style="width: 10vw"><center>Quantidade</center></th>
                                    <th style="width: 10vw"><center>Total</center></th>
                                    <th style="width: 10vw"><center>Ações</center></th>
                                </thead>
                                <tbody id="tableBodyDados">
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

    <style>
        /* Esconder manualmente a navbar */
        .main-header {
            display: none !important;
        }

        /* Esconder manualmente a sidebar */
        .main-sidebar {
            display: none !important;
        }

        /* Ajustar o layout para ocupar toda a largura da tela */
        .content-wrapper, .main-footer {
            margin-left: 0 !important;
        }

        .layout-top-nav .wrapper .content-wrapper, .layout-top-nav .wrapper .main-footer, .layout-top-nav .wrapper .main-header {
            margin-left: 0!important;
        }

        .container, .container-md, .container-sm {
            max-width: 99999px!important; 
        }

        .sticky{
            position: sticky!important;
            top: 0;
            z-index: 1000;
        }

        #divTableItensCarrinho{
            max-height: 20vw;
            overflow: auto;
        }

    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="{{env('APP_URL')}}/main.js"></script>

    <script>
        // DADOS GLOBAIS
            var timeoutFiltro = 0;
            var dadosPedido = {
                NOME: '',
                TELEFONE: '',
                RUA: '',
                NUMERO: '',
                CEP: '',
                BAIRRO: '',
                ITENS: [],
                RETIRADA: 'N',
                VALOR_TOTAL: 0
            }

            $('#inputCarrinhoTelefone').mask("(00) 0000-00000");
            $('#inputCarrinhoCEP').mask("00000-000");
        // FIM

        function buscarDados(param = 1) {
            var validacao = true;
        
            if (validacao) {
                $.ajax({
                    type: 'post',
                    datatype: 'json',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'FILTRO_BUSCA': $('#inputFiltro').val()
                    },
                    url: "{{route('cardapio.buscar.pedido')}}",
                    success: function (r) {
                        resultadoDados = r.dados;
                        totaldeCadastros = r.contagem;
        
                        popularTabelaDados(resultadoDados);
                    },
                    error: function (e) {
                        console.log(e)
                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: 'Não foi possivel obter dados, entre em contato com a administração.',
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                })
            }
        }
        
        function popularTabelaDados(Dados) {
            var htmlDados = '';
        
            for (i = 0; i < Dados.length; i++) {
                var DadosKeys = Object.keys(Dados[i]);
                for (j = 0; j < DadosKeys.length; j++) {
                    if (Dados[i][DadosKeys[j]] == null) {
                        Dados[i][DadosKeys[j]] = '';
                    }
                }

                var classeItem = 'danger';
                var iconeItem = 'fa-hamburger';
                
                if(Dados[i]['ID_ITEM_PDV_CLASSE'] == 2){
                    classeItem = 'info';
                    var iconeItem = 'fa-beer';
                } else if(Dados[i]['ID_ITEM_PDV_CLASSE'] == 3){
                    classeItem = 'warning';
                    var iconeItem = 'fa-drumstick-bite';
                }
        
                htmlDados += `
                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                        <div class="card bg-light d-flex flex-fill">
                            <div class="card-header text-muted border-bottom-0">
                                <i class="fas ${iconeItem} fa-lg mt-2"></i><span class="badge bg-${classeItem}"> ${Dados[i]['CLASSE']}</span>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="lead"><b>${Dados[i]['DESCRICAO']}</b></h2>
                                        <p class="text-muted text-sm"><b>Detalhes: </b> ${Dados[i]['DETALHES']}</p>
                                        <ul class="ml-4 mb-0 fa-ul">
                                            <li class="large"><span class="fa-li"><i class="fas fa-lg fa-money-check-alt"></i></span> ${mascaraFinanceira(Dados[i]['VALOR'])}</li>
                                        </ul>
                                    </div>
                                    <div class="col-5 text-center">
                                        <img src="{{env('APP_URL')}}/vendor/adminlte/dist/img/AdminLTELogo.jpeg" alt="foto-item" class="img-circle img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    <button onclick="adicionarItemCarrinho(${Dados[i]['ID']}, '${Dados[i]['DESCRICAO']}', ${Dados[i]['VALOR']})" class="btn btn-sm btn-${classeItem}">
                                        <i class="fas fa-plus"></i> Adicionar ao pedido
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>`;
            }
        
            $('#divListaItens').html(htmlDados);
        }

        function adicionarItemCarrinho(ID, item, valor){
            Swal.fire({
                title: "Informe a quantidade e adicione observações se necessário:",
                icon: "warning",
                html: `
                    <div class="row d-flex p-0 m-0">
                        <div class="col-12">
                            <label>Quantidade:</label>
                            <input class="form-control" type="number" id="inputQtdeItem" value="1">
                        </div>
                            
                        <div class="col-12">
                            <label>Observações:</label>
                            <textarea id="textareaObservacaoItem" placeholder="Quero meu lanche sem ..." class="form-control col-12"></textarea>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonText: `
                    Adicionar item
                `,
                cancelButtonText: `
                    Cancelar
                `
            }).then(function(r){
                if(r.value){
                    var validacao = true;

                    if($('#inputQtdeItem').val() == '' || $('#inputQtdeItem').val() == null || $('#inputQtdeItem').val() < 1){
                        validacao = false;
                        Swal.fire({
                            title: "Atenção!",
                            text: "Informe uma quantidade válida para o item.",
                            icon: "error"
                        });
                    }

                    if(validacao){
                        var valorTotal = valor * $('#inputQtdeItem').val();

                        var dadosItem = {
                            'ID': Math.random().toString(36).substring(2, 7),
                            'ID_ITEM': ID,
                            'ITEM': item,
                            'VALOR': valor,
                            'QTDE': $('#inputQtdeItem').val(),
                            'OBS': $('#textareaObservacaoItem').val(),
                            'VALOR_TOTAL': valorTotal
                        };

                        dadosPedido.ITENS.push(dadosItem);
                        
                        Swal.fire({
                            title: "Sucesso!",
                            text: "Item adicionado ao carrinho.",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }

        function removerItemCarrinho(ID){
            Swal.fire({
                title: "Atenção!",
                icon: "warning",
                text: `Deseja realmente remover o item do carrinho?`,
                showCloseButton: false,
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: `Sim, remover`,
                cancelButtonText: `Não, cancelar`
            }).then(function(r) {
                if(r.value){
                    let index = dadosPedido.ITENS.findIndex(
                        (el)=>{                      
                            return el.ID == ID;
                        }
                    );

                    dadosPedido.ITENS.splice(index,1);

                    Swal.fire({
                        title: "Sucesso!",
                        text: "Item removido do carrinho.",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    })

                    popularTabelaCarrinhoItens();
                }
            });
        }

        function exibirCarrinho(){
            if(dadosPedido.ITENS.length > 0){
                popularTabelaCarrinhoItens();
    
                $('#modal-cadastro').modal('show');
            } else {
                Swal.fire(
                    'Atenção!',
                    'O seu carrinho esta vazio.',
                    'warning',
                )
            }
        }

        function popularTabelaCarrinhoItens() {
            var htmlDados = '';
            var Dados = dadosPedido.ITENS;
            var valorTotalPedido = 0;
        
            for (i = 0; i < Dados.length; i++) {
                var DadosKeys = Object.keys(Dados[i]);
                for (j = 0; j < DadosKeys.length; j++) {
                    if (Dados[i][DadosKeys[j]] == null) {
                        Dados[i][DadosKeys[j]] = '';
                    }
                }
        
                htmlDados += `
                    <tr id="tableRow${Dados[i]['ID']}">
                        <td>${Dados[i]['ITEM']}</td>
                        <td><center>${Dados[i]['OBS']}</center></td>
                        <td><center>${mascaraFinanceira(Dados[i]['VALOR'])}</center></td>
                        <td><center>${Dados[i]['QTDE']}</center></td>
                        <td><center>${mascaraFinanceira(Dados[i]['VALOR'] * Dados[i]['QTDE'])}</center></td>
                        <td>
                            <center>
                                <i class="fas fa-trash-alt btn-acao" onclick="removerItemCarrinho('${Dados[i]['ID']}')"></i>
                            </center>
                        </td>
                    </tr>`;
                
                valorTotalPedido = valorTotalPedido + Dados[i]['VALOR'] * Dados[i]['QTDE'];
            }

            dadosPedido.VALOR_TOTAL = valorTotalPedido;

            $('#spanValorTotalVenda').html(mascaraFinanceira(dadosPedido.VALOR_TOTAL));
        
            $('#tableBodyDados').html(htmlDados);
        }

        function inserirPedido(){
            var validacao = true;
            var mensagemErro = 'Preencha os campos obrigatórios.';

            $('inputCarrinhoCEP').removeClass('is-invalid');
            $('inputCarrinhoRua').removeClass('is-invalid');
            $('inputCarrinhoNumero').removeClass('is-invalid');
            $('inputCarrinhoBairro').removeClass('is-invalid');

            if(limparMascaraTelefonica($('#inputCarrinhoTelefone').val()).length < 10){
                validacao = false;
                mensagemErro = 'Informe um telefone válido.';
                $('#inputCarrinhoTelefone').addClass('is-invalid');
            } else {
                $('#inputCarrinhoTelefone').removeClass('is-invalid');
                dadosPedido.TELEFONE = $('#inputCarrinhoTelefone').val();
            }

            if($('#inputCarrinhoNome').val() == '' || $('#inputCarrinhoNome').val().length < 4){
                validacao = false;
                mensagemErro = 'Informe um nome válido.';
                $('#inputCarrinhoNome').addClass('is-invalid');
            } else {
                $('#inputCarrinhoNome').removeClass('is-invalid');
                dadosPedido.NOME = $('#inputCarrinhoNome').val();
            }

            if($('#inputCarrinhoRetirada').prop('checked') == false){
                var inputs = [
                    'inputCarrinhoCEP',
                    'inputCarrinhoRua',
                    'inputCarrinhoNumero',
                    'inputCarrinhoBairro'
                ]
                
                for(i = 0; i< inputs.length; i++){
                    if($('#'+inputs[i]).val() == ''){
                        $('#'+inputs[i]).addClass('is-invalid');
                        validacao = false;                    
                    }else{
                        $('#'+inputs[i]).removeClass('is-invalid');
                    };
                }
            }

            if(dadosPedido.ITENS.length < 1){
                validacao = false;
                mensagemErro = 'Adicione um item ao menos ao seu pedido.'
            }

            if(validacao){
                dadosPedido.CEP = $('#inputCarrinhoCEP').val();
                dadosPedido.RUA = $('#inputCarrinhoRua').val();
                dadosPedido.NUMERO = $('#inputCarrinhoNumero').val();
                dadosPedido.BAIRRO = $('#inputCarrinhoBairro').val();
                dadosPedido.RETIRADA = $('#inputCarrinhoRetirada').prop('checked') ? 'S' : 'N';

                $.ajax({
                    type: 'post',
                    datatype: 'json',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'DADOS_PEDIDO': dadosPedido,
                    },
                    url: "{{route('cardapio.pedido.inserir')}}",
                    success: function (r) {
                        $('#modal-cadastro').modal('hide');

                        Swal.fire({
                            title: "Sucesso!",
                            text: 'Pedido encaminhado para a cozinha com sucesso, entraremos em contato em breve através do WhatsApp.',
                            icon: "success"
                        }).then(() => {

                            $('#divItensPedido').addClass('d-none');
                            
                            $('#divPedidoConfirmado').removeClass('d-none');
                            $('#divPedidoConfirmado').addClass('d-flex');
                        });                        

                    },
                    error: function (e) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: 'Não foi possivel obter dados, entre em contato com a administração.',
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                })
            } else {
                Swal.fire({
                    title: "Atenção!",
                    text: mensagemErro,
                    icon: "warning"
                });
            }
        }

        $('#inputFiltro').on('keyup', () => {
            clearTimeout(timeoutFiltro);

            timeoutFiltro = setTimeout(() => {
                buscarDados();
            }, 900);
        })

        $('#btnCarrinho').on('click', () => {
            exibirCarrinho();
        });

        $('#btnConfirmar').on('click', () => {
            Swal.fire({
                title: "Atenção!",
                icon: "warning",
                text: `Deseja confirmar o pedido? Revise seus dados antes de continuar, entraremos em contato pelo WhatsApp através do numero informado.`,
                showCloseButton: false,
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: `Sim, fazer pedido`,
                cancelButtonText: `Não, cancelar`
            }).then(function(r) {
                if(r.value){
                    inserirPedido();
                }
            });
        });

        $('#inputCarrinhoRetirada').on('change', () => {
            $('#inputCarrinhoCEP').prop('disabled', $('#inputCarrinhoRetirada').prop('checked'));
            $('#inputCarrinhoRua').prop('disabled', $('#inputCarrinhoRetirada').prop('checked'));
            $('#inputCarrinhoNumero').prop('disabled', $('#inputCarrinhoRetirada').prop('checked'));
            $('#inputCarrinhoBairro').prop('disabled', $('#inputCarrinhoRetirada').prop('checked'));

            $('#inputCarrinhoCEP').removeClass('is-invalid');
            $('#inputCarrinhoRua').removeClass('is-invalid');
            $('#inputCarrinhoNumero').removeClass('is-invalid');
            $('#inputCarrinhoBairro').removeClass('is-invalid');
        })

        $(document).ready(function() {
            buscarDados();

            setTimeout(() => {
                $('#inputFiltro').focus();
            }, 500);
        })
    </script>
@stop