<?
/****************************************************************************
* Software: SMSU - Secretaria Municipal de Servi�os Urbanos                 *
* Version:  1.00                                                            *
* Copyright (c) 2003 COINPEl                                                *
*                                                                           *
* Author:   Eduardo Bastos                                                  *
* Date:     2003/06/16                                                      *
****************************************************************************/

include('../includes/session.php');
include('../includes/dashboard/header.php');
include('../includes/variaveisAmbiente.php');

include('../class/class.tab.php');
$tab = new Tab();

$tab->setTab('Tutor', 'fas fa-file-code', 'CADASTROANIMAL_form.php?id_tutor='.$id_tutor);
$tab->setTab('Avaliação', 'fa-solid fa-plus', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);


if(!is_numeric($id_tutor_animais)) exit;

 // Verifica se o avaliação já foi feita
 $query->exec("SELECT dt_avaliacao FROM canil.tutor_animais_servico_prestado ta WHERE id_tutor_animais=$id_tutor_animais");
       
 $query->proximo();
 if($query->record[0] != ''){
  echo callException("Avaliação já realizada.", 2);
  exit;
 }
 

if(isset($edit)){
    include "../class/class.valida.php";
    $erro='';

    $valida_descricao=new Valida($form_dt_avaliacao,'Data');
    $valida_descricao->TamMinimo(10);
    $erro.=$valida_descricao->PegaErros();

       
        
  
    
}

if (!$erro && isset($edit)) {
   $query->begin();
   $query->exec("SELECT ta.id_tutor,ta.nome FROM canil.tutor_animais ta WHERE id_tutor_animais=$id_tutor_animais");
   $query->proximo();
   $nome_animal = $query->record[1];
   $query->insertTupla('canil.tutor_movimentacoes', array($id_tutor,
                                                           "Realizada pesquisa de satisfação para o animal $nome_animal",
                                                           "I",
                                                           $_login,
                                                           $_ip,
                                                           $_data,
                                                           $_hora));
   $query->insertTupla('canil.tutor_animais_servico_prestado', array($id_tutor_animais,
                                                                     $form_dt_avaliacao,
                                                                     $form_nro_confere,
                                                                     $form_satisfeito,
                                                                     $form_transtorno,
                                                                     $form_material,
                                                                     $form_pagamento,
                                                                     $form_observacao,
                                                                     $_login,
                                                                     $_ip,
                                                                     $_data,
                                                                     $_hora));

   $query->commit();

if(!$erro)
{
    ?>
      <script>window.location = 'CADASTROANIMAL_form.php?id_tutor=<?=$id_tutor;?>';</script>
    <?
  exit;
}

}



if($erro) echo callException($erro, 2);


