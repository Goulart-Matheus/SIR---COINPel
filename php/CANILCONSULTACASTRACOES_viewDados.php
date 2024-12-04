<?



include('../includes/session.php');
include('../class/class.report.php');
include('../function/function.date.php');
include('../function/function.misc.php');
include('../includes/variaveisAmbiente.php');

$where = "";
if ($form_tipo <> '') $where .= " AND t.tipo='$form_tipo'";
if ($form_situacao == 'S')    $where .= " AND dt_castracao IS NOT NULL AND nro_chip IS NOT NULL";
if ($form_situacao == 'N')    $where .= " AND dt_castracao IS NOT NULL AND nro_chip is NULL";
if ($form_situacao == 'A')    $where .= " AND dt_castracao IS NULL";
if ($form_chip <> '')         $where .= " AND ta.nro_chip='$form_chip'";
if ($form_castra_movel <> '') $where .= " AND ta.castra_movel='$form_castra_movel'";

if ($form_dt_i <> "" and $form_dt_f == "") $where .= " AND ta.dt_castracao>='$form_dt_i'";
if ($form_dt_i == "" and $form_dt_f <> "") $where .= " AND ta.dt_castracao<='$form_dt_f'";
if ($form_dt_i <> "" and $form_dt_f <> "") $where .= " AND ta.dt_castracao BETWEEN '$form_dt_i' AND '$form_dt_f'";

if ($form_dt_a_i <> "" and $form_dt_a_f == "") $where .= " AND ta.dt_agendamento>='$form_dt_a_i'";
if ($form_dt_a_i == "" and $form_dt_a_f <> "") $where .= " AND ta.dt_agendamento<='$form_dt_a_f'";
if ($form_dt_a_i <> "" and $form_dt_a_f <> "") $where .= " AND ta.dt_agendamento BETWEEN '$form_dt_a_i' AND '$form_dt_a_f'";

if ($form_especie <> '') $where .= " AND ta.especie='$form_especie'";
if ($form_sexo <> '')    $where .= " AND ta.sexo='$form_sexo'";
if ($form_rua <> '')     $where .= " AND ta.rua='$form_rua'";

