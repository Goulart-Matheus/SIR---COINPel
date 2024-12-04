
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

$query_cooc->exec("SELECT * FROM canil.castracoes_referencias");

$query_cooc->all();

$i = 0;

$query_sir->begin();
foreach ($query_cooc->record as $user) 
{
    $i++;

    $ip = !empty($user['ip']) ? $user['ip'] : '127.0.0.1';
    $id_paramentro = $user['id_paramentro'];

   
    $query_sir->exec("INSERT INTO canil.castracoes_referencias(
	castracoes_referencias, referencia, total, macho, femea, canil, login, ip, dt_alteracao, hr_alteracao)
	VALUES ('{$user['castracoes_referencias']}', '{$user['referencia']}', '{$user['total']}', '{$user['macho']}', '{$user['femea']}', '{$user['canil']}', '{$user['login']}', '{$user['ip']}', '{$user['dt_alteracao']}', '{$user['hr_alteracao']}');");
    echo $query_sir->sql;
    echo "<br><br>";    
    
}
$query_sir->commit();
print_r($i . " inserts.");