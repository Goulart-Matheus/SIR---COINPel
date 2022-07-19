<?
include('./session.php');
include('./variaveisAmbiente.php');


extract($_POST);
error_reporting(E_ALL);
header('Content-type: application/json');

$resposta = [];
$resposta['info'] = 1;


if (isset($nome_grupo)) { //Cadastra Responsável
    $query->begin();
    $query->insertTupla('responsavel', array(
        $nome,
        $cpf,
        $rg,
        $dt_nascimento,
        $endereco,
        $id_bairro,
        $_login,
        $_ip,
        $_data,
        $_hora,
        $_id_cliente,

    ));

    $id_responsavel = $query->last_insert[0];
    $query->commitNotMessage();

    $resposta['info'] = 1;
    $resposta['id_responsavel'] = $id_responsavel;
    $resposta['nome'] = $nome;
    $resposta['cpf'] = $cpf;
    $resposta['rg'] = $rg;
    $resposta['dt_nascimento'] = $dt_nascimento;
    $resposta['endereco'] = $endereco;
    $resposta['id_bairro'] = $id_bairro
      
    ;
} else {
    $resposta['info'] = 0;
}

echo json_encode($resposta);
