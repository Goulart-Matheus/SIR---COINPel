<?
include('../includes/session.php');
include('../includes/connection.php');
include('../includes/variaveisAmbiente.php');

extract($_GET);
extract($_POST);
header('Content-type: application/json');


$query_animal_atendimentos = new Query($bd);

if ($dt_entrada != "" && $id_animal != "") {

    $where = "";
    $where .= $dt_entrada     != "" ? " AND  dt_alteracao <= '" . $dt_entrada . "'  " : "";

    $query_animal_atendimentos->exec("SELECT  count(id_animal)
                                  FROM hospedagem
                                  WHERE id_animal = $id_animal                           
                                " . $where);

    $query_animal_atendimentos->proximo();
    $quantidade = $query_animal_atendimentos->record[0];
}

if ($id_urm != "") {

    $query_urm_ajax = new Query($bd);

    $query_urm_ajax->exec("SELECT valor FROM urm  WHERE id_urm = $id_urm");
    $n_urm_ajax = $query_urm_ajax->rows();

    if ($n_urm_ajax > 0) {
        
        $query_urm_ajax->proximo();

        if ($quantidade > 0) {

            $ret['valor'] = $query_urm_ajax->record[0] * $quantidade;
        } else {

            $ret['valor'] = $query_urm_ajax->record[0];
        }
        $ret['status'] = 1;
        $ret['quantidade'] = $quantidade;

    } else {

        $ret['status'] = 0;
    }
} else {

    $ret['status'] = 0;
}



echo json_encode($ret);
