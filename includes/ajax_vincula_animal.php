<?

include('../includes/session.php');
include('../includes/connection.php');
include('../includes/variaveisAmbiente.php');

extract($_GET);
extract($_POST);
header('Content-type: application/json');


$_id_responsavel  = $_POST['id_responsavel'];
$_id_animal = $_POST['form_vincula_animal'];
 



    $query->begin();

    if ($_id_responsavel != "" && $_id_animal!=""){
        
        $ret[] = array( "resultado"             =>  1                          ,
        "id_responsavel"                        =>                     $_id_responsavel     ,
        "form_vincula_animal[0]"                =>                     $_id_animal          ,);

        
        
      foreach($_id_animal as $animal){
        
        $query->begin();
        $query->insertTupla(
        'animal_responsavel',
       
       
       
        array(
            $animal,
            $_id_responsavel, 
            $auth->getUser(),
            $_ip,
            $_data,
            $_hora,
              )    
        );
        $query->commitNotMessage();} ; 
        
        

       

    }  else 
    {

        $ret[] = array("resultado" => 0 );

    }
                             
echo json_encode($ret);
?>