$query->exec("SELECT ta.id_tutor_animais,
                     t.nome,
                     ta.nome,
                     ta.especie,
                     ta.sexo,
                     ta.idade,
                     ta.porte,
                     ta.pelagem,
                     t.telefones,
                     ta.ativo,
                     ta.observacao_situacao,
                     ta.nro_chip,
                     TO_CHAR(ta.dt_castracao,'DD/MM/YYYY') as dt_castracao
              FROM   canil.tutor_animais ta INNER JOIN canil.tutor t USING(id_tutor)
              WHERE id_tutor_animais=" . $id_tutor_animais);
$query->proximo();
$em_edicao=1;
?>
<section class="content">
  <div class="card p-0">

    <table align="center" width="95%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          <form method="post" action="<? echo $PHP_SELF; ?>">
          <input type="hidden" name="id_tutor_animais" value="<? echo $id_tutor_animais; ?>"/>
          <input type="hidden" name="id_tutor" value="<? echo $id_tutor; ?>"/>

            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF" height="160" class="borda_form">
              <tr>
                <td>
                  <p align="center" class="dest"><b>Avaliação do Serviço Prestado</b></p>
                  <table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
                  <tr >
                      <td width="120" align="right" class="texton">Tutor: </td>
                      <td >
                          <b><?echo $query->record[1]; ?></b> Telefones: <b><?echo $query->record[8]; ?></b>
                      </td>
                    </tr>
                    <tr >
                      <td align="right" class="texton">Animal: </td>
                      <td >
                          <b><?echo $query->record[2]; ?></b>
                      </td>
                    </tr>   
                    <tr >
                      <td  align="right" class="texton">Espécie: </td>
                      <td >
                          <b><?echo $query->record[3]; ?></b>
                      </td>
                    </tr>
                    <tr >
                      <td align="right" class="texton">Sexo: </td>
                      <td >
                          <b><?echo $query->record[4]; ?></b>
                      </td>
                    </tr> 
                    <tr >
                      <td  align="right" class="texton">Idade: </td>
                      <td >
                          <b><?echo $query->record[5]; ?></b>
                      </td>
                    </tr>
                    <tr >
                      <td align="right" class="texton">Porte: </td>
                      <td >
                          <b><?echo $query->record[6]; ?></b>
                      </td>
                    </tr>  
                    <tr >
                      <td  align="right" class="texton">Pelagem: </td>
                      <td >
                          <b><?echo $query->record[7]; ?></b>
                      </td>
                    </tr>
                    <tr>
                      <td  align="right" class="texton">Nro Chip: </td>
                      <td >
                          <b><?echo $query->record["nro_chip"]; ?></b>
                      </td>
                    </tr>
                    <tr >
                      <td  align="right" class="texton">Data da Castração: </td>
                      <td >
                          <b><?echo $query->record["dt_castracao"]; ?></b>
                      </td>
                    </tr>

                    <tr>
                      <td  align="right" class="texton">&nbsp;</td>
                    </tr>
                  
                    <? if ($query->record["dt_castracao"]<>"") { ?>
                      <tr>
                      <td colspan="2">
                        <table>
                          <tr">
                          <td  align="right" class="texton">Data da Avaliação: </td>
                          <td >
                            <input name="form_dt_avaliacao" type="date" class="borda_form" max="" value="<? if($edit) echo $form_dt_castracao; else echo $_data ?>" required />
                          </td>  
                        </tr>
                        <tr>
                          <td  align="right" class="texton">Nro do Chip Confere: (<b><?echo $query->record["nro_chip"]; ?></b>)</td>
                          <td >
                            <input required name="form_nro_confere" type="radio" class="borda_form" value="S" <? if ($form_nro_confere=="S") echo "checked"?> required/>Sim                 
                            <input required name="form_nro_confere" type="radio" class="borda_form" value="N" <? if ($form_nro_confere=="N") echo "checked"?> required/>Não                
                          </td>
                        </tr>
                        <tr>
                          <td  align="right" class="texton">Está satisfeito com a realização do serviço da SOS Animais ? </td>
                          <td >
                            <input required name="form_satisfeito" type="radio" class="borda_form" value="S" <? if ($form_satisfeito=="S") echo "checked"?> required/>Sim                 
                            <input required name="form_satisfeito" type="radio" class="borda_form" value="N" <? if ($form_satisfeito=="N") echo "checked"?> required/>Não                
                          </td>
                        </tr>
                        <tr >
                          <td  align="right" class="texton">Houve algum transtorno na recuperação da castração ? </td>
                          <td >
                            <input required name="form_transtorno" type="radio" class="borda_form" value="S" <? if ($form_transtorno=="S") echo "checked"?> required/>Sim                 
                            <input required name="form_transtorno" type="radio" class="borda_form" value="N" <? if ($form_transtorno=="N") echo "checked"?> required/>Não                
                          </td>
                        </tr>
                        <tr>
                          <td  align="right" class="texton">Recebeu material informativo quanto aos cuidados do pśs-operatório ?</td>
                          <td >
                            <input required name="form_material" type="radio" class="borda_form" value="S" <? if ($form_material=="S") echo "checked"?> required/>Sim                 
                            <input required name="form_material" type="radio" class="borda_form" value="N" <? if ($form_material=="N") echo "checked"?> required/>Não                
                          </td>
                        </tr>
                        <tr >
                          <td  align="right" class="texton">Realizou algum pagamento ? ? </td>
                          <td >
                            <input required name="form_pagamento" type="radio" class="borda_form" value="S" <? if ($form_pagamento=="S") echo "checked"?> required/>Sim                 
                            <input required name="form_pagamento" type="radio" class="borda_form" value="N" <? if ($form_pagamento=="N") echo "checked"?> required/>Não                
                          </td>
                        </tr>
                        <tr>
                          <td  align="right" class="texton">Observações: </td>
                          <td >
                            <textarea  name="form_observacao" class="borda_form" cols="100" rows="4"><? if($edit) echo $form_observacao; ?></textarea>                  
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2" align="center">
                            <br>
                              <input type="submit" name="edit"    value="Salvar Avaliação" class="borda_form"/>
                              <br><br>
                            </td>
                        </tr>
                        </table>
                    </td>
                    </tr>
      
                    <?}
                    else { ?>
                    <tr>
                        <td colspan="2" align="center" class="texton">Castração Não Realizada, não é possível realizar a avaliação </td>
                    </tr>
                    <?}?>
                  </table>
                </td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
    </table>

  </div>
</section>
<? include('../includes/dashboard/footer.php'); ?>
