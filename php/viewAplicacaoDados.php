<?
include('../includes/session.php');

$condicao="";
if(trim($form_superior)!='NULL' AND trim($form_superior)!='')$condicao.=" AND a.superior=".$form_superior;
if(trim($form_tipo)!='NULL' AND trim($form_tipo)!='')$condicao.=" AND a.tipo='".$form_tipo."'";
if(trim($form_situacao)!='NULL' AND trim($form_situacao)!='')$condicao.=" AND a.situacao='".$form_situacao."'";
if(trim($form_descricao)!='')$condicao.=" AND a.descricao ilike '%".$form_descricao."%'";
if(trim($form_fonte)!='')$condicao.=" AND a.fonte ilike '%".$form_fonte."%'";

$query->exec("SELECT a.codaplicacao, b.descricao as bdescricao, a.descricao as adescricao, a.fonte as fonte, a.tipo as tipo, a.situacao as situacao 
                    FROM aplicacao a, aplicacao b WHERE a.superior=b.codaplicacao".$condicao);
$sort =new Sort($query, $sort_icon, $sort_dirname, $sort_style);
if(!$sort_by)    $sort_by =1;
if(!$sort_dir)   $sort_dir=0;
$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo   ="Aplicações";
$report_periodo     =date('d/m/Y');
if ($print){
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
        if (!isset($codaplicacao))
            $erro = 'Nenhum item selecionado!';
        else {
            $querydel = new Query($bd);
            for ($c=0; $c < sizeof($codaplicacao); $c++) {
                $where =array(0 => array('codaplicacao', $codaplicacao[$c]));
                $querydel->deleteTupla('aplicacao', $where);
            }
            unset($_POST['codaplicacao']);
        }
    }
    $paging->exec($query->sql . $sort->sort_sql);
}

include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Adicionar','fas fa-plus', 'formAplicacao.php');
$tab->setTab('Pesquisar','fas fa-search', 'viewAplicacao.php');
$tab->setTab('Gerenciar','fas fa-cog', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);
if ($remove) {
    $querydel->commit();
    unset($_POST['remove']);
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
                            <td style='<?echo $sort->verifyItem(1);?>'><?echo $sort->printItem(1, $sort->sort_dir, 'Superior')  ?></td>
                            <td style='<?echo $sort->verifyItem(2);?>'><?echo $sort->printItem(2, $sort->sort_dir, 'Descricão') ?></td>
                            <td style='<?echo $sort->verifyItem(3);?>'><?echo $sort->printItem(3, $sort->sort_dir, 'Fonte')     ?></td>
                            <td style='<?echo $sort->verifyItem(4);?>'><?echo $sort->printItem(4, $sort->sort_dir, 'Tipo')      ?></td>
                            <td style='<?echo $sort->verifyItem(5);?>'><?echo $sort->printItem(5, $sort->sort_dir, 'Sit.')  ?></td>
                        </tr>
                        <?
                        while ($n--) {
                            $paging->query->proximo();
                            $js_onclick ="OnClick=javascript:window.location=('editAplicacao.php?codaplicacao=" . $paging->query->record[0] . "')";

                            echo "<td valign='top'><input type=checkbox class='form-check-value' name='codaplicacao[]' value=" . $paging->query->record[0] ."></td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[2] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[3] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[4] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[5] . "</td>";
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
<? include_once('../includes/dashboard/footer.php'); ?>