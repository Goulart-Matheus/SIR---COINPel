
<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

function callException($msg, $type, $hide=false) {
    switch ($type) {
           case 0: $class   ='alert-green';
                    $icon_set_message = 'circle-check';
                   break;
           case 1: $class   ='alert-danger';
                    $icon_set_message = 'circle-xmark';
                   break;
           case 2: $class   ='alert-warning text-white';
                    $icon_set_message = 'circle-exclamation';
                   break;
           default:$class   ='alert-primary';
                   break;
    }
    if($hide) $class .=' alert';
    return "
        <div class='rounded p-2 ".$class." alert-dismissable' role='alert'>
            <div>
                <i class='fa-solid fa-" . $icon_set_message . "'></i>
                ".trim($msg)."
            </div>
        </div>";
}

include_once('../includes/connection.php');
//include_once('../function/function.temps.php');

$query_cooc->exec("SELECT * FROM canil.tutor order by id_tutor");

$query_cooc->all();

$i = 0;

$query_sir->begin();
$query_sir->exec("TRUNCATE TABLE canil.tutor CASCADE");
foreach ($query_cooc->record as $user) 
{
    $i++;

    $ip = !empty($user['ip']) ? $user['ip'] : '127.0.0.1';
    $nome = trim(utf8_encode($user['nome']));
    $fone = trim(utf8_encode($user['telefones']));
    $login = str_replace("'","",trim(utf8_encode($user['login'])));
    $p_ref = !empty($user['ponto_referencia']) ? trim(utf8_encode(($user['ponto_referencia']))) : 'não mencionado';
    $endereco = trim(utf8_encode($user['endereco']));
    $id_tutor = $user['id_tutor'];

   
    $query_sir->exec("INSERT INTO canil.tutor(
        id_tutor, nome, nome_sem_acento, cpf, telefones, endereco, id_microregiao, ponto_referencia, login, ip, dt_alteracao, hr_alteracao, tipo, anexo, adotante, posoperatorio)
        VALUES ('$id_tutor', '$nome', '{$user['nome_sem_acento']}', '{$user['cpf']}', '$fone', '$endereco', '{$user['id_microregiao']}', '$p_ref', '$login', '$ip', '{$user['dt_alteracao']}', '{$user['hr_alteracao']}', '{$user['tipo']}', '{$user['anexo']}', '{$user['adotante']}', '{$user['posoperatorio']}');");
    echo $query_sir->sql;
    echo "<br><br>";    
    
}
$query_sir->commit();
print_r($i . " inserts.");