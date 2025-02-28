@extends('adminlte::page')

@section('title', 'Veículos')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block"">
                <h1>Faturamento</h1>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header pb-2 m-0">
                <div class="row d-flex p-0">
                    <div class="input-group col-md-6 col-sm-12 mb-1">
                        <input type="text" id="inputPlacaBusca" class="form-control" placeholder="Placa" onkeyup="buscarFaturamento()" maxlength="8">
                    </div>
                    <div class="input-group col-md-6 col-sm-12 p-0">
                        <div class="input-group col-md-5 col-sm-12">
                            <input type="date" class="form-control" id="inputDataInicio" onchange="buscarFaturamento()">
                        </div>
                        <label class="col-2 display-content-center">Até</label>
                        <div class="input-group col-md-5 col-sm-6">
                            <input type="date" class="form-control" id="inputDataFim" onchange="buscarFaturamento()">
                        </div>
                    </div>
                </div>                 
            </div>
            <div class="card-body">
                <table class="table table-responsive-sm">
                    <thead>
                        <tr>
                            <th>Placa</th>
                            <th class="d-none d-md-table-cell">Comprador</th>
                            <th class="d-none d-md-table-cell">Data Compra</th>
                            <th>Valor Compra</th>
                            <th>Valor Venda</th>
                            <th>Valor Gastos</th>
                            <th>Lucro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyDadosFaturamento">
                    </tbody>
                </table>
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
                        <div class="card-body">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Veiculo</th>
                                        <th>Documento</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyDocumentos">
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                                <th><center>Descrição</center></th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyDadosManutencao">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{env('APP_URL')}}/main.css">
    <style>
        .table{
            background-color: #f8f9fa;
        }
    </style>
@stop

@section('footer')
    <center>
        <span id="spnVersao">
            Desenvolvido por <a href="https://gssoftware.app.br/" target="_blank">GSSoftware</a>
        </span>
    </center>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>     

        function buscarFaturamento(){
            placa = $('#inputPlacaBusca').val();
            dataInicio = $('#inputDataInicio').val();
            dataFim = $('#inputDataFim').val();

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'placa': placa,
                    'dataInicio':dataInicio,
                    'dataFim': dataFim
                },
                url:"{{route('faturamento.buscar')}}",
                success:function(r){
                    popularListaFaturamento(r);
                },
                error:err=>{exibirErro(err)}
            })
        }

        function popularListaFaturamento(dados){
            Faturamento = dados.dados;
            total = dados.total;

            var htmlFaturamento = "";

            for(i=0; i< Faturamento.length; i++){
                var FaturamentoKeys = Object.keys(Faturamento[i]);
                for(j=0;j<FaturamentoKeys.length;j++){
                    if(Faturamento[i][FaturamentoKeys[j]] == null){
                        Faturamento[i][FaturamentoKeys[j]] = "";
                    }
                }

                if(Faturamento[i]['LUCRO'] <= 0){
                    spanBG = "bg-warning";
                }  else {
                    spanBG = "bg-success";
                }

                htmlFaturamento += `
                    <tr id="tableRow${Faturamento[i]['ID']}">
                        <td class="tdTexto">${Faturamento[i]['PLACA']}</td>
                        <td class="tdTexto d-none d-md-table-cell">${Faturamento[i]['COMPRADOR']}</td>
                        <td class="tdTexto d-none d-md-table-cell">${moment(Faturamento[i]['DATA']).format('DD/MM/YYYY')}</td>
                        <td class="tdTexto">${mascaraFinanceira(Faturamento[i]['VALOR_COMPRA'])}</td>
                        <td class="tdTexto">${mascaraFinanceira(Faturamento[i]['VALOR_VENDA'])}</td>
                        <td class="tdTexto">${mascaraFinanceira(Faturamento[i]['GASTOS'])}</td>
                        <td class="tdTexto"><span class="badge ${spanBG}">${mascaraFinanceira(Faturamento[i]['LUCRO'])}</span></td>
                        <td>\
                            <button class="btn" onclick="buscarManutencao('${Faturamento[i]['PLACA']}')"><i class="fas fa-cogs"></i></button>\
                            <button class="btn" onclick="buscarDocumentos(${Faturamento[i]['ID']})"><i class="fas fa-file-alt"></i></button>\
                        </td>\                      
                    </tr>`;
            }

            htmlFaturamento += `
                    <tr id="tableRowTotal">\
                        <td class="d-none d-md-table-cell"></td>\
                        <td class="d-none d-md-table-cell"></td>\
                        <td>TOTAL:</td>\
                        <td><span class="badge bg-info">${mascaraFinanceira(total[0]['COMPRAS'])}</span></td>\
                        <td><span class="badge bg-warning">${mascaraFinanceira(total[0]['VENDAS'])}</span></td>\
                        <td><span class="badge bg-danger">${mascaraFinanceira(total[0]['GASTOS'])}</span></td>\
                        <td><span class="badge bg-success">${mascaraFinanceira(total[0]['LUCRO'])}</span></td>\
                        <td></td>\
                    </tr>`;

            $('#tableBodyDadosFaturamento').html(htmlFaturamento)
        }  

        function buscarDocumentos(idVenda){
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',                    
                    'idVenda': idVenda,
                },
                url:"{{route('venda.buscar.documentos')}}",
                success:function(r){
                    popularListaDocumentos(r);
                    $('#modal-documentacao').modal('show');
                },
                error:err=>{exibirErro(err)}
            })
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
                        </tr>`;
                }
            $('#tableBodyDocumentos').html(htmlDocumento)
        }   

        function buscarManutencao(placa){
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
                        <td class="tdTexto"><center>${Manutencao[i]['DESCRICAO']}</center></td>                     
                    </tr>`;
            }
            $('#tableBodyDadosManutencao').html(htmlManutencao)
        }

        function verDocumento(caminhoDocumento){
            url = "{{env('APP_URL')}}/"+caminhoDocumento;

            window.open(url, '_blank');
        }

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
            console.log(err)

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
        }
        
        $(document).ready(() => {
            buscarFaturamento();
            $('#inputDataInicio').val(moment(new Date()).subtract(1, 'month').format('YYYY-MM-DD'));
            $('#inputDataFim').val(moment(new Date()).format('YYYY-MM-DD'));            
        })
    </script>
@stop