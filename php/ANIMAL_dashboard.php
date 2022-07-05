<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
include('../function/function.date.php');


$query->exec(
    "SELECT id_animal , nro_ficha , nro_chip , sexo , id_pelagem , id_especie
              FROM animal
              WHERE id_animal>=1" . $where
);

$query->result($query->linha);

$id_animal    = $query->record[0];
$nro_ficha    = $query->record[1];
$nro_chip     = $query->record[2];
$sexo         = $query->record[3];
$id_pelagem   = $query->record[4];
$id_especie   = $query->record[5];


$tab = new Tab();

$tab->setTab($query->record[1], $query->record[5], $_SERVER['PHP_SELF'] . '?id_animal='. $id_animal);
$tab->setTab('Editar', 'fas fa-pencil-alt', 'ANIMAL_edit.php?id_animal='. $id_animal);
$tab->setTab('Cadastrar', 'fas fa-comments', 'ANIMAL_form.php?id_animal='. $id_animal);
$tab->setTab('Informação de Animais Cadastrados', 'fas fa-info-circle', 'ANIMAL_form.php?id_animal='. $id_animal);



$tab->printTab($_SERVER['PHP_SELF'] . '?id_animal=' . $query->record[0]);

?>

<section class="content">

    <div class="card p-0">

        <div class="card-header border-bottom-1 mb-3 bg-light-2">

            <div class="row">

                <div class="col-12 col-md-1 text-center">

                    <h1>
                        <i class="<?= $query->record[5] ?>"></i>
                    </h1>

                </div>

                <div class="col-12 col-md-10 text-center text-md-left">

                    <div class="row">

                        <div class="col-12 col-md-6">

                            <div class="row">
                                <div class="col-12">
                                    <h5>
                                        <?= $query->record[1] ?><br>
                                        <small><?= $query->record[2] ?></small>
                                    </h5>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">

                                </div>
                            </div>

                        </div>

                    </div>

                    
                </div>

            </div>


        </div>

        <div class="card-body pt-0">

            <div class="row">

                <div class="col-12">

                    <? include("../includes/cards/dashboard_orgao/card_dashboard_orgao_horizontal.php"); ?>

                </div>

            </div>

            <div class="row">

                <div class="col-12 col-md-4">

                    <? include("../includes/cards/dashboard_orgao/card_dashboard_orgao_pedido_informacao.php"); ?>

                </div>

                <div class="col-12 col-md-4">

                    <? include("../includes/cards/dashboard_orgao/card_dashboard_orgao_pedido_providencia.php"); ?>

                </div>

                <div class="col-12 col-md-4">

                    <? include("../includes/cards/dashboard_orgao/card_dashboard_orgao_pedido_reuniao.php"); ?>

                </div>

            </div>

        </div>

    </div>

</section>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    var deferido = <?= $deferido ?>;
    var nao_deferido = <?= $outros   ?>;

    var total_i = <?= $total_i ?>

    function drawChart() {

        ///////////////////////////////////////////////////////////////////////////////////////////// PEDIDO DE INFORMAÇÃO
        if (total_i == 0) {
            var data_ped_info = google.visualization.arrayToDataTable([
                ['Área', 'Total'],
                ['Nenhum Pedido', 100]
            ]);

            var options_ped_info = {
                title: '',
                colors: ['#F3EFE7'],
                legend: 'none',
                pieHole: 0.8
            };
        } else {
            var data_ped_info = google.visualization.arrayToDataTable([
                ['Área', 'Total'],
                ['Respondidos', <?= $resp ?>],
                ['Próximos do Prazo Final', <?= $paresp ?>],
                ['Atrasados', <?= $aresp ?>],
                ['Resposta Provisória', <?= $presp ?>],
                ['Sem Respondidos', <?= $nresp ?>]
            ]);

            var options_ped_info = {
                title: '',
                colors: ['#28a745', '#ffc107', '#dc3545', '#17a2b8', '#343a40'],
                legend: 'none',
                pieHole: 0.8,
            };
        }


        var chart = new google.visualization.PieChart(document.getElementById('chart_info'));
        chart.draw(data_ped_info, options_ped_info);

        ///////////////////////////////////////////////////////////////////////////////////////////// PEDIDO DE PROVIDÊNCIAS
        if (deferido + nao_deferido == 0) {
            var data = google.visualization.arrayToDataTable([
                ['Área', 'Total'],
                ['Nenhum Pedido', 100]
            ]);

            var options = {
                title: '',
                colors: ['#F3EFE7'],
                legend: 'none',
                pieHole: 0.8
            };
        } else {
            var data = google.visualization.arrayToDataTable([
                ['Área', 'Total'],
                ['Atendidos', <?= $deferido ?>],
                ['Não Atendidos', <?= $outros   ?>]
            ]);

            var options = {
                title: '',
                colors: ['#17a2b8', '#6f42c1'],
                legend: 'none',
                pieHole: 0.8
            };
        }

        var chart = new google.visualization.PieChart(document.getElementById('chart_prov'));
        chart.draw(data, options);

    }
</script>

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