@extends('adminlte::page')

@section('title', 'Veículos')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Veículos</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <button class="col-md-2 btn btn-block btn-info mr-1" id="btnNovaManutencao">
                    <i class="fas fa-cogs"></i>
                    <span class="mr-1">Gastos/Manutenção</span>
                </button>
                <button class="btn btn-warning" id="btnNovoVeiculo">
                    <i class="fas fa-car"></i>
                    <span class="ml-1">Novo</span>
                </button>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="form-group col-7 col-md-4 ">
                        <input type="text" id="inputPlacaFiltro" class="form-control" placeholder="Placa" maxlength="7" onkeyup="buscarVeiculo()">
                    </div>
                    <div class="form-group col-2">
                        <input type="radio" name="inputRadioFiltro" value="patio" id="checkPatioFiltro" onchange="buscarVeiculo()" checked>
                        <label>Pátio</label>
                    </div>
                    <div class="form-group col-2 justify-content-start">
                        <input type="radio" name="inputRadioFiltro" value="vendidos" id="checkVendidosFiltro" onchange="buscarVeiculo()">
                        <label>Vendidos</label>
                    </div>
                    <div class="form-group col-2">
                        <input type="radio" name="inputRadioFiltro" value="todos" id="checkTodosFiltro" onchange="buscarVeiculo()" >
                        <label>Todos</label>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-sm" id="tableDadosVeiculos">
                    <thead>
                        <tr>
                            <th>Placa</th>
                            <th class="d-none d-md-table-cell">Modelo</th>
                            <th class="d-none d-md-table-cell">Data de Compra</th>
                            <th><center>Valor de Compra</center></th>
                            <th><center>Gastos</center></th>
                            <th><center>Situação</center></th>
                            <th><center>Ações</center></th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyDadosVeiculos">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-cadastro">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title">Cadastro de Veículo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        
                        <input type="hidden" class="form-control col-4" id="inputID">
                        <div class="form-group col-12">
                            <label>Placa</label>
                            <input type="text" class="form-control col-4" id="inputPlaca" maxlength="7">
                        </div>
                        <div class="form-group col-12">
                            <label>Modelo</label>
                            <input type="text" class="form-control" id="inputModelo">
                        </div>
                        <div class="form-group col-12">
                            <label>Renavam</label>
                            <input type="text" class="form-control" id="inputRenavam" maxlength="20">
                        </div>
                        <div class="form-group col-12">
                            <label>Chassis</label>
                            <input type="text" class="form-control" id="inputChassis" maxlength="20">
                        </div>
                        <div class="form-group col-12 d-flex p-0">
                            <div class="col-4">
                                <label>Cor</label>
                                <input type="text" class="form-control" id="inputCor" maxlength="20">
                            </div>
                            <div class="col-4">
                                <label>Ano</label>
                                <input type="text" class="form-control" id="inputAno">
                            </div>
                            <div class="col-4">
                                <label>KM</label>
                                <input type="text" class="form-control" id="inputKM">
                            </div>
                        </div>
                        <div class="form-group col-6 col-md-2">
                            <label>Data Compra</label>
                            <input type="date" class="form-control" id="inputDataCompra">
                        </div>
                        <div class="form-group col-6 col-md-2">
                            <label>Valor Compra</label>
                            <input type="text" class="form-control" id="inputValorCompra">
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

    <div class="modal fade" id="modal-manutencao">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title">Manutenção/Gastos de Veículo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">

                        <div class="form-group col-12">
                            <label>Placa</label>
                            <input type="text" class="form-control col-xs-12 col-md-4" id="inputPlacaManutencao">
                            <input type="hidden" class="form-control col-4" id="inputManutencaoIDVeiculo">
                            <button id="btnLimparPlaca" class="btn btn-default d-none">LIMPAR</button>
                        </div>
                        <div class="form-group col-12">
                            <label>Data</label>
                            <input type="date" class="form-control col-xs-12 col-md-2" id="inputDataManutencao">
                        </div>
                        <div class="form-group col-12">
                            <label>Valor</label>
                            <input type="text" class="form-control col-xs-12 col-md-2" id="inputValorManutencao">
                        </div>
                        <div class="form-group col-12 d-flex">
                            <div class="row col-12">                                
                                <div class="col-md-3 col-xs-12">
                                    <input type="checkbox" id="checkMecanica">
                                    <label>MECANICA</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="checkbox" id="checkEletrica">
                                    <label>ELETRICA</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="checkbox" id="checkEstetica">
                                    <label>ESTETICA</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <input type="checkbox" id="checkLataria">
                                    <label>LATARIA E PINTURA</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="checkbox" id="checkoutros">
                                    <label>OUTROS</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <label>Descrição do Serviço</label>
                            <textarea maxlength="150" class="form-control" id="inputDescricaoManutencao"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarManutencao">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-manutencao-lista">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title">Manutenções do Veículo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-responsive-sm" id="tableDadosManutencao">
                        <thead>
                            <tr>
                                <th>Placa</th>
                                <th>Valor</th>
                                <th>Data</th>
                                <th>Situação</th>
                                <th><center>Eletrica</center></th>
                                <th><center>Mecânica</center></th>
                                <th><center>Estética</center></th>
                                <th><center>Lataria</center></th>
                                <th><center>Outros</center></th>
                                <th><center>Ações</center></th>
                                <th><center>Descrição</center></th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyDadosManutencao">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog ">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Documentação do Veiculo <span id="titleDocumento"></span></h5>
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
                                        <th>Placa</th>
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
        .table {
            background-color: #f8f9fa;
        }

        .ui-autocomplete{
            z-index: 1050;
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
        inserindoVeiculo = false;

        $('#inputValorCompra').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' });
        $('#inputValorManutencao').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' });
        $('#inputAno').mask('0000');
        $('#inputKM').mask('0000000');

        function cadastarVeiculo() {
            inserindoVeiculo = true;

            $('#inputID').val('');
            $('#inputPlaca').val('');
            $('#inputPlaca').prop('disabled', false);
            $('#inputModelo').val('');
            $('#inputRenavam').val('');
            $('#inputChassis').val('');
            $('#inputCor').val('');
            $('#inputAno').val('');
            $('#inputKM').val('');
            $('#inputDataCompra').val('');
            $('#inputValorCompra').val('');

            $('#modal-cadastro').modal('show');
        }

        function cadastarManutencao() {
            limparPlacaManutencao();
            $('#inputDataManutencao').val('');
            $('#inputValorManutencao').val('');
            $('#checkMecanica').prop('checked', false);
            $('#checkEletrica').val('checked', false);
            $('#checkEstetica').val('checked', false);
            $('#checkLataria').val('checked', false);
            $('#checkoutros').val('checked', false);
            $('#inputDescricaoManutencao').val('');
            
            $('#modal-manutencao').modal('show');
        }

        function buscarVeiculo(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                '_token':'{{csrf_token()}}',
                'placa': $('#inputPlacaFiltro').val(),
                'situacao' : $('[name="inputRadioFiltro"]:checked').val()
                },
                url:"{{route('veiculo.buscar')}}",
                success:function(r){
                    console.log(r);
                    popularListaVeiculos(r);
                },
                error:err=>{exibirErro(err)}
            })
        }       

        function buscarManutencao(placa){
            $('#inputPlacaManutencaoLista').val(placa);
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                '_token':'{{csrf_token()}}',
                'placa': placa,
                },
                url:"{{route('manutencao.buscar')}}",
                success:function(r){
                    console.log(r);
                    popularListaManutencao(r);
                    $('#modal-manutencao-lista').modal('show')
                },
                error:err=>{exibirErro(err)}
            })
        }

        function buscarDocumentos(){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',                    
                    'placa': $('#inputPlacaDocumentacao').val(),
                },
                url:"{{route('veiculo.buscar.documento')}}",
                success:function(r){
                    popularListaDocumentos(r);
                },
                error:err=>{exibirErro(err)}
            })
        }  

        function popularListaVeiculos(Veiculos){
            var htmlVeiculos = "";

            for(i=0; i< Veiculos.length; i++){
                var VeiculosKeys = Object.keys(Veiculos[i]);
                for(j=0;j<VeiculosKeys.length;j++){
                    if(Veiculos[i][VeiculosKeys[j]] == null){
                        Veiculos[i][VeiculosKeys[j]] = "";
                    }
                }

                if(Veiculos[i]['SITUACAO'] == 'PATIO'){
                    spanBG = "bg-warning";
                } else if(Veiculos[i]['SITUACAO'] == 'VENDIDO'){
                    spanBG = "bg-success";
                } else { 
                    spanBG = "bg-info";
                }

                htmlVeiculos += `
                    <tr id="tableRow${Veiculos[i]['ID']}">
                        <td class="tdTexto">${Veiculos[i]['PLACA']}</td>
                        <td class="tdTexto d-none d-md-table-cell">${Veiculos[i]['MODELO']}</td>
                        <td class="tdTexto d-none d-md-table-cell">${moment(Veiculos[i]['DATA_COMPRA']).format('DD/MM/YYYY')}</td>
                        <td class="tdTexto"><center>${mascaraFinanceira(Veiculos[i]['VALOR_COMPRA'])}</center></td>
                        <td class="tdTexto"><center>${mascaraFinanceira(Veiculos[i]['GASTOS'])}</center></td>
                        <td class="tdTexto"><center><span class="badge ${spanBG}">${Veiculos[i]['SITUACAO']}</span></center></td>
                        <td>\
                            <center>\
                                <button class="btn" onclick="editarVeiculo(${Veiculos[i]['ID']})"><i class="fas fa-pen"></i></button>\
                                <button class="btn" onclick="cadastarDocumento('${Veiculos[i]['PLACA']}')"><i class="fas fa-file-alt"></i></button>\
                                <button class="btn" onclick="buscarManutencao('${Veiculos[i]['PLACA']}')"><i class="fas fa-cogs"></i></button>\
                                <button class="btn" onclick="inativarVeiculo(${Veiculos[i]['ID']})"><i class="fas fa-trash"></i></button>\
                            </center>\
                        </td>\                      
                    </tr>`;
            }
            $('#tableBodyDadosVeiculos').html(htmlVeiculos)
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
                        <td class="tdTexto">${Documento[i]['PLACA']}</td>
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

        function popularListaManutencao(Manutencao){
            var htmlManutencao = "";

            for(i=0; i< Manutencao.length; i++){
                var ManutencaoKeys = Object.keys(Manutencao[i]);
                for(j=0;j<ManutencaoKeys.length;j++){
                    if(Manutencao[i][ManutencaoKeys[j]] == null){
                        Manutencao[i][ManutencaoKeys[j]] = "";
                    }
                }

                if(Manutencao[i]['SITUACAO'] == 'EM ANDAMENTO'){
                    spanBG = "bg-warning";
                } else {
                    spanBG = "bg-success";
                }


                htmlManutencao += `
                    <tr id="tableRow${Manutencao[i]['ID']}">
                        <td class="tdTexto">${Manutencao[i]['PLACA']}</td>
                        <td class="tdTexto">${mascaraFinanceira(Manutencao[i]['VALOR'])}</td>
                        <td class="tdTexto">${moment(Manutencao[i]['DATA']).format('DD/MM/YYYY')}</td>
                        <td class="tdTexto"><span class="badge ${spanBG}" >${Manutencao[i]['SITUACAO']}</span></td>
                        <td class="tdTexto"><center>${Manutencao[i]['ELETRICA']}</center></td>
                        <td class="tdTexto"><center>${Manutencao[i]['MECANICA']}</center></td>
                        <td class="tdTexto"><center>${Manutencao[i]['ESTETICA']}</center></td>
                        <td class="tdTexto"><center>${Manutencao[i]['LATARIA']}</center></td>
                        <td class="tdTexto"><center>${Manutencao[i]['OUTROS']}</center></td>
                        <td>\
                            <center>`;

                                if(Manutencao[i]['SITUACAO'] == 'EM ANDAMENTO'){
                                    htmlManutencao += `                                    
                                <button class="btn" onclick="concluirManutencao(${Manutencao[i]['ID']}, '${Manutencao[i]['PLACA']}')"><i class="fas fa-check"></i></button>`;
                                }

                                htmlManutencao += `
                                <button class="btn" onclick="inativarManutencao(${Manutencao[i]['ID']}, '${Manutencao[i]['PLACA']}')"><i class="fas fa-trash"></i></button>\
                            </center>\
                        </td>\ 
                        
                        <td class="tdTexto"><center>${Manutencao[i]['DESCRICAO']}</center></td>                     
                    </tr>`;
            }
            $('#tableBodyDadosManutencao').html(htmlManutencao)
        }

        function inserirVeiculo() {
            validacao = true;

            var inputIDs = ['inputPlaca', 'inputModelo', 'inputRenavam', 'inputChassis','inputCor', 'inputAno', 'inputKM', 'inputDataCompra', 'inputValorCompra'];

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
                valorCompra = $('#inputValorCompra').val();
                valorCompra = valorCompra.replace('.', '').replace('.', '').replace('.', '').replace('R$','').replace(' ','').replace(',', '.');

                if(inserindoVeiculo){                    
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'placa': $('#inputPlaca').val(),
                        'modelo': $('#inputModelo').val(),
                        'renavam': $('#inputRenavam').val(),
                        'chassis': $('#inputChassis').val(),
                        'cor': $('#inputCor').val(),
                        'ano': $('#inputAno').val(),
                        'km': $('#inputKM').val(),
                        'dataCompra': $('#inputDataCompra').val(),
                        'valorCompra': valorCompra,
                        },
                        url:"{{route('veiculo.inserir')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-cadastro').modal('hide');
                            buscarVeiculo();

                            Swal.fire(
                                'Sucesso!',
                                'Veículo inserido com sucesso.',
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
                        'modelo': $('#inputModelo').val(),
                        'renavam': $('#inputRenavam').val(),
                        'chassis': $('#inputChassis').val(),
                        'cor': $('#inputCor').val(),
                        'ano': $('#inputAno').val(),
                        'km': $('#inputKM').val(),
                        'dataCompra': $('#inputDataCompra').val(),
                        'valorCompra': valorCompra,
                        'ID': $('#inputID').val()
                        },
                        url:"{{route('veiculo.alterar')}}",
                        success:function(r){
                            console.log(r);
                            $('#modal-cadastro').modal('hide');
                            buscarVeiculo();

                            Swal.fire(
                                'Sucesso!',
                                'Veículo alterado com sucesso.',
                                'success',
                            )
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            }
            
        }

        function inserirManutencao(){
            validacao = true;

            var inputIDs = ['inputPlacaManutencao', 'inputDataManutencao', 'inputValorManutencao', 'inputDescricaoManutencao'];

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

            if($('#inputManutencaoIDVeiculo').val() == 0){
                validacao = false;
                $('#inputPlacaManutencao').addClass('is-invalid')
            } else {
                $('#inputPlacaManutencao').removeClass('is-invalid')
            }

            if(validacao){
                valor = $('#inputValorManutencao').val();
                valor = valor.replace('.', '').replace('R$','').replace(' ','').replace(',', '.');

                if($('#checkMecanica').prop('checked')){
                    mecanica = 'S';
                } else {
                    mecanica = 'N';
                }

                if($('#checkEletrica').prop('checked')){
                    eletrica = 'S';
                } else {
                    eletrica = 'N';
                }

                if($('#checkEstetica').prop('checked')){
                    estetica = 'S';
                } else {
                    estetica = 'N';
                }

                if($('#checkLataria').prop('checked')){
                    lataria = 'S';
                } else {
                    lataria = 'N';
                }

                if($('#checkoutros').prop('checked')){
                    outros = 'S';
                } else {
                    outros = 'N';
                }

                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                        '_token':'{{csrf_token()}}',
                        'placa': $('#inputPlacaManutencao').val(),
                        'data': $('#inputDataManutencao').val(),
                        'valor': valor,
                        'descricao': $('#inputDescricaoManutencao').val(),
                        'lataria': lataria,
                        'estetica':estetica,
                        'eletrica': eletrica,
                        'outros':outros,
                        'mecanica':mecanica
                    },
                    url:"{{route('manutencao.inserir')}}",
                    success:function(r){
                        $('#modal-manutencao').modal('hide');
                        buscarVeiculo();
                        Swal.fire(
                            'Sucesso!',
                            'Manutenção inserida com sucesso.',
                            'success',
                        )
                    },
                    error:err=>{exibirErro(err)}
                })
            }

        }

        function inativarVeiculo(idVeiculo){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar o veículo?',
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
                        'id': idVeiculo
                        },
                        url:"{{route('veiculo.inativar')}}",
                        success:function(r){
                            buscarVeiculo();

                            Swal.fire('Sucesso', 'Veículo inativado com sucesso', 'success');
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        function inativarManutencao(idManutencao, placa){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar a manutenção?',
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
                        'id': idManutencao
                        },
                        url:"{{route('manutencao.inativar')}}",
                        success:function(r){
                            console.log('placa inativar', placa)
                            buscarManutencao(placa);

                            Swal.fire('Sucesso', 'Manutenção inativada com sucesso', 'success');
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        function editarVeiculo(idVeiculo){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                '_token':'{{csrf_token()}}',
                'ID': idVeiculo,
                'placa': '',
                'situacao': ''
                },
                url:"{{route('veiculo.buscar')}}",
                success:function(r){
                    inserindoVeiculo = false;

                    $('#inputID').val(r[0]['ID']);
                    $('#inputPlaca').val(r[0]['PLACA']);
                    $('#inputPlaca').prop('disabled', true);
                    $('#inputModelo').val(r[0]['MODELO']);
                    $('#inputRenavam').val(r[0]['RENAVAM']);
                    $('#inputChassis').val(r[0]['CHASSIS']);
                    $('#inputCor').val(r[0]['COR']);
                    $('#inputAno').val(r[0]['ANO']);
                    $('#inputKM').val(r[0]['KM']);
                    $('#inputDataCompra').val(r[0]['DATA_COMPRA']);
                    $('#inputValorCompra').val(r[0]['VALOR_COMPRA'].toFixed(2));

                    $('#modal-cadastro').modal('show')
                },
                error:err=>{exibirErro(err)}
            })
        }

        function concluirManutencao(idManutencao, placa){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja concluir a manutenção?',
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
                        'id': idManutencao
                        },
                        url:"{{route('manutencao.concluir')}}",
                        success:function(r){
                            buscarManutencao(placa);

                            Swal.fire('Sucesso', 'Manutenção inativada com sucesso', 'success');
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        function limparPlacaManutencao(){
            $('#inputPlacaManutencao').val('');
            $('#btnLimparPlaca').addClass('d-none');
            $('#inputPlacaManutencao').attr('disabled', false); 
            $('#inputManutencaoIDVeiculo').val(0) 
        } 

        function cadastarDocumento(placa, ){
            $('#titleDocumento').text(placa);
            $('#inputPlacaDocumentacao').val(placa);

            buscarDocumentos();

            $('#modal-documentacao').modal('show');
        }
        
        function salvarDocumento(){
            if($("#inputArquivoDocumentacao")[0].files.length > 0){
                uploadArquivo();
            } else {
                Swal.fire('Atenção!'
                        , 'Selecione um documento para vincular ao veículo.'
                        , 'error');
            }
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
                        url:"{{route('veiculo.inativar.documento')}}",
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

        function uploadArquivo(){
            var dataAnexo = new FormData();
            anexoCaminho = "";
            placa = $('#inputPlacaDocumentacao').val();
            dataAnexo.append('meuArquivo', document.getElementById('inputArquivoDocumentacao').files[0]);
            dataAnexo.append('ID', placa);

            $.ajax({
                processData: false,
                contentType: false,
                type : 'POST',
                data : dataAnexo,
                url : "{{env('APP_URL')}}/salvarDocumentacaoPessoa.php",
                success : function(resultUpload) {
                    if(resultUpload != "error"){
                        anexoCaminho = resultUpload;
                        $.ajax({
                            type:'post',
                            datatype:'json',
                            data:{
                                '_token':'{{csrf_token()}}',
                                'placa': placa,
                                'caminho': anexoCaminho
                            },
                            url:"{{route('veiculo.inserir.documento')}}",
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

        function validaDocumento(){
            if ($("#inputArquivoDocumentacao")[0].files.length > 0) {
                $('#labelInputArquivoDocumentacao').html($("#inputArquivoDocumentacao")[0].files[0].name);
            } else {
                $('#labelInputArquivoDocumentacao').html('Selecionar Arquivos');
            }
        }        

        function fecharCadastroDocumento(){
            $('#modal-documentacao').modal('hide');
        }

        function verDocumento(caminhoDocumento){
            url = "{{env('APP_URL')}}/"+caminhoDocumento;

            window.open(url, '_blank');
        }

        $("#inputPlacaManutencao").autocomplete({
            source: function(request, cb){
                param = request.term;
                campoBuscado = param;
                $.ajax({
                    url:"{{route('veiculo.buscar')}}",
                    method: 'post',
                    data:{
                        '_token': '{{csrf_token()}}',
                        'placa': param,
                        'situacao':''
                    },
                    dataType: 'json',
                    success: function(r){
                        result = $.map(r, function(obj){
                            return {
                                label: obj.info,
                                value: obj.PLACA,
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
                    $('#inputManutencaoIDVeiculo').val(selectedData.item.data.ID);
                    $('#btnLimparPlaca').removeClass('d-none');
                    $('#inputPlacaManutencao').attr('disabled', true); 
                } else {
                    $('#inputManutencaoIDVeiculo').val(0) 
                }
            }
        });

        $('#btnLimparPlaca').click(() => {
            limparPlacaManutencao();
        });

        $('#btnConfirmar').click(() => {
            inserirVeiculo();
        });

        $('#btnNovoVeiculo').click(() => {
            cadastarVeiculo();
        })

        $('#btnNovaManutencao').click(() => {
            cadastarManutencao();
        })

        $('#btnConfirmarManutencao').click(() => {
            inserirManutencao();
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
            buscarVeiculo();
            $('#modal-cadastro').on('hidden.bs.modal', function () {
               
                $('#inputPlaca').removeClass('is-invalid');
                $('#inputModelo').removeClass('is-invalid');
                $('#inputRenavam').removeClass('is-invalid');
                $('#inputChassis').removeClass('is-invalid');
                $('#inputCor').removeClass('is-invalid');
                $('#inputAno').removeClass('is-invalid');
                $('#inputKM').removeClass('is-invalid');
                $('#inputDataCompra').removeClass('is-invalid');
                $('#inputValorCompra').removeClass('is-invalid');
                buscarVeiculo();
            });
        })
    </script>
@stop
