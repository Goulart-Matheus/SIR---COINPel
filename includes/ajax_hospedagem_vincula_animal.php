<?

include('../includes/session.php');
include('../includes/connection.php');
include('../includes/variaveisAmbiente.php');

extract($_GET);
extract($_POST);
header('Content-type: application/json');

$id_animal = $_POST['id_animal'];


$query_responsavel = new Query($bd);
$query_responsavel->exec("SELECT  count(id_responsavel)
                    FROM hospedagem
                    WHERE id_animal = $id_animal                                  
                 ");
$query_responsavel->proximo();
$responsavel = $query_responsavel->record[0];

$query_valor = new Query($bd);
$query_valor->exec("SELECT  count(a.id_animal)
                                  FROM hospedagem as h, animal as a
                                  WHERE h.id_animal = a.id_animal AND
                                        a.id_animal = $id_animal   AND
                                        h.dt_entrada <= '" . $_data . "'                     
                                        ");

$query_valor->proximo();
$quantidade = $query_valor->record[0];


if ($id_urm != "") {

    $query_urm_ajax = new Query($bd);

    $query_urm_ajax->exec("SELECT valor FROM urm  WHERE id_urm = $id_urm");
    $n_urm_ajax = $query_urm_ajax->rows();

    if ($n_urm_ajax > 0) {

        $query_urm_ajax->proximo();

        if ($quantidade > 0) {

            $valor = $query_urm_ajax->record[0] * ($quantidade + 1);
        } else {

            $valor = $query_urm_ajax->record[0];
        }
    }
}


$query_valores = new Query($bd);

if ($responsavel > 0) {
    $query_valores->exec("  SELECT   
                                a.id_animal,a.nro_ficha , a.nro_chip, ar.id_responsavel , e.descricao, p.descricao
                        FROM 
                                animal as a, animal_responsavel as ar, especie as e, pelagem as p
                        WHERE 
                                ar.id_animal =  a.id_animal 
                                AND a.id_animal =$id_animal
                                AND p.id_pelagem = a.id_pelagem 
                                AND e.id_especie = a.id_especie");
    $tipo = 1;
} else {
    $where ="";
    $where .= "AND e.id_animal = a.id_animal";
    $query_valores->exec("  SELECT   
                                    a.id_animal, a.nro_ficha , a.nro_chip, e.descricao, a.sexo, p.descricao
                            FROM 
                                    animal as a, especie as e, pelagem as p
                            WHERE       
                            id_animal = $id_animal 
                            AND p.id_pelagem = a.id_pelagem 
                            AND e.id_especie = a.id_especie");
    $tipo = 2;
}
/*
$query_dados = new Query($bd);
$query_dados->exec("SELECT  e.descricao, p.descricao
                    FROM animal as a , especie as e , pelagem as p
                    WHERE e.id_especie = a.id_especie 
                    AND p.id_pelagem = a.id_pelagem");
$query_dados->proximo();
*/

if ($query_valores->rows() > 0) {

    $n = $query_valores->rows();
    while ($n--) {

        if ($tipo == 1) {
            $query_valores->proximo();
            $ret[] = array(
                "resultado"             =>  1,
                "id_animal"                             =>  trim($query_valores->record['id_animal']),
                "nro_ficha"                             =>  trim($query_valores->record['nro_ficha']),
                "nro_chip"                              =>  trim($query_valores->record['nro_chip']),
                'id_responsavel'                        =>  trim($query_valores->record['id_responsavel']),
                'valor'                        =>  $valor,
                'reincidencias'                => $quantidade,
                "especie"                            =>  trim($query_valores->record[4]),
                "sexo"                                  =>  trim($query_valores->record['sexo']),
                "pelagem"                            =>  trim($query_valores->record[5]),
            );
        }
        if ($tipo == 2) {
            $query_valores->proximo();
            $ret[] = array(
                "resultado"             =>  2,
                "id_animal"                             =>  trim($query_valores->record['id_animal']),
                "nro_ficha"                             =>  trim($query_valores->record['nro_ficha']),
                "nro_chip"                              =>  trim($query_valores->record['nro_chip']),
                'valor'                                 =>  $valor,
                'reincidencias'                         => $quantidade,
                "especie"                            =>  trim($query_valores->record[3]),
                "sexo"                                  =>  trim($query_valores->record['sexo']),
                "pelagem"                            =>  trim($query_valores->record[5]),
            );
        }
    }
} else {
    $ret[] = array("resultado" => 0);
}

echo json_encode($ret);
