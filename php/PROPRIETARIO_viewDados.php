<?
include('../includes/session.php');


$where = "";
$where .= $form_inscricao_estadual   != "" ? " AND inscricao_estadual ilike '%{$form_inscricao_estadual}%'  " : "";
$where .= $form_nome   != "" ? " AND nome ilike '%{$form_nome}%'  " : "";

$query->exec(
    "SELECT *
    FROM proprietario
    WHERE
        cpf  ilike '%{$form_cpf}%'".$where);

$sort =new Sort($query, $sort_icon, $sort_dirname, $sort_style);
if(!$sort_by)  $sort_by  = 1;
if(!$sort_dir) $sort_dir = 0;
$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo = "Proprietários";
$report_periodo   = date('d/m/Y');
if ($print){
    // TODO
    include('../class/class.report.php');
    unset($_GET['print']);
    $report_cabecalho =array(
        array('Código',        10, 0),
        array('Superior',      50, 1),
        array('Descrição',     50, 1),
        array('Fonte',         50, 1),
        array('Tipo',          15, 1),
        array('Situação',      15, 1));
    $query->exec($query->sql . $sort->sort_sql);
    $report=new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);
    exit;
} else {
    $paging =new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);
    if (isset($remove)) {
        if (!isset($id_proprietario))
            $erro = 'Nenhum item selecionado!';
        else {
            $querydel = new Query($bd);
            for ($c=0; $c < sizeof($id_proprietario); $c++) {
                $where =array(0 => array('id_proprietario', $id_proprietario[$c]));
                $querydel->deleteTupla('proprietario', $where);
            }
            unset($_POST['id_proprietario']);
        }
    }
    $paging->exec($query->sql . $sort->sort_sql);
}

include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();


$tab->setTab('Proprietários','fas fa-user-circle', $_SERVER['PHP_SELF']);
$tab->setTab('Novo Proprietário','fas fa-plus', 'PROPRIETARIO_form.php');
$tab->printTab($_SERVER['PHP_SELF']);


if ($remove) {
    $querydel->commit();
    unset($_POST['remove']);
}
$n =$paging->query->rows();
include 'PROPRIETARIO_view.php'

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
                        <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#PROPRIETARIO_view">
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
                        <td style='<?=$sort->verifyItem(1);?>'><?=$sort->printItem(1, $sort->sort_dir, 'Nome')              ?></td>
                        <td style='<?=$sort->verifyItem(2);?>'><?=$sort->printItem(2, $sort->sort_dir, 'CPF')               ?></td>
                        <td style='<?=$sort->verifyItem(3);?>'><?=$sort->printItem(3, $sort->sort_dir, 'Data de registro')  ?></td>
                        <td style='<?=$sort->verifyItem(4);?>'><?=$sort->printItem(4, $sort->sort_dir, 'Inscrição estadual')?></td>
                    </tr>
                    <?
                    while ($n--) {
                        $paging->query->proximo();
                        $js_onclick ="OnClick=javascript:window.location=('PROPRIETARIO_edit.php?id_proprietario=" . $paging->query->record[0] . "')";

                        echo "<tr class='entered'>";

                            echo "<td valign='top'><input type=checkbox class='form-check-value' name='id_proprietario[]' value=" . $paging->query->record[0] ."></td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[2] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[3] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[5] . "</td>";

                        echo "</tr>";
                    }
                    ?>
                    </tbody>
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
