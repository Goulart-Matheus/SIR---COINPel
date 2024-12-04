
<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

include_once('../includes/connection.php');
include_once('../function/function.temps.php');

$query_cooc->exec("SELECT * FROM public.microregiao");
$query_cooc->all();

$i = 0;


foreach ($query_cooc->record as $user) {
    $i++;

    $ip = !empty($user['ip']) ? $user['ip'] : '127.0.0.1';
    $nome = str_replace("'","",trim(utf8_encode($user['nome'])));
    $login = str_replace("'","",trim(utf8_encode($user['login'])));
    $descricao = str_replace("'","",trim(utf8_encode($user['descricao'])));
    $id_micro = $user['id_microregiao'];
    $login_ins = !empty($user['login_insercao']) ? $user['login_insercao'] : 'administrador';

    /* Verifica se ja existe o user na base */

    $query_sir->exec("SELECT * FROM public.microregiao WHERE id_microregiao = '$id_micro'");
  
    if($query_sir->rows())
        continue;

        $query_sir->exec("INSERT INTO public.microregiao(
        id_microregiao, descricao, login_insercao, ip, login_alteracao, dt_criacao, id_macroregiao, meso, micro)
        VALUES ('{$user['id_microregiao']}', '$descricao', '$login_ins', '{$user['ip']}', '{$user['login_alteracao']}', '{$user['dt_criacao']}', '{$user['id_macroregiao']}', '{$user['meso']}', '{$user['micro']}');");
    }

print_r($i . " inserts.");