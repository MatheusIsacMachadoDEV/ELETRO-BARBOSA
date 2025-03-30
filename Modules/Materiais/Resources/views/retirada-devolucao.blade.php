@extends('adminlte::page')

@section('title', 'Retirada/Devolução | GSSoftware')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Retirada/Devolução</h1>
            </div>
            <div class="col-12 col-md-9 d-flex justify-content-end p-2">
                <div class="col-12 col-md-6 row d-flex d-flex justify-content-end ">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-block btn-warning" id="btnNovo">
                            <i class="fas fa-exchange-alt"></i>
                            <span class="ml-1">Nova Devolução</span>
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
                        <input type="text" class="form-control form-control-border col" id="inputFiltro" placeholder="Material/Responsável" onkeyup="buscarDados()">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-responsive-xs">
                    <thead>
                        <th class="d-none d-lg-table-cell" style="padding-left: 5px!important">Material</th>
                        <th class="d-none d-lg-table-cell"><center>Responsável</center></th>
                        <th class="d-none d-lg-table-cell"><center>Data</center></th>
                        <th class="d-none d-lg-table-cell"><center>Tipo</center></th>
                        <th class="d-none d-lg-table-cell"><center>Ações</center></th>
                    </thead>
                    <tbody id="tableBodyDados">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modal-cadastro" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Registrar Retirada/Devolução</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <div class="row d-flex p-0 m-0">
                        <div class="col">
                            <div class="input-group mb-3 col-12">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" id="btnQRCODE"><i class="fas fa-qrcode"></i></span>
                                </div>
                                <input type="text" class="form-control form-control-border col-12" placeholder="Equipamento" id="inputDevolucaoEquipamento" >
                                <input type="hidden" id="inputDevolucaoIDEquipamento">
                            </div>

                            <div class="col-12" id="divImagem">
                                <input type="file" id="fileInput" accept="image/*" style="display: none;">
                                <div id="cameraContainer" style="width: 100%; max-width: 500px; margin: auto; display: none;">
                                    <button id="stopScan">Parar</button>
                                    <video id="video" style="width: 100%;" playsinline autoplay></video>
                                </div>
                                <canvas id="canvas" style="display: none;"></canvas>
                            </div>
                        </div>

                        <div class="col btnLimpar d-none">
                            <button id="btnLimpar" class="btnLimpar btn btn-sm btn-danger d-none col-12"><i class="fas fa-eraser"></i>LIMPAR</button>
                        </div>

                        <div class="col-12 row d-flex p-0 m-0">
                            <div class="col">
                                <input type="text" class="form-control form-control-border col-12" placeholder="Responsável" id="inputDevolucaoPessoa" >
                                <input type="hidden" id="inputDevolucaoIDPessoa">                                
                            </div>
                            <div class="col btnLimparPessoa d-none">
                                <button id="btnLimparPessoa" class="btnLimparPessoa btn btn-sm btn-danger d-none col-12"><i class="fas fa-eraser"></i>LIMPAR</button>
                            </div>
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <input type="datetime-local" class="form-control form-control-border" id="inputDevolucaoData" value="{{date('Y-m-d H:i')}}" >
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <select id="selectDevolucaoTipo" class="form-control form-control-border">
                                <option value="0">Tipo Movimentação</option>
                                <option value="2">Retirada</option>
                                <option value="1">Devolução</option>
                            </select>
                        </div>
                    </div>
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
                    <button type="button" class="btn btn-primary" id="btnSalvarRetiradaDevolucao">Salvar</button> 
                </div> 
            </div> 
        </div> 
    </div> 

    <div class="modal fade" id="modal-documentacao">
        <div class="modal-dialog ">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" >Documentação dados <span id="titleDocumento"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="">
                                <input type="hidden" id="inputPlacaDocumentacao">
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
        #cameraContainer {
            position: relative;
            width: 100%;
            max-width: 500px;
            margin: auto;
            display: none;
        }
        #stopScan {
            position: absolute;
            top: 10px;
            right: 10px;
            background: red;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            z-index: 10;
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
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        $('#inputValor').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputQtde').mask('000000000');
        let stream;
        let scanning = false;
        var idFuncionario = '{{$idFuncionario}}';
        var nomeFuncionario = '{{$nomeFuncionario}}';


        function exibirModalCadastro(){
            resetarCampos();

            if(idFuncionario != '' && nomeFuncionario != ''){
                
                $('#inputDevolucaoPessoa').val(nomeFuncionario);
                $('#inputDevolucaoIDPessoa').val(idFuncionario);
                $('#inputDevolucaoPessoa').attr('disabled', true); 
                $('.btnLimparPessoa').removeClass('d-none');
            }
            $('#modal-cadastro').modal('show');
        }

        function buscarDados(){
            editar = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'FILTRO_BUSCA': $('#inputFiltro').val()
                },
                url:"{{route('material.buscar.movimento')}}",
                success:function(r){
                    popularListaDados(r.dados);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularListaDados(dados){
            var htmlTabela = "";
            for(i=0; i< dados.length; i++){
                var Keys = Object.keys(dados[i]);
                for(j=0;j<Keys.length;j++){
                    if(dados[i][Keys[j]] == null){
                        dados[i][Keys[j]] = "";
                    }
                }

                var tipoMovimento = 'Retirada';
                var classeBadgeTipoMovimento = 'bg-warning';

                if(dados[i]['TIPO_MOVIMENTO'] == 1){
                    tipoMovimento = 'Devolução';
                    classeBadgeTipoMovimento = 'bg-success';
                }

                btnAcoes = ` <button class="btn" onclick="inativarMovimentacao(${dados[i]['ID']})"><i class="fas fa-trash"></i></button>`;
                htmlTabela += `
                    <tr id="tableRowdados" class="d-none d-lg-table-row">
                        <td style="padding-left: 5px!important">${dados[i]['EQUIPAMENTO']}</td>
                        <td><center>${dados[i]['PESSOA']}</center></td>
                        <td><center>${moment(dados[i]['DATA']).format('DD/MM/YYYY HH:mm')}</center></td>
                        <td>
                            <center>
                                <span class="badge ${classeBadgeTipoMovimento}">${tipoMovimento}</span>
                            </center>
                        </td>
                        <td>
                            <center>
                                ${btnAcoes}
                            </center>
                        </td>
                    </tr>
                
                    <tr id="tableRow${dados[i]['ID']}" class="d-table-row d-lg-none">
                        <td>
                            <div class="col-12">
                                <center>
                                    <b>${dados[i]['EQUIPAMENTO']} = ${dados[i]['PESSOA']}</b>
                                </center>
                            </div>
                            <div class="col-12" style="font-size: 4vw">
                                <center>
                                    <span class="badge bg-info">${moment(dados[i]['DATA']).format('DD/MM/YYYY HH:mm')}</span>
                                    <span class="badge ${classeBadgeTipoMovimento}">${tipoMovimento}</span>
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
            $('#tableBodyDados').html(htmlTabela);
        }

        function inserirRetiradaDevolucao() {
            validacao = true;

            var inputIDs = ['inputDevolucaoData', 'inputDevolucaoEquipamento', 'inputDevolucaoIDEquipamento', 'inputDevolucaoPessoa', 'selectDevolucaoTipo', 'inputDevolucaoIDPessoa'];

            for (var i = 0; i < inputIDs.length; i++) {
                var inputID = inputIDs[i];
                var input = $('#' + inputID);
                
                if (input.val() === '' || input.val() == '0' || inputID == 'selectDevolucaoTipo' && input.val() == 0) {
                    if(inputID == 'inputDevolucaoIDEquipamento'){
                        $('#inputDevolucaoEquipamento').addClass('is-invalid');
                    }else if(inputID == 'inputDevolucaoIDPessoa') {
                        $('#inputDevolucaoPessoa').addClass('is-invalid');
                    } else {
                        input.addClass('is-invalid');
                    }
                    validacao = false;
                } else {
                    input.removeClass('is-invalid');
                }
            }

            if(validacao){
                var data = $('#inputDevolucaoData').val();
                var pessoa = $('#inputDevolucaoPessoa').val();
                var idPessoa = $('#inputDevolucaoIDPessoa').val();
                var equipamento = $('#inputDevolucaoEquipamento').val();
                var idEquipamento = $('#inputDevolucaoIDEquipamento').val();
                var tipo = $('#selectDevolucaoTipo').val();

                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                        '_token':'{{csrf_token()}}',
                        'data': data,
                        'pessoa': pessoa,
                        'idPessoa': idPessoa,
                        'equipamento': equipamento,
                        'idEquipamento': idEquipamento,
                        'tipo': tipo
                    },
                    url:"{{route('material.inserir.devolucao')}}",
                    success:function(r){
                        Swal.fire(
                            'Sucesso!',
                            'Retirada cadastrada com sucesso.',
                            'success',
                        );
                        $('#modal-cadastro').modal('hide');
                        buscarDados();
                    },
                    error:err=>{exibirErro(err)}
                    })
            }
            
        }

        function inativarMovimentacao(idMovimentacao){
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja inativar a movimentação?',
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
                            'ID': idMovimentacao
                        },
                        url:"{{route('material.inativar.movimentacao')}}",
                        success:function(r){
                            Swal.fire('Sucesso!'
                                    , 'Movimentação inativada com sucesso.'
                                    , 'success');
                            buscarDados();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            });
        }

        function resetarCampos(){
            $('#inputDevolucaoIDEquipamento').val('0')
            $('#inputDevolucaoEquipamento').val('')
            $('#inputDevolucaoEquipamento').removeClass('is-invalid');
            $('#inputDevolucaoEquipamento').attr('disabled', false); 
            
            $('#inputDevolucaoIDPessoa').val('0')
            $('#inputDevolucaoPessoa').val('')
            $('#inputDevolucaoPessoa').removeClass('is-invalid');
            $('#inputDevolucaoPessoa').attr('disabled', false);
            
            $('#selectDevolucaoTipo').val('0')
            $('#selectDevolucaoTipo').removeClass('is-invalid');
            
            $('#inputDevolucaoData').removeClass('is-invalid');
            $('#inputDevolucaoData').val(moment().format('YYYY-MM-DD HH:mm'))

            
            limparCampo('inputDevolucaoEquipamento', 'inputDevolucaoIDEquipamento', 'btnLimpar');
            limparCampo('inputDevolucaoPessoa', 'inputDevolucaoIDPessoa', 'btnLimparPessoa');


            $('.btnLimpar').addClass('d-none');
        }

        function scanQRCode() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(function(s) {
                    stream = s;
                    scanning = true;
                    let video = document.getElementById('video');
                    let cameraContainer = document.getElementById('cameraContainer');
                    cameraContainer.style.display = 'block';
                    video.srcObject = stream;
                    video.setAttribute("playsinline", true);
                    video.play();
                    let canvas = document.getElementById('canvas');
                    let context = canvas.getContext('2d');
                    
                    function tick() {
                        if (!scanning) return;
                        if (video.readyState === video.HAVE_ENOUGH_DATA) {
                            canvas.width = video.videoWidth;
                            canvas.height = video.videoHeight;
                            context.drawImage(video, 0, 0, canvas.width, canvas.height);
                            let imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                            let code = jsQR(imageData.data, canvas.width, canvas.height);
                            if (code) {
                                Swal.fire(
                                    'CODIGO LIDO QR CODE',
                                    code.data,
                                    'success',
                                );
                                $('#inputDevolucaoEquipamento').val(code.data);
                                setTimeout(() => { $('#inputDevolucaoEquipamento').autocomplete("search"); }, 500);
                                stopScan();
                                return;
                            }
                        }
                        requestAnimationFrame(tick);
                    }
                    setTimeout(() => requestAnimationFrame(tick), 500);
                }).catch(() => {
                    $('#fileInput').click();
                });
            } else {
                $('#fileInput').click();
            }
        }
        
        function stopScan() {
            scanning = false;
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            document.getElementById('cameraContainer').style.display = 'none';
        }

        $('#btnNovo').click(() => {
            exibirModalCadastro();
        });

        $('#btnLimpar').click(() => {
            limparCampo('inputDevolucaoEquipamento', 'inputDevolucaoIDEquipamento', 'btnLimpar');
        });

        $('#btnLimparPessoa').click(() => {
            limparCampo('inputDevolucaoPessoa', 'inputDevolucaoIDPessoa', 'btnLimparPessoa');
        });
        
        $('#btnLimpar').click(() => {
            limparCampo('inputDevolucaoEquipamento', 'inputDevolucaoIDEquipamento', 'btnLimpar');
        });

        $('#btnQRCODE').click(() => {
            scanQRCode();
        });

        $('#stopScan').click(() => {
            stopScan();
        });

        $('#btnSalvarRetiradaDevolucao').click(() => {
            inserirRetiradaDevolucao();
        });

        $("#inputDevolucaoEquipamento").autocomplete({
            source: function(request, cb){
                param = request.term;
                campoBuscado = param;
                $.ajax({
                    url:"{{route('material.busca')}}",
                    method: 'post',
                    data:{
                        '_token': '{{csrf_token()}}',
                        'filtro': param,
                        'tipo_material': 1
                    },
                    dataType: 'json',
                    success: function(r){
                        result = $.map(r.dados, function(obj){
                            return {
                                label: obj.info,
                                value: obj.MATERIAL,
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
                    $('#inputDevolucaoEquipamento').val(selectedData.item.data.MATERIAL);
                    $('#inputDevolucaoIDEquipamento').val(selectedData.item.data.ID);
                    $('#inputDevolucaoEquipamento').attr('disabled', true); 
                    $('.btnLimpar').removeClass('d-none');
                    $('#btnQRCODE').addClass('d-none');
                } else {
                    limparCampo('inputDevolucaoEquipamento', 'inputDevolucaoIDEquipamento', 'btnLimpar');
                }
            }
        });

        $("#inputDevolucaoPessoa").autocomplete({
            source: function(request, cb){
                param = request.term;
                campoBuscado = param;
                $.ajax({
                    url:"{{route('pessoa.buscar')}}",
                    method: 'post',
                    data:{
                        '_token': '{{csrf_token()}}',
                        'FILTRO_BUSCA': param,
                        'ID_TIPO': 2
                    },
                    dataType: 'json',
                    success: function(r){
                        result = $.map(r.dados, function(obj){
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
                if (selectedData.item.label != 'Nenhum Resultado Encontrado.'){
                    $('#inputDevolucaoPessoa').val(selectedData.item.data.NOME);
                    $('#inputDevolucaoIDPessoa').val(selectedData.item.data.ID_USUARIO);
                    $('#inputDevolucaoPessoa').attr('disabled', true); 
                    $('.btnLimparPessoa').removeClass('d-none');
                } else {
                    limparCampo('inputDevolucaoPessoa', 'inputDevolucaoIDPessoa', 'btnLimparPessoa');
                }
            }
        });
        
        $('#fileInput').change(function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function() {
                    let img = new Image();
                    img.onload = function() {
                        let canvas = document.getElementById('canvas');
                        let context = canvas.getContext('2d');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        context.drawImage(img, 0, 0);
                        let imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                        let code = jsQR(imageData.data, canvas.width, canvas.height);
                        if (code) {
                            $('#inputDevolucaoEquipamento').val(code.data);
                            setTimeout(() => { $('#inputDevolucaoEquipamento').trigger('focusout'); }, 500);
                        }
                    };
                    img.src = reader.result;
                };
                reader.readAsDataURL(file);
            }
        });

        $(document).ready(function() {
            buscarDados();
        })
    </script>
@stop