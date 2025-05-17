@extends('adminlte::page')

@section('title', 'Documentos')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-3 d-none d-md-block">
                <h1>Documentos</h1>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-body m-0 p-0">
                    <div class="card m-0 p-0">
                        <div class="card-header">
                            <div class="row d-flex">
                                <div class="col-12 d-flex justify-content-end">
                                    <button class="btn btn-info col-3" id="btnAdicionarArquivoProjeto">
                                        <i class="fas fa-plus"></i> Arquivo
                                    </button>
                                    <button class="btn btn-primary col-3" id="btnAdicionarPasta">
                                        <i class="fas fa-plus"></i> Pasta
                                    </button>
                                </div>

                                {{-- Matheus 04/05/2025 20:08:06 - CAMINHO DA DOCUMENTACAO --}}
                                <ol class="breadcrumb float-sm-right" id="olTipoArquivo">                                    
                                </ol>
                                <div class="col-12 d-none">
                                    <input type="hidden" id="inputIDProjeto">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="inputArquivoDocumentacao" onchange="validaDocumento()">
                                            <label class="custom-file-label" for="inputArquivoDocumentacao" id="labelInputArquivoDocumentacao">Selecionar Arquivos</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body m-0 p-0">
                            <div class="col-12">
                                <table class="table table-responsive-xs">
                                    <tbody id="tableBodyDocumentos">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <div class="modal fade" id="modal-pasta" style="display: none;" aria-hidden="true"> 
        <div class="modal-dialog"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h4 class="modal-title">Adicionar Pasta</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">×</span> 
                    </button> 
                </div> 
                <div class="modal-body"> 
                    <div class="form-group col-12">
                        <label>Nome da Pasta</label>
                        <input type="text" id="inputNomePasta" class="form-control col-12" placeholder="Nome da Pasta">
                    </div> 
                </div> 
                <div class="modal-footer justify-content-between"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
                    <button type="button" class="btn btn-primary" id="btnSalvarPasta">Salvar</button> 
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
        var idPastaAtual = 0;
        var tipoArquivoBusca = 0;
        // DOCUMENTOS
            function buscarCaminhoAtual(){
                editar = false;
                $.ajax({
                    type:'post',
                    datatype:'json',
                    data:{
                        '_token':'{{csrf_token()}}',
                        'ID_PASTA_ATUAL': idPastaAtual,
                        'ID_PROJETO': $('#inputIDProjeto').val(),
                    },
                    url:"{{route('empresa.documento.buscar.caminho')}}",
                    success:function(r){
                        popularCaminhoAtual(r.dados);
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
                        'ID_TIPO': tipoArquivoBusca,
                        'ID_PASTA_ATUAL': idPastaAtual
                    },
                    url:"{{route('empresa.documento.buscar')}}",
                    success:function(r){
                        popularListaDocumentos(r.dados);
                    },
                    error:err=>{exibirErro(err)}
                })
            }  

            function popularCaminhoAtual(dados){
                var htmlTabela = ``;
                var olTipoArquivo = ``;

                if(idPastaAtual > 0){
                    olTipoArquivo = `<li class="breadcrumb-item">
                                        <span style="cursor: pointer; text-decoration: underline" onclick="acessarPasta(0)">Geral</span>
                                    </li>`;
                }

                for(i=0; i< dados.length; i++){
                    var materialKeys = Object.keys(dados[i]);
                    for(j=0;j<materialKeys.length;j++){
                        if(dados[i][materialKeys[j]] == null){
                            dados[i][materialKeys[j]] = "";
                        }
                    }
                    var styleMenu = 'cursor: pointer; text-decoration: underline';
                    var funcaoMenu = `acessarPasta(${dados[i]['ID']})`;

                    if((i+1) == dados.length ){
                        styleMenu = '';
                        funcaoMenu = '';
                    }

                    olTipoArquivo += ` <li class="breadcrumb-item">
                                            <span style="${styleMenu}" onclick="${funcaoMenu}">${dados[i]['NOME']}</span>
                                        </li>`;

                }

                $('#olTipoArquivo').html(olTipoArquivo);
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
                    
                    var icone = 'fas fa-file';
                    var documentoCaminho = Documento[i]['CAMINHO_DOCUMENTO'].split('/')[3];
                    var funcao = `verDocumento('${Documento[i]['CAMINHO_DOCUMENTO']}')`;
                    var funcaoInativar = `inativarDocumento(${Documento[i]['ID']})`;
                    var classes = `text-decoration: underline;`;
                    var classeIcone = '';

                    if(Documento[i]['TIPO'] == 1){
                        icone = 'fas fa-folder-open';
                        documentoCaminho = Documento[i]['CAMINHO_DOCUMENTO'];
                        funcao = `acessarPasta(${Documento[i]['ID']})`;
                        funcaoInativar = `inativarPasta(${Documento[i]['ID']})`;
                        classes = '';
                        classeIcone = 'color: #f3c625;';
                    }

                    htmlDocumento += `
                        <tr id="tableRow${Documento[i]['ID']}">
                            <td class="tdTexto"><i onclick="${funcao}" style="${classeIcone}" class="${icone}"></i></td>
                            <td class="tdTexto">
                                <span style="${classes} cursor: pointer;" onclick="${funcao}">${documentoCaminho}</span>
                            </td>
                            <td>\
                                <center>\
                                    <button class="btn" onclick="${funcaoInativar}"><i class="fas fa-trash"></i></button>\
                                </center>\
                            </td>\                      
                        </tr>`;
                    }
                $('#tableBodyDocumentos').html(htmlDocumento)
            }

            function cadastarDocumento(idProjeto, descricaoProjeto, buscar = false){
                idPastaAtual = 0;
                buscarDetalhesProjetoSelecionado = buscar;

                $('#titleDocumento').text(descricaoProjeto);
                $('#inputIDProjeto').val(idProjeto);

                buscarCaminhoAtual();
                buscarDocumentos();

                $('#modal-documentacao').modal('show');
                
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
                idProjeto = $('#inputIDProjeto').val();
                dataAnexo.append('meuArquivo', document.getElementById('inputArquivoDocumentacao').files[0]);
                dataAnexo.append('ID', idProjeto);

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
                                    'ID_PROJETO': idProjeto,
                                    'caminho': anexoCaminho,
                                    'ID_TIPO': idPastaAtual
                                },
                                url:"{{route('empresa.documento.inserir')}}",
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

            function verDocumento(caminhoDocumento){
                url = "{{env('APP_URL')}}/"+caminhoDocumento;

                window.open(url, '_blank');
            }

            function validaDocumento(){
                if ($("#inputArquivoDocumentacao")[0].files.length > 0) {
                    $('#labelInputArquivoDocumentacao').html($("#inputArquivoDocumentacao")[0].files[0].name);
                } else {
                    $('#labelInputArquivoDocumentacao').html('Selecionar Arquivos');
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
                            url:"{{route('empresa.documento.inativar')}}",
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

            function inativarPasta(idPasta){
                Swal.fire({
                    title: 'Deseja realmente inativar a pasta?',
                    text: 'TODOS os arquivos e pasta dentro dela serão inativados também.',
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
                                'idPasta': idPasta
                            },
                            url:"{{route('empresa.pasta.inativar')}}",
                            success:function(r){
                                dispararAlerta('success', 'Pasta inativada com sucesso!');
                                buscarDocumentos();
                            },
                            error:err=>{exibirErro(err)}
                        })
                    }
                });
            }  

            function adicionarPasta(){
                $('#modal-documentacao').modal('hide');

                $('#inputNomePasta').val('');

                $('#modal-pasta').modal('show');
            }

            function acessarPasta(idPasta){
                idPastaAtual = idPasta;
                buscarDocumentos();
                buscarCaminhoAtual();
            }

            function salvarPasta(){
                var validacao = true;
                var nomePasta = $('#inputNomePasta').val();

                if(nomePasta.length == 0){
                    dispararAlerta('warning', 'Informe um nome para a pasta!');
                    validacao = false;
                }

                if(validacao){
                    $.ajax({
                        type:'post',
                        datatype:'json',
                        data:{
                        '_token':'{{csrf_token()}}',
                        'NOME': nomePasta,
                        'ID_PASTA_PAI': idPastaAtual,
                        'ID_DOCUMENTO': $('#inputIDProjeto').val(),
                        },
                        url:"{{route('empresa.pasta.inserir')}}",
                        success:function(r){
                            buscarCaminhoAtual();

                            dispararAlerta('success', 'Pasta salva com sucesso!');

                            $('#modal-pasta').modal('hide');

                            buscarDocumentos();
                        },
                        error:err=>{exibirErro(err)}
                    })
                }
            }

            $('#btnAdicionarArquivoProjeto').on('click', () => {
                $('#inputArquivoDocumentacao').click();
            });

            $('#inputArquivoDocumentacao').on('change', () => {
                uploadArquivo();
            })

            $('#btnAdicionarPasta').on('click', () => {
                adicionarPasta();
            });

            $('#btnSalvarPasta').on('click', () => {
                salvarPasta();
            });
        // FIM

        $(document).ready(function() {
            buscarCaminhoAtual();
            buscarDocumentos();
        })
    </script>
@stop