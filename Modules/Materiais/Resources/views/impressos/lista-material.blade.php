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

        html, body {
            margin: 5 !important;
            padding: 0 !important;
            border: 0 !important;
            font-family: Arial, Helvetica, sans-serif;
            width: 98%;
            height: 100%;
        }
        
        /* Container principal - solução compatível com PDF */
        .div-principal {
            width: 100%;
            overflow: hidden; /* Contém os floats */
            margin-bottom: 20px;
        }
        
        /* Logo à esquerda */
        .img-logo {
            float: left;
            width: 200px; /* Largura fixa conforme definido no HTML */
            height: auto;
        }
        
        /* Dados da empresa no meio */
        .dados-empresa {
            float: left;
            width: calc(60% - 215px); /* 60% do espaço menos a largura da logo + margem */
            font-weight: bold;
            font-size: 14px;
            padding-left: 10;
        }
        
        /* Dados da ordem à direita */
        .dados-ordem {
            float: right;
            width: 200px;
            border: 1px solid #000;
            padding: 5px 10px;
            font-size: 13px;
        }
        
        .dados-ordem span {
            display: block;
            font-size: 13px;
        }
        
        /* Limpar os floats */
        .div-principal::after {
            content: "";
            display: table;
            clear: both;
        }

        .info-cliente-fornecedor {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
        }
        
        .info-cliente-fornecedor td {
            padding: 8px;
            border: 1px solid #000;
        }

        .dados-gerais{
            border: 1px solid #000;
            padding: 5!important;
        }

        .dados-itens{
            margin-top: 10!important;
            border: 1px solid #000;
        }
        .dados-itens td{
            margin-top: 10!important;
            border-top: 1px solid #000;
            border-right: 1px solid #000;
        }
        footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60px; /* Altura do footer */
        }
    </style>
</head>
<body>
    <div class="div-principal">
        <img src="{{env('APP_URL')}}/vendor/adminlte/dist/img/logo-cortada.jpeg" class="img-logo" width="200"/>
        
        <div class="dados-empresa">
            <div>
                @php 
                    echo "Rua ".$dadosEmpresa->RUA.", ".$dadosEmpresa->NUMERO." - ".$dadosEmpresa->BAIRRO
                @endphp
            </div>
            <div>
                @php 
                    echo "{$dadosEmpresa->CIDADE}-{$dadosEmpresa->ESTADO} - CEP ".substr_replace($dadosEmpresa->CEP, '-', 5, 0);
                @endphp
            </div>
            <div>@php echo "Fone: {$dadosEmpresa->TELEFONE}" @endphp</div>
            <div>@php echo "CNPJ: {$dadosEmpresa->CNPJ}" @endphp</div>
        </div>
    </div>

    <div>
        <center><b>Lista de Materiais - {{$dadosOrdemCompra->ID}}</b></center>
    </div>
        
    <div class="dados-itens">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: red">
                <th>Item</th>
                <th>Fornecedor</th>
                <th>Quantidade</th>
                <th>Valor Unit</th>
                <th>Valor Total</th>
            </thead>
            <tbody>
                @foreach($dadosItemOrdemCompra as $item)
                <tr>
                    <td style="width: 20%; border: 0px 1px 0px 0px solid black;font-size: 13px">
                        <center>{{ $item->MATERIAL }}</center>
                    </td>
                    <td style="width: 25%; border: 0px 1px 0px 0px solid black;;font-size: 13px">
                        <center>{{ $item->NOME_FORNECEDOR }}</center>
                    </td>
                    <td style="width: 10%; border: 0px 1px 0px 0px solid black;;">
                        <center>{{ $item->QTDE }}</center>
                    </td>
                    <td style="width: 20%; border: 0px 1px 0px 0px solid black;;">
                        <center>R$ {{ number_format($item->VALOR_ITEM, 2, ',', '.') }}</center>
                    </td>
                    <td style="width: 20%; border: 0px 1px 0px 0px solid black;;">
                        <center>R$ {{ number_format($item->VALOR_ITEM * $item->QTDE, 2, ',', '.') }}</center>
                    </td>
                </tr>
                @endforeach
                <tr style="background-color: red">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="font-weight: bold"><center>Total:<center></td>
                    <td style="font-weight: bold"><center>R$ {{ number_format($dadosOrdemCompra->VALOR_TOTAL, 2, ',', '.') }}<center></td>
                </tr>               
            </tbody>
        </table>
    </div>

    <footer>
        {{-- <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 70%;font-weight: bold;">Aprovado Por: {{$dadosOrdemCompra->USUARIO_APROVACAO}}</td>
                <td style="width: 30%;font-weight: bold;">Emitido Por: {{$dadosOrdemCompra->USUARIO}}</td>
            </tr>
        </table> --}}
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 100%;font-weight: bold;">Assinatura:</td>
            </tr>
        </table>
    </footer>

</body>
</html>
