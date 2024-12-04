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

$where="";
if ($form_tipo<>'') $where.=" AND t.tipo='$form_tipo'";
if ($form_situacao=='S') $where.=" AND dt_castracao IS NOT NULL AND nro_chip IS NOT NULL";
if ($form_situacao=='N') $where.=" AND dt_castracao IS NOT NULL AND nro_chip is NULL";
if ($form_situacao=='A') $where.=" AND dt_castracao IS NULL";
if ($form_dt_i<>"" and $form_dt_f=="") $where.=" AND ta.dt_castracao>='$form_dt_i'";
if ($form_dt_i=="" and $form_dt_f<>"") $where.=" AND ta.dt_castracao<='$form_dt_f'";
if ($form_dt_i<>"" and $form_dt_f<>"") $where.=" AND ta.dt_castracao BETWEEN '$form_dt_i' AND '$form_dt_f'";
if ($form_especie<>'') $where.=" AND ta.especie='$form_especie'";
if ($form_sexo<>'')    $where.=" AND ta.sexo='$form_sexo'";
if ($form_rua<>'')     $where.=" AND ta.rua='$form_rua'";

$query->exec("SELECT CASE WHEN ta.dt_castracao IS NULL THEN 'Aguardando' else TO_CHAR(ta.dt_castracao,'YYYY/MM') end,
                      count(*) as total
              FROM canil.tutor t, canil.tutor_animais ta 
			     WHERE t.id_tutor=ta.id_tutor $where
              GROUP BY 1");
        
$sort =new Sort($query, $sort_img, $sort_dirname, $sort_style);
if(!$sort_by)    $sort_by =0;
if(!$sort_dir)   $sort_dir=0;
$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo   ="Castrações";
$report_periodo     =date('d/m/Y');
$report_orientation ="L";

if ($print){
   unset($_GET['print']);
   $query->exec($query->sql . $sort->sort_sql);
   $report_cabecalho =array(
                            array('id_tutor',     10, 0),
                            array('Nome',    	  90, 1),
                            array('CPF',    	     30, 1),
                            array('Tipo',    	  15, 1),
                            array('Animal',       30, 1),
                            array('Sexo',         10, 1),
                            array('dt1',          20, 0),
                            array('Cadastro'    , 20, 1),
                            array('dt_castracao', 20, 0),
                            array('Castração',    20, 1),
                            array('Chip/Info',    60, 1));
   $report=new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);
   exit;
} else {
   $paging =new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);
   $paging->exec($query->sql . $sort->sort_sql); 
}
include('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();
$tab->setTab('Ocorrências','fas fa-file-code',$_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

$n =$paging->query->rows();
if (!$n) {
    echo callException('Nenhum item cadastrado para listagem!', 2);
    exit;
}
?>

<section class="content">
    <form name="objectItems" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        
        <div class="card p-0">
            
            <div class="card-header border-bottom-1 mb-3 bg-light-2">
                
                <div class="row">
                    <div class="col-12 col-md-4 offset-md-4 text-center">
                        <h4><?php echo $CabecalhoFiltro . $TituloFiltro; ?></h4>
                    </div>

                    <div class="col-12 col-md-4 text-center text-md-right mt-2 mt-md-0">
                            <!-- Abre Modal de Filtro -->
                        <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#CANILCASTRACOESTOTAIS_view">
                                <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                </div>
                
                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <?php
                            if (!$n)   { echo callException('Nenhum registro encontrado!', 2); }
                            if ($erro) { echo callException($erro, 1); }
                            if ($remove) {
                                $querydel->commit();
                                unset($_POST['remove']);
                            }
                        ?>
                    </div>
                </div>
            </div>

            <div class="card-body pt-0 table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="8">
                                Resultados de
                                <span class="range-resultados"><?php echo $paging->getResultadoInicial() . "-" . $paging->getResultadoFinal(); ?></span>
                                sobre
                                <span class="numero-paginas"><?php echo $paging->getRows(); ?></span>
                                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?print=1<?php echo $paging->verificaVariaveis(); ?>" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr bgcolor="#DFDFDF">
                            <td style="<?php echo $sort->verifyItem(0); ?>"><?php echo $sort->printItem(0, $sort->sort_dir, 'Mês'); ?></td>
                            <td style="<?php echo $sort->verifyItem(1); ?>"><?php echo $sort->printItem(1, $sort->sort_dir, 'Total'); ?></td>
                        </tr>
                        <?php
                        while ($n--) {
                            $paging->query->proximo();
                            echo "<tr class='entered'>";

                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[0] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
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

        <div class="card-footer bg-light-2">
                <?php
                    if ($paging->query->rows()) {
                        $btns = array('selectAll', 'remove');
                        include('../includes/dashboard/footer_forms.php');
                    }
                ?>
        </div>

        </div>
    </form>
</section>

<?  include('../includes/dashboard/footer.php'); 
    include 'CANILCASTRACOESTOTAIS_view.php';
?>