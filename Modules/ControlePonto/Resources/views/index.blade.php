@php
    date_default_timezone_set('America/Sao_Paulo');
@endphp

@extends('adminlte::page')

@section('title', 'GSSoftware')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h3>Controle de Ponto</h1>
            </div>
        </div>
    </div>

    <div class="content-body d-flex row">
        <div class="col-12 d-flex row">
            <div class="col-12 d-flex justify-content-center">
                <span id="spanContadorPonto" style="font-size: 80px">00:00:00</span>
            </div>
            <div class="col-12 d-flex justify-content-center m-2">
                <button class="btn btn-lg btn-info" id="btnRegistrarPonto"><i class="fas fa-clock"></i><span id="spanBtnPonto"> Registrar</span></button>
            </div>
        </div>

        <div class="card col-12">
            <div class="card-header">
                <div class="row d-flex m-0 p-0">
                    <div class="col-12 col-md-8">
                        <input type="text" class="form-control form-control-border" id="inputFiltro" placeholder="Filtro" maxlength="8" onkeyup="buscarDados()">
                    </div>
                    <div class="col-12 col-md-4 row d-flex">
                        <div class="form-group col-12 col-md-5">
                            <input id="inputFiltroDataInicio" type="date" class="form-control form-control-border" value="{{date('Y-m-d')}}">
                        </div>
                        <label class="col-2">Até</label>
                        <div class="form-group col-12 col-md-5">
                            <input id="inputFiltroDataFim" type="date" class="form-control form-control-border" value="{{date('Y-m-d')}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-xs">
                    <thead>
                        <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">Usuário</th>
                        <th class="d-none d-lg-table-cell"><center>Entrada</center></th>
                        <th class="d-none d-lg-table-cell"><center>Saída</center></th>
                        <th class="d-none d-lg-table-cell"><center>Decorrido</center></th>
                        <th class="d-none d-lg-table-cell"><center>Localização Entrada</center></th>
                        <th class="d-none d-lg-table-cell"><center>Localização Saída</center></th>
                        <th class="d-none d-lg-table-cell"><center>Ações</center></th>
                    </thead>
                    <tbody id="tableBodyDados">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog modal-lg">
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
        var timeoutContador = 0;
        var tempoAberto = '{{$tempoAberto}}';

        function buscarDados(){
            editar = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'FILTRO_BUSCA': $('#inputFiltro').val(),
                    'DATA_INICIO': $('#inputFiltroDataInicio').val(),
                    'DATA_TERMINO': $('#inputFiltroDataFim').val(),
                },
                url:"{{route('controle.ponto.buscar')}}",
                success:function(r){
                    popularListaDados(r.dados);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularListaDados(dados){
            var htmlTabela = "";
            var tempoApontamentoAberto = 0;

            for(i=0; i< dados.length; i++){
                var Keys = Object.keys(dados[i]);
                for(j=0;j<Keys.length;j++){
                    if(dados[i][Keys[j]] == null){
                        dados[i][Keys[j]] = "";
                    }
                }
                var spanLocalizacaoEntrada = ``;
                var spanLocalizacaoSaida = `-`;
                var tempoApontamento = '-';
                var dataSaida = '-';

                btnAcoes = ` <button class="btn" onclick="exibirModalEdicao(${dados[i]['ID']})"><i class="fas fa-pen"></i></button>
                             <button class="btn" onclick="inativar(${dados[i]['ID']})"><i class="fas fa-trash"></i></button>`;

                if(dados[i]['LATITUDE_ENTRADA'] != '' && dados[i]['LONGITUDE_ENTRADA'] != '' ){
                    spanLocalizacaoEntrada = `<span style="cursor: pointer;font-weight: bold" onclick="abrirLocalizacao('${dados[i]['LATITUDE_ENTRADA']}', '${dados[i]['LONGITUDE_ENTRADA']}')">${dados[i]['LATITUDE_ENTRADA']},${dados[i]['LONGITUDE_ENTRADA']}</span>`;
                }   

                if(dados[i]['LATITUDE_SAIDA'] != '' && dados[i]['LONGITUDE_SAIDA'] != '' ){
                    spanLocalizacaoSaida = `<span style="cursor: pointer;font-weight: bold" onclick="abrirLocalizacao('${dados[i]['LATITUDE_SAIDA']}', '${dados[i]['LONGITUDE_SAIDA']}')">${dados[i]['LATITUDE_SAIDA']},${dados[i]['LONGITUDE_SAIDA']}</span>`;
                }

                if(dados[i]['DATA_SAIDA'] != '') {
                    dataSaida = moment(dados[i]['DATA_SAIDA']).format('DD/MM/YYYY HH:mm:ss');

                    let diff = moment.duration(moment(dados[i]['DATA_SAIDA']).diff(moment(dados[i]['DATA_ENTRADA'])));
                    let horas = String(Math.floor(diff.asHours())).padStart(2, '0');
                    let minutos = String(diff.minutes()).padStart(2, '0');
                    let segundos = String(diff.seconds()).padStart(2, '0');
                    tempoApontamento =  `${horas}:${minutos}:${segundos}`;
                } else {
                    tempoApontamentoAberto = moment.duration(moment().diff(moment(dados[i]['DATA_ENTRADA']))).asSeconds();

                    let diff = moment.duration(moment().diff(moment(dados[i]['DATA_ENTRADA'])));
                    let horas = String(Math.floor(diff.asHours())).padStart(2, '0');
                    let minutos = String(diff.minutes()).padStart(2, '0');
                    let segundos = String(diff.seconds()).padStart(2, '0');
                    tempoApontamento =  `${horas}:${minutos}:${segundos}`;
                }

                htmlTabela += `
                    <tr id="tableRow${dados[i]['ID']}" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">${dados[i]['NOME_USUARIO']}</td>
                        <td class="tdTexto"><center>${moment(dados[i]['DATA_ENTRADA']).format('DD/MM/YYYY HH:mm:ss')}</center></td>
                        <td class="tdTexto"><center>${dataSaida}</center></td>
                        <td class="tdTexto"><center>${tempoApontamento}</center></td>
                        <td class="tdTexto"><center>${spanLocalizacaoEntrada}</center></td>
                        <td class="tdTexto"><center>${spanLocalizacaoSaida}</center></td>
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

        function abrirLocalizacao(latitude, longitude){
            var url = `https://www.google.com/maps?q=${latitude},${longitude}`;
            window.open(url, '_blank')
        }

        function registrarPonto(){
            Swal.fire({
                icon: 'question',
                title: 'Atenção!',
                text: 'Deseja INICIAR/FINALIZAR o registro de ponto ?',
                showCloseButton: false,
                showConfirmButton: true,
                confirmButtonText:
                    'Sim',
                showCancelButton: true,
                cancelButtonText:
                    'Não',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                           '_token':'{{csrf_token()}}',
                           'LATITUDE':latitudeAtualUsuario,
                           'LONGITUDE': longitudeAtualUsuario
                        },
                        url:"{{route('controle.ponto.registrar')}}",
                        success:function(r){
                            if(r.PONTO_ABERTO == 0){
                                dispararAlerta('success', 'Registro de ponto iniciado com sucesso!');
                                iniciarContagem(0);
                            } else {
                                dispararAlerta('success', 'Registro de ponto finalizado com sucesso!');
                                iniciarContagem(0, false);
                            }

                            buscarDados();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            })
        }

        function iniciarContagem(totalSegundos, iniciar = true) {
            if (iniciar) {
                function formatarTempo(segundos) {
                    let horas = Math.floor(segundos / 3600);
                    let minutos = Math.floor((segundos % 3600) / 60);
                    let segundosRestantes = segundos % 60;
                    return `${String(horas).padStart(2, '0')}:${String(minutos).padStart(2, '0')}:${String(segundosRestantes).padStart(2, '0')}`;
                }

                // Atualiza imediatamente o tempo inicial
                $('#spanContadorPonto').text(formatarTempo(totalSegundos));

                // Limpa qualquer contagem anterior antes de iniciar uma nova
                clearInterval(timeoutContador);

                // Inicia a contagem regressiva
                timeoutContador = setInterval(function () {
                    totalSegundos++;
                    $('#spanContadorPonto').text(formatarTempo(totalSegundos));
                }, 1000);
            } else {
                clearInterval(timeoutContador);
                $('#spanContadorPonto').text('00:00:00');
            }
        }

        /* Matheus 18/09/2024 09:52:40 - FUNÇÕES GEOLOCALIZAÇÃO */
            var latitudeAtualUsuario = '';
            var longitudeAtualUsuario = '';

            function buscarGeolocalizacaoUsuario(){
                if(navigator.geolocation){
                    navigator.geolocation.getCurrentPosition(retornarGeolocalizacaoUsuario, exibirErroGeolocalizacaoUsuario, { enableHighAccuracy: true });
                    return true;
                } else {
                    Swal.fire(
                        'Atenção!',
                        'Geolocalização não é suportada neste navegador.',
                        'error'
                    )

                    return false;
                }
            }

            function retornarGeolocalizacaoUsuario(position) {
                latitudeAtualUsuario = position.coords.latitude;
                longitudeAtualUsuario = position.coords.longitude;

                $('#inputLatitudeUsuario').val(latitudeAtualUsuario);
                $('#inputLongitudeUsuario').val(longitudeAtualUsuario);
            }

            function exibirErroGeolocalizacaoUsuario(error) {
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        Swal.fire(
                            'Atenção!',
                            'Erro ao tentar obter geolocalização: Usuário negou a solicitação de Geolocalização.<br>Caso esteja em um dispositivo móvel, é necessário ativar a localização nas configurações.',
                            'error'
                        )
                        break;
                    case error.POSITION_UNAVAILABLE:
                        Swal.fire(
                            'Atenção!',
                            'Erro ao tentar obter geolocalização: Informações de localização indisponíveis.',
                            'error'
                        )
                        break;
                    case error.TIMEOUT:
                        Swal.fire(
                            'Atenção!',
                            'Erro ao tentar obter geolocalização: O pedido para obter a localização do usuário expirou.',
                            'error'
                        )
                        break;
                    case error.UNKNOWN_ERROR:
                        Swal.fire(
                            'Atenção!',
                            'Erro ao tentar obter geolocalização: Ocorreu um erro desconhecido.',
                            'error'
                        )
                        break;
                }
            }

        /* Matheus 18/09/2024 09:52:12 - FIM */

        $('#btnRegistrarPonto').on('click', () => {
            registrarPonto();
        });

        $('#inputFiltro').on('keyup', () => {
            buscarDados();
        });

        $('#inputFiltroDataInicio').on('change', () => {
            buscarDados();
        });

        $('#inputFiltroDataFim').on('change', () => {
            buscarDados();
        });

        $(document).ready(function() {
            buscarGeolocalizacaoUsuario();
            buscarDados();

            if(tempoAberto > 0){
                iniciarContagem(tempoAberto)
            }
        })
    </script>
@stop