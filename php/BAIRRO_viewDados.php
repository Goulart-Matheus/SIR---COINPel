<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');

$query->exec("SELECT id_bairro , descricao
                    FROM bairro
                   WHERE descricao ilike '%" . $form_descricao . "%'
                   
                ");

$sort = new Sort($query, $sort_icon, $sort_dirname, $sort_style);

if (!$sort_by)   $sort_by  = 1;
if (!$sort_dir)   $sort_dir = 0;

$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo   = "Descrição";
$report_periodo     = date('d/m/Y');

if ($print) {
    include('../class/class.report.php');

    unset($_GET['print']);

    $report_cabecalho = array(
        array('Código',     10, 0),
        array('Descricao',     190, 1)
    );

    $query->exec($query->sql . $sort->sort_sql);

    $report = new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);

    exit;
} else {
    $paging = new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);

    if (isset($remove)) {

        if (!isset($id_bairro)) {

            $erro = 'Nenhum item selecionado!';
        } else {

            $querydel = new Query($bd);

            for ($c = 0; $c < sizeof($id_bairro); $c++) {

                $where = array(0 => array('id_bairro', $id_bairro[$c]));
                $querydel->deleteTupla('bairro', $where);
            }

            unset($_POST['id_bairro']);
        }
    }

    $paging->exec($query->sql . $sort->sort_sql);
}

include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Adicionar', 'fas fa-plus', 'BAIRRO_form.php');
$tab->setTab('Pesquisar', 'fas fa-search', 'BAIRRO_view.php');
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
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Descrição '); ?> </td>

                        </tr>

                        <?

                        while ($n--) {

                            $paging->query->proximo();

                            $js_onclick = "OnClick=javascript:window.location=('BAIRRO_edit.php?id_bairro=" . $paging->query->record[0] . "')";

                            echo "<tr>";

                            echo "<td valign='middle'><input type=checkbox class='form-check-value' name='id_bairro[]' value=" . $paging->query->record[0] . "></td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";

                            echo "</tr>";
                        }

                        ?>

                    </tbody>

                    <tfoot>

                        <tr>
                            <td colspan="2">

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