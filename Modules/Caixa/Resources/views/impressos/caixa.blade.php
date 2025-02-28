<!DOCTYPE html>
<html>
<head>
    <title>Venda</title>
    <style>
        .footer {
            position: absolute;
            bottom: 1cm;
            width: 100%; /* Garante que a largura da div seja igual à largura da página */
            text-align: center; /* Alinha o conteúdo ao centro */
        }

        .endereco {
            position: absolute; /* Posição absoluta para fixar à direita */
            right: 0; /* Alinha à direita */
            color: #69f557;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }   

        body{
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body>
    <div>
        <img src="{{env('APP_URL')}}/vendor/adminlte/dist/img/logo.jpeg" width="100"/>

        <div style="margin-top: -100px;margin-left: 150px">
            <div>@php echo $dadosEmpresa->NOME @endphp</div>
            <div>@php echo $dadosEmpresa->CNPJ @endphp</div>
            <div>@php echo $dadosEmpresa->TELEFONE @endphp</div>
            <div>@php echo "Rua ".$dadosEmpresa->RUA.", ".$dadosEmpresa->NUMERO.", ".$dadosEmpresa->BAIRRO.", ".substr_replace($dadosEmpresa->CEP, '-', 5, 0).", ".$dadosEmpresa->CIDADE."-".$dadosEmpresa->ESTADO @endphp</div>
        </div>
    </div>

    <hr>
    
    <div style="font-size: 15px">
        <center>
            <b>Resumo de caixa - {{ $data }}</b>
        </center>
    </div>
    <hr>
        
    <div>
        <table>
            <thead>
                <th>Venda</th>
                <th>Data</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Pagamento</th>
            </thead>
            <tbody>
                @foreach($dadosVenda as $item)
                <tr>
                    <td style="width: 100px; border: 1px solid black;font-size: 13px">
                        <center>{{ $item->ID }}</center>
                    </td>
                    <td style="width: 100px; border: 1px solid black;">
                        <center>{{ $item->DATA }}</center>
                    </td>
                    <td style="width: 250px; border: 1px solid black;">
                        <center>{{ $item->CLIENTE_NOME }}</center>
                    </td>
                    <td style="width: 95px; border: 1px solid black;">
                        <center>R$ {{ number_format($item->VALOR_TOTAL, 2, ',', '.') }}</center>
                    </td>
                    <td style="width: 95px; border: 1px solid black;">
                        <center>{{ $item->PAGAMENTO }}</center>
                    </td>
                </tr>
                @endforeach                    
            </tbody>
        </table>
    </div>
    <br>
    <hr>
    <div>
        <table>
            <tbody>
                <tr>
                    <td>Total vendas no PIX:</td>
                    <td>R$ @php echo number_format($dadosVendaPix->TOTAL, 2, ',', '.') @endphp</td>
                </tr>
                <tr>
                    <td>Total vendas no CARTÃO:</td>
                    <td>R$ @php echo number_format($dadosVendaCartao->TOTAL, 2, ',', '.') @endphp</td>
                </tr>
                <tr>
                    <td>Total vendas no DINHEIRO:</td>
                    <td>R$ @php echo number_format($dadosVendaDinheiro->TOTAL, 2, ',', '.') @endphp</td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>

    <div>
        <table>
            <tbody> 
                <tr>
                    <td>Data abertura caixa: </td>
                    <td>{{$dadosCaixa->DATA_ABERTURA}}</td>
                </tr>
                <tr>
                    <td>Valor abertura caixa </td>
                    <td>R$ @php echo number_format($dadosCaixa->VALOR_ABERTURA, 2, ',', '.') @endphp</td>
                </tr>
                <tr>
                    <td>Data fechamento caixa: </td>
                    <td>{{$dadosCaixa->DATA_FECHAMENTO}}</td>
                </tr>
                <tr>
                    <td>Valor fechamento caixa :</td>
                    <td>R$ @php echo number_format($dadosCaixa->VALOR_FECHAMENTO, 2, ',', '.') @endphp</td>
                </tr>
                <tr>
                    <td>Lucro :</td>
                    <td>R$ @php echo number_format($dadosCaixa->LUCRO, 2, ',', '.') @endphp</td>
                </tr>
            </tbody>
        </table>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <div class="footer">

        <span class="endereco"><b>______________________________________________________________________________GSSoftware</span>
        
    </div>

</body>
</html>
