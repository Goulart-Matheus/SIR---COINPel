<?
date_default_timezone_set('America/Sao_Paulo');
$_data  = (strftime("%Y-%m-%d"));
$_hora  = (strftime("%H:%M"));
$_datahora = strftime('%Y-%m-%d %H:%M');
$_login = $auth->getUser();
$_ip    = $_SERVER['REMOTE_ADDR'];
$_id_cliente      = $auth->getClientId()              ;
$_id_orgao        = $auth->getOrgaoId()               ;
$_name_orgao      = $auth->getOrgaoName()             ;


?>