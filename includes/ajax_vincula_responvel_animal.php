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

//CAPTA INFORMAÇÕES DO PACIENTE COMORBIDADE
    //$query->exec("SELECT * FROM paciente_comorbidade WHERE id_paciente = $id_paciente_base");
    
   // $n = $query->rows();
    //$c = 0;
   // $z = 1;

   // $dados_paciente_comorbidade = [];
   // $nulos = array();

    //if($n > 0)
   // {
        //while($n--)
       // {
           // $query->proximo();

           // $dados_paciente_comorbidade[$c][0] = "";

            //for($i=0; $i < sizeof($query->record)/2; $i++)
            //{
               // if($i > 1)
               // {
                   // $dados_paciente_comorbidade[$c][$z] = $query->record[$i];
                   // $z++;
                //}
           // }

            //$dados_paciente_comorbidade[$c][2] = $_login;
            //$dados_paciente_comorbidade[$c][3] = $_ip;
           // $dados_paciente_comorbidade[$c][4] = $_data;
            //$dados_paciente_comorbidade[$c][5] = $_hora;

            //$c++;
        //}   
    //}

//CAPTA INFORMAÇÕES DO PACIENTE PROFISSÃO EDUCAÇÃO
    //$query->exec("SELECT * FROM paciente_profissao_educacao WHERE id_paciente = $id_paciente_base");
    //$n = $query->rows();
   // $c = 0;
   // $z = 1;

   // $dados_paciente_educacao = [];
    //$nulos = array();

    //if($n > 0)
    //{
        //while($n--)
       // {
           // $query->proximo();

            //$dados_paciente_educacao[$c][0] = "";

            //for($i=0; $i < sizeof($query->record)/2; $i++)
            //{
                //if($i > 1)
                //{
                    //$dados_paciente_educacao[$c][$z] = $query->record[$i];
                //}
                //$z++;
            //}

           // $dados_paciente_educacao[$c][4] = $_login;
            //$dados_paciente_educacao[$c][5] = $_ip;
            //$dados_paciente_educacao[$c][6] = $_data;
            //$dados_paciente_educacao[$c][7] = $_hora;

            //$c++;
        //}   
    //}

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


//CAPTA INFORMAÇÕES DO PACIENTE VACINA
    //$query->exec("SELECT * FROM paciente_vacina WHERE id_paciente = $id_paciente_base");
    //$n = $query->rows();
   // $c = 0;
   // $z = 0;

    //$dados_paciente_vacina = [];
    //$nulos = array();

    //if($n > 0)
   // {
       // while($n--)
       // {
            //$query->proximo();

           // $dados_paciente_vacina[$c][0] = "";

           // for($i=0; $i < sizeof($query->record)/2; $i++)
            //{
                //if($i > 1)
                //{
                    //$dados_paciente_vacina[$c][$z] = $query->record[$i];
                    //$z++;
                //}
            //}

           // $dados_paciente_vacina[$c][4] = $_login;
           // $dados_paciente_vacina[$c][5] = $_ip;
           // $dados_paciente_vacina[$c][6] = $_data;
            //$dados_paciente_vacina[$c][7] = $_hora;

            //$c++;
        //}   
   // }

//INICIA OS PROCESSOS DE INSERÇÃO DO NOVO REGISTRO

    $query->insertTupla('responsavel',$dados_responsavel);
    $new_id = $query->last_insert[0];

    if($new_id > 0)
    {
        $query->updateTupla1Coluna('responsavel' , 'id_responsavel_vinculado' , $new_id , 'id_responsavel' , $id_responsavel_base);

        //$query->exec("SELECT id_geral_casos, dt_relatorio FROM relatorios_diarios.geral_casos WHERE status_relatorio = 'A' OR status_relatorio = 'P' ORDER BY id_geral_casos DESC LIMIT 1");
        //$query->result($query->linha);

        //$id_geral_casos = $query->record[0];
        //$dt_relatorio   = $query->record[1];

        //$idade = ($dados_paciente[3] != '0') ? $dados_paciente[3] : $dados_paciente[4];
        //$unidade_idade = ($dados_paciente[3] != '0') ? 'A' : 'M';

        //$query->insertTupla('relatorios_diarios.geral_casos_novos',array($id_geral_casos,
                                                                        //$idade,
                                                                         //$dados_paciente[5],
                                                                         //$_login,
                                                                         //$_ip,
                                                                         //$_data,
                                                                         //$_hora,
                                                                         //$dt_relatorio,
                                                                         //$unidade_idade
                                                                        //)
                           // );

        //$query->insertTupla('paciente_evolucao', array($new_id,4,$_data,'',$_login,$_ip,$_data,$_hora,'NULL','NULL','NULL'));

        //for($i = 0; $i < sizeof($dados_paciente_comorbidade); $i++)
        //{ 
            //$dados_paciente_comorbidade[$i][0] = $new_id; 
            //$query->insertTupla('paciente_comorbidade', $dados_paciente_comorbidade[$i]);
        //}

       // for($i = 0; $i < sizeof($dados_paciente_educacao); $i++)
        //{ 
            //$dados_paciente_educacao[$i][0] = $new_id; 
            //$query->insertTupla('paciente_profissao_educacao', $dados_paciente_educacao[$i]);
        //}

        //for($i = 0; $i < sizeof($dados_paciente_telefone); $i++)
       // { 
            //$dados_paciente_telefone[$i][0] = $new_id; 
            //$query->insertTupla('paciente_telefone', $dados_paciente_telefone[$i]);
        //}

        //for($i = 0; $i < sizeof($dados_paciente_vacina); $i++)
        //{ 
           // $dados_paciente_vacina[$i][0] = $new_id; 
            //$query->insertTupla('paciente_vacina', $dados_paciente_vacina[$i]);
        //}
        
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