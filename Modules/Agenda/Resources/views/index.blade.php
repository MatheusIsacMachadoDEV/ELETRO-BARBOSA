@extends('adminlte::page')

@section('title', 'Agenda')

@section('content')
     
    <div class="container-fluid">
        <div class="row d-flex display-content-between">
            <div class="col">
                <button type="button"  class="btn btn-block btn-warning m-1" onclick="fecharAgenda()"><i class="fas fa-angle-double-left"></i> Voltar</button>
            </div>
            <div class="col d-none">
                <button id="btnEditar" class="btn btn-block btn-primary m-1 " onclick="adicionarLocal()"><i class="fas fa-map-marker-alt"></i> Locais</button>
            </div>
            <div class="col">
                <button type="button" class="btn btn-block btn-primary m-1" onclick="novoEvento()"><i class="fas fa-plus"></i> Novo</button>
            </div>

            @can('ADMINISTRADOR')
            <div class="form-group col m-1">
                <select id="selectUsuarioFiltro" class="form-control">
                    <option value="0">Selecionar Usuário</option>
                </select>
            </div>
            @endcan
        </div>
        <div id="calendar"style="user-select: none" class="p-1 fc fc-media-screen fc-direction-ltr fc-theme-bootstrap"><!-- Matheus 12/07/2023 16:22:26 - CALENDARIO GERADO AUTOMATICAMENTE PELO JS -->                
        </div>
    </div>

    <div class="modal fade" id="modal-local" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog modal-lg"> 
            <div class="modal-content"> 
                <div class="content-header" style="padding:0; border-bottom: 1px solid #e9ecef;">
                    <div class="container-fluid" >
                        <div class="row mb-2 justify-content-between d-flex">
                            <div class="col-6 mt-2">
                                <h1>Locais de Evento</h1>
                            </div>
                            <div class="col-6 mt-2">
                                <button type="button" class="btn btn-primary  float-right" onclick="modalAdicionarLocal()" ><i class="fas fa-plus"></i>&nbsp NOVO</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body "> 
                    <table class="table table-responsive-lg w-100" id="tableDadosLocal">
                        <thead>
                            <th>ID</th>
                            <th style="width: 15vw;padding-left:15px" >DESCRIÇÃO</th>
                            <th>TAMANHO</th>
                            <th><center>QTDE</center></th>
                            <th><center>PREÇO</center></th>
                            <th><center>OBSERVAÇÃO</center></th>
                            <th><center>INATIVAR</center></th>
                        </thead>
                        <tbody id="tableBodyDadosLocal">
                        </tbody>
                    </table>
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" onclick="fecharModalListaLocais()">Fechar</button> 
                </div> 
            </div> 
        </div> 
    </div>

    <div class="modal fade" id="modal-local-adicionar" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Cadastro de Local</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <label class="ml-2">Descrição</label>
                    <div class="d-flex col-12 mb-2">
                        <input type="text" class="form-control"  id="input-local-descricao" name="input-local-descricao" placeholder="Descrição" >
                    </div>
                    <label class="ml-2">Tamanho</label>
                    <div class="d-flex col-12 mb-2">
                        <input type="text" class="form-control"  id="input-local-tamanho" name="input-local-tamanho" placeholder="Tamanho (m²)" >
                    </div>
                    <label class="ml-2">QTDE pessoas</label>
                    <div class="d-flex col-12 mb-2">
                        <input type="text" class="form-control"  id="input-local-qtde" name="input-local-qtde" placeholder="QTDE (máx)" >
                    </div>
                    <label class="ml-2">Preço</label>
                    <div class="d-flex col-12 mb-2">
                        <input type="text" class="form-control"  id="input-local-preco" name="input-local-preco" placeholder="Preço" >
                    </div>
                    <label class="ml-2">Observação</label>
                    <div class="d-flex col-12 mb-2">
                        <textarea type="text" class="form-control"  id="input-local-obs" name="input-local-obs" placeholder="Observação" ></textarea>
                    </div>
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" onclick="fecharModalAdicionarLocal()">Fechar</button> 
                    <button type="button" class="btn btn-primary" onclick="salvarLocal()">Salvar</button> 
                </div> 
            </div> 
        </div> 
    </div> 

    <div class="modal fade" id="modal-evento-adicionar" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog modal-lg"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title"></h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <div class="col-md-12">
                        <div class="sticky-top mb-3">        
                            <div class="card d-flex" id="cardEvento">
                                <div class="card-header d-flex justify-content-between mr-0 ">
                                    <div class="row align-content-center">
                                        <h3 class="card-title">Criar Evento</h3>
                                    </div>
                                </div>
        
                                <div class="card-body">
                                    <div class="col-12 d-flex">
                                        <div class="col-8">
                                            <ul class="fc-color-picker" id="color-chooser">
                                                <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
                                                <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
                                                <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
                                                <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
                                            </ul>
                                        </div>
                                        <div class="col-4 d-flex justify-content-end">
                                            <div class="">
                                                <input class="form-check-input" type="checkbox" id="eventoDiaTodo">
                                                <label class="form-check-label" for="eventoDiaTodo">
                                                    Dia todo
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="dadosEvento">
                                        <div class="input-group col-12 p-0 mb-1">
                                            <input id="eventoTitulo" type="text" class="form-control" placeholder="Título do Evento">                     
                                        </div>
                                        <div class="input-group col-12 p-0 mb-1 d-flex">
                                            <input id="eventoDataInicio" type="datetime-local" class="form-control" placeholder="Data do Evento" onchange="validaDataInicioEvento()"> 
                                            <label class="p-1">ATÉ</label>
                                            <input id="eventoDataFinal" type="datetime-local" class="form-control" placeholder="Data do Evento" onchange="validaDataFimEvento()">                                         
                                        </div>
                                        <div class="input-group col-12 p-0 mb-1">
                                            <input id="eventoResponsavel" type="text" class="form-control" placeholder="Responsável">                     
                                        </div>
                                        <div class="form-group col-12 p-0 mb-1">
                                            <select class="form-control" id="selectClienteAdicionar">
                                                <option value="0">Selecionar cliente...</option>
                                            </select>
                                        </div>
                                        <div class="input-group col-12 p-0 mb-1 d-none">
                                            <input id="idEventoLocal" type="hidden" class="form-control" placeholder="ID do Local" value="0">                     
                                            <input id="eventoLocal" type="text" class="form-control" placeholder="Local do Evento" onchange="validaCampoLocal()">
                                            <button id="btnLimparLocal" class="btn btn-default d-none">LIMPAR</button>
                                        </div>
                                        <div class="input-group col-12 p-0 mb-1 d-none">
                                            <input id="eventoValor" type="text" class="form-control" placeholder="Valor">                     
                                        </div>
                                        <div class="input-group col-12 p-0 mb-1">
                                            <textarea id="eventoObservacao" class="form-control" placeholder="Observação"></textarea>                    
                                        </div>
                                        <div class="input-group-append mt-1 p-0">
                                            <button id="btnAddEvento" type="button" class="btn btn-default col-12">Adicionar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
                </div> 
            </div> 
        </div> 
    </div> 

    <div class="modal fade" id="modal-evento-editar" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title"></h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <div class="d-flex justify-content-center" id="divLoadingEdicaoEvento">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <div class="card d-flex d-none" id="cardEventoDados">
                        <div class="card-header d-flex justify-content-between mr-0 " id="divBotoesEditar">
                            <div class="row align-content-center">
                                <h3 class="card-title" >Editar Evento <span id="spanSituacaoEvento" class="badge bg-secondary"></span></h3>
                            </div>
                            <div class="ml-auto">
                                <button class="btn btn-primary" id="btnEditarEvento">Editar</button>
                                <button class="btn btn-info" id="btnFinalizarEvento">Finalizar</button>
                                <button class="btn btn-danger" id="btnExcluirEvento">Excluir</button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div id="dadosEvento">
                                <input id="idEvento" type="hidden" class="form-control" placeholder="ID" >
                                <input id="codEvento" type="hidden" class="form-control" placeholder="ID" >
                                <div class="input-group col-12 p-0 mb-1">
                                    <input id="eventoTituloEditar" type="text" class="form-control" placeholder="Título do Evento">                     
                                </div>
                                <div class="input-group col-12 p-0 mb-1 d-flex">
                                    <input id="eventoDataInicioEditar" type="DateTime-Local" class="form-control" placeholder="Data do Evento"> 
                                    <label class="p-1"> ATÉ </label>
                                    <input id="eventoDataFinalEditar" type="DateTime-Local" class="form-control" placeholder="Data do Evento">                                         
                                </div>
                                <div class="input-group col-12 p-0 mb-1">
                                    <input id="eventoResponsavelEditar" type="text" class="form-control" placeholder="Responsável">                     
                                </div>
                                <div class="form-group col-12 p-0 mb-1">
                                    <select class="form-control" id="selectClienteEditar">
                                        <option value="0">Selecionar cliente...</option>
                                    </select>
                                </div>
                                <div class="input-group col-12 p-0 mb-1 d-none">
                                    <input id="idEventoLocalEditar" type="hidden" class="form-control" placeholder="ID do Local" >                     
                                    <input id="eventoLocalEditar" type="text" class="form-control" placeholder="Local do Evento">
                                    <button id="btnLimparLocalEditar" class="btn btn-default d-none">LIMPAR</button>
                                </div>
                                <div class="input-group col-12 p-0 mb-1 d-none">
                                    <input id="eventoValorEditar" type="text" class="form-control" placeholder="Valor">                     
                                </div>
                                <div class="input-group col-12 p-0 mb-1">
                                    <textarea id="eventoObservacaoEditar" class="form-control" placeholder="Observação"></textarea>                    
                                </div>
                            </div>
                        </div>
                    </div> 
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
                    <button id="btnSalvarEdicao" type="button" class="d-none btn btn-primary">Salvar</button> 
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
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>   

    <script>
        /* Matheus 13/07/2023 13:56:37 - VARIAVEIS GLOBAIS */
            var date = new Date()
            var d    = date.getDate(),
                m    = date.getMonth(),
                y    = date.getFullYear();

            var containerEl = document.getElementById('external-events');
            var checkbox = document.getElementById('drop-remove');
            var calendarEl = document.getElementById('calendar');
            var calendar;
            var corSelecionada = '#3c8dbc';
            var btnAddLocal = false;
            var eventoDiaTodo = false;
        /* FIM */
        
        $('#input-local-tamanho').maskMoney({thousands: "",decimal:"."});
        $('#input-local-qtde').mask('0000000');
        $('#input-local-preco').maskMoney({thousands: "",decimal:"."});
        $('#eventoValor').maskMoney({thousands: "",decimal:"."});
        $('#eventoValorEditar').maskMoney({thousands: "",decimal:"."});
        
        /* Matheus 13/07/2023 14:00:50 - DESENHA O CALENDARIO E INSERE NA DIV CALENDAR */
        function criarCalendario(){
            calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left  : 'prev,next today',
                    center: 'title',
                    right : 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hoje',
                    month: 'Mês',
                    week: 'Semana',
                    day: 'Dia'
                },
                customButtons: {
                    myCustomButton: {
                        text: 'Novo Evento',
                        click: function() {
                            alert('clicked the custom button!');
                        }
                    }
                },
                themeSystem: 'bootstrap',
                dateClick: function(info){
                    var clickedDate = info.dateStr; // Data do dia clicado no formato 'YYYY-MM-DD'
                    var dataFormatada = clickedDate.slice(0, 16);
                    exibirCadastroEvento(dataFormatada);
                },
                eventDrop: function(info) {
                    var evento = info.event;
                    var dataNovaInicio = evento.start; 
                    var dataNovaFim= evento.end; 
                    var idEvento = evento.id;
                    dataNovaInicio = moment(dataNovaInicio).format('YYYY-MM-DDTHH:mm');
                    dataNovaFim = moment(dataNovaFim).format('YYYY-MM-DDTHH:mm');
        
                    editarEventoDragNDrop(dataNovaInicio, dataNovaFim, idEvento);
                },
                eventResize: function(info){
                    var evento = info.event;
                    var dataNovaInicio = evento.start; 
                    var dataNovaFim= evento.end; 
                    var idEvento = evento.id;
                    dataNovaInicio = moment(dataNovaInicio).format('YYYY-MM-DDTHH:mm');
                    dataNovaFim = moment(dataNovaFim).format('YYYY-MM-DDTHH:mm');
        
                    editarEventoDragNDrop(dataNovaInicio, dataNovaFim, idEvento);
                },
                editable: true,           
                locale: 'pt-br',
            });
            
            calendar.render();

            reajustarCalendario();
        
            calendar.on("eventClick", function (info) {
                var eventId = info.event.id;
                exibirEdicaoEvento(eventId);
            });
            
            calendar.viewDidMount = function() {
                reajustarCalendario();
            };
            buscarEventos();
        }

        /* Matheus 18/07/2023 08:44:30 - ADICIONA O EVENTO AO FULL CALLENDAR */
        function geraEvento(idEvento, eventoTitulo, eventoDataIni, eventoDataFim, cor, diaTodo){
            calendar.addEvent({
                id:idEvento,
                title: eventoTitulo,
                start: eventoDataIni,
                end: eventoDataFim,
                borderColor: cor,
                backgroundColor: cor,
                editable: true,
                startEditable: true,
                durationEditable: true,
                allDay: diaTodo
            });
        }

        function novoEvento(){            
            var data = moment(new Date()).format('YYYY-MM-DDTHH:mm');
            exibirCadastroEvento(data);
        }

        /* Matheus 14/07/2023 13:22:11 - EXIBE O MODAL PARA CADASTRO DE LOCAIS */
        function modalAdicionarLocal(){      
            $('#input-local-descricao').val('');
            $('#input-local-tamanho').val('');
            $('#input-local-qtde').val('');
            $('#input-local-preco').val('');
            $('#input-local-obs').val('');
            
            btnAddLocal = true;
            $('#modal-local').modal('hide') ;
            $('#modal-local').on('hidden.bs.modal', function () {
                if(btnAddLocal){
                    $('#modal-local-adicionar').modal('show');
                    btnAddLocal = false;
                }
            }); 
        }

        /* Matheus 14/07/2023 13:22:33 - FECHA O MODAL DE CADASTRO DE LOCAL */
        function fecharModalAdicionarLocal(){
            $('#modal-local').modal('show') ;
            $('#modal-local-adicionar').modal('hide');
            buscarDadosLocal();
        }

        /* Matheus 19/07/2023 10:14:03 - FECHA O MODAL DA LISTA DE LOCAIS */
        function fecharModalListaLocais(){
            $('#modal-local').modal('hide');
        }

        /* Matheus 14/07/2023 13:22:58 - SALVA O CADASTRO DO LOCAL */
        function salvarLocal(){
            validacao = true;
            dadosLocalInserir = {
                'descricao': $('#input-local-descricao').val(),
                'tamanho':$('#input-local-tamanho').val(),
                'qtde':$('#input-local-qtde').val(),
                'preco':$('#input-local-preco').val()
            };

            campos = [
                'descricao',
                'tamanho',
                'qtde',
                'preco'
            ];

            inputs = [
                'input-local-descricao',
                'input-local-tamanho',
                'input-local-qtde',
                'input-local-preco'
            ]

            for(i = 0; i< campos.length; i++){
                if(dadosLocalInserir[campos[i]] == ""){
                    $('#'+inputs[i]).addClass('is-invalid');
                    validacao = false;

                }else{
                    $('#'+inputs[i]).removeClass('is-invalid');
                };
            }

            if(validacao){
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                    '_token':'{{csrf_token()}}',
                    'codCli': {{auth()->user()->id }},
                    'nomeCli': '{{auth()->user()->NOME_PROFIS}}',
                    'cliente': '{{Config::get('database.connections.mysql.database')}}',
                    'descricao': $('#input-local-descricao').val(),
                    'tamanho': $('#input-local-tamanho').val(),
                    'qtde': $('#input-local-qtde').val(),
                    'preco': $('#input-local-preco').val(),
                    'observacao': $('#input-local-obs').val()
                    },
                    url:"{{route('agenda.inserir.local')}}",
                    success:function(r){
                    Swal.fire(
                        'Sucesso!',
                        'Local inserido com sucesso.',
                        'success'
                    )
                    fecharModalAdicionarLocal();
                    },
                    error:err=>{exibirErro(err)}
                })
            }
        }

        /* Matheus 19/07/2023 10:46:40 - INATIVA O LOCAL ALTERANDO A ATIVO = 0 */
        function inativarLocal(idLocal){
            Swal.fire({
                icon: 'warning',
                title: 'Deseja confirmar a inativação do local?',
                showCloseButton: false,
                showConfirmButton: true,
                confirmButtonText: 'Sim',
                showCancelButton: true,
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value == true) {
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'id_codigo': idLocal,
                        },
                        url:"{{route('agenda.inativar.local')}}",
                        success:function(r){
                            buscarDadosLocal();
                            Swal.fire(
                                'Sucesso!',
                                'Local inativado com sucesso.',
                                'success'
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                } 
            })            
        }

        /* Matheus 14/07/2023 13:23:09 - BUSCA A LISTA DE LOCAIS CADASTRADOS */
        function buscarDadosLocal(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                   '_token':'{{csrf_token()}}',
                    'cliente': '{{Config::get('database.connections.mysql.database')}}',
                },
                url:"{{route('agenda.dados.local')}}",
                success:function(r){
                   popularTabelaLocal(r);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function buscarUsuarios(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                   '_token':'{{csrf_token()}}',
                },
                url:"{{route('usuarios.buscar')}}",
                success:function(r){
                    popularSelectUsuario(r.dados);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularSelectUsuario(dados){
            var htmlSelect = `<option value="0">Selecionar Usuário</option>`;
            for(i=0; i< dados.length; i++){
                var dadosKeys = Object.keys(dados[i]);
                for(j=0;j<dadosKeys.length;j++){
                    if(dados[i][dadosKeys[j]] == null){
                        dados[i][dadosKeys[j]] = "";
                    }
                }

                htmlSelect += `
                   <option value="${dados[i]['ID']}">${dados[i]['NAME']}</option>`;
            }  

            $('#selectUsuarioFiltro').html(htmlSelect);
            
        }

        /* Matheus 14/07/2023 13:23:20 - POPULA OS VALORES DA TABELA DE LISTA DE LOCAIS */
        function popularTabelaLocal(locais){
            var htmlLocal = "";
            for(i=0; i< locais.length; i++){
                var locaisKeys = Object.keys(locais[i]);
                for(j=0;j<locaisKeys.length;j++){
                    if(locais[i][locaisKeys[j]] == null){
                        locais[i][locaisKeys[j]] = "";
                    }
                }

                htmlLocal += `
                    <tr id="tableRow${locais[i]['ID']}">
                        <td class="tdTexto">${locais[i]['ID']}</td>
                        <td class="tdTexto">${locais[i]['DESCRICAO']}</td>
                        <td class="tdTexto">${locais[i]['TAMANHO']}</td>
                        <td class="tdTexto"><center>${locais[i]['QTDE_MAX']}</center> </td>
                        <td class="tdTexto"><center>${locais[i]['PRECO'].toLocaleString('pt-br',{style: 'currency', currency: 'BRL'})}</center></td>                        
                        <td class="tdTexto"><center>${locais[i]['OBSERVACAO']}</center></td>
                        <td class="tdTexto"><center><i class="fas fa-trash-alt fa-lg" onclick="inativarLocal(${locais[i]['ID']})" ><i/></center></td>`;
            }  

            $('#tableBodyDadosLocal').html(htmlLocal);
        }        

        /* Matheus 14/07/2023 16:58:08 - METODO QUE VALIDA OS DADOS DO EVENTO E SALVA */
        function adicionarEvento(){         
            var dataIniValidacao = new Date($('#eventoDataInicio').val()).getTime();
            var dataFimValidacao = new Date($('#eventoDataFinal').val()).getTime();
            validacao = true;

            if(dataIniValidacao > dataFimValidacao){
                Swal.fire(
                    'Erro ao adicionar evento!',
                    'A data final não pode ser maior que a data inicial.',
                    'error'
                );
                validacao = false;
            }

            dadosEvento = {
                'eventoTitulo': $('#eventoTitulo').val(),
                'eventoDataInicio':$('#eventoDataInicio').val(),
                'eventoResponsavel':$('#eventoResponsavel').val()
            };

            campos = [
                'eventoTitulo',
                'eventoDataInicio',
                'eventoResponsavel'
            ];

            inputs = [
                'eventoTitulo',
                'eventoDataInicio',
                'eventoResponsavel'
            ]

            for(i = 0; i< campos.length; i++){
                if(dadosEvento[campos[i]] == ""){
                    if(inputs[i] == 'idEventoLocal'){
                        $('#eventoLocal').addClass('is-invalid');
                    }
                    $('#'+inputs[i]).addClass('is-invalid');
                    validacao = false;

                }else{
                    $('#'+inputs[i]).removeClass('is-invalid');
                };
            }


            if(validacao){
                var eventoDataIni = $('#eventoDataInicio').val();
                var eventoDataIniVal = $('#eventoDataInicio').val();
                var eventoDataFim = $('#eventoDataFinal').val();
                var eventoTitulo = $('#eventoTitulo').val();
                var responsavel = $('#eventoResponsavel').val();
                var idLocal = $('#idEventoLocal').val(); 
                var preco = $('#eventoValor').val();
                var observacao = $('#eventoObservacao').val();   
                var codcli = $('#selectClienteAdicionar').val();   
                var diaTodo = eventoDiaTodo;   

                var eventoDataIniFormatada = moment(eventoDataIni).format();
                var eventoDataFimFormatada = moment(eventoDataFim).format();

                var idEvento = gerarIdEvento();

                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                       '_token':'{{csrf_token()}}',
                       'id_usuario' : {{auth()->user()->id }},
                       'codevento': idEvento,
                       'titulo':eventoTitulo,
                       'responsavel': responsavel,
                       'id_local':idLocal,
                       'preco': preco,
                       'observacao': observacao,
                       'cor':corSelecionada,
                       'data_ini': eventoDataIni,
                       'data_fim': eventoDataFim,
                       'diaTodo': eventoDiaTodo,
                       'CODCLI': codcli,
                        'cliente': '{{Config::get('database.connections.mysql.database')}}',
                    },
                    url:"{{route('agenda.inserir.evento')}}",
                    success:function(r){
                        $('#eventoDataInicio').val('');
                        $('#eventoDataFinal').val('');
                        $('#eventoTitulo').val('');
                        $('#eventoResponsavel').val('');
                        $('#idEventoLocal').val('');
                        $('#eventoLocal').val('');
                        $('#eventoValor').val('');
                        $('#eventoObservacao').val('');                        
                        $('#eventoDiaTodo').prop('checked', false);                        
                        $('#eventoLocalEditar').attr('disabled', false); 

                        geraEvento(idEvento, eventoTitulo, eventoDataIniFormatada, eventoDataFimFormatada, corSelecionada, diaTodo);
                        
                        dispararAlerta('success', 'Evento gerado com sucesso.');
                        Swal.fire({
                            icon: 'question',
                            title: 'Deseja adicionar o evento na agenda do seu celular?',
                            showCloseButton: true,
                            showConfirmButton: true,
                            confirmButtonText: 'Sim',
                            showCancelButton: true,
                            cancelButtonText: 'Não'
                        }).then((result) => {
                            if (result.value) {
                                adicionarEventoCelular(eventoTitulo, eventoDataIniFormatada, eventoDataFimFormatada, diaTodo, observacao)
                            } else {
                                Swal.close()
                            }
                        })
                        
                        $('#btnLimparLocal').addClass('d-none');

                        $('#modal-evento-adicionar').modal('hide');

                        eventoDiaTodo = false;
                    },
                    error:err=>{exibirErro(err)}
                })                
            }
        }

        /* Matheus 18/07/2023 10:23:35 - SALVA A EDIÇÃO DE UM EVENTO */
        function editarEvento(){
            var dataIniValidacao = new Date($('#eventoDataInicioEditar').val()).getTime();
            var dataFimValidacao = new Date($('#eventoDataFinalEditar').val()).getTime();
            validacao = true;

            if(dataIniValidacao > dataFimValidacao){
                Swal.fire(
                    'Erro ao adicionar evento!',
                    'A data final não pode ser maior que a data inicial.',
                    'error'
                );
                validacao = false;
            }
            dadosEvento = {
                'eventoTituloEditar': $('#eventoTituloEditar').val(),
                'eventoDataInicioEditar':$('#eventoDataInicioEditar').val(),
                'eventoResponsavelEditar':$('#eventoResponsavelEditar').val()
            };

            campos = [
                'eventoTituloEditar',
                'eventoDataInicioEditar',
                'eventoResponsavelEditar'
            ];

            inputs = [
                'eventoTituloEditar',
                'eventoDataInicioEditar',
                'eventoResponsavelEditar'
            ]

            for(i = 0; i< campos.length; i++){
                if(dadosEvento[campos[i]] == ""){
                    $('#'+inputs[i]).addClass('is-invalid');
                    validacao = false;

                }else{
                    $('#'+inputs[i]).removeClass('is-invalid');
                };
            }

            if(validacao){
                var eventoDataIni = $('#eventoDataInicioEditar').val();
                var eventoDataFim = $('#eventoDataFinalEditar').val();
                var eventoTitulo = $('#eventoTituloEditar').val();
                var responsavel = $('#eventoResponsavelEditar').val();
                var idLocal = $('#idEventoLocalEditar').val(); 
                var preco = $('#eventoValorEditar').val();
                var observacao = $('#eventoObservacaoEditar').val();
                var idEvento = $('#idEvento').val();
                var codEvento = $('#codEvento').val();
                var codcli = $('#selectClienteEditar').val();

                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                       '_token':'{{csrf_token()}}',
                       'id_codigo': idEvento,
                       'id_usuario' : {{auth()->user()->id }},
                       'codevento': codEvento,
                       'titulo':eventoTitulo,
                       'responsavel': responsavel,
                       'id_local':idLocal,
                       'preco': preco,
                       'observacao': observacao,
                       'data_ini': eventoDataIni,
                       'data_fim': eventoDataFim,
                       'CODCLI': codcli,
                        'cliente': '{{Config::get('database.connections.mysql.database')}}',
                    },
                    url:"{{route('agenda.editar.evento')}}",
                    success:function(r){
                        calendar.removeAllEvents();
                        buscarEventos();

                        $('#modal-evento-editar').modal('hide');
                        $('#btnEditarEvento').removeClass('d-none');
                        $('#btnExcluirEvento').removeClass('d-none');

                        $('#btnLimparLocalEditar').addClass('d-none');
                        Swal.fire(
                            'Sucesso!',
                            'Evento editado com sucesso!',
                            'success'
                        );
                    },
                    error:err=>{exibirErro(err)}
                })
            }
        }

        /* Matheus 19/07/2023 10:47:50 - EDITA O EVENTO AO ARRASTAR E SOLTAR O EVENTO NO CALENDARIO*/
        function editarEventoDragNDrop(dataInicio, dataFim, codEvento){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'id_usuario' : {{auth()->user()->id }},
                    'cliente': '{{Config::get('database.connections.mysql.database')}}',
                    'codevento': codEvento,
                    'data_ini': dataInicio,
                    'data_fim': dataFim,
                    'editDragNDrop': true,
                },
                url:"{{route('agenda.editar.evento')}}",
                success:function(r){
                Swal.fire(
                    'Sucesso!',
                    'Evento editado com sucesso.',
                    'success'
                )
                },
                error:err=>{exibirErro(err)}
            })
        }

        /* Matheus 18/07/2023 08:43:20 - GERA UM ID ALEATORIO PARA O EVENTO */
        function gerarIdEvento() {
            return '_' + Math.random().toString(36).substr(2, 9);
        }

        function exibirCadastroEvento(dataEventoInicio){
            if(dataEventoInicio.indexOf("T") < 1 ){
                horaAtual = moment(new Date()).format('YYYY-MM-DDTHH:mm').toString().split("T")[1];
                dataEventoInicio = dataEventoInicio+"T"+horaAtual;
            }

            dataEventoFim = moment(dataEventoInicio).add(1, 'hour').format('YYYY-MM-DDTHH:mm');

            $('#eventoTitulo').val('');
            $('#eventoDataInicio').val(dataEventoInicio);
            $('#eventoDataFinal').val(dataEventoFim);
            $('#eventoResponsavel').val('');
            $('#idEventoLocal').val('');
            $('#eventoLocal').val('');
            $('#eventoLocal').removeAttr('disabled');
            $('#eventoValor').val('');
            $('#eventoObservacao').val('');

            $('#modal-evento-adicionar').modal('show');
        }

        function exibirEdicaoEvento(idEvento){
            $('#modal-evento-editar').modal('show');

            $('#divLoadingEdicaoEvento').removeClass('d-none');
            $('#divLoadingEdicaoEvento').addClass('d-flex');

            $('#cardEventoDados').removeClass('d-flex');
            $('#cardEventoDados').addClass('d-none');

            var idUsuario = {{auth()->user()->id }};
            
            if($('#selectUsuarioFiltro').val() == 0){
                idUsuario = {{auth()->user()->id }};
            } else {
                idUsuario = $('#selectUsuarioFiltro').val() ;
            }

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                   '_token':'{{csrf_token()}}',
                    'id_usuario' : idUsuario,
                    'cliente': '{{Config::get('database.connections.mysql.database')}}',
                    'id_evento': idEvento,
                },
                url:"{{route('agenda.dados.evento')}}",
                success:function(r){
                    visualizaEvento(r);
                },
                error:err=>{exibirErro(err)}
            })

        }

        function visualizaEvento(dadosEvento){
            for(i=0; i< dadosEvento.length; i++){
                var dadosEventoKeys = Object.keys(dadosEvento[i]);
                for(j=0;j<dadosEventoKeys.length;j++){
                    if(dadosEvento[i][dadosEventoKeys[j]] == null){
                        dadosEvento[i][dadosEventoKeys[j]] = "";
                    }
                }

                $('#idEvento').val(dadosEvento[i]['ID']);
                $('#codEvento').val(dadosEvento[i]['CODEVENTO']);
                $('#eventoTituloEditar').val(dadosEvento[i]['TITULO']);
                $('#eventoDataInicioEditar').val(dadosEvento[i]['DATA_INI']);
                $('#eventoDataFinalEditar').val(dadosEvento[i]['DATA_FIM']);
                $('#eventoResponsavelEditar').val(dadosEvento[i]['RESPONSAVEL']);
                $('#idEventoLocalEditar').val(dadosEvento[i]['ID_LOCAL']);
                $('#eventoLocalEditar').val(dadosEvento[i]['LOCAL']);
                $('#eventoValorEditar').val(dadosEvento[i]['PRECO']);
                $('#eventoObservacaoEditar').val(dadosEvento[i]['OBSERVACAO']);
                $('#selectClienteEditar').val(dadosEvento[i]['CODCLI']);

                $('#eventoTituloEditar').prop('disabled', 'true');
                $('#eventoDataInicioEditar').prop('disabled', 'true');
                $('#eventoDataFinalEditar').prop('disabled', 'true');
                $('#eventoResponsavelEditar').prop('disabled', 'true');
                $('#idEventoLocalEditar').prop('disabled', 'true');
                $('#eventoLocalEditar').prop('disabled', 'true');
                $('#eventoValorEditar').prop('disabled', 'true');
                $('#eventoObservacaoEditar').prop('disabled', 'true');
                $('#selectClienteEditar').prop('disabled', 'true');   

                $('#btnSalvarEdicao').addClass('d-none');
                $('#btnLimparLocalEditar').addClass('d-none');
                $('#btnEditarEvento').removeAttr('disabled');
                $('#btnExcluirEvento').removeAttr('disabled');

                if(dadosEvento[i]['FINALIZADO'] == 'S'){                    
                    $('#btnEditarEvento').addClass('d-none');
                    $('#btnFinalizarEvento').addClass('d-none');

                    $('#spanSituacaoEvento').html('FINALIZADO');
                } else {          
                    $('#btnEditarEvento').removeClass('d-none');
                    $('#btnFinalizarEvento').removeClass('d-none');

                    $('#spanSituacaoEvento').html('');
                }
            }
            
            $('#divLoadingEdicaoEvento').removeClass('d-flex');
            $('#divLoadingEdicaoEvento').addClass('d-none');

            $('#cardEventoDados').removeClass('d-none');
            $('#cardEventoDados').addClass('d-flex');
        }

        /* Matheus 18/07/2023 09:20:54 - BUSCA TODOS OS EVENTOS DA PESSOA */
        function buscarEventos(){
            var idUsuario = {{auth()->user()->id }};
            
            if($('#selectUsuarioFiltro').val() == 0){
                idUsuario = {{auth()->user()->id }};
            } else {
                idUsuario = $('#selectUsuarioFiltro').val() ;
            }

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'id_usuario' : idUsuario,
                    'cliente': '{{Config::get('database.connections.mysql.database')}}',
                },
                url:"{{route('agenda.dados.evento')}}",
                success:function(r){
                   r.forEach(element => {
                       var dataIniFormatada = moment(element.DATA_INI).format();
                       var dataFimFormatada = moment(element.DATA_FIM).format();

                       geraEvento(element.CODEVENTO, element.TITULO, dataIniFormatada, dataFimFormatada, element.COR_EVENTO, element.DIA_TODO);
                   });
                },
                error:err=>{exibirErro(err)}
            })
        }

        /* Matheus 19/07/2023 10:43:43 - VALIDA O VALOR DIGITADO NO CAMPO DE LOCAL NO CADASTRO */
        function validaCampoLocal(){
            if($('#eventoLocal').val().trim() == ''){
                $('#idEventoLocal').val('');
                $('#eventoValor').val('');
            }
        }

        /* Matheus 19/07/2023 10:44:03 - VALIDA O VALOR DIGITADO NO CAMPO DE LOCAL NA EDIÇÃO */
        function validaCampoLocalEditar(){
            if($('#eventoLocalEditar').val().trim() == ''){
                $('#idEventoLocalEditar').val('');
                $('#eventoValorEditar').val('');
            }
        }

        /* Matheus 19/07/2023 10:44:29 - SETA A DATA DE FIM AUTOMATICAMENTE CONFORMA A DATA INICIAL */
        function validaDataInicioEvento(){
            if(eventoDiaTodo){ // SE É UM EVENTO DO DIA TODO MUDA AS DATAS DE INICIO E FIM PARA : 00:00 , NA DATA FIM ADICIONA 1 DIA
                dataIniHora = $('#eventoDataInicio').val().toString().split("T")[0];
                dataEvento = moment(dataIniHora+"T00:00").format('YYYY-MM-DDTHH:mm');
                $('#eventoDataInicio').val(dataEvento);
                validaDataFimEvento();
            } else if($('#eventoDataInicio').val() != null && $('#eventoDataFinal').val() == null){  // SE NÃO É O DIA TODO E NAO TEM UMA DATA DE FIM ALTERA A DATA DE FIM PARA DATA ATUAL+ 1 HORA               
                dataEventoFim = moment($('#eventoDataInicio').val()).add(1, 'hour').format('YYYY-MM-DDTHH:mm');
                $('#eventoDataFinal').val(dataEventoFim);             
            }
        }

        function validaDataFimEvento(){
            if($('#eventoDataFinal').val() != null && eventoDiaTodo){
                dataFimHora = $('#eventoDataFinal').val().toString().split("T")[0];
                $('#eventoDataFinal').val(moment(dataFimHora+"T23:59").format('YYYY-MM-DDTHH:mm'));
            }
        }

        function adicionarLocal(){
            buscarDadosLocal();
            $('#modal-local').modal('show');
            $('#modal-evento-adicionar').modal('hide');
        }

        function buscarCliente(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                   '_token':'{{csrf_token()}}',
                },
                url:"{{route('pessoa.buscar')}}",
                success:function(r){
                    popularSelectCliente(r);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularSelectCliente(dados){
            var htmlSelect = '<option value="0">Selecionar cliente...</option>';
            for(i=0; i< dados.length; i++){
                var dadosKeys = Object.keys(dados[i]);
                for(j=0;j<dadosKeys.length;j++){
                    if(dados[i][dadosKeys[j]] == null){
                        dados[i][dadosKeys[j]] = "";
                    }
                }

                htmlSelect += `
                    <option value="${dados[i]['ID']}">${dados[i]['NOME']}</option>`;
                }  

            $('#selectClienteAdicionar').html(htmlSelect);
            $('#selectClienteEditar').html(htmlSelect);
        }

        function visualizarNotificacaoAgenda(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                   '_token':'{{csrf_token()}}',
                },
                url:"{{route('agenda.visualizar.eventos')}}",
                success:function(r){
                },
                error:err=>{exibirErro(err)}
            })
        }

        function finalizarEvento(){
            Swal.fire({
                icon: 'warning',
                title: 'Deseja realmente finalizar o evento?',
                showCloseButton: false,
                showConfirmButton: true,
                confirmButtonText: 'Sim',
                showCancelButton: true,
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    var idEvento = $('#idEvento').val();
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                           '_token':'{{csrf_token()}}',
                           'ID_EVENTO' : idEvento
                        },
                        url:"{{route('agenda.finalizar.evento')}}",
                        success:function(r){
                            Swal.fire(
                                'Sucesso!',
                                'Evento finalizado com sucesso.',
                                'success'
                            );

                            $('#modal-evento-editar').modal('hide');

                            criarCalendario();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            })
        }

        /* Matheus 21/07/2023 13:35:53 - CONFIGURAÇÕES DOS BOTÕES */
        $('#color-chooser > li > a').click(function (e) {
            e.preventDefault()
            // Save color
            corSelecionada = $(this).css('color')
            // Add color effect to button
            $('#btnAddEvento').css({
                'background-color': corSelecionada,
                'border-color'    : corSelecionada,
                'color'           : '#ffffff'
            })
        })

        $('#btnAddEvento').click(function (e) {
            adicionarEvento();
        })

        function adicionarEventoCelular(eventoTitulo, eventoDataIniFormatada, eventoDataFimFormatada, diaTodo, observacao) {
          
            if (!eventoTitulo) {
                dispararAlerta('warning', 'O título do evento não pode estar vazio!')
                return;
            }

            var dataInicio = new Date(eventoDataIniFormatada);
            var dataFim = new Date(eventoDataFimFormatada);

            if (isNaN(dataInicio.getTime()) || isNaN(dataFim.getTime())) {
                dispararAlerta('warning', 'As datas fornecidas são inválidas!')
                return;
            }

            if (dataInicio >= dataFim) {
                dispararAlerta('warning', 'A data de início deve ser anterior à data de fim.')
                return;
            }

            var startDate, endDate;

            if (diaTodo) {
                startDate = dataInicio.toISOString().slice(0, 10).replace(/-/g, ""); // YYYYMMDD
                endDate = dataFim.toISOString().slice(0, 10).replace(/-/g, ""); // YYYYMMDD
            } else {
                startDate = dataInicio.toISOString().replace(/-|:|\.\d\d\d/g, ""); // YYYYMMDDTHHMMSSZ
                endDate = dataFim.toISOString().replace(/-|:|\.\d\d\d/g, ""); // YYYYMMDDTHHMMSSZ
            }

            var androidUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(eventoTitulo)}&details=${encodeURIComponent(observacao)}&dates=${startDate}/${endDate}&allday=${diaTodo}`;
            
            try {
                if (/Android/i.test(navigator.userAgent)) {
                    window.location.href = androidUrl;
                } else if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                    addEventToIOS(eventoTitulo, observacao, startDate, endDate, diaTodo)
                } else {
                    dispararAlerta('warning', 'Abra em um dispositivo compatível (iOS/Android)')
                }
            } catch (error) {
                // SWAL APENAS PARA DEPURAÇÃO EM HOMOLOGAÇÃO
                // Swal.fire(
                //     'Erro!',
                //     'Ocorreu um erro ao tentar abrir o aplicativo de calendário:' + error.message,
                //     'error'
                // )
                dispararAlerta('warning', 'Ocorreu um erro ao tentar abrir o aplicativo de calendário, favor entre em contato com o suporte!')
            }
        }

        function addEventToIOS(eventoTitulo, observacao, startDate, endDate, diaTodo) {
            try {
                if (!eventoTitulo || typeof eventoTitulo !== "string") {
                    eventoTitulo = "Evento sem título";
                }
                if (!observacao || typeof observacao !== "string") {
                    observacao = "Sem descrição";
                }

                let formattedStartDate = startDate;
                let formattedEndDate = endDate;

                let icsContent = "BEGIN:VCALENDAR\n" +
                                 "VERSION:2.0\n" +
                                 "CALSCALE:GREGORIAN\n" +
                                 "BEGIN:VEVENT\n" +
                                 `SUMMARY:${eventoTitulo}\n` +
                                 `DESCRIPTION:${observacao}\n` +
                                 `DTSTART:${startDate}\n` +
                                 `DTEND:${endDate}\n` +
                                 "END:VEVENT\n" +
                                 "END:VCALENDAR";

            let dataUrl = `data:text/calendar;charset=utf-8,${encodeURIComponent(icsContent)}`;

                let link = document.createElement("a");
                link.href = dataUrl;
                link.download = "evento.ics";
                link.click();
            } catch (error) {
                Swal.fire(
                    "Erro!",
                    `Ocorreu um erro ao tentar criar o evento:<br>${error.message}`,
                    "error"
                );
            }
        }

        $('#btnAddLocal').click(()=>{                  
            adicionarLocal();
        })

        $('#btnEditarEvento').click(()=>{
            $('#eventoTituloEditar').removeAttr('disabled');
            $('#eventoDataInicioEditar').removeAttr('disabled');
            $('#eventoDataFinalEditar').removeAttr('disabled');
            $('#eventoResponsavelEditar').removeAttr('disabled');
            $('#idEventoLocalEditar').removeAttr('disabled');
            $('#eventoLocalEditar').removeAttr('disabled');
            $('#eventoValorEditar').removeAttr('disabled');
            $('#eventoObservacaoEditar').removeAttr('disabled');
            $('#selectClienteEditar').removeAttr('disabled');   

            $('#btnSalvarEdicao').removeClass('d-none');
            $('#btnEditarEvento').prop('disabled', 'true');
            $('#btnExcluirEvento').prop('disabled', 'true');

            if($('#eventoLocalEditar').val() != ''){
                $('#btnLimparLocalEditar').removeClass('d-none');
                $('#eventoLocalEditar').attr('disabled', true); 
            }
        })

        $('#btnSalvarEdicao').click(()=>{
            Swal.fire({
                icon: 'warning',
                title: 'Deseja confirmar a edição do evento?',
                showCloseButton: false,
                showConfirmButton: true,
                confirmButtonText: 'Sim',
                showCancelButton: true,
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value == true) {
                    editarEvento();
                } 
            })            
        })

        $('#btnExcluirEvento').click(()=>{
            Swal.fire({
                icon: 'warning',
                title: 'Deseja confirmar a exclusão do evento?',
                showCloseButton: false,
                showConfirmButton: true,
                confirmButtonText: 'Sim',
                showCancelButton: true,
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value == true) {
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'id_codigo': $('#idEvento').val(),
                            'id_usuario' : {{auth()->user()->id }},
                            'codevento': $('#codEvento').val(),
                            'cliente': '{{Config::get('database.connections.mysql.database')}}',
                        },
                        url:"{{route('agenda.excluir.evento')}}",
                        success:function(r){
                            calendar.removeAllEvents();
                            buscarEventos(); 

                            $('#modal-evento-editar').modal('hide');

                            Swal.fire(
                                'Sucesso!',
                                'Evento excluído com sucesso.',
                                'success'
                            );
                        },
                        error:err=>{exibirErro(err)}
                    })
                } 
            })   
        })

        $("#eventoLocal").autocomplete({ 
                source: function(request, cb){
                    param = request.term;
                    campoBuscado = param;
                    $.ajax({
                        url:"{{ route('agenda.buscar.local') }}",
                        method: 'post',
                        data:{
                            '_token':'{{ csrf_token() }}',
                            param
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
                    if (selectedData.item.label != 'Nenhum Cadastro Encontrado.'){
                        $('#idEventoLocal').val(selectedData.item.data.ID_LOCAL);
                        $('#eventoLocal').val(selectedData.item.data.DESCRICAO);
                        $('#eventoValor').val(selectedData.item.data.PRECO);
                        $('#btnLimparLocal').removeClass('d-none');
                        $('#eventoLocal').attr('disabled', true); 
                    } else {
                        $('#idEventoLocal').val('');
                        $('#eventoLocal').val(''); 
                        $('#eventoValor').val('');
                    }
                }
        });

        $("#eventoLocalEditar").autocomplete({ 
                source: function(request, cb){
                    param = request.term;
                    campoBuscado = param;
                    $.ajax({
                        url:"{{ route('agenda.buscar.local') }}",
                        method: 'post',
                        data:{
                            '_token':'{{ csrf_token() }}',
                            param
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
                    if (selectedData.item.label != 'Nenhum Cadastro Encontrado.'){
                        $('#idEventoLocalEditar').val(selectedData.item.data.ID_LOCAL);
                        $('#eventoLocalEditar').val(selectedData.item.data.DESCRICAO);
                        $('#eventoValorEditar').val(selectedData.item.data.PRECO);
                        $('#btnLimparLocalEditar').removeClass('d-none');
                        $('#eventoLocalEditar').attr('disabled', true); 
                    } else {
                        $('#idEventoLocalEditar').val('');
                        $('#eventoLocalEditar').val(''); 
                        $('#eventoValorEditar').val(''); 
                    }
                }
        });

        $('#btnLimparLocal').click(()=>{
            $('#eventoLocal').val('');
            $('#eventoLocal').attr('disabled', false); 
            $('#btnLimparLocal').addClass('d-none')
            validaCampoLocal();
        });

        $('#btnLimparLocalEditar').click(()=>{
            $('#eventoLocalEditar').val('');
            $('#eventoLocalEditar').attr('disabled', false); 
            $('#btnLimparLocalEditar').addClass('d-none')
            validaCampoLocalEditar();
        });

        $('#eventoDiaTodo').on('change', function() {
            eventoDiaTodo = !eventoDiaTodo;
            validaDataInicioEvento();
            validaDataFimEvento();
        });

        $('#selectUsuarioFiltro').on('change', () => {
            criarCalendario();
        });

        $('#btnFinalizarEvento').on('click', () => {
            finalizarEvento();
        });

        /* Matheus 21/07/2023 13:36:25 - FIM CONFIGURAÇÕES BOTOES */
        
        function reajustarCalendario() {
            var windowWidth = window.innerWidth;
            var windowHeight = window.innerHeight;
            var headerHeight = document.querySelector('.fc-header-toolbar').offsetHeight;
            var newHeight = windowHeight - headerHeight - 20;// Ajuste conforme necessário

            calendar.setOption('height', newHeight);
        }

        function fecharAgenda(){
            document.location.href = "{{env('APP_URL')}}";
        }

        // Chamar a função ao carregar a página e quando a janela for redimensionada
        window.addEventListener('resize', reajustarCalendario);

        $(document).ready(() => {
            criarCalendario();
            buscarCliente();
            buscarUsuarios();

            visualizarNotificacaoAgenda();
        });

    </script>
  @stop
