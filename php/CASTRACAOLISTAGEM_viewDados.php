<?


include('../includes/session.php');
include('../class/class.report.php');
include('../includes/variaveisAmbiente.php');

include('../includes/inc_castracoes_totais_referencia.php');

$where = '';

if(isset($filter)) // Filtro da Modal
    {
        $where .= $form_sexo != "" ? " AND sexo = '$form_sexo' " : "";
        $where .= $form_castra_movel != "" ? " AND castra_movel = '$form_castra_movel' " : "";
        $where .= $form_especie != "" ? " AND especie = '$form_especie' " : ""; 
    }

$query->exec("SELECT ta.id_tutor_animais,
                     row_number() OVER (PARTITION by 0 ORDER BY ta.sexo,ta.id_tutor_animais)  as _seq,
                     t.nome,
                     ta.nome,
                     ta.especie,
                     ta.sexo,
                     ta.idade,
                     ta.porte,
                     ta.pelagem,
                     t.telefones,
                     ta.castra_movel
              FROM   canil.tutor_animais ta INNER JOIN canil.tutor t USING(id_tutor)
              WHERE t.nome  ilike '%{$form_nome}%' ". $where);

$sort = new Sort($query, $sort_icon, $sort_dirname, $sort_style);
if (!$sort_by)    $sort_by = 1;
if (!$sort_dir)   $sort_dir = 0;
$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo   = "Aplicações";
$report_periodo     = date('d/m/Y');

if ($print) {
   include('../class/class.report.php');
   unset($_GET['print']);
   $report_cabecalho = array(
      array('id',        10, 0),
      array('Seq',       10, 1),
      array('Tutor',     65, 1),
      array('Animal',    25, 1),
      array('Espécie',   20, 1),
      array('Sexo',      10, 1),
      array('Idade',     35, 1),
      array('Porte',     10, 1),
      array('Pelagem',   70, 1),
      array('Telefone',  30, 1),
      array('Castra Móvel',  30, 1)
   );
   $query->exec($query->sql . $sort->sort_sql);
   $report = new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);
   exit;
} else {
   $paging = new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);
   $paging->exec($query->sql . $sort->sort_sql);
}

include('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();
$tab->setTab('Listagem para Castração', 'fas fa-file-code', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);


$n = $paging->query->rows();

if (!$n) {
   echo callException('Nenhum item cadastrado para listagem!', 2);
   exit;
}

include 'CASTRACAOLISTAGEM_view.php';


?>


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
                        <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#CASTRACAOLISTAGEM_view">
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

                        <td style='<? echo $sort->verifyItem(0); ?>' width="5px"></td>

                        <td style='<? echo $sort->verifyItem(0); ?>'><? echo $sort->printItem(0, $sort->sort_dir, 'Seq')     ?></td>
                        <td style='<? echo $sort->verifyItem(2); ?>'><? echo $sort->printItem(2, $sort->sort_dir, 'Tutor')     ?></td>
                        <td style='<? echo $sort->verifyItem(9); ?>'><? echo $sort->printItem(9, $sort->sort_dir, 'Telefone')     ?></td>
                        <td style='<? echo $sort->verifyItem(3); ?>'><? echo $sort->printItem(3, $sort->sort_dir, 'Animal')     ?></td>
                        <td style='<? echo $sort->verifyItem(4); ?>'><? echo $sort->printItem(4, $sort->sort_dir, 'Espécie') ?></td>
                        <td style='<? echo $sort->verifyItem(6); ?>'><? echo $sort->printItem(6, $sort->sort_dir, 'Idade')      ?></td>
                        <td style='<? echo $sort->verifyItem(7); ?>'><? echo $sort->printItem(7, $sort->sort_dir, 'Porte.')  ?></td>
                        <td style='<? echo $sort->verifyItem(5); ?>'><? echo $sort->printItem(5, $sort->sort_dir, 'Sexo')  ?></td>
                        <td style='<? echo $sort->verifyItem(8); ?>'><? echo $sort->printItem(8, $sort->sort_dir, 'Pelagem')  ?></td>
                        <td style='<? echo $sort->verifyItem(10); ?>'><? echo $sort->printItem(10, $sort->sort_dir, 'Castra Móvel')  ?></td>

                     </tr>

                     <?
                     while ($n--) {
                        $paging->query->proximo();
                        $js_onclick = "OnClick=javascript:window.location=('CASTRACAO_edit.php?id_tutor_animais=" . $paging->query->record['id_tutor_animais'] . "')";

                        echo "<tr class='entered'>";
                        
                        echo "<td class='text-center' valign='top' " . $js_onclick . "><i class='" . $paging->query->record[0]  . "'></i></td>";
                        echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record['_seq'] . "</td>"; //SEQ
                        echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[2] . "</td>"; //TUTOR
                        echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[9] . "</td>"; //TELEFONE
                        echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[3] . "</td>"; //ANIMAL
                        echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[4] . "</td>"; //ESPECIE
                        echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[6] . "</td>"; //IDADE
                        echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[7] . "</td>"; //PORTE
                        echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[5] . "</td>"; //SEXO
                        echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[8] . "</td>"; //PELAGEM
                        echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record[10] . "</td>"; //Castra Movel
                        echo "</tr>";
                     }
                     ?>

                  </tbody>

                  <tfoot>
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

         </div>
      </form>
   </div>   
</section>

<? 
include('../includes/dashboard/footer.php'); 
?>
