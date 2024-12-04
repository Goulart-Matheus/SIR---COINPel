<?

////////////////////////////////////////////////////////////////////////////////////////////////////// DADOS DO BANCO

$host			= "192.168.0.214";
$port			= "5432";
$database		= "proges_sdeti";
$user			= "postgres";
$pass			= "C8KiUx1H";
$conexao	    = pg_connect("host=$host port=$port dbname=$database user=$user password=$pass");

if ($conexao)  { echo '<h3>Conectado ao '.$database.' </h3>';    } else { echo '<h3>Erro ao conectar no '.$database.' </h3>'; exit;   }

////////////////////////////////////////////////////////////////////////////////////////////////////// DADOS DO TABELA USUARIO

$tabela    = "usuario";
$col_login = "login"  ;
$col_senha = "senha"  ;
$col_dt_val = "dt_validade";


$sql = "SELECT " . trim($col_login) . " , " . trim($col_senha) . " FROM " . trim($tabela) ;

$result = pg_exec($conexao, $sql);
$i      = pg_numrows($result);

// echo "<table border='1px'>";

while($i--){

    $login = pg_fetch_array($result,$i)[0];
    $pass  = pg_fetch_array($result,$i)[1];

    if(strlen(trim($pass)) < 8){
        $senha_nova = "C01npel@" . trim($pass);
    } else {
        $senha_nova = trim($pass);
    }

    $senha_cript = sha1(trim($senha_nova));
    $data_validade = date('Y-m-d', strtotime('+60 days', strtotime((strftime("%Y-%m-%d")))));


    $sql           = "UPDATE $tabela SET $col_senha = '" . $senha_cript . "' ,  alterou_senha = 'N', $col_dt_val   = '" . $data_validade . "'  WHERE $col_login = '" . utf8_encode($login) ."';";
    // $result_update = pg_exec($conexao, $sql);

    if(!pg_errormessage($conexao)){
        $ret_bd    = "[OK]";
        $bg_color  = "green";
        $color     = "white";
    } else {
        $ret_bd    = pg_errormessage($conexao);
        $bg_color  = "red";
        $color     = "white";
    }

    // echo "<tr>";

    //     echo "<td>" . $sql       . "</td>";
    //     echo "<td>" . $login       . "</td>";
    //     echo "<td>" . $pass        . "</td>";
    //     echo "<td>" . $senha_nova  . "</td>";
    //     echo "<td>" . $senha_cript . "</td>";
    //     echo "<td style='background-color: " . $bg_color . ";'>" . $ret_bd      . "</td>";

    // echo "</tr>";
    // echo $sql." ->>>".$senha_nova."<br>";
    echo $sql." <br>";

}

// echo "</table>";

?>
