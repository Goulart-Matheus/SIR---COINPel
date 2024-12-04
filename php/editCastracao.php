<?
/****************************************************************************
* Software: SMSU - Secretaria Municipal de Serviços Urbanos                 *
* Version:  1.00                                                            *
* Copyright (c) 2003 COINPEl                                                *
*                                                                           *
* Author:   Eduardo Bastos                                                  *
* Date:     2003/06/16                                                      *
****************************************************************************/

include('../includes/session.php');
include('../includes/cabecalho.php');
include('../includes/variaveisAmbiente.php');

include('../class/class.tab.php');
$tab = new Tab();

$tab->setTab('Listagem',  'viewCastracaoListagem.php');
$tab->setTab('Castração',    $PHP_SELF);
$tab->printTab($PHP_SELF);

if(!is_numeric($id_tutor_animais)) exit;

if(isset($edit)){
    include "../class/class.valida.php";
    $erro='';

    $valida_descricao=new Valida($form_dt_castracao,'Data da Castração');
    $valida_descricao->TamMinimo(10);
    $erro.=$valida_descricao->PegaErros();

    if ($form_dt_castracao>$_data) $erro.="Data maior que o dia de hoje <br>";

    if ($form_realizada=="S") {
    	 $valida_descricao=new Valida($form_chip,'Chip');
    	 $valida_descricao->TamMinimo(15);
  	   $erro.=$valida_descricao->PegaErros();
    }
    else {
       $valida_descricao=new Valida($form_observacao,'Observação');
       $valida_descricao->TamMinimo(10);
       $erro.=$valida_descricao->PegaErros();
    }
}

if (!$erro && isset($edit)) {
   $query->begin();
   $query->exec("SELECT ta.id_tutor,ta.nome FROM canil.tutor_animais ta WHERE id_tutor_animais=$id_tutor_animais");
   $query->proximo();
   $id_tutor    = $query->record[0];
   $nome_animal = $query->record[1];
   if ($form_realizada=="S") {
      $query->insertTupla('canil.tutor_movimentacoes', array($id_tutor,
                                                             "Realizada a castração do animal $nome_animal'. Observação: $form_observacao",
                                                             "I",
                                                             $_login,
                                                             $_ip,
                                                             $_data,
                                                             $_hora));
      $query->exec("UPDATE canil.tutor_animais 
                    SET nro_chip='$form_chip',
                        dt_castracao='$form_dt_castracao',
                        observacao='$form_observacao',
                        login_castracao='$_login' 
                    WHERE id_tutor_animais=$id_tutor_animais");
   }
   else {
        $query->exec("UPDATE canil.tutor_animais 
                      SET nro_chip=NULL,dt_castracao='$form_dt_castracao',observacao='$form_observacao',login_castracao='$_login' 
                      WHERE id_tutor_animais=$id_tutor_animais"); 
        $query->insertTupla('canil.tutor_movimentacoes', array($id_tutor,
                                                               "NÃO Realizada a castração do animal $nome_animal'. Observação: $form_observacao",
                                                               "I",
                                                               $_login,
                                                               $_ip,
                                                               $_data,
                                                               $_hora));
   } 
   $query->commit();
   exit;
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
                     ta.dt_castracao
              FROM   canil.tutor_animais ta INNER JOIN canil.tutor t USING(id_tutor)
              WHERE id_tutor_animais=" . $id_tutor_animais);
$query->proximo();
$em_edicao=1;
if ($query->record["dt_castracao"]<>"") {
  echo callException("Castração Já Realizada", 2); 
  exit;
}
?>
<br/>
<table align="center" width="95%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <form method="post" action="<? echo $PHP_SELF; ?>">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF" height="160" class="borda_form">
          <tr>
            <td>
              <p align="center" class="dest"><b>Castração</b></p>
              <table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
               <tr bgcolor="#F0F0F0">
                  <td width="120" align="right" class="texton">Tutor: </td>
                  <td >
                      <b><?echo $query->record[1]; ?></b> Telefones: <?echo $query->record[8]; ?>
                  </td>
                </tr>
                 <tr >
                  <td align="right" class="texton">Animal: </td>
                  <td >
                      <b><?echo $query->record[2]; ?></b>
                  </td>
                </tr>   
                <tr bgcolor="#F0F0F0">
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
                 <tr bgcolor="#F0F0F0">
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
                 <tr bgcolor="#F0F0F0">
                  <td  align="right" class="texton">Pelagem: </td>
                  <td >
                      <b><?echo $query->record[7]; ?></b>
                  </td>
                </tr>
                  <tr >
                  <td  align="right" class="texton">&nbsp;</td>
                </tr>
                           
                <tr bgcolor="#F0F0F0">
                  <td  align="right" class="texton">Chip: </td>
                  <td >
                    <input name="form_chip" type="text" class="borda_form" value="<? if($edit) echo $form_descricao; ?>" size="15" maxlength="15" />                  
                  </td>
                </tr>
                
                <tr >
                  <td  align="right" class="texton">Data: </td>
                  <td >
                    <input name="form_dt_castracao" type="date" class="borda_form" max="<?=$_data?>" value="<? if($edit) echo $form_dt_castracao; ?>" required />                  
                  </td>
                </tr>
                <tr  bgcolor="#F0F0F0">
                  <td  align="right" class="texton">Observação: </td>
                  <td >
                    <textarea name="form_observacao" class="borda_form" cols="100" rows="4"><? if($edit) echo $form_observacao; ?></textarea>                  
                  </td>
                </tr>
                <tr >
                  <td  align="right" class="texton">Castração Realizada: </td>
                  <td >
                    <input required name="form_realizada" type="radio" class="borda_form" value="S" <? if ($form_realizada=="S") echo "checked"?> />Sim                 
                    <input required name="form_realizada" type="radio" class="borda_form" value="N" <? if ($form_realizada=="N") echo "checked"?> />Não                  
                  </td>
                </tr>
                <tr>
                  <td colspan="2" align="center">
                      <input type="hidden" name="id_tutor_animais" value="<? echo $id_tutor_animais; ?>"/>
                      <input type="submit" name="edit"    value="Editar" class="borda_form"/>
                      <input type="reset"  name="clear"   value="Limpar"  class="borda_form"/>
                    </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>
<? include('../includes/rodape.php'); ?>
