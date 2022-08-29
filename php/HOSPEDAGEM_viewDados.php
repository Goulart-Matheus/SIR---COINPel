<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');

/*$query->exec("SELECT id_hospedagem , id_animal , valor , endereco_recolhimento , id_bairro , id_responsavel , dt_entrada , dt_retirada , situacao

FROM hospedagem ");*/

/*WHERE endereco_recolhimento= " . $endereco_recolhimento);*/

$query->exec("SELECT '', h.id_hospedagem , id_animal  , endereco_recolhimento as recolhimento , b.descricao as bairro, r.nome , h.dt_entrada , h.dt_retirada ,m.descricao as motivo,h.situacao

FROM hospedagem as h, bairro as b , responsavel as r, motivo as m WHERE b.id_bairro = h.id_bairro AND r.id_responsavel = h.id_responsavel AND h.id_motivo = m.id_motivo");


$sort = new Sort($query, $sort_icon, $sort_dirname, $sort_style);

if (!$sort_by)   $sort_by  = 1;
if (!$sort_dir)   $sort_dir = 0;

$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo   = "Id_animal";
$report_periodo     = date('d/m/Y');

if ($print) {
    include('../class/class.report.php');

    unset($_GET['print']);

    $report_cabecalho = array(
        array('Código',     10, 0),
        array('Id_animal',     10, 1),
        array('Endereco_recolhimento',    190, 1),
        array('Id_airro',     19, 1),
        array('Observacao',    190, 1),
        array('Dt_entrada',     19, 1),
        array('Dt_retirada',     19, 1),
        array('Id_responsavel',     19, 1),
        array('Id_motivo',     19, 1),
        array('Id_urm',     19, 1),
        array('Valor',     19, 1),
        array('Nro_boleto',     19, 1),
        array('Situacao',     19, 1)

    );

    $query->exec($query->sql . $sort->sort_sql);

    $report = new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);

    exit;
} else {
    $paging = new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);

    if (isset($remove)) {

        if (!isset($id_hoepedagem)) {

            $erro = 'Nenhum item selecionado!';
        } else {

            $querydel = new Query($bd);

            for ($c = 0; $c < sizeof($id_hospedagem); $c++) {

                $where = array(0 => array('id_hospedagem', $id_hospedagem[$c]));
                $querydel->deleteTupla('hospedagem', $where);
            }

            unset($_POST['id_hospedagem']);
        }
    }

    $paging->exec($query->sql . $sort->sort_sql);
}

include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Hospedaria', 'fas fa-heading', $_SERVER['PHP_SELF']);
$tab->setTab('Nova Hospedaria', 'fas fa-plus', 'HOSPEDAGEM_form.php');
//$tab->setTab('Pesquisar', 'fas fa-search', 'HOSPEDAGEM_view.php');


$tab->printTab($_SERVER['PHP_SELF']);

$n = $paging->query->rows();
// include('HOSPEDAGEM_view.php');

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
                        <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#HOSPEDAGEM_view">
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
                            <td style=' <? echo $sort->verifyItem(2); ?>'> <? echo $sort->printItem(2, $sort->sort_dir, 'Hospedagem'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(3); ?>'> <? echo $sort->printItem(3, $sort->sort_dir, 'Animal'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(4); ?>'> <? echo $sort->printItem(4, $sort->sort_dir, 'Endereco_recolhimento'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(5); ?>'> <? echo $sort->printItem(5, $sort->sort_dir, 'Bairro'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(6); ?>'> <? echo $sort->printItem(6, $sort->sort_dir, 'Responsavel'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(7); ?>'> <? echo $sort->printItem(7, $sort->sort_dir, 'Dt_entrada'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(8); ?>'> <? echo $sort->printItem(8, $sort->sort_dir, 'Dt_retirada'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(9); ?>'> <? echo $sort->printItem(9, $sort->sort_dir, 'Motivo'); ?> </td>                            

                        </tr>

                        <?
                        while ($n--) {

                            $paging->query->proximo();

                            $js_onclick = "OnClick=javascript:window.location=('HOSPEDAGEM_edit.php?id_hospedagem=" . $paging->query->record[1] . "')";

                            echo "<tr class='entered'>";

                                echo "<td valign='middle'><input type=checkbox class='form-check-value' name='id_hospedagem[]' value=" . $paging->query->record[0] . "></td>";
                                echo "<td valign='top' " . $js_onclick . ">" . ($query->record[9] == "S" ? "<i class='fas fa-circle text-green'</i>" : "<i class='fas fa-circle text-light'</i>") . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[2] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[3] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[4] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[5] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[6] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[7] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[8] . "</td>";

                            echo "</tr>";
                        }

                        ?>

                    </tbody>

                    <tfoot>

                        <tr>
                            <td colspan="8">

                                <span>Situação: </span>
                                <span><i class='fas fa-circle text-light'></i> Não Disponível</span>
                                <span><i class='fas fa-circle text-green'></i> Disponível</span>

                            </td>
                        </tr>

                        <tr>
                            <td colspan="12">
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