<?
include('../includes/connection.php');


extract($_GET);
extract($_POST);
header('Content-type: application/json');


$query = new Query($bd);

$query->exec("SELECT ot.status 
                  FROM   denuncias.ocorrencia as o, denuncias.ocorrencia_tramitacao as ot
                  WHERE  o.id_ocorrencia = ot.id_ocorrencia
                  AND o.id_ocorrencia = $id_ocorrencia order by ot.id_ocorrencia_tramitacao  desc");

$valor = $query->rows();
$dados = [];

if ($valor > 0) {

    $query->proximo();
    $dados[] = array(
        'status' => $query->record[0],
    );
} else {
    $dados[] = array(
        'status' => 0,

    );
}


echo json_encode($dados);
