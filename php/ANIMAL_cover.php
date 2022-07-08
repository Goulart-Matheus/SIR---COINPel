<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
include('../function/function.date.php');

$query->exec("SELECT 
                    a.id_animal , a.nro_ficha , a.nro_chip , a.sexo , a.observacao, p. descricao , e.descricao
                    FROM 
                        animal a, pelagem p , especie e
                    WHERE 
                        id_animal = $id_animal
                        AND 
                        a.id_pelagem = p.id_pelagem
                        AND 
                        a.id_especie = e.id_especie

                ");

$query->result($query->linha);

$id_animal         = $query->record[0];
$nro_ficha         = $query->record[1];
$nro_chip          = $query->record[2];
$id_pelagem        = $query->record[3];
$id_especie        = $query->record[4];
$sexo              = $query->record[5];
$observacao        = $query->record[6];

$tab = new Tab();

$tab->setTab('Pesquisar', 'fas fa-search', 'ANIMAL_view.php?id_animal='                  . $id_animal);
$tab->setTab($query->record[1], $query->record[5], $_SERVER['PHP_SELF'] .'?id_animal='   . $id_animal);
$tab->setTab('Editar', 'fas fa-pencil-alt', 'ANIMAL_edit.php?id_animal='                 . $id_animal);
$tab->printTab($_SERVER['PHP_SELF'] . '?id_animal=' . $query->record[0]);

?>


<section class="content">

    <div class="card p-0">

        <div class="card-header border-bottom-1 mb-3 bg-light-2">

            <div class="text-center">
                <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
            </div>

            <div class="row text-center">
                <div class="col-12 col-sm-4 offset-sm-4">
                    <? if ($erro) echo callException($erro, 2); ?>
                </div>
            </div>

        </div>


        <div class="card-body pt-3">


            <div class="row">

                <div class="col-12">

                    <? include("../includes/Cards/Dashboard_ANIMAL/card_dashboar_ANIMAL_FICHA.php"); ?>

                </div>

            </div>

            <div class="row">

                <div class="col-12 col-md-4">

                    <? include("../includes/Cards/Dashboard_ANIMAL/card_dashboard_ANIMAL_RESPONSAVEL.php"); ?>


                </div>



                <div class="col-12 col-md-12">

                    <? include("../includes/Cards/Dashboard_ANIMAL/card-dashboard_ANIMAL_OCORRENCIA.php"); ?>

                </div>

            </div>

        </div>

    </div>

</section>


<?
include_once('../includes/dashboard/footer.php');

function isNum($val)
{
    if (is_numeric($val)) {
        if (strtoupper($val) != "NAN") {
            return intval($val);
        }
    }
    return 0;
}
?>