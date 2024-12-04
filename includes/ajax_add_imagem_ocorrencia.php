<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');

extract($_GET);
extract($_POST);

error_reporting(E_ALL);
header('Content-type: application/json');


if ($controle == 'atualiza_microrregiao') {

    $query_microrregiao = new Query($bd);

    $query_microrregiao->exec("select m.id_microrregiao, m.nome from denuncias.regiao_administrativa as r, denuncias.microrregiao as m where r.id_regiao_administrativa = m.id_regiao_administrativa and r.id_regiao_administrativa = $grupo_regiao ORDER BY m.nome");
    $n_query_microrregiao = $query_microrregiao->rows();

    $microrregiao = [];

    $c      = 0;

    while ($n_query_microrregiao--) {

        $query_microrregiao->proximo();

        $microrregiao[$c] = array(
            "id_microrregiao"   => $query_microrregiao->record['id_microrregiao'],
            "nome"          => $query_microrregiao->record['nome'],
        );

        $c++;
    }
    echo json_encode($microrregiao);
}
