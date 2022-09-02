<?php

include('./session.php');
include('./variaveisAmbiente.php');
include("../function/function.date.php");

$query_aux = new Query($bd);

error_reporting(E_ALL);
header("Content-type: application/json");



$ret['status'] = 1;
$ret['excluido'] = $id_responsavel_animal;

if (!isset($id_responsavel_animal)) {

    $ret['status'] = 0;
    
} else {

    $querydel = new Query($bd);

    $where = array(0 => array('id_animal_responsavel', $id_responsavel_animal));
    $querydel->deleteTupla('animal_responsavel', $where);
}


echo json_encode($ret);
