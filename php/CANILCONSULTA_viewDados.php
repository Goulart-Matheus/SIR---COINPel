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
if ($form_tipo <> '')                    $where .= " AND t.tipo='$form_tipo'";
if ($form_dt_i <> "" and $form_dt_f == "") $where .= " AND t.dt_alteracao>='$form_dt_i'";
if ($form_dt_i == "" and $form_dt_f <> "") $where .= " AND t.dt_alteracao<='$form_dt_f'";
if ($form_dt_i <> "" and $form_dt_f <> "") $where .= " AND t.dt_alteracao BETWEEN '$form_dt_i' AND '$form_dt_f'";
if ($form_login <> '')                   $where .= " AND t.login='$form_login'";
if ($form_id_macroregiao <> '')          $where .= " AND m2.id_macroregiao='$form_id_macroregiao'";
if ($form_id_microregiao <> '')          $where .= " AND m1.id_microregiao='$form_id_microregiao'";
if ($form_adotante <> '')                $where .= " AND t.adotante='$form_adotante'";
if ($form_posoperatorio <> '')           $where .= " AND t.posoperatorio='$form_posoperatorio'";
if ($form_status == 'A')                 $where .= " AND t.id_tutor IN (SELECT ta.id_tutor FROM canil.tutor_animais ta WHERE ta.dt_castracao IS NULL AND ta.ativo='S' )";
if ($form_status == 'R')                 $where .= " AND t.id_tutor IN (SELECT ta.id_tutor FROM canil.tutor_animais ta WHERE ta.dt_castracao IS NOT NULL AND ta.ativo='S' )";

$query->exec("SELECT COUNT(*)  as animais_aguardando_castracao
              FROM canil.tutor t inner join microregiao m1 using(id_microregiao) 
                                 inner join macroregiao m2 using(id_macroregiao) 
                                 inner join canil.tutor_animais ta using(id_tutor)
              WHERE t.nome ilike '%$form_tutor%' AND 
                    ta.ativo='S' AND
                    ta.dt_castracao IS NULL
                    $where");
$query->proximo();
$TotalAguardandoCastracao = $query->record[0];

$query->exec("SELECT t.id_tutor,
                     t.nome,
                     t.cpf,
                     t.tipo,
                     (SELECT COUNT(*) FROM canil.tutor_animais ta WHERE ta.id_tutor=t.id_tutor  AND ativo<>'E') as animais,
                     (SELECT COUNT(*) FROM canil.tutor_animais ta WHERE ta.id_tutor=t.id_tutor  AND ativo='S') as animais_ativos,
                     (SELECT COUNT(*) FROM canil.tutor_animais ta WHERE ta.id_tutor=t.id_tutor  AND dt_castracao IS NULL AND ativo='S') as animais_aguardando_castracao
              FROM canil.tutor t inner join microregiao m1 using(id_microregiao) 
                                 inner join macroregiao m2 using(id_macroregiao) 
			        WHERE nome ilike '%$form_tutor%'	$where");
//echo $query->sql;exit;

$sort = new Sort($query, $sort_img, $sort_dirname, $sort_style);
if (!$sort_by)    $sort_by = 1;
if (!$sort_dir)   $sort_dir = 0;
$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo   = "Tutores";
$report_periodo     = date('d/m/Y');
$report_orientation = "L";

$sql = "SELECT t.id_tutor,
                     t.nome,
                     t.telefones,
                     t.tipo,
                     t.endereco,
                     m1.descricao,
                     (SELECT COUNT(*) FROM canil.tutor_animais ta WHERE ta.id_tutor=t.id_tutor  AND ativo<>'E') as animais,
                     (SELECT COUNT(*) FROM canil.tutor_animais ta WHERE ta.id_tutor=t.id_tutor  AND ativo='S') as animais_ativos,
                     (SELECT COUNT(*) FROM canil.tutor_animais ta WHERE ta.id_tutor=t.id_tutor  AND dt_castracao IS NULL AND ativo='S') as animais_aguardando_castracao
              FROM canil.tutor t inner join microregiao m1 using(id_microregiao) 
                                 inner join macroregiao m2 using(id_macroregiao) 
              WHERE nome ilike '%$form_tutor%'  $where
              ORDER BY 2";
if ($print) {
    unset($_GET['print']);

    $query->exec($sql);
    $report_cabecalho = array(
        array('id_tutor',    10, 0),
        array('Nome',           70, 1),
        array('Fone',           40, 1),
        array('Tipo',        10, 1),
        array('Endereço',    80, 1),
        array('Bairro',      45, 1),
        array('Anim.',       10, 1),
        array('Ativo',       10, 1),
        array('Aguar.',      10, 1)
    );
    $report = new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);
    exit;
} elseif ($csv) {
    $csv_cabecalho = array(
        array('id_tutor',   1,  "C"),
        array('Nome',    1,  "C"),
        array('Fone',     1,  "C"),
        array('Tipo', 1,  "C"),
        array('Endereço',        1,  "C"),
        array('Bairro',         1,  "C"),
        array('Anim',       1,  "C"),
        array('Ativo',       1,  "C"),
        array('Aguardando',     1,  "C")
    );
    $query->exec($sql);
    $csv_report = new CSV($query, $csv_cabecalho);
    exit;
} else {
    $paging = new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);
    $paging->exec($query->sql . $sort->sort_sql);
}

include('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();
$tab->setTab('Ocorrências', 'fas fa-file-code', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

$n = $paging->query->rows();
if (!$n) {
    echo callException('Nenhum item cadastrado para listagem!', 2);
    exit;
}

include 'CANILCONSULTA_view.php';

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
                        <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#CANILCONSULTA_view">
                            <i class="fas fa-search"></i>
                        </button>
                        
                    </div>
                </div>

                

            </div>

            <div class="card-body pt-0 table-responsive">
            <div class="row text-center mt-2">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <p>Total de Animais Aguardando Castração: <b><?php echo $TotalAguardandoCastracao; ?></b></p>
                    </div>
                </div>
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
                        <tr class="bg-light">
                            <th><?php echo $sort->printItem(1, $sort->sort_dir, 'Nome'); ?></th>
                            <th><?php echo $sort->printItem(2, $sort->sort_dir, 'CPF'); ?></th>
                            <th><?php echo $sort->printItem(3, $sort->sort_dir, 'Tipo'); ?></th>
                            <th><?php echo $sort->printItem(4, $sort->sort_dir, 'Animais'); ?></th>
                            <th><?php echo $sort->printItem(5, $sort->sort_dir, 'Ativos'); ?></th>
                            <th><?php echo $sort->printItem(6, $sort->sort_dir, 'Aguardando'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($n--) {
                            $paging->query->proximo();
                            $js_onclick = "onclick=\"window.location=('CADASTROANIMAL_form.php?id_tutor=" . $paging->query->record[0] . "')\"";
                            echo "<tr class='entered'>";
                            if (($n % 2) == 0) echo $_GLOBALS['select_impar'];
                            else               echo $_GLOBALS['select_par'];
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[2] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[3] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[4] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[5] . "</td>";
                            echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[6] . "</td>";
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
</section>

<? include('../includes/dashboard/footer.php'); 
?>