
<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

include_once('../includes/connection.php');
include_once('../function/function.temps.php');

$query_cooc->exec("SELECT * FROM public.macroregiao");
$query_cooc->all();

$i = 0;


foreach ($query_cooc->record as $user) {
    $i++;

    $ip = !empty($user['ip']) ? trim($user['ip']) : '127.0.0.1';
    $login = str_replace("'","",trim(utf8_encode($user['login'])));
    $id_macro = $user['id_macroregiao'];
    $descricao = str_replace("'","",trim(utf8_encode($user['descricao'])));

    /* Verifica se ja existe o user na base */

    $query_sir->exec("SELECT * FROM public.macroregiao WHERE id_macroregiao = '$id_macro'");
  
    if($query_sir->rows())
        continue;



        $query_sir->exec("INSERT INTO public.macroregiao(
        id_macroregiao, descricao, sigla, login, ip, dt_alteracao, hr_alteracao)
        VALUES ('{$user['id_macroregiao']}', '$descricao', '{$user['sigla']}', '$login', '$ip', '{$user['dt_alteracao']}', '{$user['hr_alteracao']}');");
    }

print_r($i . " inserts.");