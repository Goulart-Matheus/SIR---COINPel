<?
include('../includes/session.php');
include('../includes/connection.php');
include('../includes/variaveisAmbiente.php');

extract($_GET);
extract($_POST);
header('Content-type: application/json');

$query_animal_atendimentos = new Query($bd);
$query_animal = new Query($bd);

$where = "";
$where .= $dt_entrada     != "" ? " AND  h.dt_alteracao <= '" . $dt_entrada . "'  " : "";

if ($dt_entrada != "") {

    switch ($identificador) {

        case "form_nro_ficha": {
                $query_animal_atendimentos->exec("SELECT  count(a.id_animal)
                                  FROM hospedagem as h, animal as a
                                  WHERE h.id_animal = a.id_animal AND
                                        a.nro_ficha = $nro_ficha                          
                                        " . $where);

                $query_animal->exec("SELECT a.nro_ficha, a.nro_chip, ar.id_responsavel, a.id_animal
                                     FROM animal as a, animal_responsavel as ar
                                     WHERE a.id_animal = ar.id_animal AND
                                           a.nro_ficha = $nro_ficha
                                           ORDER BY ar.id_responsavel DESC
                                    ");
                if ($query_animal->rows() > 0) {

                    $query_animal->proximo();
                    $ret['nro_ficha'] = $query_animal->record[0];
                    $ret['nro_chip'] = $query_animal->record[1];
                    $ret['id_responsavel'] = $query_animal->record[2];
                    $ret['id_animal'] = $query_animal->record[3];
                } else {

                    $query_animal->exec("SELECT nro_ficha, nro_chip, id_animal
                                        FROM animal
                                        WHERE nro_ficha = $nro_ficha                                           
                                        ");
                    if ($query_animal->rows() > 0) {

                        $query_animal->proximo();
                        $ret['nro_ficha'] = $query_animal->record[0];
                        $ret['nro_chip'] = $query_animal->record[1];
                        $ret['id_responsavel'] = null;
                        $ret['id_animal'] = $query_animal->record[2];
                    }
                }
            }
            break;

        case "form_nro_chip": {
                $query_animal_atendimentos->exec("SELECT  count(a.id_animal)
                                  FROM hospedagem as h, animal as a
                                  WHERE h.id_animal = a.id_animal AND
                                        a.nro_chip = $nro_chip                          
                                        " . $where);

                $query_animal->exec("SELECT a.nro_ficha, a.nro_chip, ar.id_responsavel, a.id_animal
                                     FROM animal as a, animal_responsavel as ar
                                     WHERE a.id_animal = ar.id_animal AND
                                     a.nro_chip = $nro_chip
                                     ORDER BY ar.id_responsavel DESC
                                    ");
                if ($query_animal->rows() > 0) {

                    $query_animal->proximo();
                    $ret['nro_ficha'] = $query_animal->record[0];
                    $ret['nro_chip'] = $query_animal->record[1];
                    $ret['id_responsavel'] = $query_animal->record[2];
                    $ret['id_animal'] = $query_animal->record[3];
                } else {

                    $query_animal->exec("SELECT nro_ficha, nro_chip, id_animal
                                        FROM animal
                                        WHERE nro_chip = $nro_chip                                        
                                        ");
                    if ($query_animal->rows() > 0) {

                        $query_animal->proximo();
                        $ret['nro_ficha'] = $query_animal->record[0];
                        $ret['nro_chip'] = $query_animal->record[1];
                        $ret['id_responsavel'] = null;
                        $ret['id_animal'] = $query_animal->record[2];
                    }
                }
            }
            break;

        case "urm": {
                $query_animal_atendimentos->exec("SELECT  count(a.id_animal)
                                  FROM hospedagem as h, animal as a
                                  WHERE h.id_animal = a.id_animal AND
                                        a.nro_chip = $nro_chip                          
                                        " . $where);
                $ret['urm'] = 1;
            }
            break;


            // case "form_id_responsavel": {
            //         $query_animal_atendimentos->exec("SELECT  count(a.id_animal)
            //                           FROM hospedagem as h, animal as a, animal_responsavel as ar, responsavel as r
            //                           WHERE h.id_animal = a.id_animal AND
            //                                 ar.id_animal = a.id_animal AND
            //                                 ar.id_responsavel = r.id_responsavel AND
            //                                 ar.id_responsavel = $id_responsavel                          
            //                                 " . $where);
            //         $ret['sql'] = $query_animal_atendimentos->sql;
            //         $ret['identificador'] = $identificador;
            //     }
            //     break;

        default:

            break;
    }
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

            $ret['valor'] = $query_urm_ajax->record[0] * ($quantidade + 1);
            
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
