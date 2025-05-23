@php
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RELATÓRIO</title>
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
            <b>{{$tituloRelatorio}}</b>
        </center>
    </div>
    <hr>

    <div>

        <table style="width: 100%">
            <thead>
                <tr>
                    @if (count($dadosRelatorioGSS) > 0)
                        @foreach(array_keys(get_object_vars($dadosRelatorioGSS[0])) as $column)
                            <th>{{ $column }}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($dadosRelatorioGSS as $dadoLista)
                    <tr>
                        @foreach(array_keys(get_object_vars($dadoLista)) as $column)
                            <td style="border: 1px solid black;font-size: 13px">{{ $dadoLista->$column }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
