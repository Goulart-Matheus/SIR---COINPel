<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');

$where = "";
$where .= $form_nro_ficha   != "" ? " AND a.nro_ficha = $form_nro_ficha " : "";

$where .= $form_sexo        != "" ? " AND a.sexo = '" . $form_sexo . "' " : "";
$where .= $form_id_pelagem  != "" ? " AND a.id_pelagem = $form_id_pelagem " : "";
$where .= $form_id_especie  != "" ? " AND a.id_especie = $form_id_especie " : "";
$where .= $form_nro_chip    != "" ? " AND a.nro_chip = $form_nro_chip " : "";


$query->exec(
    "SELECT a.id_animal , a.nro_ficha , a.nro_chip , a.sexo , pe.descricao as pelagem , es.descricao as especie , a.observacao
              FROM animal as a, pelagem as pe, especie as es
              WHERE a.id_especie = es.id_especie AND
              a.id_pelagem = pe.id_pelagem" . $where

);

$sort = new Sort($query, $sort_icon, $sort_dirname, $sort_style);

if (!$sort_by)   $sort_by  = 1;
if (!$sort_dir)   $sort_dir = 0;

$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo   = "Ativo";
$report_periodo     = date('d/m/Y');

if ($print) {
    include('../class/class.report.php');

    unset($_GET['print']);

    $report_cabecalho = array(
        array('Código',              10, 0),
        array('Nro-ficha',              19, 1),
        array('Nro_chip',              19, 2),
        array('Id_pelagem',              19, 3),
        array('Id-Especie',              19, 4),
        array('Sexo',              19, 4),
        array('Observacao',              190, 4)
    );

    $query->exec($query->sql . $sort->sort_sql);

    $report = new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);

    exit;
} else {
    $paging = new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);

    if (isset($remove)) {

        if (!isset($id_animal)) {

            $erro = 'Nenhum item selecionado!';
        } else {

            $querydel = new Query($bd);


            for ($c = 0; $c < sizeof($id_animal); $c++) {

                $where = array(0 => array('id_animal', $id_animal[$c]));
                $querydel->deleteTupla('animal_responsavel', $where);
                $querydel->deleteTupla('animal', $where);
            };

            unset($_POST['id_animal']);
        }
    }

    $paging->exec($query->sql . $sort->sort_sql);
}

include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Animais', 'fa-solid fa-dog', $_SERVER['PHP_SELF']);
$tab->setTab('Novo Animal', 'fas fa-plus', 'ANIMAL_form.php');



$tab->printTab($_SERVER['PHP_SELF']);

$n = $paging->query->rows();

?>

<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
        <div class="card p-0">
            <div class="card-header border-bottom-1 mb-3 bg-light-2">

                <div class="row">

                    <div class="col-12 col-md-4 offset-md-4 text-center">
                        <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                    </div>

                    <div class="col-12 col-md-4 text-center text-md-right mt-2 mt-md-0">

                        <!-- Geração de Relatório -->
                        <button type="button" class="btn btn-sm btn-green text-light">
                            <i class="fas fa-print"></i>
                        </button>

                        <!-- Abre Modal de Filtro -->
                        <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#ANIMAL_view">
                            <i class="fas fa-search"></i>
                        </button>

                    </div>

                </div>

            </div>
            <div class="card-body pt-0">
                <table class="table table-sm text-sm">
                    <thead>
                        <tr>
                            <th colspan="7">Resultados de
                                <span class="range-resultados"><? echo $paging->getResultadoInicial() . "-" . $paging->getResultadoFinal() . "</span> 
                                    sobre <span class='numero-paginas'>" . $paging->getRows() . "</span>"; ?>
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>

                            <td style=' <? echo $sort->verifyItem(0); ?>' width="5px"></td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Nro Ficha'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(2); ?>'> <? echo $sort->printItem(2, $sort->sort_dir, 'Nro Chip'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(3); ?>'> <? echo $sort->printItem(3, $sort->sort_dir, 'Sexo'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(4); ?>'> <? echo $sort->printItem(4, $sort->sort_dir, 'Pelagem'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(5); ?>'> <? echo $sort->printItem(5, $sort->sort_dir, 'Especie'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(6); ?>'> <? echo $sort->printItem(6, $sort->sort_dir, 'Observacao'); ?> </td>
                        </tr>

                        <?

                        while ($n--) {

                            $paging->query->proximo();

                            $js_onclick = "OnClick=javascript:window.location=('ANIMAL_cover.php?id_animal=" . $paging->query->record[0] . "')";

                            echo "<tr class='entered'>";

                            echo "<td valign='middle'><input type=checkbox class='form-check-value' name='id_animal[]' value=" . $paging->query->record[0] . "></td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[2] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . ($paging->query->record[3] == "M" ? "Macho" : "Fêmea") . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[4] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[5] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[6] . "</td>";
                            echo "</tr>";
                        }

                        ?>

                    </tbody>

                    <tfoot>

                        <tr>
                            <td colspan="8">
                                <div class="text-center pt-2">
                                    <? echo $paging->viewTableSlice(); ?>
                                </div>
                            </td>
                        </tr>

                    </tfoot>

                </table>

            </div>

            <div class="card-footer bg-light-2">
                <?
                if ($paging->query->rows()) {
                    $btns = array('selectAll', 'remove');
                    include('../includes/dashboard/footer_forms.php');
                }
                ?>
            </div>

        </div>

    </form>

</section>

<?
include_once('../includes/dashboard/footer.php');
include 'ANIMAL_view.php'
?>