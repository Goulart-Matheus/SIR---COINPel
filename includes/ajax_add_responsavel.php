<?
include('../includes/session.php');
include('../includes/connection.php');
include('../includes/variaveisAmbiente.php');

extract($_GET);
extract($_POST);
header('Content-type: application/json');


$query_add_responsavel = new Query($bd);

$ret['status']=1;



echo json_encode($ret);
?>