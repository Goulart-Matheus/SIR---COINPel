<?
include('../includes/session.php');
include('../includes/connection.php');
include('../includes/variaveisAmbiente.php');

extract($_GET);
extract($_POST);
header('Content-type: application/json');

if ($reincidencias == "") {
    $where = "";
    $where .= $dt_entrada     != "" ? " AND  h.dt_alteracao <= '" . $dt_entrada . "'  " : "";

    $query_animal_atendimentos = new Query($bd);
    $query_animal_atendimentos->exec("SELECT  count(a.id_animal)
                                      FROM hospedagem as h, animal as a
                                      WHERE h.id_animal = a.id_animal AND
                                            a.nro_chip = $nro_chip                          
                                            " . $where);
    if ($query_animal_atendimentos->rows() > 0) {
        $query_animal_atendimentos->proximo();
        $reincidencias = $query_animal_atendimentos->record[0];
        $identificador = 1;
    }
}


if ($id_urm != "" && $reincidencias != "") {

    $query_urm_ajax = new Query($bd);

    $query_urm_ajax->exec("SELECT valor FROM urm  WHERE id_urm = $id_urm");
    $n_urm_ajax = $query_urm_ajax->rows();

    if ($n_urm_ajax > 0) {

        $query_urm_ajax->proximo();

        if ($reincidencias > 0) {

            if ($identificador == 1) {
                $ret['valor'] = intval($query_urm_ajax->record[0] * ($reincidencias + 1));
            } else {
                $ret['valor'] = intval($query_urm_ajax->record[0] * ($reincidencias));
            }
        } else {

            $ret['valor'] = intval($query_urm_ajax->record[0]);
        }

        $ret['status'] = 1;
        $ret['quantidade'] = $reincidencias;
    } else {

        $ret['status'] = 0;
    }
} else {

    $ret['status'] = 0;
}

echo json_encode($ret);
