<?
include('../includes/session.php');

$condicoes =
    ($form_proprietario ? "p.id_proprietario = $form_proprietario" : "TRUE");

$query->exec(
    "SELECT p.*, dono.nome as proprietario
    FROM propriedade p
    JOIN proprietario dono ON p.id_proprietario = dono.id_proprietario
    WHERE
        p.nome ilike '%$form_nome%' AND
        $condicoes"
);
$sort = new Sort($query, $sort_icon, $sort_dirname, $sort_style);
if (!$sort_by)  $sort_by  = 1;
if (!$sort_dir) $sort_dir = 0;
$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo = "Propriedades";
$report_periodo   = date('d/m/Y');
if ($print) {
    // TODO
    include('../class/class.report.php');
    unset($_GET['print']);
    $report_cabecalho = array(
        array('Código',        10, 0),
        array('Superior',      50, 1),
        array('Descrição',     50, 1),
        array('Fonte',         50, 1),
        array('Tipo',          15, 1),
        array('Situação',      15, 1)
    );
    $query->exec($query->sql . $sort->sort_sql);
    $report = new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);
    exit;
} else {
    $paging = new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);
    if (isset($remove)) {
        if (!isset($id_propriedade))
            $erro = 'Nenhum item selecionado!';
        else {
            $querydel = new Query($bd);
            for ($c = 0; $c < sizeof($id_propriedade); $c++) {
                $where = array(0 => array('id_propriedade', $id_propriedade[$c]));
                $querydel->deleteTupla('propriedade', $where);
            }
            unset($_POST['id_propriedade']);
        }
    }
    $paging->exec($query->sql . $sort->sort_sql);
}

include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Propriedades', 'fas fa-home', $_SERVER['PHP_SELF']);
$tab->setTab('Nova Propriedade', 'fas fa-plus', 'PROPRIEDADE_form.php');
$tab->printTab($_SERVER['PHP_SELF']);
if ($remove) {
    $querydel->commit();
    unset($_POST['remove']);
}
$n = $paging->query->rows();

include('../php/PROPRIEDADE_view.php');
?>

<section class="content">

    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

        <div class="card p-0">

            <div class="card-header border-bottom-1 mb-3 bg-light-2">

                <div class="row">
                    <div class=" .col-12 col-md-4 offset-md-4 text-center">
                        <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                    </div>

                    <div class="col-12 col-md-4 text-center text-md-right mt-2 mt-md-0">

                        <!-- Geração de Relatório -->
                        <button type="button" class="btn btn-sm btn-green text-light">
                            <i class="fas fa-print"></i>
                        </button>

                        <!-- Abre Modal de Filtro -->
                        <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#PROPRIEDADE_view">
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
                                </span>

                            </th>
                        </tr>

                    </thead>

                    <tbody>
                        <tr>
                            <td style=' <? echo $sort->verifyItem(0); ?>' width="5px"></td>
                            
                            <td style='<? echo $sort->verifyItem(1); ?>'><? echo $sort->printItem(1, $sort->sort_dir, 'Proprietário')         ?></td>
                            <td style='<? echo $sort->verifyItem(2); ?>'><? echo $sort->printItem(3, $sort->sort_dir, 'Nome')               ?></td>
                        </tr>
                        <?
                        while ($n--) {
                            $paging->query->proximo();

                            $js_onclick = "OnClick=javascript:window.location=('PROPRIEDADE_edit.php?id_propriedade=" . $paging->query->record['id_propriedade'] . "')";

                            echo "<tr class='entered'>";



                            echo "<td valign='middle'><input type=checkbox class='form-check-value' name='id_propriedade[]' value=" . $paging->query->record['id_propriedade'] . "></td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record['proprietario'] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record['nome'] . "</td>";
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

<? include_once('../includes/dashboard/footer.php'); ?>