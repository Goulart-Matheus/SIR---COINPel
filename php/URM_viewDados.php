<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');

$where = "";

$where .= $form_valor           != ""  ? " AND  valor        = $form_valor        "   : "";
// $where .= $form_ativo   == "S"         ? " AND ativo = $form_ativo "   : "N";
$where .= $form_mes_referencia   != ""  ? " AND mes_referencia = $form_mes_referencia" : "";
$where .= $form_ano_referencia   != ""  ? " AND ano_referencia = $form_ano_referencia" : "";



$query->exec("SELECT id_urm , valor , ativo , mes_referencia , ano_referencia
                    FROM urm
                   WHERE ativo ILIKE '%$form_ativo%'$where
                   
                ");
echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";

$sort = new Sort($query, $sort_icon, $sort_dirname, $sort_style);

if (!$sort_by)   $sort_by  = 1;
if (!$sort_dir)   $sort_dir = 0;

$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo   = "Valor";
$report_periodo     = date('d/m/Y');

if ($print) {
    include('../class/class.report.php');

    unset($_GET['print']);

    $report_cabecalho = array(
        array('Código',      10, 0),
        array('valor',     190, 1),
        array('ativo',     190, 1),
        array('Mes_referencia',     190, 1),
        array('Ano_referencia',     190, 1)
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

$tab->setTab('Adicionar', 'fas fa-plus', 'URM_form.php');
$tab->setTab('Pesquisar', 'fas fa-search', 'URM_view.php');
$tab->setTab('Gerenciar', 'fas fa-cog', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);

$n = $paging->query->rows();

?>

<section class="content">

    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

        <div class="card p-0">

            <div class="card-header border-bottom-1 mb-3 bg-light-2">

                <div class="text-center">
                    <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                </div>

                <div class="row text-center">

                    <div class="col-12 col-sm-4 offset-sm-4">
                        <?
                        if (!$n) {
                            echo callException('Nenhum registro encontrado!', 2);
                        }

                        if ($erro) {
                            echo callException($erro, 1);
                        }

                        if ($remove) {
                            $querydel->commit();
                            unset($_POST['remove']);
                        }

                        ?>

                    </div>

                </div>

            </div>

            <div class="card-body pt-0">

                <table class="table table-striped responsive">

                    <thead>

                        <tr>
                            <th colspan="2">

                                Resultados de

                                <span class="range-resultados">
                                    <? echo $paging->getResultadoInicial() . "-" . $paging->getResultadoFinal(); ?>
                                </span>

                                sobre

                                <span class='numero-paginas'>
                                    <? echo $paging->getRows(); ?>
                                </span>

                                <a href="<? echo $_SERVER['PHP_SELF']; ?>?print=1<? echo $paging->verificaVariaveis(); ?>" target="_new">
                                    <i class="fas fa-print"></i>
                                </a>

                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td width="5px"></td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Valor'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Ativo'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Mes_referencia'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Ano _referencia'); ?> </td>


                        </tr>

                        <?

                        while ($n--) {

                            $paging->query->proximo();

                            $js_onclick = "OnClick=javascript:window.location=('URM_edit.php?id_urm=" . $paging->query->record[0] . "')";



                            echo "<tr>";

                            echo "<td valign='middle'><input type=checkbox class='form-check-value' valor='id_urm[]' value=" . $paging->query->record[0] . "></td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[0] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[2] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[3] . "</td>";

                            echo "</tr>";
                        }

                        ?>

                    </tbody>

                    <tfoot>

                        <tr>
                            <td colspan="5">

                                <div class="text-center pt-2">
                                    <? echo $paging->viewTableSlice(); ?>
                                </div>

                                <? if ($paging->query->rows()) { ?>

                                    <div class="text-right pt-2">
                                        <input name='remove' type='submit' value='Remover' class='btn btn-danger'>
                                        <input class="btn btn-warning" type="button" id="selectButton" value="Selecionar Todos" onClick="toggleSelect(); return false">
                                    </div>

                                <? } ?>

                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </form>

</section>

<?
include_once('../includes/dashboard/footer.php');
?>