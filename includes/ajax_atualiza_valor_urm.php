<?
include('../includes/session.php');
include('../includes/connection.php');
include('../includes/variaveisAmbiente.php');

extract($_GET);
extract($_POST);
header('Content-type: application/json');


$query_urm_ajax = new Query($bd);

$query_urm_ajax->exec("SELECT valor FROM urm  WHERE id_urm = $id_urm");
$n_urm_ajax = $query_urm_ajax->rows();

if($n_urm_ajax>0){
    $query_urm_ajax->proximo();
    $ret['valor']=$query_urm_ajax->record[0];
    $ret['status']=1;
}else{
    $ret['status']=0;
}

echo json_encode($ret);
?>