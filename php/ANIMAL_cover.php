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

$tab->setTab('Pesquisar', 'fas fa-search', 'ANIMAL_view.php?id_ANIMAL=' . $id_animal);
$tab->setTab($query->record[1], $query->record[5], $_SERVER['PHP_SELF'] . '?id_animal=' . $id_animal);
$tab->setTab('Editar', 'fas fa-pencil-alt', 'ANIMAL_edit.php?id_animal=' . $id_animal);
$tab->printTab($_SERVER['PHP_SELF'] . '?id_animal=' . $query->record[0]);

?>

<section class="content">

    <div class="card p-0">

        <div class="card-header border-bottom-1 mb-3 bg-light-2">

            <div class="row">

                <div class="col-12 col-md-1 text-center">

                    <h1>
                        <i class="<?= $query->record[1] ?>"></i>
                    </h1>

                </div>

                <div class="col-12 col-md-10 text-center text-md-left">

                    <div class="row">

                        <div class="col-12 col-md-6">

                            <div class="row">
                                <div class="col-12">
                                    <h5>Nro_ficha:
                                        <?= $query->record[1]                           ?><br><br>
                                        <small>Nro_chip:       <?= $query->record[2] ?></small><br>
                                        <small>Sexo:           <?= $query->record[3] ?></small><br>
                                        <small>Id_pelagem:     <?= $query->record[5] ?></small><br>
                                        <small>Especie:        <?= $query->record[6] ?></small><br>
                                        <small>Observacao:     <?= $query->record[4] ?></small><br>
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

                    <? include("../includes/cards/dashboard_ANIMAL/dashboard_orgao/card_dashboard_ANIMAL_horizontal.php"); ?>

                </div>

            </div>

            <div class="row">

                <div class="col-12 col-md-6">

                    <? include("../includes/cards/dashboard_ANIMAL/card_dashboard_ANIMAL_INFO.php"); ?>

                </div>

                <div class="col-12 col-md-6">

                    <? include("../includes/cards/dashboard_ANIMAL/card_dashboard_ANIMAL_RESPONSAVEL.php"); ?>

                </div>

                <div class="col-12 col-md-12">

                    <? include("../includes/cards/dashboard_ANIMAL/card_dashboard_ANIMAL_OCORRENCIA.php"); ?>

                </div>

            </div>

        </div>

    </div>

</section>
<!--

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">

        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        var deferido     = <? //= $deferido 
                            ?>;
        var nao_deferido = <? //= $outros   
                            ?>;

        var total_i = <? //= $total_i
                        ?>

        function drawChart() {

            ///////////////////////////////////////////////////////////////////////////////////////////// PEDIDO DE INFORMAÇÃO
            if(total_i == 0)
            {
                var data_ped_info = google.visualization.arrayToDataTable([
                    ['Área', 'Total'],
                    ['Nenhum Pedido',100]
                ]);

                var options_ped_info = {  
                        title: ''   ,
                        colors:['#F3EFE7'],
                        legend: 'none',
                        pieHole: 0.8
                    };
            }
            else
            {
                var data_ped_info = google.visualization.arrayToDataTable([
                    ['Área', 'Total'],
                    ['Respondidos'              ,  <? //=   $resp 
                                                    ?>],
                    ['Próximos do Prazo Final'  ,  <? //= $paresp 
                                                    ?>],
                    ['Atrasados'                ,  <? //=  $aresp 
                                                    ?>],
                    ['Resposta Provisória'      ,  <? //=  $presp 
                                                    ?>],
                    ['Sem Respondidos'          ,  <? //=  $nresp 
                                                    ?>]
                ]);

                var options_ped_info = {
                    title: ''   ,
                    colors:['#28a745','#ffc107','#dc3545','#17a2b8','#343a40'],
                    legend: 'none',
                    pieHole: 0.8,
                };
            }


            var chart = new google.visualization.PieChart(document.getElementById('chart_info'));
            chart.draw(data_ped_info, options_ped_info);

            ///////////////////////////////////////////////////////////////////////////////////////////// PEDIDO DE PROVIDÊNCIAS
            if(deferido+nao_deferido == 0)
            {
                var data = google.visualization.arrayToDataTable([
                    ['Área', 'Total'],
                    ['Nenhum Pedido',100]
                ]);

                var options = { title: ''   ,
                                colors:['#F3EFE7'],
                                legend: 'none',
                                pieHole: 0.8
                               };
            }
            else
            {
                var data = google.visualization.arrayToDataTable([
                    ['Área', 'Total'],
                    ['Atendidos'     ,  <? //= $deferido 
                                        ?>],
                    ['Não Atendidos' ,  <? //= $outros   
                                        ?>]
                ]);

                var options = { title: ''   ,
                                colors:['#17a2b8','#6f42c1'],
                                legend: 'none',
                                pieHole: 0.8
                               };
            }
            
            var chart = new google.visualization.PieChart(document.getElementById('chart_prov'));
            chart.draw(data, options);

        }

    </script>  -->

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