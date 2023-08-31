<?
include('../includes/session.php');
include('../includes/connection.php');
include('../includes/variaveisAmbiente.php');

extract($_GET);
extract($_POST);

error_reporting(E_ALL);
header('Content-type: application/json');

$query = new Query($bd);

$query->exec("SELECT o.id_ocorrencia, o.descricao FROM denuncias.ocorrencia as o WHERE o.status = 'X'");
$query->proximo();
$audio = $query->rows() > 0 ? 'S' : 'N';

// UPDATE na tabela OCORRÊNCIA do status 'X' que deve haver ÁUDIO NOTIFICAÇÃO para status 'N' OCORRÊNCIA NOVA.

$query->updateTupla1Coluna("denuncias.ocorrencia" , 'status' , 'N' , 'status', 'X');

$query->exec("SELECT o.id_ocorrencia, o.descricao FROM denuncias.ocorrencia as o WHERE o.status = 'N'");
$query->proximo();
$valor= $query->rows();


$query->exec("SELECT distinct ot.id_ocorrencia FROM denuncias.ocorrencia_tramitacao as ot, denuncias.tipo_ocorrencia as toc,  orgao as org
WHERE ot.id_tipo_ocorrencia = toc.id_tipo_ocorrencia 
and toc.id_orgao = org.id_orgao
and org.id_orgao = $_id_orgao
and ot.id_ocorrencia not in (SELECT id_ocorrencia FROM denuncias.ocorrencia_orgao where id_orgao = $_id_orgao)");
$query->proximo();
$notificacao= $query->rows();


// $query->exec("SELECT ot.id_ocorrencia, ot.visualizado FROM denuncias.ocorrencia_tramitacao as ot
// WHERE ot.id_ocorrencia not in (SELECT id_ocorrencia FROM denuncias.ocorrencia_orgao)");
// $query->proximo();
// $notificacao= $query->rows();

$ret = array
      (
        'valor' => $valor,
        'audio' => $audio,
        'notificacao'=> $notificacao,
      );

echo json_encode($ret);
