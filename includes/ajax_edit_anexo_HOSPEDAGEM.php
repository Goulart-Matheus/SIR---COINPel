<?php

include('./session.php');
include('./variaveisAmbiente.php');
include("../function/function.date.php");

$query_anexo_ajax = new Query($bd);
$query = new Query($bd);

error_reporting(E_ALL);
header("Content-type: application/json");


if ($form_arquivo == 'undefined') {

    $query_anexo_ajax->exec("SELECT *
            FROM hospedagem_anexo
            WHERE id_hospedagem_anexo = $id_hospedagem_anexo                    
        ");

    if ($query_anexo_ajax->rows() > 0) {
        $query_anexo_ajax->proximo();

        if (strcmp(trim($query_anexo_ajax->record['nome']), trim($form_nome)) == 0) {

            $ret['status'] = 'Sem alterações realizadas';
        } else {

            $query->begin();

            $itens = array(
                $id_hospedagem,
                $form_nome,
                $query_anexo_ajax->record['arquivo'],
                $_login,
                $_ip,
                $_data,
                $_hora,
            );

            $where = array(0 => array('id_hospedagem_anexo', $id_hospedagem_anexo));
            $query->updateTupla('hospedagem_anexo', $itens, $where);
            $query->commitNotMessage();


            $ret['status'] = 'nome alterado com sucesso';
            $ret['query'] = $query->sql;
        }
    }
} else {


    $query_anexo_ajax->exec("SELECT *
            FROM hospedagem_anexo
            WHERE id_hospedagem_anexo = $id_hospedagem_anexo                    
        ");

    if ($query_anexo_ajax->rows() > 0) {
        $query_anexo_ajax->proximo();

        unlink("../arquivos/" . $query_anexo_ajax->record['login'] . "/" . $query_anexo_ajax->record['arquivo']);
    }

    if (trim($_FILES['form_arquivo']['name']) != '') {
        $diretorio = "../arquivos/$_login/";

        include('../includes/uploadarquivo2.php');

        $form_arquivo = $arquivo_nome;

        $itens = array(
            $id_hospedagem,
            $form_nome,
            $form_arquivo,
            $_login,
            $_ip,
            $_data,
            $_hora
        );

        $where = array(0 => array('id_hospedagem_anexo', $id_hospedagem_anexo));
        $query->updateTupla('hospedagem_anexo', $itens, $where);
        $query->commitNotMessage();
        $ret['status'] = 'Atualizado com sucesso';
    }
}

$query_anexo_ajax->exec("SELECT *
                        FROM hospedagem_anexo
                        WHERE id_hospedagem = $id_hospedagem                    
");

if ($query_anexo_ajax->rows() > 0) {

    $n_anexo = $query_anexo_ajax->rows();

    while ($n_anexo--) {

        $query_anexo_ajax->proximo();
        $ret[] = array(
            "resultado"                             =>  1,
            "nome"                                  =>  trim($query_anexo_ajax->record['nome']),
            "login"                                 =>  trim($query_anexo_ajax->record['login']),
            "arquivo"                               =>  trim($query_anexo_ajax->record['arquivo']),
            "dt_alteracao"                          =>  trim($query_anexo_ajax->record['dt_alteracao']),
            "id_hospedagem_anexo"                   =>  trim($query_anexo_ajax->record['id_hospedagem_anexo']),

        );
    }
} else {
    
    $ret[] = array("resultado" => 0);
}


echo json_encode($ret);
