<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holerite de Pagamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 10px;
        }
        .header {
            margin-bottom: 15px;
        }
        .employer-info {
            margin-bottom: 10px;
        }
        .employee-info {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .totals {
            margin-top: 15px;
            font-weight: bold;
        }
        .footer {
            margin-top: 15px;
            font-size: 10px;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin-top: 50px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <img src="{{env('APP_URL')}}/vendor/adminlte/dist/img/logo.jpeg" width="100"/>
    
            <div style="margin-top: -100px;margin-left: 150px">
                <div><a href="https://gssoftware.app.br"><b>GSSoftware</b></a></div>
                <div>@php echo $dadosEmpresa->NOME @endphp</div>
                <div>@php echo $dadosEmpresa->CNPJ @endphp</div>
                <div>@php echo $dadosEmpresa->TELEFONE @endphp</div>
                <div>@php echo "Rua ".$dadosEmpresa->RUA.", ".$dadosEmpresa->NUMERO.", ".$dadosEmpresa->BAIRRO.", ".substr_replace($dadosEmpresa->CEP, '-', 5, 0).", ".$dadosEmpresa->CIDADE."-".$dadosEmpresa->ESTADO @endphp</div>
            </div>
        </div>
        
        <div class="employee-info">
            <table>
                <tr>
                    <th>Código</th>
                    <th>FUNÇÃO</th>
                    <th>NOME</th>
                </tr>
                <tr>
                    <td>{{ $dadosPagamento->ID }}</td>
                    <td>{{ $dadosPagamento->FUNCAO ?? 'FUNCIONÁRIO(A)' }}</td>
                    <td>{{ $dadosPagamento->NOME_FUNCIONARIO }}</td>
                </tr>
            </table>
        </div>
    </div>

    <table>
        <tr>
            <th>Cód.</th>
            <th>Descrição</th>
            <th>Referência</th>
            <th>Proventos</th>
            <th>Descontos</th>
        </tr>
        <tr>
            <td>.001</td>
            <td>SALARIO BASE</td>
            <td>{{ number_format($dadosPagamento->HORAS_MENSAIS, 2, ',', '.') }} h</td>
            <td class="text-right">{{ number_format($dadosPagamento->SALARIO_BASE, 2, ',', '.') }}</td>
            <td class="text-right"></td>
        </tr>
        @if($dadosPagamento->VALOR_HORA_EXTRA > 0)
        <tr>
            <td>.002</td>
            <td>HORAS EXTRAS</td>
            <td>{{ number_format($dadosPagamento->HORAS_EXTRAS, 2, ',', '.') }} h</td>
            <td class="text-right">{{ number_format($dadosPagamento->VALOR_HORA_EXTRA, 2, ',', '.') }}</td>
            <td class="text-right"></td>
        </tr>
        @endif
        @if($dadosPagamento->VALOR_DIARIA > 0)
        <tr>
            <td>.003</td>
            <td>DIÁRIAS</td>
            <td></td>
            <td class="text-right">{{ number_format($dadosPagamento->VALOR_DIARIA, 2, ',', '.') }}</td>
            <td class="text-right"></td>
        </tr>
        @endif
        <tr>
            <td>.903</td>
            <td>INSS</td>
            <td></td>
            <td class="text-right"></td>
            <td class="text-right">{{ number_format($dadosPagamento->VALOR_INSS, 2, ',', '.') }}</td>
        </tr>
        @if($dadosPagamento->VALOR_DESCONTO > 0)
        <tr>
            <td>.904</td>
            <td>DESCONTOS</td>
            <td></td>
            <td class="text-right"></td>
            <td class="text-right">{{ number_format($dadosPagamento->VALOR_DESCONTO, 2, ',', '.') }}</td>
        </tr>
        @endif
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Total dos Proventos</td>
                <td class="text-right">{{ number_format($dadosPagamento->SALARIO_BASE + $dadosPagamento->VALOR_DIARIA + $dadosPagamento->VALOR_HORA_EXTRA, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total dos Descontos</td>
                <td class="text-right">{{ number_format($dadosPagamento->VALOR_DESCONTO + $dadosPagamento->VALOR_INSS, 2, ',', '.') }}</td>
            </tr>
            <tr class="bold">
                <td>Líquido a Receber</td>
                <td class="text-right">{{ number_format($dadosPagamento->VALOR, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <table>
            <tr>
                <td>Salário Base</td>
                <td>Base INSS</td>
                <td>Base FGTS</td>
            </tr>
            <tr>
                <td>{{ number_format($dadosPagamento->SALARIO_BASE, 2, ',', '.') }}</td>
                <td>{{ number_format($dadosPagamento->SALARIO_BASE + $dadosPagamento->VALOR_DIARIA + $dadosPagamento->VALOR_HORA_EXTRA, 2, ',', '.') }}</td>
                <td>{{ number_format($dadosPagamento->VALOR, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>FGTS do Mês</td>
                <td>Base IRRF</td>
                <td>Horas Trabalhadas</td>
            </tr>
            <tr>
                <td>{{ number_format($dadosPagamento->VALOR_INSS, 2, ',', '.') }}</td>
                <td>{{ number_format($dadosPagamento->VALOR, 2, ',', '.') }}</td>
                <td>{{ number_format($dadosPagamento->HORAS_REGISTRADAS, 2, ',', '.') }} h</td>
            </tr>
        </table>
        
        <div style="margin-top: 50px;">
            <div class="signature-line"></div>
            <div>Assinatura do Funcionário</div>
        </div>
    </div>
</body>
</html>