$query->exec("SELECT t.id_tutor,
                       t.nome as tutor,
                       t.cpf,
                       CASE WHEN t.tipo='T' THEN 'Tutor' WHEN t.tipo='C' THEN 'Canil' WHEN t.tipo='P' THEN 'Protetor(a)' ELSE 'Erro' END AS tipo,
                       ta.nome as animal,
                       ta.sexo,
                       ta.dt_alteracao as dt_cadastro,
                       TO_CHAR(ta.dt_alteracao,'DD/MM/YYYY') as dt_cadastro_formatada,
                       ta.dt_castracao,
                       TO_CHAR(ta.dt_castracao,'DD/MM/YYYY') as dt_castracao_formatada,
                       CASE WHEN (dt_castracao is not null and ta.nro_chip is null) then ta.observacao else ta.nro_chip end as nro_chip,
                       t.telefones,
                       ta.dt_agendamento as dt_agendamento,
                       TO_CHAR(ta.dt_agendamento,'DD/MM/YYYY') as dt_agendamento_formatada,
                       ta.especie,
                       t.endereco,
                       mr.descricao as microregiao
                FROM canil.tutor t INNER JOIN canil.tutor_animais ta USING(id_tutor)
                 INNER JOIN microregiao mr USING(id_microregiao)
                WHERE (t.nome ilike '%$form_tutor%'  or t.cpf ilike '%$form_tutor%')
                      AND ta.nome ilike '%$form_animal%' 
                      AND ta.ativo<>'E'
                   $where");

$sort = new Sort($query, $sort_img, $sort_dirname, $sort_style);
if (!$sort_by)    $sort_by = 1;
if (!$sort_dir)   $sort_dir = 0;
$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo   = "Castrações";
$report_periodo     = date('d/m/Y');
$report_orientation = "L";

if ($print) {
   unset($_GET['print']);
   $query->exec($query->sql . $sort->sort_sql);
   $report_cabecalho = array(
      array('id_tutor',     10, 0),
      array('Nome',         50, 1),
      array('CPF',          30, 0),
      array('Tipo',         20, 0),
      array('Animal',       30, 1),
      array('Sexo',         10, 1),
      array('dt1',          20, 0),
      array('Cadastro'    , 20, 1),
      array('dt_castracao', 20, 0),
      array('Castração',    20, 1),
      array('Chip/Info',    30, 1),
      array('Telefone',     30, 1),
      array('Agendamento',  20, 0),
      array('Agend.',       20, 0),
      array('Espécie',      15, 1),
      array('Endereco',     40, 1),
      array('Bairro',       30, 1)
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

include 'CANILCONSULTACASTRACOES_view.php';

?>

<script language="JavaScript">
   function MM_openBrWindow(theURL, winName, features) { //v2.0
      window.open(theURL, winName, features);
   }
</script>

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
                        <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#CANILCONSULTACASTRACOES_view">
                            <i class="fas fa-search"></i>
                        </button>
                        
                    </div>

            </div>

            <div class="row text-center">
               <div class="col-12 col-sm-4 offset-sm-4">
                  <?php
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

         <div class="card-body pt-0 table-responsive">
            <table class="table">

               <thead>
                  <tr>
                     <th colspan="12">
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
                     
                     <td style="<?php echo $sort->verifyItem(1); ?>"><?php echo $sort->printItem(1, $sort->sort_dir, 'Nome') ?></td>
                     <td style="<?php echo $sort->verifyItem(15); ?>"><?php echo $sort->printItem(15, $sort->sort_dir, 'Endereço') ?></td>
                     <td style="<?php echo $sort->verifyItem(16); ?>"><?php echo $sort->printItem(16, $sort->sort_dir, 'Bairro') ?></td>
                     <td style="<?php echo $sort->verifyItem(2); ?>"><?php echo $sort->printItem(2, $sort->sort_dir, 'CPF') ?></td>
                     <td style="<?php echo $sort->verifyItem(4); ?>"><?php echo $sort->printItem(4, $sort->sort_dir, 'Animal') ?></td>
                     <td style="<?php echo $sort->verifyItem(5); ?>"><?php echo $sort->printItem(5, $sort->sort_dir, 'Sexo') ?></td>
                     <td style="<?php echo $sort->verifyItem(5); ?>"><?php echo $sort->printItem(14, $sort->sort_dir, 'Espécie') ?></td>
                     <td style="<?php echo $sort->verifyItem(6); ?>"><?php echo $sort->printItem(6, $sort->sort_dir, 'Cadastro') ?></td>
                     <td style="<?php echo $sort->verifyItem(13); ?>"><?php echo $sort->printItem(13, $sort->sort_dir, 'Agendamento') ?></td>
                     <td style="<?php echo $sort->verifyItem(8); ?>"><?php echo $sort->printItem(8, $sort->sort_dir, 'Castração') ?></td>
                     <td style="<?php echo $sort->verifyItem(10); ?>"><?php echo $sort->printItem(10, $sort->sort_dir, 'Chip/Info') ?></td>
                     <td style="<?php echo $sort->verifyItem(11); ?>"><?php echo $sort->printItem(11, $sort->sort_dir, 'Telefone') ?></td>
                     
                  </tr>

                  <?php
                  while ($n--) {
                     $paging->query->proximo();

                     $js_onclick = "OnClick=javascript:window.location=('CADASTROANIMAl_form.php?id_tutor=" . $paging->query->record[0] . "')";

                     echo "<tr class='entered'/>";

                     echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                     echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record["endereco"] . "</td>";
                     echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record["microregiao"] . "</td>";
                     echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[2] . "</td>";
                     echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[4] . "</td>";
                     echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[5] . "</td>";
                     echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record["especie"] . "</td>";
                     echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[7] . "</td>";
                     echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[13] . "</td>";
                     echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[8] . "</td>";
                     echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[10] . "</td>";
                     echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[11] . "</td>";
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

<?    
   include('../includes/dashboard/footer.php');  
?>