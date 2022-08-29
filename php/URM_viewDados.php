<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');


$where = "";

$where .= $form_valor            != ""  ? " AND valor           = $form_valor        "  : "";
$where .= $form_mes_referencia   != ""  ? " AND mes_referencia  = $form_mes_referencia" : "";
$where .= $form_ano_referencia   != ""  ? " AND ano_referencia  = $form_ano_referencia" : "";

$query->exec("SELECT id_urm , valor , ativo , mes_referencia , ano_referencia
                    FROM urm
                   WHERE ativo ilike '%$form_ativo%'
                   
                " . $where);

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
        array('valor',              190, 1),
        array('ativo',              190, 2),
        array('Mes_referencia',     190, 3),
        array('Ano_referencia',     190, 4)
    );

    $query->exec($query->sql . $sort->sort_sql);

    $report = new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);

    exit;
} else {
    $paging = new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);

    if (isset($remove)) {

        if (!isset($id_urm)) {

            $erro = 'Nenhum item selecionado!';
        } else {

            $querydel = new Query($bd);

            for ($c = 0; $c < sizeof($id_urm); $c++) {

                $where = array(0 => array('id_urm', $id_urm[$c]));
                $querydel->deleteTupla('urm', $where);
            }

            unset($_POST['id_urm']);
        }
    }

    $paging->exec($query->sql . $sort->sort_sql);
}

include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('URM', 'fab fa-asymmetrik', $_SERVER['PHP_SELF']);
$tab->setTab('Nova URM', 'fas fa-plus', 'URM_form.php');

$tab->printTab($_SERVER['PHP_SELF']);

$n = $paging->query->rows();

include 'URM_view.php'

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
                        <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#URM_view">
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
                                    <a href="<? echo $_SERVER['PHP_SELF']; ?>?print=1<? echo $paging->verificaVariaveis(); ?>" target="_new">
                                    </a>
                            </th>
                        </tr>
                    </thead>

                    <tr>

                        <td style=' <? echo $sort->verifyItem(0); ?>' width="5px"></td>
                        <td style=' <? echo $sort->verifyItem(1); ?>' width="5px"> <? echo $sort->printItem(1, $sort->sort_dir, ''); ?> </td>
                        <td style=' <? echo $sort->verifyItem(4); ?>'> <? echo $sort->printItem(4, $sort->sort_dir, 'Ano _referencia'); ?> </td>
                        <td style=' <? echo $sort->verifyItem(3); ?>'> <? echo $sort->printItem(3, $sort->sort_dir, 'Mes_referencia'); ?> </td>
                        <td style=' <? echo $sort->verifyItem(2); ?>'> <? echo $sort->printItem(2, $sort->sort_dir, 'Valor'); ?> </td>




                    </tr>

                    <?

                    while ($n--) {

                        $paging->query->proximo();

                        $js_onclick = "OnClick=javascript:window.location=('URM_edit.php?id_urm=" . $paging->query->record[0] . "')";



                        echo "<tr class='entered'>";

                        echo "<td valign='middle'><input type=checkbox class='form-check-value' name='id_urm[]' value=" . $paging->query->record[0] . "></td>";
                        echo "<td valign='top' " . $js_onclick . ">" . ($query->record[2] == "S" ? "<i class='fas fa-circle text-green'</i>" : "<i class='fas fa-circle text-light'</i>") . "</td>";
                        echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[4] . "</td>";
                        echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[3] . "</td>";
                        echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";

                        echo "</tr>";
                    }

                    ?>

                    </tbody>

                    <tfoot>

                        <tr>
                            <td colspan="8">

                                <span>Situação: </span>
                                <span><i class='fas fa-circle text-light'></i> Não Habilitado</span>
                                <span><i class='fas fa-circle text-green'></i> Habilitado</span>

                            </td>
                        </tr>

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
?>