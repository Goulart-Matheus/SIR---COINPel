<?
include('../includes/session.php');
$query->exec("SELECT * FROM grupo");
$sort =new Sort($query, $sort_icon, $sort_dirname, $sort_style);
if(!$sort_by)    $sort_by =1;
if(!$sort_dir)   $sort_dir=0;
$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo   =$auth->getApplicationDescription($_SERVER['PHP_SELF']);
$report_periodo     =date('d/m/Y');
if ($print){
    include('../class/class.report.php');
    unset($_GET['print']);
    $report_cabecalho =array(
        array('Código',        10, 0),
        array('Descrição',      50, 1));
    $query->exec($query->sql . $sort->sort_sql);
    $report=new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);
    exit;
} else {
    $paging =new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);
    if (isset($submit)) {
        if (!isset($codgrupo))
            $erro = 'Nenhum item selecionado!';
        else {
            $querydel = new Query($bd);
            foreach ($codgrupo as $id) {
                $querydel->deleteTupla('grupo', [['codgrupo', $id]]);
                $querydel->deleteTupla('grupo_aplicacao', [['codgrupo' => $id]]);
            }
            unset($_POST['codgrupo']);
        }
    }
    $paging->exec($query->sql . $sort->sort_sql);
}

include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Adicionar','fas fa-plus', 'formGrupo.php');
$tab->setTab('Pesquisar','fas fa-search', 'viewGrupo.php');
$tab->setTab('Gerenciar','fas fa-cog', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);
if ($submit) {
    $querydel->commit();
    unset($_POST['submit']);
}
$n =$paging->query->rows();
?>

<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
        <div class="card p-1">
            <div class="card-header border-bottom-0">
                <div class="text-center">
                    <h4><?=$auth->getApplicationDescription($_SERVER['PHP_SELF'])?></h4>
                </div>
                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <?if(!$n) echo callException('Nenhum registro encontrado!', 2);
                        if ($erro) {echo callException($erro, 1);}
                        ?>

                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th colspan="6">Resultados de
                            <span class="range-resultados"><?echo $paging->getResultadoInicial() . "-" . $paging->getResultadoFinal() . "</span>
                                sobre <span class='numero-paginas'>".$paging->getRows()."</span>";?>
                                <a href="<?echo $_SERVER['PHP_SELF'];?>?print=1<?echo $paging->verificaVariaveis();?>" target="_new">
                                    <i class="fas fa-print"></i>
                                </a>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td width="5px"></td>
                        <td style='<?echo $sort->verifyItem(1);?>'><?echo $sort->printItem(1, $sort->sort_dir, 'Descrição')  ?></td>
                    </tr>
                    <?
                    while ($n--) {
                        $paging->query->proximo();
                        $js_onclick ="OnClick=javascript:window.location=('editGrupo.php?codgrupo=" . $paging->query->record[0] . "')";

                        echo "<td valign='top'><input type=checkbox class='form-check-value' name='codgrupo[]' value=" . $paging->query->record[0] ."></td>";
                        echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="text-center pt-2">
                                <? echo $paging->viewTableSlice(); ?>
                            </div>
                            <?
                            if($paging->query->rows()) {
                                ?>
                                <div class="text-right pt-2">
                                    <input name='submit' type='submit' value='Remover' class='btn btn-danger'>
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
<? include_once('../includes/dashboard/footer.php'); ?>