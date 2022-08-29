<?
    include('../includes/session.php');
    include('../includes/variaveisAmbiente.php');
    include_once('../includes/dashboard/header.php');
    include('../class/class.tab.php');
    include('../function/function.date.php');


    $query->exe("h.id_hospedagem, h.id_animal, h.endereco_recolhimento, h.bairro , h.responsavel , h.dt_entrada , h.dt_retirada
    FROM hospedagem as h ");



?>