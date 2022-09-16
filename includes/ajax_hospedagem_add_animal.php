<?
    include('../includes/session.php');
    include('../includes/connection.php');
    include('../includes/variaveisAmbiente.php');

    extract($_GET);
    extract($_POST);

    header('Content-type: application/json');

    
    $query_verifica = new Query($bd);
    $query_verifica->exec("SELECT nro_ficha , nro_chip, ar.id_responsavel FROM animal as a, animal_responsavel as ar 
    WHERE ar.id_animal =  a.id_animal AND nro_ficha ='$nro_ficha' OR nro_chip = '$nro_chip'");

    $query_verifica->begin();



        if($query_verifica->rows() > 0){
         $ret['resultado'] = 0;
         $ret['msg'] = "Animal já existe";
    
     }else{
        
         $ret['status'] = 1;
         $query = new Query($bd);
         $query->begin();
         $query->insertTupla(
             'animal',
             array(
                 
                 trim($nro_ficha),
                 $nro_chip,
                 $id_pelagem,
                 $id_especie,
                 trim($sexo),
                 $observacao,
                 $_login,
                 $_ip,
                 $_data,
                 $_hora,

             )
         );
         $query->commitNotMessage();

  $ret[] = array(
        'resultado' => '1',
        'nro_ficha' => $nro_ficha,
         'nro_chip' => $nro_chip,
         'sexo' => $sexo,
         'id_animal' => $query->lastInsert(),
         'msg' => "Animal Cadastrado com Sucesso",
         'id_responsavel' => $query_verifica->record[2]

        
     );
  
    }

  $ret['erro']= $query->sql;

    



echo json_encode($ret);
