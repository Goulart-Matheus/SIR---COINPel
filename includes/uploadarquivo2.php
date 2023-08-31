<?php

$arquivo = isset($_FILES["form_arquivo"]) ? $_FILES["form_arquivo"] : FALSE;


if ($arquivo) {

    if (!preg_match("/^image|^application|^text\/(vnd.oasis.opendocument.spreadsheet|vnd.oasis.opendocument.text|octet-stream|plain|msword|pdf|pjpeg|jpeg|png|gif|bmp|odt|ods)$/", $arquivo["type"] , $matches)) 
    { // Verifica o Tipo do Arquivo
        if (!preg_match("/\.(xls|doc|pdf|txt|gif|png|jpg|jpeg|odt|ods){1}$/i", $arquivo["name"])) 
        { // Verifica a extens�o
            $erro = "Arquivo em formato inválido! O arquivo deve ser xls, doc, txt, pdf, jpg, gif, odt, ods ou png.<br>";
    	}
    }
    else
    {
        preg_match("/\.(xls|doc|pdf|txt|gif|png|jpg|jpeg|odt|ods){1}$/i", $arquivo["name"], $ext);
        $arquivo_nome = md5(uniqid(time())) . "." . $ext[1];
        $arquivo_dir = "$diretorio/" . $arquivo_nome;
        if (!move_uploaded_file($arquivo["tmp_name"], $arquivo_dir)) $erro="Erro ao enviar o arquivo !<br>";
    }
}