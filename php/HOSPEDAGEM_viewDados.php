<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');

$query->exec("SELECT id_hospedagem , id_animal , valor , endereco_recolhimento , id_bairro , id_responsavel , dt_entrada , dt_retirada , situacao

FROM hospedagem

WHERE endereco_recolhimento= " . $endereco_recolhimento);


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
        array('Código'                   ,     10, 0),
        array('Id_animal'                ,     10, 1),
        array('Endereco_recolhimento'    ,    190, 1),
        array('Id_airro'                 ,     19, 1),
        array('Observacao'               ,    190, 1),
        array('Dt_entrada'               ,     19, 1),
        array('Dt_retirada'              ,     19, 1),
        array('Id_responsavel'           ,     19, 1),
        array('Id_motivo'                ,     19, 1),
        array('Id_urm'                   ,     19, 1),
        array('Valor'                    ,     19, 1),
        array('Nro_boleto'               ,     19, 1),
        array('Situacao'                 ,     19, 1)

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

$tab->setTab('Adicionar', 'fas fa-plus', 'HOSPEDAGEM_form.php');
$tab->setTab('Pesquisar', 'fas fa-search', 'HOSPEDAGEM_view.php');
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


                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Hospedagem'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Animal'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Endereco_recolhimento'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Bairro'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Responsavel'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Dt_entrada'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Dt_retirada'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Motivo'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Situacao'); ?> </td>

                        </tr>

                        <?
                        while ($n--) {

                            $paging->query->proximo();

                            $js_onclick = "OnClick=javascript:window.location=('HOSPEDAGEM_edit.php?id_hospedagem=" . $paging->query->record[0] . "')";

                            echo "<tr>";

                            echo "<td valign='middle'><input type=checkbox class='form-check-value' name='id_hospedagem[]' value=" . $paging->query->record[0] . "></td>";

                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[2] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[3] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[4] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[5] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[6] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[7] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[8] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[9] . "</td>";


                            echo "</tr>";
                        }

                        ?>

                    </tbody>

                    <tfoot>

                        <tr>
                            <td colspan="7">

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