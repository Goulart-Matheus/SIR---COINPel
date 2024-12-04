
<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

include_once('../includes/connection.php');
include_once('../function/function.temps.php');

$query_cooc->exec("SELECT * FROM usuario");
$query_cooc->all();

$i = 0;


foreach ($query_cooc->record as $user) {
    $i++;

    $ip = !empty($user['ip']) ? $user['ip'] : '127.0.0.1';
    $nome = str_replace("'","",trim(utf8_encode($user['nome'])));
    $login = str_replace("'","",trim(utf8_encode($user['login'])));
    $dt_validade = $user['dt_validade'] == "" ? date("Y-m-d") : $user['dt_validade'];

    /* Verifica se ja existe o user na base */

    $query_sir->exec("SELECT * FROM usuario WHERE login = '$login'");
  
    if($query_sir->rows())
        continue;

    /* Busca id baseado no login */
    //$user_id = retornaIdentificador($query_sir, $login);

    echo "<pre>";
    print_r($user);
    print_r("INSERT INTO public.usuario(
        login, senha, nome, email, habilitado, dt_validade, dt_inatividade_inicial, dt_inatividade_final, alterou_senha, alteracao_login, alteracao_ip, alteracao_data, id_cliente, id_orgao)
        VALUES ('$login', '{$user['senha']}', '$nome', 'teste@teste.com', 'S','$dt_validade', NULL, NULL,'{$user['alterou_senha']}', NULL, '$ip', NULL, 1, 1);"); 
    echo "</pre>";
    die;

    $query_sir->exec("INSERT INTO public.usuario(
	login, senha, nome, email, habilitado, dt_validade, dt_inatividade_inicial, dt_inatividade_final, alterou_senha, alteracao_login, alteracao_ip, alteracao_data, id_cliente, id_orgao)
	VALUES ('$login', '{$user['senha']}', '$nome', 'teste@teste.com', 'S','$dt_validade', NULL, NULL,'{$user['alterou_senha']}', NULL, '$ip', NULL, 1, 1);");

}

print_r($i . " inserts.");