@extends('adminlte::page')

@section('title', 'Caixa')

@section('content')
    <div class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>Controle de Caixa</h1>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                <div>
                    <div class="form-group col-12">
                        <label>Caixa</label>
                        <select id="selectCaixa" class="form-control">
                            <option value="0">Selecionar Caixa</option>
                        </select>
                    </div>

                    <div class="col-12 d-none" id="divAbertura">
                        <div class="col-12">
                            <div class="col-12 row d-flex justify-content-center">
                                <span>Situacao:</span><span class="badge bg-danger">Fechado</span>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Valor Abertura</label>
                                    <input type="text" class="form-control" id="inputValorAbertura">
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-success col-12" id="btnAbrirCaixa"><i class="fas fa-wallet"></i> Abrir Caixa</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 d-none" id="divFechamento">
                        <div class="col-12">
                            <div class="col-12 row d-flex justify-content-center">
                                <span>Situacao:</span><span class="badge bg-success">Aberto</span>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Valor Fechamento</label>
                                    <input type="text" class="form-control" id="inputValorFechamento">
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-danger col-12" id="btnFecharCaixa"><i class="fas fa-wallet"></i> Fechar Caixa</button>
                            </div>
                        </div>
                    </div>
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
        $('#inputValorAbertura').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputValorFechamento').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});

        function buscarSelectCaixa() {
            var validacao = true;

            $('#divFechamento').addClass('d-none');
            $('#divAbertura').addClass('d-none');

            if (validacao) {
                $.ajax({
                    type: 'post',
                    datatype: 'json',
                    data: {
                        '_token': '{{csrf_token()}}',
                    },
                    url: "{{route('caixa.buscar')}}",
                    success: function (r) {
                        resultadoDados = r.dados;
                        totaldeCadastros = r.contagem;                        

                        popularSelectCaixa(resultadoDados);
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

        function popularSelectCaixa(Dados) {
            var htmlDados = '<option value="0">Selecionar Caixa</option>';

            for (i = 0; i < Dados.length; i++) {
                var DadosKeys = Object.keys(Dados[i]);
                for (j = 0; j < DadosKeys.length; j++) {
                    if (Dados[i][DadosKeys[j]] == null) {
                        Dados[i][DadosKeys[j]] = '';
                    }
                }

                htmlDados += `
                    <option value="${Dados[i]['ID']}">${Dados[i]['DESCRICAO']}</option>  
                `;
            }

            $('#selectCaixa').html(htmlDados);
        }

        function buscarSituacaoCaixa(){
            resetarValores();
            
            if($('#selectCaixa').val() > 0){
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                       '_token':'{{csrf_token()}}',
                    },
                    url:"{{route('caixa.buscar')}}",
                    success:function(r){
                        var dados = r.dados[0];

                        if(dados['CAIXA_ABERTO'] == 0){
                            $('#divAbertura').removeClass('d-none');
                            $('#divFechamento').addClass('d-none');
                        } else {
                            $('#divAbertura').addClass('d-none');
                            $('#divFechamento').removeClass('d-none');
                        }
                    },
                    error:err=>{exibirErroAJAX(err)}
                })
            } else {
                $('#divFechamento').addClass('d-none');
                $('#divAbertura').addClass('d-none');
            }
        }

        function abrirCaixa(){
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: 'Deseja realmente abrir o caixa?',
                showCloseButton: false,
                showConfirmButton: true,
                confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Sim, abrir',
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
                           'VALOR': limparMascaraFinanceira($('#inputValorAbertura').val())
                        },
                        url:"{{route('caixa.abrir')}}",
                        success:function(r){
                            Swal.fire(
                                'Sucesso!',
                                'Caixa aberto com sucesso',
                                'success'
                            );

                            buscarSelectCaixa();
                        },
                        error:err=>{exibirErroAJAX(err)}
                    })
                }
            })
        }

        function fecharCaixa(){
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: 'Deseja realmente fechar o caixa?',
                showCloseButton: false,
                showConfirmButton: true,
                confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Sim, fechar',
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
                           'VALOR': limparMascaraFinanceira($('#inputValorFechamento').val())
                        },
                        url:"{{route('caixa.fechar')}}",
                        success:function(r){
                            Swal.fire(
                                'Sucesso!',
                                'Caixa fechado com sucesso',
                                'success'
                            );

                            buscarSelectCaixa();

                            imprimirResumoCaixa();
                        },
                        error:err=>{exibirErroAJAX(err)}
                    })
                }
            });
        }

        function resetarValores(){
            $('#divFechamento').val('');
            $('#divAbertura').val('');
        }

        function imprimirResumoCaixa(){
            window.open('{{route('caixa.impresso')}}');
        }

        $('#selectCaixa').on('change', () => {
            buscarSituacaoCaixa();
        });

        $('#btnAbrirCaixa').on('click', () => {
            abrirCaixa();
        });

        $('#btnFecharCaixa').on('click', () => {
            fecharCaixa();
        });

        $(document).ready(function() {
            buscarSelectCaixa();
        })
    </script>
@stop