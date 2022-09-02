<?

include('../includes/session.php');
include('../includes/connection.php');
include('../includes/variaveisAmbiente.php');

extract($_GET);
extract($_POST);
header('Content-type: application/json');


$_id_animal  = $_POST['id_animal'];
$_id_responsavel = $_POST['form_vincula_responsavel'];

$ret['status'] = 1;

$query->begin();

if ($_id_responsavel != "" && $_id_animal != "") {

  foreach ($_id_responsavel as $responsavel) {

    $query->begin();
    $query->insertTupla(
      'animal_responsavel',
      array(
        $_id_animal,
        $responsavel,
        $auth->getUser(),
        $_ip,
        $_data,
        $_hora,
      )
    );

    $query->commitNotMessage();
  };


} else {

  $ret['status'] = 0;
}

echo json_encode($ret);
