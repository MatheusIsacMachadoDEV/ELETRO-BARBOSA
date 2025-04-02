@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4"># Obras em Andamento</h1>

    <div class="row">
        <!-- Seção de Obras -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0">## Obra</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">- Continental</li>
                        <li class="list-group-item">- Ulbner</li>
                        <li class="list-group-item">- Tetra Pak</li>
                        <li class="list-group-item">- Prefeitura</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Seção de Progresso -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h2 class="h5 mb-0">## Progresso</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">- Valor</li>
                        <li class="list-group-item">- Gastos</li>
                        <li class="list-group-item">- Cargil</li>
                        <li class="list-group-item">- Continental</li>
                        <li class="list-group-item">- Tetra Pak</li>
                        <li class="list-group-item">- Prefeitura</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção Gastos X Lucros -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h2 class="h5 mb-0">## Gastos X Lucros</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Projeto</th>
                            <th>Valor</th>
                            <th>Gastos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox"> Cargil</td>
                            <td>R$12.520,00</td>
                            <td class="text-success">↑ R$1.520,00</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"> Continental</td>
                            <td>R$11.521,00</td>
                            <td class="text-danger">↓ R$998,23</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"> Tetra Pak</td>
                            <td>R$1.230,25</td>
                            <td class="text-danger">↓ R$123,00</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"> Prefeitura</td>
                            <td>R$35.562,00 USD</td>
                            <td class="text-success">↑ R$5.562,23</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Seção Projetos Fechados -->
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h2 class="h5 mb-0">## PROJETOS FECHADOS</h2>
        </div>
        <div class="card-body text-center">
            <h3 class="text-primary">2.17%</h3>
            <p class="lead">$35,230.43</p>
            <h4 class="text-muted">PROJETOS ENTREGUES</h4>
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
        .card {
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card-header {
            font-weight: bold;
        }
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
        .table th {
            border-top: none;
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
        $('#inputValor').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputQtde').mask('000000000');
        function exibirModalCadastro(){
            resetarCampos();
            buscarMarca();
            $('#modal-cadastro').modal('show');
        }
        function buscarDados(){
            editar = false;
            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                    '_token':'{{csrf_token()}}',
                    'filtro': $('#inputDescricaoFiltro').val()
                },
                url:"",
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
                btnAcoes = ` <button class="btn" onclick="exibirModalEdicao(dados)"><i class="fas fa-pen"></i></button>
                             <button class="btn" onclick="inativar(dados)"><i class="fas fa-trash"></i></button>`;
                htmlTabela += `
                    <tr id="tableRowdados" class="d-none d-lg-table-row">
                        <td class="tdTexto" style="padding-left: 5px!important">dados</td>
                        <td class="tdTexto">dados</td>
                        <td>
                            <center>
                                btnAcoes
                            </center>
                        </td>
                    </tr>
                `;
            }
            $('#tableBodyDadosdados').html(htmlTabela);
        }
    </script>
@stop