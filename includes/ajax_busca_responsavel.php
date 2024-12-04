<?

include('../includes/session.php');
include('../includes/connection.php');
include('../includes/variaveisAmbiente.php');

extract($_GET);
extract($_POST);
header('Content-type: application/json');



$where = "";
$where .= $cpf    != "" ? " AND r.cpf   ilike '%".$cpf."%' "  : "";
$where .= $rg     != "" ? " AND r.rg    ilike '%".$rg."%'  "  : "";

$query_valores = new Query($bd);

$query_valores->exec("SELECT   r.id_responsavel, r.nome, r.cpf , r.rg , r.dt_nascimento, r.endereco, b.descricao
                              FROM responsavel r , bairro b
                              WHERE r.id_bairro = b.id_bairro AND 
                                    r.nome ilike '%" . $nome . "%'    
                                " . $where);




if ($query_valores->rows() > 0) {
    $n = $query_valores->rows();
    while ($n--) {
        $query_valores->proximo();
        $ret[] = array(
            "resultado"             =>  1,
            "id_responsavel"                   =>  trim($query_valores->record['id_responsavel']),
            "nome"                             =>  trim($query_valores->record['nome']),
            "cpf"                              =>  trim($query_valores->record['cpf']),
            "rg"                               =>  trim($query_valores->record['rg']),
            "dt_nascimento"                    =>  trim($query_valores->record['dt_nascimento']),
            "endereco"                         =>  trim($query_valores->record['endereco']),
            "bairro"                           =>  trim($query_valores->record['descricao']),


        );
    }
} else {
    $ret[] = array("resultado" => 0);
}

echo json_encode($ret);
