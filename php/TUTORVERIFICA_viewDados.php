<?php
include('../includes/session.php');
include('../function/function.date.php');
include('../class/class.report.php');
include('../function/function.string.php');
include('../includes/variaveisAmbiente.php');

$where = '';

if(isset($filter)) // Filtro da Modal
    {
        $where .= $form_cpf != "" ? " AND cpf = '$form_cpf' " : ""; 
    }

$query->exec("SELECT id_tutor , nome , cpf 
                FROM canil.tutor 
                WHERE nome ilike '%{$form_nome}%' ". $where);

$query->proximo();

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
  
  $report = new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);
  exit;
} else {
  $paging = new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);
  $paging->exec($query->sql . $sort->sort_sql);
}

include('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();
$tab->setTab('Pesquisa', 'fa-solid fa-search', $_SERVER['PHP_SELF']);
$tab->setTab('Adicionar', 'fa-solid fa-plus', 'TUTOR_form.php');
$tab->printTab($_SERVER['PHP_SELF']);

$n = $query->rows();
if (!$n) {
  echo callException('Nenhum item cadastrado para listagem!', 2);
  exit;
}

if ($erro) {
  echo callException($erro, 2);
}

include 'TUTORVERIFICA_view.php';

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

              <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#TUTORVERIFICA_view">
                <i class="fas fa-search"></i>
              </button>

              <a href="TUTOR_form.php?nome=<?php echo $form_nome; ?>" class="btn btn-sm btn-green text-light">
                <i class="fa-solid fa-plus"></i>
              </a>

            </div>
          </div>
        </div>

        <div class="card-body table-responsive">
          <table class="table">

            <thead>
              <tr class="bg-light">
                <th><?php echo $sort->printItem(2, $sort->sort_dir, 'Nome') ?></th>
                <th><?php echo $sort->printItem(3, $sort->sort_dir, 'CPF') ?></th>
              </tr>
            </thead>

            <tbody>
              <?php
              while ($n--) {
                $paging->query->proximo();
                $js_onclick = "OnClick=javascript:window.location=('CADASTROANIMAL_form.php?id_tutor=" . $paging->query->record[0] . "')";
                echo "<tr class='entered'>";

                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record['nome'] . "</td>";
                echo "<td valign='top' " . $js_onclick . ">" . $paging->query->record['cpf'] . "</td>";
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
<? 
  include('../includes/dashboard/footer.php');
?>