<?

/************************************************************************
			SAI - Sistema de Administra��o da Intranet
					     Vers�o 1.00
				Copyright (c) 2003 COINPEL
Autor:	Leonardo Assis
Dispon�vel em:		http://www.pelotas.com.br/xxx/sai/
Data de cria��o:	27/05/2004
Data de modifica��o:	27/05/2004
 ************************************************************************/
include('../includes/session.php');
include('../class/class.report.php');
include('../function/function.date.php');
include('../function/function.misc.php');
include('../includes/variaveisAmbiente.php');

$where = "";
if ($form_nro_confere <> '') $where .= " AND a.nro_confere ='$form_nro_confere'";
if ($form_satisfeito <> '')  $where .= " AND a.satisfeito  ='$form_satisfeito'";
if ($form_transtorno <> '')  $where .= " AND a.transtorno  ='$form_transtorno'";
if ($form_material <> '')    $where .= " AND a.material    ='$form_material'";
if ($form_pagamento <> '')   $where .= " AND a.pagamento   ='$form_pagamento'";
if ($form_dt_i <> "" and $form_dt_f == "") $where .= " AND a.dt_avaliacao>='$form_dt_i'";
if ($form_dt_i == "" and $form_dt_f <> "") $where .= " AND a.dt_avaliacao<='$form_dt_f'";
if ($form_dt_i <> "" and $form_dt_f <> "") $where .= " AND a.dt_avaliacao BETWEEN '$form_dt_i' AND '$form_dt_f'";

$query->exec("SELECT t.id_tutor,
                     t.nome as tutor,
                     t.tipo,
                     ta.nome as animal,
                     a.nro_confere,
                     a.satisfeito,
                     a.transtorno,
                     a.material,
                     a.pagamento,
                     a.observacao,
                     a.login,
                     a.dt_alteracao,
                     TO_CHAR(a.dt_alteracao,'DD/MM/YYYY') as dt_alt_formatada,
                     a.dt_avaliacao,
                     TO_CHAR(a.dt_avaliacao,'DD/MM/YYYY') as dt_ava_formatada
                        FROM canil.tutor AS t 
                  INNER JOIN canil.tutor_animais AS ta USING(id_tutor)
                  INNER JOIN canil.tutor_animais_servico_prestado AS a USING(id_tutor_animais)
                       WHERE t.nome ilike '%$form_tutor%' AND ta.nome ilike '%$form_animal%' $where");

$sort = new Sort($query, $sort_img, $sort_dirname, $sort_style);
if (!$sort_by)    $sort_by = 13;
if (!$sort_dir)   $sort_dir = 0;
$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo   = "Avaliações";
$report_periodo     = date('d/m/Y');
$report_orientation  = "L";

if ($print) {
    unset($_GET['print']);
    $query->exec($query->sql . $sort->sort_sql);
    $report_cabecalho = array(
        array('id_tutor',     10, 0),
        array('Tutor',        105, 1),
        array('Tipo',         10, 1),
        array('Animal',       30, 1),
        array('Conf.',        10, 1),
        array('Sat.',         10, 1),
        array('Tr.',          10, 1),
        array('Mat.',         10, 1),
        array('Pag.',         10, 1),
        array('Obs',          10, 0),
        array('Por',          40, 1),
        array('Em',           10, 0),
        array('Em',           20, 1),
        array('Ref.',         10, 0),
        array('Ref.',         20, 1)
    );
    $report = new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);
    exit;
} else {
    $paging = new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);
    $paging->exec($query->sql . $sort->sort_sql);
}
include('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();
$tab->setTab('Consultar', 'fas fa-file-code', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

$n = $paging->query->rows();
if (!$n) {
    echo callException('Nenhum item cadastrado para listagem!', 2);
    exit;
}
?>

<script>
    function MM_openBrWindow(theURL, winName, features) { //v2.0
        window.open(theURL, winName, features);
    }
</script>

<section class="content">
    <div class="card p-0">
        <form name="objectItems" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

            <div class="card p-0">

                <div class="card-header border-bottom-1 mb-3 bg-light-2">

                    <div class="row">
                        <div class="col-12 col-md-4 offset-md-4 text-center">
                            <h4><?php echo $CabecalhoFiltro . $TituloFiltro; ?></h4>
                        </div>

                        <div class="col-12 col-md-4 text-center text-md-right mt-2 mt-md-0">

                            <!-- Abre Modal de Filtro -->
                            <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#CANILCONSULTAAVALIACAO_view">
                                <i class="fas fa-search"></i>
                            </button>

                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="bg-light">
                                <th><?php echo $sort->printItem(13, $sort->sort_dir, 'Ref.') ?></th>
                                <th><?php echo $sort->printItem(1, $sort->sort_dir, 'Tutor') ?></th>
                                <th><?php echo $sort->printItem(2, $sort->sort_dir, 'Tipo') ?></th>
                                <th><?php echo $sort->printItem(3, $sort->sort_dir, 'Animal') ?></th>
                                <th><?php echo $sort->printItem(4, $sort->sort_dir, 'Confere') ?></th>
                                <th><?php echo $sort->printItem(5, $sort->sort_dir, 'Satisfeito') ?></th>
                                <th><?php echo $sort->printItem(6, $sort->sort_dir, 'Transtorno') ?></th>
                                <th><?php echo $sort->printItem(7, $sort->sort_dir, 'Material') ?></th>
                                <th><?php echo $sort->printItem(8, $sort->sort_dir, 'Pagamento') ?></th>
                                <th><?php echo $sort->printItem(9, $sort->sort_dir, 'Obs') ?></th>
                                <th><?php echo $sort->printItem(10, $sort->sort_dir, 'Feito por') ?></th>
                                <th><?php echo $sort->printItem(11, $sort->sort_dir, 'Em') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($n--) {
                                $paging->query->proximo();
                                $js_onclick = "OnClick=javascript:window.location=('CADASTROANIMAL_form.php?id_tutor=" . $paging->query->record[0] . "')";
                                echo "<tr class='entered'>";
                                
                                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[14] . "</td>";
                                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[2] . "</td>";
                                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[3] . "</td>";
                                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[4] . "</td>";
                                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[5] . "</td>";
                                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[6] . "</td>";
                                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[7] . "</td>";
                                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[8] . "</td>";
                                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[9] . "</td>";
                                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[10] . "</td>";
                                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[12] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="12">
                                    <div class="text-center pt-2">
                                        <?php echo $paging->viewTableSlice(); ?>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>

                    </table>
                </div>

            </div>
        </form>
    </div>
</section>

<? include('../includes/dashboard/footer.php');
include 'CANILCONSULTAAVALIACAO_view.php'
?>