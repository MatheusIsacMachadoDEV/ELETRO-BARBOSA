@extends('adminlte::page')

@section('title', 'Cardapio')

@section('content')
    <div class="content-header">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Cardápio</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <button class="col-md-4 btn btn-sm m-1 btn-info" id="btnLinkCardapio">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Link Cardário</span>
                </button>

                <button class="col-md-4 btn btn-sm m-1 btn-primary" id="btnNovo">
                    <i class="fas fa-hamburger"></i>
                    <span class="ml-1">Novo</span>
                </button>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header" >
                <div class="input-group-prepend">
                    <input type="text" class="form-control" placeholder="Buscar" id="inputFiltro">
                    <span class="input-group-text">
                        <input type="checkbox" id="checkboxInativos">
                        <span> &nbsp Inativos</span>
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div>
                    <table class="table table-responsive-xs" id="tableDados">
                        <thead>
                            <th class="d-none d-lg-table-cell" style="width: 5vw">Numero</th>
                            <th class="d-none d-lg-table-cell" style="width: 15vw"><center>Item</center></th>
                            <th class="d-none d-lg-table-cell" style="width: 15vw"><center>Classe</center></th>
                            <th class="d-none d-lg-table-cell" style="width: 15vw"><center>Valor</center></th>
                            <th class="d-none d-lg-table-cell" style="width: 10vw"><center>Ações</center></th>
                        </thead>
                        <tbody id="tableBodyDados">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-6 col-md-2">
                        <span>Dados por Página:</span>
                    </div>
                    <div class="col-6 col-md-2">
                        <select class="form-control" id="selectDadosPorPagina" onchange="alterarDadosPorPagina(this.value)">
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="todos">Todos</option>
                        </select>
                    </div>
                    <div class="col-12 col-md">
                        <ul class="pagination justify-content-center" id="ulPagination">
                        </ul>
                    </div>
                    <div class="col-6 col-md-2">
                        <span>Ir para página:</span>
                    </div>
                    <div class="col-6 col-md-1">
                        <select class="form-control" id="irParaPagina" onchange="buscarDados(this.value)">
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-cadastro">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title">Item de Cardápio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="form-group col-12">
                            <label>Titulo</label>
                            <div class="col-12 d-flex p-0">
                                <input type="text" class="form-control col-12" id="inputItem">
                                <input type="hidden" class="form-control col-12" id="inputIDItem">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">                  
                            <label>Classe</label>
                            <select id="selectClasseProduto" class="form-control">
                                <option value="0">Selecionar Classe</option>
                            </select>
                        </div>
                        <div class="form-group col-xs-2 col-md-3">
                            <label>Valor</label>
                            <input type="text" inputmode="numeric" class="form-control" id="inputItemValor">
                        </div>
                        <div class="form-group col-xs-2 col-md-3">
                            <label>Numero do Item</label>
                            <input type="text" inputmode="numeric" class="form-control" id="inputItemNumero">
                        </div>
                        <div class="form-group col-12">
                            <label>Detalhes</label>
                            <textarea id="textareaItemDetalhes" class="col-12 form-control" rows="5"></textarea>
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
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="{{env('APP_URL')}}/main.js"></script>
    
    <script>
        // DADOS GLOBAIS
            var dadosPorPagina = 20;
            var paginaAtual = 1;
            var pesquisarInativos = false;
            var timeoutFiltro = 0;
        // FIM
        $('#inputItemValor').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});

        function alterarDadosPorPagina(param) {
            dadosPorPagina = param;
            buscarDados();
        }

        function buscarDados(param = 1) {
            var validacao = true;
            paginaAtual = parseInt(param);
            offset = dadosPorPagina * (param-1);

            if (validacao) {
                $.ajax({
                    type: 'post',
                    datatype: 'json',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'FILTRO_BUSCA': $('#inputFiltro').val(),
                        'BUSCAR_INATIVOS': $('#checkboxInativos').prop('checked')
                    },
                    url: "{{route('cardapio.buscar')}}",
                    success: function (r) {
                        resultadoDados = r.dados;
                        totaldeCadastros = r.contagem;
                        ultimaPaginaDados = totaldeCadastros % dadosPorPagina;
                        totaldePaginas = (totaldeCadastros - ultimaPaginaDados) / dadosPorPagina;
                        if (ultimaPaginaDados > 0) {
                            totaldePaginas ++;
                        }
                        var htmlSelect = '';

                        if(totaldePaginas <= 1 || isNaN(totaldePaginas)) {
                            htmlSelect = '<option value="1">1</option>'
                        } else {
                            for(i = 1; i <= totaldePaginas; i++) {
                                htmlSelect += '<option value="' + i + '">' + i + '</option>'
                            }
                        }

                        $('#irParaPagina').html(htmlSelect)
                        $('#irParaPagina').val(param)

                        if(totaldePaginas <= 1 || isNaN(totaldePaginas)) {
                            $('#ulPagination').html('                                <li class="page-item active"><button class="page-link">1</button></li>                            ')
                        } else if(param == 1) {
                            $('#ulPagination').html('                                <li class="page-item active"><button class="page-link">1</button></li>                                <li class="page-item"><button class="page-link" onclick="buscarClasses(2)">2</button></li>                                <li class="page-item"><button class="page-link" onclick="buscarClasses(' + totaldePaginas + ')">Último</button></li>                            ')
                        } else if(param == totaldePaginas) {
                            $('#ulPagination').html('                                <li class="page-item"><button class="page-link" onclick="buscarClasses(1)">Primeiro</button></li>                                <li class="page-item"><button class="page-link" onclick="buscarClasses(' + (parseInt(param) - 1) + ')">' + (parseInt(param) - 1) + '</button></li>                                <li class="page-item active"><button class="page-link">' + parseInt(param) + '</button></li>                            ')
                        } else {
                            $('#ulPagination').html('                                <li class="page-item"><button class="page-link" onclick="buscarClasses(1)">Primeiro</button></li>                                <li class="page-item"><button class="page-link" onclick="buscarClasses(' + (parseInt(param) - 1) + ')">' + (parseInt(param) - 1) + '</button></li>                                <li class="page-item active"><button class="page-link">' + (parseInt(param)) + '</button></li>                                <li class="page-item"><button class="page-link" onclick="buscarClasses(' + (parseInt(param) + 1) + ')">' + (parseInt(param) + 1) + '</button></li>                                <li class="page-item"><button class="page-link" onclick="buscarClasses(' + totaldePaginas + ')">Último</button></li>                            ')
                        }

                        popularTabelaDados(resultadoDados);
                    },
                    error: function (e) {
                        console.log(e)
                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: 'Não foi possivel obter dados, favor entrar em contato com o suporte.',
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                })
            }
        }

        function popularTabelaDados(Dados) {
            var htmlDados = '';
            var contagemDados = 0;

            for (i = 0; i < Dados.length; i++) {
                var DadosKeys = Object.keys(Dados[i]);
                for (j = 0; j < DadosKeys.length; j++) {
                    if (Dados[i][DadosKeys[j]] == null) {
                        Dados[i][DadosKeys[j]] = '';
                    }
                }

                if (contagemDados%2 == 0){
                    classelinhaZebrada = ''
                } else {
                    classelinhaZebrada = 'linhaZebrada'
                }

                if(Dados[i]['STATUS'] == 'A'){
                    btnAcoes = `
                                <i class="btn-acao far fa-eye" onclick="exibirModalVisualizacao(${Dados[i]['ID']})"></i>
                                <i class="btn-acao fas fa-pencil-alt" onclick="exibirModalEdicao(${Dados[i]['ID']})"></i>
                                <i class="btn-acao fas fa-trash-alt" onclick="inativarCardapio(${Dados[i]['ID']})"></i>`; 
                } else {
                    btnAcoes = `
                                <i class="btn-acao fas fa-check" onclick="ativarItem(${Dados[i]['ID']})"></i>`; 
                }
                            
                htmlDados += `
                    <tr id="tableRow${Dados[i]['ID']}" class="${classelinhaZebrada} d-none d-lg-table-row">
                        <td>${Dados[i]['NUMERO']}</td>
                        <td><center>${Dados[i]['DESCRICAO']}</center></td>
                        <td><center>${Dados[i]['CLASSE']}</center></td>
                        <td><center>${mascaraFinanceira(Dados[i]['VALOR'])}</center></td>
                        <td>
                            <center>
                            ${btnAcoes}
                            </center>
                        </td>
                    </tr>

                    <tr></tr>

                    <tr id="tableRow${Dados[i]['ID']}" class="${classelinhaZebrada} d-table-row d-lg-none">
                        <td>
                            <div class="col-12">
                                <center>
                                    ${Dados[i]['NUMERO']}-${Dados[i]['DESCRICAO']}
                                    ${mascaraFinanceira(Dados[i]['VALOR'])}
                                </center>
                            </div>
                            <div class="col-12">
                                <center>
                                    ${btnAcoes}
                                </center>
                            </div>
                        </td>
                    </tr>`;
               contagemDados++
            }

            $('#tableBodyDados').html(htmlDados);
        }

        function salvarCardapio(){
            var validacao = true;

            inputs = [
                'inputItem',
                'inputItemValor',
                'inputItemNumero',
                'textareaItemDetalhes'
            ]
            
            for(i = 0; i< inputs.length; i++){
                if($('#'+inputs[i]).val() == ''){
                    $('#'+inputs[i]).addClass('is-invalid');
                    validacao = false;
                    
                }else{
                    $('#'+inputs[i]).removeClass('is-invalid');
                }
            }

            if($('#selectClasseProduto').val() == '0'){
                $('#selectClasseProduto').addClass('is-invalid');
                validacao = false;                
            }else{
                $('#selectClasseProduto').removeClass('is-invalid');
            }

            if(validacao){
                if($('#inputIDItem').val() == 0){
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'ITEM': $('#inputItem').val(),
                            'DESCRICAO': $('#textareaItemDetalhes').val(),
                            'VALOR': limparMascaraFinanceira($('#inputItemValor').val()),
                            'NUMERO': $('#inputItemNumero').val(),
                            'CLASSE': $('#selectClasseProduto').val(),
                        },
                        url:"{{route('cardapio.inserir')}}",
                        success:function(r){
                            $('#modal-cadastro').modal('hide');
                            buscarDados();
    
                            Swal.fire(
                                'Sucesso!',
                                'Item de cardápio inserido com sucesso.',
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
                            'ID': $('#inputIDItem').val(),
                            'ITEM': $('#inputItem').val(),
                            'DESCRICAO': $('#textareaItemDetalhes').val(),
                            'VALOR': limparMascaraFinanceira($('#inputItemValor').val()),
                            'NUMERO': $('#inputItemNumero').val(),
                            'CLASSE': $('#selectClasseProduto').val(),
                        },
                        url:"{{route('cardapio.alterar')}}",
                        success:function(r){
                            $('#modal-cadastro').modal('hide');
                            buscarDados();
    
                            Swal.fire(
                                'Sucesso!',
                                'Item de cardápio alterado com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })   
                }
            } 
        }

        function inativarCardapio(ID){
            Swal.fire({
                title: "Atenção!",
                icon: "warning",
                text: `Deseja realmente inativar o item de cardápio?`,
                showCloseButton: false,
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: `Sim, inativar`,
                cancelButtonText: `Não, cancelar`
            }).then(function(r) {
                if(r.value){
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'ID': ID
                        },
                        url:"{{route('cardapio.inativar')}}",
                        success:function(r){
                            buscarDados();
    
                            Swal.fire(
                                'Sucesso!',
                                'Item de cardápio inativado com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })   
                }
            });
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
        
        function exibirModalCadastro(){
            resetarModalCadastro();

            $('#modal-cadastro').modal('show');
        }

        function exibirModalEdicao(ID){
            resetarModalCadastro();

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'ID_CARDAPIO': ID
                },
                url:"{{route('cardapio.buscar')}}",
                success:function(r){
                    var dados = r.dados[0];

                    $('#inputIDItem').val(dados['ID']);
                    $('#inputItem').val(dados['DESCRICAO']);
                    $('#textareaItemDetalhes').val(dados['DETALHES']);
                    $('#inputItemValor').val(mascaraFinanceira(dados['VALOR']));
                    $('#inputItemNumero').val(dados['NUMERO']);
                    $('#selectClasseProduto').val(dados['ID_ITEM_PDV_CLASSE']);

                    $('#modal-cadastro').modal('show');
                },
                error:err=>{exibirErro(err)}
            })
        }

        function exibirModalVisualizacao(ID){
            resetarModalCadastro();

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'ID_CARDAPIO': ID
                },
                url:"{{route('cardapio.buscar')}}",
                success:function(r){
                    var dados = r.dados[0];

                    $('#inputIDItem').val(dados['ID']);
                    $('#inputItem').val(dados['DESCRICAO']);
                    $('#textareaItemDetalhes').val(dados['DETALHES']);
                    $('#inputItemValor').val(mascaraFinanceira(dados['VALOR']));
                    $('#inputItemNumero').val(dados['NUMERO']);
                    $('#selectClasseProduto').val(dados['ID_ITEM_PDV_CLASSE']);

                    $('#inputIDItem').prop('disabled', true);
                    $('#inputItem').prop('disabled', true);
                    $('#textareaItemDetalhes').prop('disabled', true);
                    $('#inputItemValor').prop('disabled', true);
                    $('#inputItemNumero').prop('disabled', true);
                    $('#selectClasseProduto').prop('disabled', true);

                    $('#btnConfirmar').addClass('d-none');

                    $('#modal-cadastro').modal('show');
                },
                error:err=>{exibirErro(err)}
            })
        }

        function resetarModalCadastro(){
            $('#inputIDItem').val('0');
            $('#inputItem').val('');
            $('#textareaItemDetalhes').val('');
            $('#inputItemValor').val('');
            $('#inputItemNumero').val('');
            $('#selectClasseProduto').val('0');

            $('#inputIDItem').prop('disabled', false);
            $('#inputItem').prop('disabled', false);
            $('#textareaItemDetalhes').prop('disabled', false);
            $('#inputItemValor').prop('disabled', false);
            $('#inputItemNumero').prop('disabled', false);
            $('#selectClasseProduto').prop('disabled', false);

            $('#btnConfirmar').removeClass('d-none');
        }

        function ativarItem(ID){
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: 'Deseja realmente reativar o item?',
                showCloseButton: false,
                showConfirmButton: true,
                confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Sim, reativar',
                showCancelButton: true,
                cancelButtonText:
                    '<i class="fa fa-thumbs-down"></i> Não, cancelar'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                           '_token':'{{csrf_token()}}',
                           'ID': ID
                        },
                        url:"{{route('cardapio.ativar.item')}}",
                        success:function(r){
                            Swal.fire(
                                'Sucesso!',
                                'Item ativado com sucesso',
                                'success'
                            );

                            buscarDados();
                        },
                        error:err=>{exibirErroAJAX(err)}
                    })
                }
            });
        }

        function copiarLinkCardapio() {
        
            const texto = "https://gssoftware.app.br";            
        
            const elementoTemporario = document.createElement("textarea");

            elementoTemporario.value = texto;
        
            document.body.appendChild(elementoTemporario);
        
            elementoTemporario.select();

            elementoTemporario.setSelectionRange(0, 99999);
        
            document.execCommand("copy");
        
            document.body.removeChild(elementoTemporario);

            Swal.fire(
                'Sucesso!',
                'Link copiado para àrea de transferência.',
                'success'
            )
        }


        $('#inputFiltro').on('keyup', () => {
            clearTimeout(timeoutFiltro);

            timeoutFiltro = setTimeout(() => {
                buscarDados();
            }, 900);
        })

        $('#checkboxInativos').on('change', () => {
            buscarDados();
        })

        $('#btnNovo').on('click', () => {
            exibirModalCadastro();
        })

        $('#btnConfirmar').on('click', () => {
            salvarCardapio();
        })

        $('#btnLinkCardapio').on('click', () => {
            copiarLinkCardapio();
        })

        $(document).ready(function() {
            buscarDados();
            buscarClasseProduto();
        })
    </script>
@stop