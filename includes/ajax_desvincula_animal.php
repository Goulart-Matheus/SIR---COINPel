<?php

include('./session.php');
include('./variaveisAmbiente.php');
include("../function/function.date.php");

error_reporting(E_ALL);
header("Content-type: application/json");

$ret['status'] = 1;

$ret['excluido'] = $id_animal_responsavel;

if (!isset($id_animal_responsavel)) {

    $ret['status'] = 0;
} else {

    $querydel = new Query($bd);

    $where = array(0 => array('id_animal_responsavel', $id_animal_responsavel));
    $querydel->deleteTupla('animal_responsavel', $where);
    $ret['status'] = 1;
}





echo json_encode($ret);
