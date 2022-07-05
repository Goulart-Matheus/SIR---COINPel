
<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include('../function/function.date.php');
include_once('../includes/dashboard/header.php');

$query->exec("SELECT id_animal , id_responsavel FROM animal_responsavel WHERE (data_validade IS NULL or data_validade>=current_date) ORDER BY id_animal_responsavel");

$n = $query->rows();

while ($n--) {
  $query->proximo();
  $mensagem .= nl2br($query->record[0]);
}

?>

<section class="content">

  <div class="card">
    <div class="card-body">
      Bem-Vindo ao Sistema de Hospedaria para Recolhimento de Animais de Pelotas -RS
    </div>
  </div>

  <?

    //if($_id_orgao == 3 || $_id_orgao == 1 || $_id_orgao == 6)
    //{
     // include('index1.php');
   // }
   // else
    //{
      //include('index2.php');


    //}


    //HEADER PARA PRODUÇÃO
     //header("location: /sir/php/index.php");

    //HEADER LOCAL
    //header("location: /php/index.php");


  ?>

</section>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<?

include_once('../includes/dashboard/footer.php');

?>