<?
include('./session.php');
include('./variaveisAmbiente.php');


extract($_POST);
error_reporting(E_ALL);
header('Content-type: application/json');

$resposta = [];
$resposta['info'] = 1;

//responsavel animal
if (isset($nome_grupo)) { //Cadastra Responsável
    $query->begin();
    $query->insertTupla('responsavel', array(
       
        $nome,
        $cpf,
        $rg,
        $_login,
        $_ip,
        $_data,
        $_hora,
        
     ));

    $id_responsavel = $query->last_insert[0];
    $query->commitNotMessage();

    $resposta['info'] = 1;
    $resposta['id_responsavel'] = $id_responsavel;
    $resposta['nome'] = $nome;
    
} else {
    $resposta['info'] = 0;
}

echo json_encode($resposta);
