<?
include("../includes/connection.php");

// error_reporting(E_ALL);
header("Content-type: application/json");

extract($_POST);

$method = $_POST['method'];


// error_reporting(E_ALL);
header('Content-type: application/json');

if (strlen($protocolo) == 20) {

    $query = new Query($bd);

    $query->exec("SELECT distinct o.id_ocorrencia,o.descricao,o.data,o.hora, ot.protocolo,o.status 
                  FROM            denuncias.ocorrencia as o, denuncias.ocorrencia_tramitacao as ot
                  WHERE o.id_ocorrencia = ot.id_ocorrencia AND ot.protocolo  =  '" . $protocolo . "' order by o.data desc");

    $valor = $query->rows();
    $dados = [];

    if ($valor > 0) {
        while ($valor--) {
            $query->proximo();
            $dados[] = array(
                'status' => 1,
                'id_ocorrencia' => $query->record[0],
                'descricao' => $query->record[1],
                'data' => $query->record[2],
                'hora' => $query->record[3],
                'protocolo' => $query->record[4],
                'situacao' => $query->record[5],
            );
        }
    } else {
        $dados[] = array(
            'status' => 0,

        );
    }
} else {

    $dados[] = array(
        'status' => 0,

    );
}

echo json_encode($dados);
