import './bootstrap';

function exibirErro(err){
    errorMessage =
    "<p><b>Exception: </b>"+err.responseJSON.exception+"<p></br>"
    +"<p><b>File: </b>"+err.responseJSON.file+"<p></br>"
    +"<p><b>Line: </b>"+err.responseJSON.line+"<p></br>"
    +"<p><b>Message: </b>"+err.responseJSON.message+"<p></br>";

    Swal.fire(
        'Request exception',
        errorMessage,
        'error'
    )
    console.log(err)
}

function mascaraFinanceira(valor){
    return (valor-0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}