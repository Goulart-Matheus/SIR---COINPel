<?php

include('./session.php');
include('./variaveisAmbiente.php');
include("../function/function.date.php");

$query_aux = new Query($bd);

error_reporting(E_ALL);
header("Content-type: application/json");

$id_responsavel_base = trim($_GET["id_responsavel_base"]);

function trataNulos($valor)
{
    return $valor != "" ? $valor : "NULL";
}

function trataZeros($valor)
{
    return $valor != "" ? $valor : "0";
}

//CAPTA INFORMAÇÕES DO RESPONSAVEL
    $query->exec("SELECT * FROM responsavel WHERE id_responsavel = $id_responsavel_base");
    $query->result($query->linha);
    $c = 0;

    $dados_responsavel = [];
    $nulos = array(10,11,13,14,17,18,27);
    $zeros = array(4,5);

    for($i=0; $i < sizeof($query->record)/2; $i++)
    {
        if($i > 0)
        {
            if(in_array($i,$nulos))
            {
                $query->record[$i] = trataNulos($query->record[$i]);
            }

            if(in_array($i,$zeros))
            {
                $query->record[$i] = trataZeros($query->record[$i]);
            }
    
            $dados_responsavel[$c] = $query->record[$i];

            $c++;
        }
        
    }

    $dados_responsavel[19] = $dados_responsavel[23] = $_data;
    $dados_responsavel[20] = $dados_responsavel[24] = $_hora;
    $dados_responsavel[21] = $_login;
    $dados_responsavel[22] = $_ip;



//CAPTA INFORMAÇÕES DO PACIENTE TELEFONE
    $query->exec("SELECT * FROM responsavel_contato WHERE id_responsavel = $id_responsavel_base");
    $n = $query->rows();
    $c = 0;
    $z = 1;

    $dados_responsavel_contato = [];
    $nulos = array();

    if($n > 0)
    {
        while($n--)
        {
            $query->proximo();

            $dados_responsavel_contato[$c][0] = "";

            for($i=0; $i < sizeof($query->record)/2; $i++)
            {
                if($i > 1)
                {
                    $dados_responsavel_contato[$c][$z] = $query->record[$i];
                    $z++;
                }
            }

            $dados_responsavel_contato[$c][3] = $_login;
            $dados_responsavel_contato[$c][4] = $_ip;
            $dados_responsavel_contato[$c][5] = $_data;
            $dados_responsavel_contato[$c][6] = $_hora;

            $c++;
        }   
    }




//INICIA OS PROCESSOS DE INSERÇÃO DO NOVO REGISTRO

    $query->insertTupla('responsavel',$dados_responsavel);
    $new_id = $query->last_insert[0];

    if($new_id > 0)
    {
        $query->updateTupla1Coluna('responsavel' , 'id_responsavel_vinculado' , $new_id , 'id_responsavel' , $id_responsavel_base);

        
        
        $return[] = array("id_responsavel_new" => $new_id, 
                          "status"          => 1
                        );
    }
    else
    {
        $return[] = array("status" => 0);
    }

    echo json_encode($return);

?>