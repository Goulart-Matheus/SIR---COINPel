<?php

include('./session.php');
include('./variaveisAmbiente.php');
include("../function/function.date.php");

$query_anexo_ajax = new Query($bd);
$querydel = new Query($bd);

error_reporting(E_ALL);
header("Content-type: application/json");



$ret['status'] = 1;
$ret['excluido'] = $id_anexo;

if (!isset($id_anexo)) {

    $ret['status'] = 0;

} else {

    $query_anexo_ajax->exec("SELECT *
              FROM hospedagem_anexo
              WHERE id_hospedagem_anexo = $id_anexo                    
              ");     

    if ($query_anexo_ajax->rows() > 0) {
        $query_anexo_ajax->proximo();        

        unlink("../arquivos/" . $query_anexo_ajax->record['login'] . "/" . $query_anexo_ajax->record['arquivo']);  

        $where = array(0 => array('id_hospedagem_anexo', $id_anexo));
        $querydel->deleteTupla('hospedagem_anexo', $where);

        $ret['status'] = 1;
       
    }else{
        $ret['status'] = 0;
    }
}


echo json_encode($ret);
