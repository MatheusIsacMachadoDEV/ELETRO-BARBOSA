@extends('adminlte::page')

@section('title', 'GSSoftware - Pagamento de Funcionários')

@section('content')
    <div class="content-title">
        <div class="row d-flex">
            <div class="col-6 d-none d-md-block">
                <h1>Folha Pagamento</h1>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-end p-2">
                <div class="col-12 col-md-6 row d-flex d-flex justify-content-end">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-block btn-warning" id="btnNovoPagamento">
                            <i class="fas fa-plus"></i>
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
                        <input type="text" class="form-control form-control-border" id="inputFiltro" placeholder="Filtro" onkeyup="buscarDados()">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Funcionário</th>
                            <th><center>Data Agendada</th>
                            <th><center>Valor</th>
                            <th><center>Horas</th>
                            <th><center>Status</th>
                            <th><center>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyPagamentos">
                        <!-- Dados serão carregados aqui -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPagamento" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPagamentoLabel">Cadastrar Pagamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row d-flex">
                    <div class="form-group col-12 col-md-3">
                        <label>Funcionário</label>
                        <select class="form-control" id="selectPessoa" required>
                            <option value="">Selecione um funcionário</option>
                            <!-- Opções serão preenchidas via JavaScript -->
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label>Data de Agendamento</label>
                        <input type="datetime-local" class="form-control col-12" id="inputDataAgendamento" required>
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label>Período</label>
                        <select class="form-control form-control-border" id="selectPeriodoPagamento">
                            <option value="1">Janeiro</option>
                            <option value="2">Fevereiro</option>
                            <option value="3">Março</option>
                            <option value="4">Abril</option>
                            <option value="5">Maio</option>
                            <option value="6">Junho</option>
                            <option value="7">Julho</option>
                            <option value="8">Agosto</option>
                            <option value="9">Setembro</option>
                            <option value="10">Outubro</option>
                            <option value="11">Novembro</option>
                            <option value="12">Dezembro</option>
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label>Salário Base</label>
                        <input type="text" class="form-control" placeholder="Salário Base" id="inputSalarioBase" required>
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label>Horas Mensais</label>
                        <input type="text" step="0.01" class="form-control" id="inputHorasMensais" placeholder="Horas mensais">
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label>Horas Registradas</label>
                        <input type="text" step="0.01" class="form-control" id="inputHorasRegistradas" placeholder="Horas Registradas">
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label>Horas Extras</label>
                        <input type="text" step="0.01" class="form-control" id="inputHorasExtras" placeholder="Horas Extras">
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label>Valor Diárias</label>
                        <input type="text" class="form-control" id="inputValorDiarias" placeholder="Valor Diárias" required>
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label>Valor Horas Extras</label>
                        <input type="text" class="form-control" id="inputValorHorasExtras" placeholder="Valor Horas Extras" required>
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label>Valor Descontos</label>
                        <input type="text" class="form-control" id="inputValorDescontos" placeholder="Valor Descontos" required>
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label>Desconto INSS</label>
                        <input type="text" class="form-control" id="inputValorDescontosINSS" placeholder="Desconto INSS" required>
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label>Valor Pagamento</label>
                        <input type="text" class="form-control" id="inputValor" placeholder="Valor Pagamento" disabled>
                    </div>
                    <div class="form-group col-12">
                        <label>Observação</label>
                        <textarea class="form-control" id="inputObservacao" rows="2" placeholder="Observações"></textarea>
                    </div>
                    <input type="hidden" id="inputIDPagamento" value="0">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarPagamento">Salvar</button>
                </div>
            </div>
        </div>
    </div>
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
        $('#inputSalarioBase').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputValorDiarias').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputValorHorasExtras').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputValorDescontos').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputValorDescontosINSS').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        $('#inputValor').maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',' , allowZero: true});
        var timeoutValorAtualizacao = 0;

        function buscarDados() {
            $.ajax({
                type: 'post',
                url: "{{ route('pagamento.buscar') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'filtro': $('#inputFiltro').val()
                },
                success: function(response) {
                    popularTabela(response.dados);
                },
                error: err => {
                    exibirErro(err);
                }
            });
        }

        function popularTabela(dados) {
            let html = '';
            dados.forEach(pagamento => {
                const dataAgendamento = moment(pagamento.DATA_AGENDAMENTO).format('DD/MM/YYYY HH:mm');
                const status = pagamento.PAGO === 'S' ? 
                    `<span class="badge bg-success">Pago (${moment(pagamento.DATA_PAGAMENTO).format('DD/MM/YYYY')})</span>` : 
                    `<span class="badge bg-warning">Pendente</span>`;

                let btnAcoes = `
                    <div class="input-group-prepend show justify-content-center">
                        <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown">
                            Ações
                        </button>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item" onclick="abrirModalPagamento(${pagamento.ID}, false)">
                                <span class="btn"><i class="fas fa-eye"></i> Visualizar</span>
                            </li>
                            <li class="dropdown-item" onclick="gerarImpresso(${pagamento.ID})">
                                <span class="btn"><i class="fas fa-print"></i> Imprimir</span>
                            </li>
                            ${pagamento.PAGO === 'N' ? `
                            <li class="dropdown-item" onclick="abrirModalPagamento(${pagamento.ID})">
                                <span class="btn"><i class="fas fa-pen"></i> Editar</span>
                            </li>
                            <li class="dropdown-item" onclick="confirmarPagamento(${pagamento.ID})">
                                <span class="btn"><i class="fas fa-check"></i> Confirmar Pagamento</span>
                            </li>
                            <li class="dropdown-item" onclick="inativarPagamento(${pagamento.ID})">
                                <span class="btn"><i class="fas fa-trash"></i> Remover</span>
                            </li>
                            ` : ''}
                        </ul>
                    </div>
                `;

                html += `
                    <tr>
                        <td>${pagamento.NOME_PESSOA}</td>
                        <td><center>${dataAgendamento}</center></td>
                        <td><center>${mascaraFinanceira(pagamento.VALOR)}</center></td>
                        <td><center>${pagamento.HORAS_MENSAIS || '-'}</center></td>
                        <td><center>${status}</center></td>
                        <td><center>${btnAcoes}</center></td>
                    </tr>
                `;
            });
            $('#tableBodyPagamentos').html(html);
        }

        function carregarPessoas() {
            $.ajax({
                type: 'post',
                url: "{{ route('pessoa.buscar') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'ID_TIPO': 2
                },
                success: function(response) {
                    let options = '<option value="">Selecione um funcionário</option>';                    
                    response.forEach(pessoa => {
                        options += `<option value="${pessoa.ID}">${pessoa.NOME}</option>`;
                    });
                    $('#selectPessoa').html(options);
                },
                error: err => {
                    exibirErro(err);
                }
            });
        }

        function carregarDadosPessoa(idPessoa){
            dispararAlerta('warning', 'Buscando dados do funcionário...');

            $.ajax({
                type:'post',
                datatype:'json',
                data:{
                   '_token':'{{csrf_token()}}',
                   'id': idPessoa,
                   'FILTRO_DIARIA': $('#selectPeriodoPagamento').val(),
                   'PONTO_ELETRONICO': $('#selectPeriodoPagamento').val(),
                },
                url:"{{route('pessoa.buscar')}}",
                success:function(r){
                    var dadosPessoa = r[0];
                    console.log(dadosPessoa)
                    $('#inputSalarioBase').val(mascaraFinanceira(dadosPessoa['SALARIO_BASE']));
                    $('#inputHorasMensais').val(dadosPessoa['HORAS_MENSAIS']);
                    $('#inputHorasRegistradas').val(dadosPessoa['TOTAL_HORAS']);
                    $('#inputValorDiarias').val(mascaraFinanceira(dadosPessoa['VALOR_TOTAL_DIARIA']));
                    
                    calcularValores();

                    dispararAlerta('success', 'Dados atualizados com sucesso!')

                },
                error:err=>{exibirErro(err)}
            })
        }

        function abrirModalPagamento(idPagamento = 0, editar = true) {
            resetarModalCadastro();

            $('#selectPessoa').prop('disabled', !editar);
            $('#inputDataAgendamento').prop('disabled', !editar);
            $('#inputValor').prop('disabled', !editar);
            $('#inputObservacao').prop('disabled', !editar);
            $('#inputHorasMensais').prop('disabled', !editar);
            $('#inputHorasRegistradas').prop('disabled', !editar);
            $('#inputHorasExtras').prop('disabled', !editar);
            $('#inputValor').prop('disabled', !editar);
            $('#inputSalarioBase').prop('disabled', !editar);
            $('#inputValorDiarias').prop('disabled', !editar);
            $('#inputValorHorasExtras').prop('disabled', !editar);
            $('#inputValorDescontos').prop('disabled', !editar);
            $('#inputValorDescontosINSS').prop('disabled', !editar);
            
            if(editar){
                $('#btnConfirmarPagamento').removeClass('d-none');
            } else {
                $('#btnConfirmarPagamento').addClass('d-none');
            }

            if(idPagamento === 0) {
                $('#modalPagamentoLabel').text('Cadastrar Pagamento');
                $('#inputIDPagamento').val('0');
                $('#selectPessoa').val('');
                $('#inputDataAgendamento').val(moment().format('YYYY-MM-DDTHH:mm'));
                $('#inputValor').val('');
                $('#inputObservacao').val('');
            } else {
                $.ajax({
                    type: 'post',
                    url: "{{ route('pagamento.buscar') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'ID': idPagamento
                    },
                    success: function(response) {
                        const pagamento = response.dados[0];
                        $('#modalPagamentoLabel').text('Editar Pagamento');
                        $('#inputIDPagamento').val(pagamento.ID);
                        $('#selectPessoa').val(pagamento.ID_PESSOA);
                        $('#inputDataAgendamento').val(moment(pagamento.DATA_AGENDAMENTO).format('YYYY-MM-DDTHH:mm'));
                        $('#inputValor').val(mascaraFinanceira(pagamento.VALOR));
                        $('#inputObservacao').val(pagamento.OBSERVACAO);
                        $('#inputHorasMensais').val(pagamento.HORAS_MENSAIS)
                        $('#inputHorasRegistradas').val(pagamento.HORAS_REGISTRADAS)
                        $('#inputHorasExtras').val(pagamento.HORAS_EXTRAS)
                        $('#inputValor').val(mascaraFinanceira(pagamento.VALOR));
                        $('#inputSalarioBase').val(mascaraFinanceira(pagamento.SALARIO_BASE));
                        $('#inputValorDiarias').val(mascaraFinanceira(pagamento.VALOR_DIARIA));
                        $('#inputValorHorasExtras').val(mascaraFinanceira(pagamento.VALOR_HORA_EXTRA));
                        $('#inputValorDescontos').val(mascaraFinanceira(pagamento.VALOR_DESCONTO));
                        $('#inputValorDescontosINSS').val(mascaraFinanceira(pagamento.VALOR_INSS));  
                    }
                });
            }
            $('#modalPagamento').modal('show');
        }

        function salvarPagamento() {
            const idPagamento = $('#inputIDPagamento').val();
            const idPessoa = $('#selectPessoa').val();
            const dataAgendamento = $('#inputDataAgendamento').val();
            const horasMensais = $('#inputHorasMensais').val();
            const horasRegistradas = $('#inputHorasRegistradas').val();
            const horasExtras = $('#inputHorasExtras').val();
            const valor = limparMascaraFinanceira($('#inputValor').val());
            const valorSalarioBase = limparMascaraFinanceira($('#inputSalarioBase').val());
            const valorDiarias = limparMascaraFinanceira($('#inputValorDiarias').val());
            const valorExtras = limparMascaraFinanceira($('#inputValorHorasExtras').val());
            const valorDescontos = limparMascaraFinanceira($('#inputValorDescontos').val());
            const valorINSS = limparMascaraFinanceira($('#inputValorDescontosINSS').val());
            const observacao = $('#inputObservacao').val();
            const periodo = $('#selectPeriodoPagamento').val();

            if(!idPessoa || !dataAgendamento || !valor) {
                Swal.fire({
                    title: 'Atenção',
                    text: 'Preencha todos os campos obrigatórios!',
                    icon: 'warning'
                });
                return;
            }

            const url = idPagamento === '0' ? "{{ route('pagamento.inserir') }}" : "{{ route('pagamento.alterar') }}";
            const data = {
                '_token': '{{ csrf_token() }}',
                'ID': idPagamento,
                'ID_PESSOA': idPessoa,
                'VALOR': valor,
                'OBSERVACAO': observacao,
                'DATA_AGENDAMENTO': dataAgendamento,
                'HORAS_REGISTRADAS': horasRegistradas,
                'HORAS_MENSAIS': horasMensais,
                'HORAS_EXTRAS': horasExtras,
                'VALOR_EXTRAS': valorExtras,
                'VALOR_DIARIAS': valorDiarias,
                'VALOR_DESCONTOS': valorDescontos,
                'VALOR_INSS': valorINSS,
                'PERIODO': periodo,
            };

            $.ajax({
                type: 'post',
                url: url,
                data: data,
                success: function() {
                    $('#modalPagamento').modal('hide');
                    buscarDados();
                    Swal.fire({
                        title: 'Sucesso',
                        text: 'Pagamento salvo com sucesso!',
                        icon: 'success'
                    });
                },
                error: err => {
                    exibirErro(err);
                }
            });
        }

        function confirmarPagamento(id) {
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja confirmar o pagamento?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: "{{ route('pagamento.confirmar') }}",
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'ID': id
                        },
                        success: function() {
                            buscarDados();
                            Swal.fire({
                                title: 'Sucesso',
                                text: 'Pagamento confirmado com sucesso!',
                                icon: 'success'
                            });
                        },
                        error: err => {
                            exibirErro(err);
                        }
                    });
                }
            });
        }

        function inativarPagamento(id) {
            Swal.fire({
                title: 'Confirmação',
                text: 'Deseja realmente remover este pagamento?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: "{{ route('pagamento.inativar') }}",
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'ID': id
                        },
                        success: function() {
                            buscarDados();
                            Swal.fire({
                                title: 'Sucesso',
                                text: 'Pagamento removido com sucesso!',
                                icon: 'success'
                            });
                        },
                        error: err => {
                            exibirErro(err);
                        }
                    });
                }
            });
        }

        function calcularValores(){
            var salarioBase = parseFloat(limparMascaraFinanceira($('#inputSalarioBase').val()));
            var horasMensais = parseFloat($('#inputHorasMensais').val()) > 0 ? parseFloat($('#inputHorasMensais').val()) : 1;
            var horasRegistradas = parseFloat($('#inputHorasRegistradas').val());
            var valorDiaria = parseFloat(limparMascaraFinanceira($('#inputValorDiarias').val()));

            var horasExtras = Math.max(horasRegistradas - horasMensais, 0); 

            var valorHoraNormal = salarioBase / horasMensais;

            var valorTotalHoraExtra = horasExtras * (valorHoraNormal * 1.5);

            var valorSalarioBruto = salarioBase + valorTotalHoraExtra + valorDiaria;

            var valorINSS = valorSalarioBruto * 0.08;
            var inputValorDescontos = parseFloat(limparMascaraFinanceira($('#inputValorDescontos').val())) || 0; 

            var valorSalarioLiquido = valorSalarioBruto - (valorINSS + inputValorDescontos);

            $('#inputHorasExtras').val(horasExtras.toFixed(2));
            $('#inputValorHorasExtras').val(mascaraFinanceira(valorTotalHoraExtra));
            $('#inputValorDescontosINSS').val(mascaraFinanceira(valorINSS));
            $('#inputValor').val(mascaraFinanceira(valorSalarioLiquido));
        }

        function resetarModalCadastro(){
            $('#inputIDPagamento').val('0');
            $('#selectPessoa').val('0');
            $('#inputDataAgendamento').val(moment().format('YYYY-MM-DDTHH:mm'));
            $('#inputValor').val(mascaraFinanceira(0));
            $('#inputObservacao').val('');
            $('#inputHorasMensais').val('')
            $('#inputHorasRegistradas').val('')
            $('#inputHorasExtras').val('')
            $('#inputValor').val(mascaraFinanceira('0'));
            $('#inputSalarioBase').val(mascaraFinanceira('0'));
            $('#inputValorDiarias').val(mascaraFinanceira('0'));
            $('#inputValorHorasExtras').val(mascaraFinanceira('0'));
            $('#inputValorDescontos').val(mascaraFinanceira('0'));
            $('#inputValorDescontosINSS').val(mascaraFinanceira('0'));
            $('#selectPeriodoPagamento').val(moment().month() + 1);
        
        }

        function gerarImpresso(idPagamento){
            window.open(`{{env('APP_URL')}}/financeiro/impresso/folha-pagamento/${idPagamento}`)
        }

        $('#selectPessoa').on('change', () => {
            carregarDadosPessoa($('#selectPessoa').val());
        });
        $('#inputHorasMensais').on('keyup', () => {
            clearTimeout(timeoutValorAtualizacao)
            timeoutValorAtualizacao = setTimeout(() => {
                calcularValores();
            }, 1500)
        });
        $('#inputHorasRegistradas').on('keyup', () => {
            clearTimeout(timeoutValorAtualizacao)
            timeoutValorAtualizacao = setTimeout(() => {
                calcularValores();
            }, 1500)
        });
        $('#inputHorasExtras').on('keyup', () => {
            clearTimeout(timeoutValorAtualizacao)
            timeoutValorAtualizacao = setTimeout(() => {
                calcularValores();
            }, 1500)
        });
        $('#inputValor').on('keyup', () => {
            clearTimeout(timeoutValorAtualizacao)
            timeoutValorAtualizacao = setTimeout(() => {
                calcularValores();
            }, 1500)
        });
        $('#inputSalarioBase').on('keyup', () => {
            clearTimeout(timeoutValorAtualizacao)
            timeoutValorAtualizacao = setTimeout(() => {
                calcularValores();
            }, 1500)
        });
        $('#inputValorDiarias').on('keyup', () => {
            clearTimeout(timeoutValorAtualizacao)
            timeoutValorAtualizacao = setTimeout(() => {
                calcularValores();
            }, 1500)
        });
        $('#inputValorHorasExtras').on('keyup', () => {
            clearTimeout(timeoutValorAtualizacao)
            timeoutValorAtualizacao = setTimeout(() => {
                calcularValores();
            }, 1500)
        });
        $('#inputValorDescontos').on('keyup', () => {
            clearTimeout(timeoutValorAtualizacao)
            timeoutValorAtualizacao = setTimeout(() => {
                calcularValores();
            }, 1500)
        });
        $('#inputValorDescontosINSS').on('keyup', () => {
            clearTimeout(timeoutValorAtualizacao)
            timeoutValorAtualizacao = setTimeout(() => {
                calcularValores();
            }, 1500)
        });

        $('#btnNovoPagamento').click(function() {
            abrirModalPagamento();
        });

        $('#btnConfirmarPagamento').click(function() {
            salvarPagamento();
        });

        $('#selectPeriodoPagamento').on('change', () => {
            carregarDadosPessoa($('#selectPessoa').val());
        });

        $(document).ready(function() {
            buscarDados();
            carregarPessoas();            
        });
    </script>
@stop