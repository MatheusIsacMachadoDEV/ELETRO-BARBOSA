<html>
    <head>
        <title>Etiqueta {{$codigoEtiqueta}}</title>
        <style>
            /* Remover margens e padding do body e do html */
            html, body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                font-family: sans-serif;
            }
            
            /* Se desejar remover margens e padding do img também */
            img {
                margin: 0;
                padding: 0;
                margin-top: 5px;
            }

            /* Outros ajustes, como centralizar ou definir o layout */
            .qr-code-container {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="qr-code-container">
            <img src="{{$qrCodeBase64}}" width="100px">
        </div>

        <div>
            <center><span>{{$codigoEtiqueta}}</span></center>
        </div>
    </body>
</html>
