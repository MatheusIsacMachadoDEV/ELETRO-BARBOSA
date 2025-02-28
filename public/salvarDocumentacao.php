<?php 

if (isset($_FILES['meuArquivo']) && !empty($_FILES['meuArquivo']['name'])) {
    
    $arquivoCaminho = "arquivos/VENDA/".$_POST['ID'].'/';

    if(! is_dir($arquivoCaminho) ){
        mkdir($arquivoCaminho, 0777, true);
    }

    $nomedoArquivo = date('Ymd_his').'_.._'.$_FILES['meuArquivo']['name'];

    $replacedChars = [
        '\\',
        '/',
        '|',
        '?',
        '<',
        '>',
        '*',
        ':',
        '“',
        '|',
        ' ',
        "#",
        "!",
        "@",
    ];
    
    $nomedoArquivo = str_replace($replacedChars, "_", $nomedoArquivo);

    if(move_uploaded_file($_FILES['meuArquivo']['tmp_name'],$arquivoCaminho.$nomedoArquivo)){
        die($arquivoCaminho.$nomedoArquivo);
    }else{
        die("error");
    }
} else { 
    die("error");
}