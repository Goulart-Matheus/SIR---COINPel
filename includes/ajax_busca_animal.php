<?

include('../includes/session.php');
include('../includes/connection.php');
include('../includes/variaveisAmbiente.php');

extract($_GET);
extract($_POST);
header('Content-type: application/json');

$_nro_ficha = $_POST['nro_ficha'];
$_nro_chip  = $_POST['nro_chip'];
$_especie   = $_POST['especie'];
$_pelagem   = $_POST['pelagem'];
$_sexo      = $_POST['sexo'];


            
        $where = "";
        
        if($_nro_ficha!=""){
            $where.= " and a.nro_ficha = '$_nro_ficha'";
        }
        if($_nro_chip!=""){
            $where.= " and a.nro_chip  = '$_nro_chip'";
        }
        if($_especie !=""){
            $where.= " and e.descricao ilike '{$_especie}'";
        }
        if($_pelagem!=""){
            $where.= " and p.descricao ilike '{$_pelagem}'";
        }
        if($_sexo!=""){
            $where.= " and a.sexo ilike '{$_sexo}'";
        }

        $query_valores = new Query($bd);
        $query_valores->exec("SELECT   a.nro_ficha , a.nro_chip ,e.descricao as especie,p.descricao as pelagem,a.sexo
                              FROM animal a, especie e, pelagem p
                              WHERE a.id_pelagem = p.id_pelagem
                              AND   a.id_especie = e.id_especie     
                                                       
                                ".$where);
                                

        if($query_valores->rows() > 0 ){
            $query_valores->proximo();
            $ret[] = array( "resultado"             =>  1                                                             ,
           
            "nro_ficha"                             =>  trim($query_valores->record['nro_ficha'])            ,
            "nro_chip"                              =>  trim($query_valores->record['nro_chip'])                  ,
            "pelagem"                               =>  trim($query_valores->record['pelagem'])                     ,                        
            "especie"                               =>  trim($query_valores->record['especie'])             ,
            "sexo"                                  =>  trim($query_valores->record['sexo'])                      ,    
           
            
        );
        }else{
            $ret[] = array("resultado" => 0 );
        }
  echo ($query_valores->sql);
echo json_encode($ret);
?>