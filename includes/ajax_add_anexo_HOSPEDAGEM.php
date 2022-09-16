<?
include('../includes/session.php');
include('../includes/connection.php');
include('../includes/variaveisAmbiente.php');


extract($_GET);
extract($_POST);
header('Content-type: application/json');

$query_add_anexo = new Query($bd);

if(!$form_arquivo){
    $status=1;
}else{
    $status=0;
}

if (trim($_FILES['form_arquivo']['name']) != '') {
    $diretorio = "../arquivos/$_login/";

    include('../includes/uploadarquivo2.php');

    $form_arquivo = $arquivo_nome;

    $dados = array(        
        $id_hospedagem,
        $form_nome,
        $form_arquivo,
        $_login,
        $_ip,
        $_data,
        $_hora
    );

    $query_add_anexo->insertTupla('hospedagem_anexo', $dados);
    $query_add_anexo->commitNotMessage();
}

$ret[]=array(
    'status'=>$status,
    'arquivo'=>$arquivo_dir,
    'nome_arquivo'=>$form_nome,
    'id_hospedagem'=>$id_hospedagem,
    'query' => $query_add_anexo->sql,
    'diretorio'=>$diretorio,
    'login'=>$_login,

);

echo json_encode($ret);
?>