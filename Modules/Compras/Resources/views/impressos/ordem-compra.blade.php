<!DOCTYPE html>
<html>
<head>
    <title>Ordem de Compra</title>
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
            <div><a href="https://gssoftware.app.br"><b>GSSoftware</b></a></div>
            <div>@php echo $dadosEmpresa->NOME @endphp</div>
            <div>@php echo $dadosEmpresa->CNPJ @endphp</div>
            <div>@php echo $dadosEmpresa->TELEFONE @endphp</div>
            <div>@php echo "Rua ".$dadosEmpresa->RUA.", ".$dadosEmpresa->NUMERO.", ".$dadosEmpresa->BAIRRO.", ".substr_replace($dadosEmpresa->CEP, '-', 5, 0).", ".$dadosEmpresa->CIDADE."-".$dadosEmpresa->ESTADO @endphp</div>
        </div>
    </div>

    <hr>
    
    <div style="font-size: 15px">
        <center>
            <b>Ordem de Compra {{ $dadosOrdemCompra->ID }} - Data Ordem {{ date("d/m/Y H:i", strtotime($dadosOrdemCompra->DATA_CADASTRO)) }}</b>
        </center>
    </div>
    <hr>
        
    <div>
        <table>
            <thead>
                <th>Item</th>
                <th>Qtde</th>
                <th>Unitário</th>
                <th>Subtotal</th>
                <th>Observação</th>
            </thead>
            <tbody>
                @foreach($dadosItemOrdemCompra as $item)
                <tr>
                    <td style="width: 200px; border: 1px solid black;font-size: 13px">
                        <center>{{ $item->MATERIAL }}</center>
                    </td>
                    <td style="width: 30px; border: 1px solid black;">
                        <center>{{ $item->QTDE }}</center>
                    </td>
                    <td style="width: 95px; border: 1px solid black;">
                        <center>R$ {{ number_format($item->VALOR_UNITARIO, 2, ',', '.') }}</center>
                    </td>
                    <td style="width: 95px; border: 1px solid black;">
                        <center>R$ {{ number_format($item->VALOR_TOTAL, 2, ',', '.') }}</center>
                    </td>
                    <td style="width: 250px; border: 1px solid black;">
                        <center>{{ $item->OBSERVACAO }}</center>
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
                    <td>Total de Itens:</td>
                    <td>@php echo count($dadosItemOrdemCompra) @endphp </td>
                </tr>
                <tr>
                    <td>Valor Total:</td>
                    <td>R$ @php echo number_format($dadosOrdemCompra->VALOR, 2, ',', '.') @endphp</td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>
    
    <div class="footer">
        <span>_________________________________________________</span>
        <br>
        <span>Responsável</span>
        <br>        
    </div>

</body>
</html>
