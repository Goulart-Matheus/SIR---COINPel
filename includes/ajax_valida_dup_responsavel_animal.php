<?php

include("./connection.php");

error_reporting(E_ALL);
header("Content-type: application/json");

$nome = trim($_GET["nome"]);
$cpf  = trim($_GET["cpf"]);

if($cpf != "")
{
    $cpf = str_replace(".", "", $cpf);
    $cpf = str_replace(",", "", $cpf);
    $cpf = str_replace("-", "", $cpf);
    $cpf = str_replace("/", "", $cpf);

    $where = " OR cpf = '" . $cpf . "'";
}

$query->exec("SELECT id_responsavel, nome, cpf, rg, dt_nascimento, endereco, b.descricao 
FROM responsavel, bairro b 
WHERE nome ilike '" . $nome . "' " . $where);
$n = $query->rows();

if($n){

    while ($n--) {
        $query->proximo();

        $query->record[4] = $query->record[4] == "" ? 0 : $query->record[4];
        $query->record[5] = $query->record[5] == "" ? 0 : $query->record[5];

        $query->record[2] = substr($query->record[2],0,3) . "." . substr($query->record[2],3,3) . "." . substr($query->record[2],6,3) . "-" . substr($query->record[2],9);

        $return[] = array("id_responsavel" => $query->record[0], 
                          "nome"           => $query->record[1], 
                          "cpf"            => $query->record[2],
                          "rg"             => $query->record[3],
                          "dt_nascimento"  => $query->record[4],
                          "endereco"       => $query->record[5],
                          "descricao"      => 1
                        );
    }

}else{

    $return[] = array("status" => 0);

}

echo json_encode($return);
