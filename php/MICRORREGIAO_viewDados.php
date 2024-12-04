<?
include('../includes/session.php');

$condicao  = "";

if (isset($filter)) {
    $condicao .= $form_habilitado != "" ? " AND m.habilitado='" . $form_habilitado . "'" : "";
    $condicao .= $form_nome       != "" ? " AND m.nome ilike '%" . $form_nome . "%' " : "";
}

$query->exec("SELECT m.id_microrregiao, m.nome as microrregiao , m.habilitado as situacao , rad.nome as regiao_administrativa
                    FROM denuncias.microrregiao as m, denuncias.regiao_administrativa as rad                     
                   WHERE m.id_regiao_administrativa = rad.id_regiao_administrativa" . $condicao);


$sort = new Sort($query, $sort_icon, $sort_dirname, $sort_style);

if (!$sort_by)   $sort_by  = 1;
if (!$sort_dir)   $sort_dir = 0;

$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo   = "Microrregião";
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

        if (!isset($id_microrregiao)) {

            $erro = 'Nenhum item id_microrregiao!';
        } else {

            $querydel = new Query($bd);

            for ($c = 0; $c < sizeof($id_microrregiao); $c++) {

                $where = array(0 => array('id_microrregiao', $id_microrregiao[$c]));
                $querydel->deleteTupla('denuncias.microrregiao', $where);
            }

            unset($_POST['id_microrregiao']);
        }
    }

    $paging->exec($query->sql . $sort->sort_sql);
}

include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Microterritórios', 'fas fa-map-marked', $_SERVER['PHP_SELF']);
$tab->setTab('Novo Microterritório', 'fas fa-plus', 'MICRORREGIAO_form.php');

$tab->printTab($_SERVER['PHP_SELF']);

$n = $paging->query->rows();

include 'MICRORREGIAO_view.php'
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
                        <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#MICRORREGIAO_view">
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
                            <td style=' <? echo $sort->verifyItem(1); ?>' width="5px"> <? echo $sort->printItem(1, $sort->sort_dir, ''); ?> </td>
                            <td style=' <? echo $sort->verifyItem(2); ?>'> <? echo $sort->printItem(2, $sort->sort_dir, 'Nome'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(3); ?>'> <? echo $sort->printItem(3, $sort->sort_dir, 'Região Administrativa'); ?> </td>


                        </tr>

                        <?

                        while ($n--) {

                            $paging->query->proximo();

                            $js_onclick = "OnClick=javascript:window.location=('MICRORREGIAO_edit.php?id_microrregiao=" . $paging->query->record[0] . "')";

                            echo "<tr class='entered'>";

                            echo "<td valign='top'><input type=checkbox class='form-check-value' name='id_microrregiao[]' value=" . $paging->query->record[0] . "></td>";
                            echo "<td valign='top' " . $js_onclick . ">" . ($query->record['situacao'] == "S" ? "<i class='fas fa-circle text-green'</i>" : "<i class='fas fa-circle text-light'</i>") . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record['microrregiao'] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record['regiao_administrativa'] . "</td>";

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