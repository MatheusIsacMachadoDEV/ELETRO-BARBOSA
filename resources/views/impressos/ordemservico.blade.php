<!DOCTYPE html>
<html>
<head>
    <title>Orçamento</title>
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
            <b>"NÃO É DOCUMENTO FISCAL - NÃO É VÁLIDO
                COMO RECIBO E COMO GARANTIA DE MERCADORIA
                - NÃO COMPROVA PAGAMENTO"</b>
        </center>
    </div>
    <hr>
        
    <div>
        <table>
            <thead>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Valor Unitário</th>
                <th>Subtotal</th>
            </thead>
            <tbody>
                <tr>
                    @foreach($dadosItemOrdemServico as $item)
                    <td style="width: 300px; border: 1px solid black;font-size: 13px">
                        <center>{{ $item->SERVICO }}</center>
                    </td>
                    <td style="width: 50px; border: 1px solid black;">
                        <center>{{ $item->QUANTIDADE }}</center>
                    </td>
                    <td style="width: 150px; border: 1px solid black;">
                        <center>R$ {{ number_format($item->VALOR_UNITARIO, 2, ',', '.') }}</center>
                    </td>
                    <td style="width: 150px; border: 1px solid black;">
                        <center>R$ {{ number_format($item->VALOR_TOTAL, 2, ',', '.') }}</center>
                    </td>
                @endforeach                    
                </tr>
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
                    <td>@php echo count($dadosItemOrdemServico) @endphp </td>
                </tr>
                <tr>
                    <td>Valor Total:</td>
                    <td>R$ @php echo number_format($dadosOrdemServico->VALOR_TOTAL, 2, ',', '.') @endphp</td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>
    <div>
        <span>Método de pagamento: <b>Dinheiro</b></span>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <div class="footer">

        <span>_________________________________________</span>, <span><b>@php echo "$data" @endphp</b></span>
        <br>
        <span>@php echo $dadosOrdemServico->NOME_CLIENTE @endphp</span>
        <br>
        <span class="endereco"><b>____________________________________________________________________________GSSoftware</span>
        
    </div>

</body>
</html>
