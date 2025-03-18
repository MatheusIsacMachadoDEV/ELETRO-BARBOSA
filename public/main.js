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

function limparCampo(input1, input2, botao, inputsAdicionais){
    $('#'+input1).val('')
    $('#'+input2).val('0')
    $('#'+input1).attr('disabled', false); 
    $('.'+botao).addClass('d-none');

    if(input1 == 'inputDevolucaoEquipamento'){
        $('#btnQRCODE').removeClass('d-none');
    }

    for (let index = 0; index < inputsAdicionais.length; index++) {
        var inputID = inputsAdicionais[index];
        var input = $('#' + inputID);
        input.val('');        
    }
}

$('.modal').on('show.bs.modal', function() {
    setTimeout(() => {
        $(this).find('input').first().focus();        
    }, 500);
});

function formatCPForCNPJ(document) {
    // Remove caracteres não numéricos
    document = document.replace(/\D/g, '');

    if (document.length === 11) { // Se o documento tem 11 dígitos, é um CPF
        // Adicione a máscara de CPF
        document = document.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, '$1.$2.$3-$4');
    } else if (document.length === 14) { // Se o documento tem 14 dígitos, é um CNPJ
        // Adicione a máscara de CNPJ
        document = document.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
    }
    
    return document;
}

function formatarTelefone(numero) {
    if (typeof numero !== 'string') {
        return numero;
    }

    // Remove todos os caracteres não numéricos do número
    const numeros = numero.replace(/\D/g, '');

    // Verifica se o número tem 10 ou 11 dígitos
    if (numeros.length === 10) {
        return `(${numeros.slice(0, 2)}) ${numeros.slice(2, 6)}-${numeros.slice(6, 10)}`;
    } else if (numeros.length === 11) {
        return `(${numeros.slice(0, 2)}) ${numeros.slice(2, 7)}-${numeros.slice(7, 11)}`;
    } else {
        return numero;
    }
}

function mascaraTelefone(input) {
    // Remove qualquer caractere que não seja número do valor do input
    let numero = input.value.replace(/\D/g, '');

    // Verifica o tamanho do número inserido e formata de acordo
    if (numero.length === 11) {
        input.value = numero.replace(/(\d{2})(\d{1})(\d{4})(\d{4})/, '($1) $2 $3-$4');
    } else if (numero.length === 10) {
        input.value = numero.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
    } else {
        // Se o número não se encaixar em nenhum dos formatos, deixe-o inalterado
        input.value = numero;
    }
}

function mascararDocumento(input) {
    // Remove todos os caracteres não numéricos
    const valorLimpo = input.value.replace(/\D/g, '');

    if (valorLimpo.length <= 11) {
        // Formatando como CPF (###.###.###-##)
        input.value = valorLimpo.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    } else {
        // Formatando como CNPJ (##.###.###/####-##)
        input.value = valorLimpo.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    }
}

function dispararAlerta(icone, mensagem){
    Swal.fire({
        toast: true,
        position: "top-end",
        icon: icone,
        title: mensagem,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
}

function atualizarModulos(){
    $.ajax({
        type:'post',
        datatype:'json',
        data:{
        },
        url:"./atualizar/modulos",
        success:function(r){
        },
        error:err=>{exibirErro(err)}
    })
}

function atualizarMenus(){
    $.ajax({
        type:'post',
        datatype:'json',
        data:{
        },
        url:"./atualizar/menus",
        success:function(r){
        },
        error:err=>{exibirErro(err)}
    })
}

$(document).ready(function() {
    setTimeout(() => {
        $(this).find('input').first().focus();        
    }, 500);

    atualizarModulos();
    atualizarMenus();
})