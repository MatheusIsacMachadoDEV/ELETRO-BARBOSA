function limparMascaraFinanceira(string){
    return string.replace('.', '').replace('R$','').replace(' ','').replace(',', '.');
}
function limparMascaraTelefonica(string){
    return string.replace(')', '').replace('(','').replace(' ','').replace('-', '');
}
function mascaraFinanceira(valor){
    return (valor-0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}


function mascaraDocumento(numero) {
    numero = numero.replace(/\D/g, '');

    if (numero.length === 11) {
        return numero.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    } else if (numero.length === 14) {
        return numero.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    } else {
        return numero;
    }
}

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

$('.modal').on('show.bs.modal', function() {
    setTimeout(() => {
        $(this).find('input').first().focus();        
    }, 500);
});

$(document).ready(function() {
    setTimeout(() => {
        $(this).find('input').first().focus();        
    }, 500);
})