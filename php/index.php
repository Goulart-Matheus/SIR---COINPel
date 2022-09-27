<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include('../function/function.date.php');
include_once('../includes/dashboard/header.php');

$query->exec("SELECT TRIM(descricao) FROM mensagem WHERE (data_validade IS NULL or data_validade>=current_date) ORDER BY codmensagem");
$n = $query->rows();
while ($n--) {
  $query->proximo();
  $mensagem .= nl2br($query->record[0]);
}
?>
<section class="content">
  <div class="card">
    <div class="card-body text-center">
      
      <h4>Bem-Vindo ao Sistema SIR</h4>

      <div class="row">

        <div class="col-12">

          <? include("../includes/cards/Card_dashboard_GERAL/card_Dashboard_GERAL.php"); ?>

        </div>

      </div>

    </div>
  </div>
  <div class="container-fluid ">

  </div>
</section>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load("current", {
    packages: ["corechart"]
  });
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Área', 'Total'],
      ['Domínio Público', 11],
      ['Fiscal', 2],
      ['trabalhista', 7]
    ]);

    var options = {
      title: 'Processos por Área',
      is3D: true,
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
    chart.draw(data, options);
  }
</script>
<? include_once('../includes/dashboard/footer.php'); ?>