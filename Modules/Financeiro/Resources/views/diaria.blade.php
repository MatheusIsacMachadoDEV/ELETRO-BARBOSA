@extends('adminlte::page')

@section('title', 'GSSoftware')

@section('content')

    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>GSSoftware</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <div class="col-12 col-md-6 row d-flex d-flex justify-content-end ">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-block btn-warning" id="btnNovo">
                            <i class="fas fa-plug"></i>
                            <span class="ml-1">Novo</span>
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
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="d-none d-lg-table-cell">Funcionário</th>
                            <th class="d-none d-lg-table-cell"><center>Data Início</center></th>
                            <th class="d-none d-lg-table-cell"><center>Data Fim</center></th>
                            <th class="d-none d-lg-table-cell"><center>Tempo (Dias)</center></th>
                            <th class="d-none d-lg-table-cell"><center>Valor por Dia</center></th>
                            <th class="d-none d-lg-table-cell"><center>Valor Total</center></th>
                            <th class="d-none d-lg-table-cell"><center>Paga</center></th>
                            <th class="d-none d-lg-table-cell"><center>Ações</center></th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyDiarias">
                        <!-- Dados das diárias serão preenchidos aqui -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Documentação da diária <span id="titleDocumento"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="">
                                <input type="hidden" id="inputIDDiariaDocumento">
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
                            <table class="table table-responsive-xs">
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
    
    <div class="modal fade" id="modal-diaria" tabindex="-1" role="dialog" aria-labelledby="modalDiariaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDiariaLabel">Cadastrar Diária</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <input type="text" class="form-control form-control-border" placeholder="Descrição" id="inputDescricao">
                    </div>

                    <!-- Autocomplete de Funcionário -->
                    <div class="form-group">
                        <input type="text" class="form-control form-control-border" id="inputFuncionario" placeholder="Funcionário">
                        <input type="hidden" id="inputIDFuncionario">
                        <div class="btnLimparFuncionario d-none">
                            <button id="btnLimparFuncionario" class="btn btn-sm btn-danger mt-2"><i class="fas fa-eraser"></i> LIMPAR</button>
                        </div>
                    </div>

                    <div class="col-12 row p-0 m-0" id="divSelecionarProjeto">
                        <div class="col">
                            <input type="text" class="form-control form-control-border col-12" placeholder="Projeto" id="inputProjeto">
                            <input type="hidden" id="inputIDProjeto">
                        </div>
                        <div class="col btnLimparProjeto d-none m-0 p-0">
                            <button id="btnLimparProjeto" class="btnLimparProjeto btn btn-sm btn-danger d-none col-12"><i class="fas fa-eraser"></i>LIMPAR</button>
                        </div>
                    </div>

                    <!-- Data Início e Data Fim -->
                    <div class="form-group">
                        <label>Inicio</label>
                        <input type="date" class="form-control form-control-border" id="inputDataInicio">
                    </div>
                    
                    <div class="form-group">
                        <label>Fim</label>
                        <input type="date" class="form-control form-control-border" id="inputDataFim">
                    </div>

                    <!-- Valor por Dia -->
                    <div class="form-group">
                        <input type="number" class="form-control form-control-border" id="inputValorDia" placeholder="Valor Por Dia">
                    </div>

                    <!-- Valor Total (calculado automaticamente) -->
                    <div class="form-group">
                        <input type="number" class="form-control form-control-border" id="inputValorTotal" readonly placeholder="Valor Total">
                    </div>

                    <input type="hidden" id="inputIDDiaria" value="0">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarDiaria">Salvar</button>
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

        var timeoutCalculo = 0;

        function exibirModalCadastro(){
            resetarCamposCadastro();

            $('#modal-diaria').modal('show');
        }
    
        // Buscar Diárias
        function buscarDados() {
            $.ajax({
                type: 'post',
                url: "{{route('diaria.buscar')}}",
                data: {
                    '_token': '{{csrf_token()}}',
                    'filtro': $('#inputFiltroBusca').val()
                },
                success: function(r) {
                    popularTabelaDados(r.dados);
                },
                error: err => {
                    console.log(err);
                }
            });
        }
    
        // Popular lista de diárias
        function popularTabelaDados(dados) {
            var htmlTabela = "";

            for(i=0; i< dados.length; i++){
                var Keys = Object.keys(dados[i]);
                for(j=0;j<Keys.length;j++){
                    if(dados[i][Keys[j]] == null){
                        dados[i][Keys[j]] = "";
                    }
                }

                var diariaPaga = dados[i]['PAGAMENTO_REALIZADO'] == 'S' ? 'Sim' : 'Não';
                var classeDiariaPaga = dados[i]['PAGAMENTO_REALIZADO'] == 'S' ? 'bg-success' : 'bg-danger';
                var dataInicio = moment(dados[i]['DATA_INICIO']).format('DD/MM/YYYY')
                var dataFim = moment(dados[i]['DATA_FIM']).format('DD/MM/YYYY')

                btnInserirPagamento = `<li class="dropdown-item" onclick="pagamentoDiaria(${dados[i]['ID']})"><span class="btn"><i class="fas fa-dollar-sign"></i></span> Inserir Pagamento</li>`;
                btnEditar = `<li class="dropdown-item" onclick="editarDiaria(${dados[i]['ID']})"><span class="btn"><i class="fas fa-pen"></i></span> Editar</li>`;
                btnArquivos = `<li class="dropdown-item" onclick="cadastarDocumento(${dados[i]['ID']}, '${dados[i]['NOME_USUARIO']}')"><span class="btn"><i class="fas fa-file-alt"></i></span> Arquivos</li>`;
                btnInativar = `<li class="dropdown-item" onclick="inativarDiaria(${dados[i]['ID']})"><span class="btn"><i class="fas fa-trash"></i></span> Inativar</li>`;

                if(dados[i]['PAGAMENTO_REALIZADO'] == 'S'){
                    btnInserirPagamento = '';
                }

                var btnOpcoes = ` <div class="input-group-prepend show justify-content-center" style="text-align: center">
                                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                Ações
                                            </button>
                                            <ul class="dropdown-menu ">
                                                ${btnInserirPagamento}
                                                ${btnEditar}
                                                ${btnInativar}                                            
                                                ${btnArquivos}                                          
                                            </ul>
                                        </div>`;
                
                var spanDiariaPaga = `<span class="badge ${classeDiariaPaga}">${diariaPaga}</span>`;

                htmlTabela += `
                    <tr class="d-none d-lg-table-row">
                        <td>${dados[i]['NOME_USUARIO']}</td>
                        <td><center>${dataInicio}</center></td>
                        <td><center>${dataFim}</center></td>
                        <td><center>${dados[i]['TEMPO_DIAS']} dias</center></td>
                        <td><center>${mascaraFinanceira(dados[i]['VALOR_DIA'])}</center></td>
                        <td><center>${mascaraFinanceira(dados[i]['VALOR_TOTAL'])}</center></td>
                        <td><center>${spanDiariaPaga}</center></td>
                        <td>
                            <center>
                                ${btnOpcoes}
                            </center>
                        </td>
                    </tr>

                    <tr></tr>

                    <tr class="d-lg-none">
                        <td class="row d-flex ">
                            <div class="col-12 d-flex justify-content-center">
                                <span><b>${dados[i]['NOME_USUARIO']}</b> ${spanDiariaPaga}</span>
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                ${dataInicio} - ${dataFim} (${dados[i]['TEMPO_DIAS']} DIAS)
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                ${mascaraFinanceira(dados[i]['VALOR_TOTAL'])} (${mascaraFinanceira(dados[i]['VALOR_DIA'])} / DIA)
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                ${btnOpcoes}
                            </div>
                        </td>
                    </tr>
                `;
            }
            $('#tableBodyDiarias').html(htmlTabela);
        }

        function salvarDiaria(){
            var validacao = true;
            var mensagemErro = 'Verifique os campos obrigatórios!';
            const inputs = [
                'inputFuncionario',
                'inputIDFuncionario',
                'inputDataInicio',
                'inputDataFim',
                'inputValorTotal',
                'inputDescricao',
                'inputValorDia'
            ];
            
            for(i = 0; i< inputs.length; i++){
                if($('#'+inputs[i]).val() == '' || (inputs[i] == 'inputIDFuncionario' && $('#'+inputs[i]).val() == 0) ){
                    if(inputs[i] == 'inputIDFuncionario'){
                        $('#inputFuncionario').addClass('is-invalid');
                    } else {
                        $('#'+inputs[i]).addClass('is-invalid');
                    }
                    validacao = false;                    
                }else{
                    $('#'+inputs[i]).removeClass('is-invalid');
                }
            }

            if(validacao){
                const idDiaria = $('#inputIDDiaria').val();
                const idUsuario = $('#inputIDFuncionario').val();
                const usuario = $('#inputFuncionario').val();
                const dataInicio = $('#inputDataInicio').val();
                const dataFim = $('#inputDataFim').val();
                const valorDia = $('#inputValorDia').val();
                const valorTotal = $('#inputValorTotal').val();
                const descricao = $('#inputDescricao').val();

                const url = idDiaria == 0 ? "{{route('diaria.inserir')}}" : "{{route('diaria.alterar')}}";
                const data = {
                    '_token': '{{csrf_token()}}',
                    'ID': idDiaria,
                    'ID_USUARIO': idUsuario,
                    'DATA_INICIO': dataInicio,
                    'DATA_FIM': dataFim,
                    'DESCRICAO': descricao,
                    'USUARIO': usuario,
                    'VALOR_DIA': valorDia
                };

                $.ajax({
                    type: 'post',
                    url: url,
                    data: data,
                    success: function(r) {
                        $('#modal-diaria').modal('hide');
                        buscarDados();
                        Swal.fire('Sucesso!', 'Diária salva com sucesso.', 'success');
                    },
                    error: err => {
                        console.log(err);
                        Swal.fire('Erro!', 'Ocorreu um erro ao salvar a diária.', 'error');
                    }
                });
            } else {
                dispararAlerta('warning', mensagemErro);
            }            
        }
    
        // Editar Diária
        function editarDiaria(idDiaria) {
            $.ajax({
                type: 'post',
                data: {
                    '_token': '{{csrf_token()}}',
                    'ID': idDiaria
                },
                url: "{{route('diaria.buscar')}}",
                success: function(r) {
                    const diaria = r.dados[0];
                    $('#inputIDDiaria').val(diaria.ID);
                    $('#inputFuncionario').val(diaria.NOME_USUARIO);
                    $('#inputIDFuncionario').val(diaria.ID_USUARIO);
                    $('#inputDataInicio').val(diaria.DATA_INICIO);
                    $('#inputDataFim').val(diaria.DATA_FIM);
                    $('#inputValorDia').val(diaria.VALOR_DIA);
                    $('#inputValorTotal').val(diaria.VALOR_TOTAL);

                    $('#inputFuncionario').attr('disabled', true);
                    $('.btnLimparFuncionario').removeClass('d-none');

                    $('#modal-diaria').modal('show');
                },
                error: err => {
                    console.log(err);
                }
            });
        }
    
        // Inativar Diária
        function inativarDiaria(idDiaria) {
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar esta diária?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: "{{route('diaria.inativar')}}",
                        data: {
                            '_token': '{{csrf_token()}}',
                            'ID': idDiaria
                        },
                        success: function(r) {
                            Swal.fire('Sucesso!', 'Diária inativada com sucesso.', 'success');
                            buscarDados();
                        },
                        error: err => {
                            console.log(err);
                        }
                    });
                }
            });
        }

        function pagamentoDiaria(idDiaria) {
            Swal.fire({
                title: 'Deseja informar o pagamento da diária?',
                text: 'Será inserido um CPG, já pago, refente ao pagamento dessa diária.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: "{{route('diaria.pagar')}}",
                        data: {
                            '_token': '{{csrf_token()}}',
                            'ID': idDiaria
                        },
                        success: function(r) {
                            dispararAlerta('success', 'Diária paga com sucesso.');

                            buscarDados();
                        },
                        error: err => {
                            console.log(err);
                        }
                    });
                }
            });
        }

        function resetarCamposCadastro(){
            $('#inputIDDiaria').val('');
            $('#inputFuncionario').val('');
            $('#inputIDFuncionario').val('0');
            $('#inputDataInicio').val('');
            $('#inputDataFim').val('');
            $('#inputValorDia').val('');
            $('#inputValorTotal').val('');

            limparCampo('inputFuncionario', 'inputIDFuncionario', 'btnLimparFuncionario');
        }

        function calcularValorTotal(){
            const dataInicio = new Date($('#inputDataInicio').val());
            const dataFim = new Date($('#inputDataFim').val());
            const valorDia = parseFloat($('#inputValorDia').val());

            if(moment(dataInicio).isAfter(moment(dataFim))){
                $('#inputDataInicio').val('');
                $('#inputDataFim').val('');

                $('#inputValorDia').val('0');
                $('#inputValorTotal').val('0');

                dispararAlerta('warning', 'A data de inicio não pode ser maior que a data final.');
            } else {
                if (dataInicio && dataFim && valorDia) {
                    const tempoDias = ((dataFim - dataInicio) / (1000 * 60 * 60 * 24)) + 1;
                    const valorTotal = tempoDias * valorDia;
                    $('#inputValorTotal').val(valorTotal.toFixed(2));
                }
            }
        }

        // DOCUMENTOS
            function buscarDocumentos(){
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                        '_token':'{{csrf_token()}}',                    
                        'ID_DIARIA': $('#inputIDDiariaDocumento').val(),
                    },
                    url:"{{route('diaria.buscar.documento')}}",
                    success:function(r){
                        popularListaDocumentos(r);
                    },
                    error:err=>{exibirErro(err)}
                })
            }  

            function cadastarDocumento(ID_DIARIA, descricaoDiaria){
                $('#titleDocumento').text(ID_DIARIA +' - '+descricaoDiaria);
                $('#inputIDDiariaDocumento').val(ID_DIARIA);

                buscarDocumentos();

                $('#modal-documentacao').modal('show');
            }  

            function inativarDocumento(idDocumento){
                Swal.fire({
                    title: 'Confirmação',
                    text: 'Deseja inativar o documento?',
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
                                'idDocumento': idDocumento
                            },
                            url:"{{route('contasreceber.inativar.documento')}}",
                            success:function(r){
                                Swal.fire('Sucesso!'
                                        , 'Documento inativado com sucesso.'
                                        , 'success');
                                buscarDocumentos();
                            },
                            error:err=>{exibirErro(err)}
                        })
                    }
                });
            }

            function verDocumento(caminhoDocumento){
                url = "{{env('APP_URL')}}/"+caminhoDocumento;

                window.open(url, '_blank');
            }

            function popularListaDocumentos(Documento){
                var htmlDocumento = "";

                for(i=0; i< Documento.length; i++){
                    var DocumentoKeys = Object.keys(Documento[i]);
                    for(j=0;j<DocumentoKeys.length;j++){
                        if(Documento[i][DocumentoKeys[j]] == null){
                            Documento[i][DocumentoKeys[j]] = "";
                            }
                        }
                    
                    documentoCaminho = Documento[i]['CAMINHO_DOCUMENTO'];
                    documentoCaminho = documentoCaminho.split('/')[3];
                    
                    htmlDocumento += `
                        <tr id="tableRow${Documento[i]['ID']}">
                            <td class="tdTexto"><span style="text-decoration: underline; cursor: pointer;" onclick="verDocumento('${Documento[i]['CAMINHO_DOCUMENTO']}')">${documentoCaminho}</span></td>
                                <td>\
                                    <center>\
                                    <button class="btn" onclick="inativarDocumento(${Documento[i]['ID']})"><i class="fas fa-trash"></i></button>\
                                    </center>\
                                </td>\                      
                            </tr>`;
                    }
                $('#tableBodyDocumentos').html(htmlDocumento)
            }  

            function validaDocumento(){
                if ($("#inputArquivoDocumentacao")[0].files.length > 0) {
                    $('#labelInputArquivoDocumentacao').html($("#inputArquivoDocumentacao")[0].files[0].name);
                } else {
                    $('#labelInputArquivoDocumentacao').html('Selecionar Arquivos');
                }
            }

            function salvarDocumento(){
                if($("#inputArquivoDocumentacao")[0].files.length > 0){
                    uploadArquivo();
                } else {
                    Swal.fire('Atenção!'
                            , 'Selecione um documento para vincular à venda.'
                            , 'error');
                }
            }

            function uploadArquivo(){
                var dataAnexo = new FormData();
                anexoCaminho = "";
                ID_DIARIA = $('#inputIDDiariaDocumento').val();
                dataAnexo.append('meuArquivo', document.getElementById('inputArquivoDocumentacao').files[0]);
                dataAnexo.append('ID', ID_DIARIA);

                $.ajax({
                    processData: false,
                    contentType: false,
                    type : 'POST',
                    data : dataAnexo,
                    url : "{{env('APP_URL')}}/salvarDocumentacao.php",
                    success : function(resultUpload) {
                        if(resultUpload != "error"){
                            anexoCaminho = resultUpload;
                            $.ajax({
                                type:'post',
                                datatype:'json',
                                data:{
                                    '_token':'{{csrf_token()}}',
                                    'caminhoArquivo': resultUpload,
                                    'ID_DIARIA': ID_DIARIA,
                                    'caminho': anexoCaminho
                                },
                                url:"{{route('diaria.inserir.documento')}}",
                                success:function(resultInsert){

                                    buscarDocumentos();                                
                                    $("#inputArquivoDocumentacao").val('');
                                    validaDocumento();

                                    Swal.fire('Sucesso!'
                                            , 'Documento salvo com sucesso.'
                                            , 'success');
                                },
                                error:err=>{exibirErro(err)}
                            })  
                        }else{
                            console.log(r)
                            Swal.fire(
                                'Atenção!',
                                'Erro ao enviar Anexo.',
                                'error'
                            )
                        }
                    },
                    error: err=>{exibirErro(err)}
                });
            }
        // FIM

        // Limpar campo de funcionário
        $('#btnLimparFuncionario').click(function() {
            limparCampo('inputFuncionario', 'inputIDFuncionario', 'btnLimparFuncionario');
        });
    
        // Calcular valor total
        $('#inputDataFim, #inputValorDia').on('change', function() {
            clearTimeout(timeoutCalculo);

            timeoutCalculo = setTimeout(() => {
                calcularValorTotal();
            }, 1000);
        });

        // Inserir/Editar Diária
        $('#btnConfirmarDiaria').click(function() {
            salvarDiaria();
        });

        // Autocomplete de Funcionário
        $("#inputFuncionario").autocomplete({
            source: function(request, cb) {
                param = request.term;
                $.ajax({
                    url: "{{route('pessoa.buscar')}}",
                    method: 'post',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'nome': param,
                        'ID_TIPO': 2
                    },
                    dataType: 'json',
                    success: function(r) {
                        result = $.map(r, function(obj) {
                            return {
                                label: obj.info,
                                value: obj.NOME,
                                data: obj
                            };
                        });
                        cb(result);
                    },
                    error: err => {
                        console.log(err);
                    }
                });
            },
            select: function(e, selectedData) {
                if (selectedData.item.label != 'Nenhum Funcionário Encontrado.') {
                    $('#inputFuncionario').val(selectedData.item.data.NOME);
                    $('#inputIDFuncionario').val(selectedData.item.data.ID);
                    $('#inputFuncionario').attr('disabled', true);
                    $('.btnLimparFuncionario').removeClass('d-none');
                } else {
                    limparCampo('inputFuncionario', 'inputIDFuncionario', 'btnLimparFuncionario');
                }
            }
        });

        $('#btnNovo').on('click',  () => {
            exibirModalCadastro();
        });
        
        $("#inputProjeto").autocomplete({
            source: function(request, cb) {
                param = request.term;
                $.ajax({
                    url: "{{route('projeto.buscar')}}",
                    method: 'post',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'filtro': param
                    },
                    dataType: 'json',
                    success: function(r) {
                        result = $.map(r.dados, function(obj) {
                            return {
                                label: obj.info,
                                value: obj.TITULO,
                                data: obj
                            };
                        });
                        cb(result);
                    },
                    error: err => {
                        console.log(err);
                    }
                });
            },
            select: function(e, selectedData) {
                if (selectedData.item.label != 'Nenhum Funcionário Encontrado.') {
                    $('#inputProjeto').val(selectedData.item.data.TITULO);
                    $('#inputIDProjeto').val(selectedData.item.data.ID);
                    $('#inputProjeto').attr('disabled', true);
                    $('.btnLimparProjeto').removeClass('d-none');
                } else {
                    limparCampo('inputProjeto', 'inputIDProjeto', 'btnLimparProjeto');
                }
            }
        });

        $(document).ready(function() {
            buscarDados();
        })
    </script>
@stop