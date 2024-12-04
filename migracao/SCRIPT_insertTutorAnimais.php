

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

$query_cooc->exec("SELECT * FROM canil.tutor_animais");
$query_cooc->all();

$i = 0;

$query_sir->begin();
$query_sir->exec("TRUNCATE TABLE canil.tutor_animais CASCADE");

foreach ($query_cooc->record as $user) 
    {
        $i++;

        $ip = !empty($user['ip']) ? $user['ip'] : '127.0.0.1';
        $id_login_castracao = !empty($user['login_castracao']) ? $user['login_castracao'] : 3;
        $nome = trim(utf8_encode($user['nome']));
        $login = trim(utf8_encode($user['login']));
        $obsv = trim(utf8_encode($user['observacao']));
        $obsv_situacao = trim(utf8_encode($user['observacao_situacao']));
        $id_tutor = $user['id_tutor'];
        $id_tutor_animais = $user['id_tutor_animais'];
        $idade = !empty(trim(utf8_encode($user['idade']))) ? trim(utf8_encode($user['idade'])) : 'N/D';
        $pelagem = trim(utf8_encode($user['pelagem']));
        $nro_chip = !empty($user['nro_chip']) ? $user['nro_chip'] : '0';
        $dt_ag = !empty($user['dt_agendamento']) ? $user['dt_agendamento'] : '';
        $id_ab = !empty($user['id_abrigo']) ? $user['id_abrigo'] : '';
        $dt_castracao = !empty($user['dt_castracao']) ? $user['dt_castracao'] : '';

        $query_sir->exec("SELECT * FROM canil.tutor_animais WHERE  id_tutor_animais = '$id_tutor_animais'");
  
        if($query_sir->rows())
            continue;

        $query_sir->exec("INSERT INTO canil.tutor_animais(
        id_tutor_animais, id_tutor, nome, especie, sexo, idade, porte, pelagem, login, ip, dt_alteracao, hr_alteracao, nro_chip, login_castracao,
        dt_castracao, observacao, rua, ativo, observacao_situacao, dt_agendamento, castra_movel, id_abrigo)
        VALUES ('$id_tutor_animais', 
        '$id_tutor', 
        '$nome', 
        '{$user['especie']}', 
        '{$user['sexo']}', 
        '$idade',
        '{$user['porte']}', 
        '$pelagem', 
        '$login', 
        '$ip', 
        '{$user['dt_alteracao']}', 
        '{$user['hr_alteracao']}', 
        '$nro_chip', 
        '{$id_login_castracao}', 
         NULLIF('$dt_castracao', '')::date, 
        '{$obsv}', 
        '{$user['rua']}', 
        '{$user['ativo']}', 
        '{$obsv_situacao}', NULLIF('$dt_ag', '')::date, '{$user['castra_movel']}', NULLIF('$id_ab', '')::integer);");
        echo $query_sir->sql;
        echo "<br><br>";
    }

    $query_sir->commit();
    print_r($i . " inserts.");

