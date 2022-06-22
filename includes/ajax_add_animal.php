<?
include('./session.php');
include('./variaveisAmbiente.php');


extract($_POST);
error_reporting(E_ALL);
header('Content-type: application/json');

$resposta = [];
$resposta['info'] = 1;


if (isset($nro_ficha)) { //Cadastra Animal
    $query->begin();
    $query->insertTupla('animal', array(
        $nro_ficha,
        $nro_chip,
        $id_pelagem,
        $id_especie,
        $sexo,
        $_login,
        $_ip,
        $_data,
        $_hora

    ));

    $id_animal = $query->last_insert[0];
    $query->commitNotMessage();

    $resposta['info'] = 1;
    $resposta['nro_ficha'] = $nro_ficha;
    $resposta['nro_chip'] = $nro_chip;
    $resposta['id_pelagem'] = $id_pelagem;
    $resposta['id_especie'] = $id_especie;
    $resposta['sexo'] = $sexo;
    

} else {
    $resposta['info'] = 0;
}

echo json_encode($resposta);
?>