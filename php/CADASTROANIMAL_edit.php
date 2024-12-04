<?php

include('../includes/session.php');
include('../includes/dashboard/header.php');
include('../includes/variaveisAmbiente.php');

include('../class/class.tab.php');
$tab = new Tab();

$tab->setTab('Listagem', 'fas fa-file-code', 'TUTORVERIFICA_viewDados.php');
$tab->setTab('Cadastro Tutor/Animal', 'fa-solid fa-plus', 'CADASTROANIMAL_form.php?id_tutor=' . $id_tutor);
$tab->setTab('Edição Animal', 'fa-solid fa-plus', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

if (!is_numeric($id_tutor_animais)) exit;

if (isset($edit)) {
  include "../class/class.valida.php";
  $erro = '';

  $valida_descricao = new Valida($form_situacao, 'Situação');
  $valida_descricao->TamMinimo(1);
  $erro .= $valida_descricao->PegaErros();

  $valida_descricao = new Valida($form_observacao, 'Observação');
  $valida_descricao->TamMinimo(10);
  $erro .= $valida_descricao->PegaErros();
}


if (!$erro && isset($edit_castra_movel)) {
  $query->begin();
  $query->exec("SELECT ta.id_tutor,ta.nome FROM canil.tutor_animais ta WHERE id_tutor_animais=$id_tutor_animais");
  $query->proximo();
  $nome_animal = $query->record[1];
  $query->insertTupla('canil.tutor_movimentacoes', array(
    $id_tutor,
    "Alterada a informação [Castra Móvel] do animal $nome_animal para '$form_castra_movel'. Motivo: $form_observacao_castra_movel",
    "I",
    $_login,
    $_ip,
    $_data,
    $_hora
  ));
  $query->exec("UPDATE canil.tutor_animais SET castra_movel='$form_castra_movel',login='$_login',ip='$_ip',dt_alteracao='$_data',hr_alteracao='$_hora' WHERE id_tutor_animais=$id_tutor_animais");
  $query->commit();
}
if (!$erro && isset($edit)) {
  $query->begin();
  $query->exec("SELECT ta.id_tutor,ta.nome FROM canil.tutor_animais ta WHERE id_tutor_animais=$id_tutor_animais");
  $query->proximo();
  $nome_animal = $query->record[1];
  $query->insertTupla('canil.tutor_movimentacoes', array(
    $id_tutor,
    "Alterada situa��o do animal $nome_animal para '$form_situacao'. Motivo: $form_observacao",
    "I",
    $_login,
    $_ip,
    $_data,
    $_hora
  ));
  $query->exec("UPDATE canil.tutor_animais SET ativo='$form_situacao',observacao_situacao='$form_observacao',login='$_login',ip='$_ip',dt_alteracao='$_data',hr_alteracao='$_hora' WHERE id_tutor_animais=$id_tutor_animais");
  $query->commit();
}

if (isset($chip)) {
  include "../class/class.valida.php";
  $erro = '';

  $valida_descricao = new Valida($form_chip, 'Chip');
  $valida_descricao->TamMinimo(15);
  $erro .= $valida_descricao->PegaErros();

  $valida_descricao = new Valida($form_observacao_chip, 'Observação');
  $valida_descricao->TamMinimo(10);
  $erro .= $valida_descricao->PegaErros();
}

if (!$erro && isset($chip)) {
  $query->begin();
  $query->exec("SELECT ta.id_tutor,ta.nome,ta.nro_chip FROM canil.tutor_animais ta WHERE id_tutor_animais=$id_tutor_animais");
  $query->proximo();
  $nome_animal = $query->record[1];
  $numero_chip = $query->record[2];
  $query->insertTupla('canil.tutor_movimentacoes', array(
    $id_tutor,
    "Alterado chip  $numero_chip do animal $nome_animal para '$form_chip'. Motivo: $form_observacao_chip",
    "I",
    $_login,
    $_ip,
    $_data,
    $_hora
  ));
  $query->exec("UPDATE canil.tutor_animais SET nro_chip='$form_chip',login='$_login',ip='$_ip',dt_alteracao='$_data',hr_alteracao='$_hora' WHERE id_tutor_animais=$id_tutor_animais");
  $query->commit();
}


if (isset($data)) {
  include "../class/class.valida.php";
  $erro = '';

  $valida_descricao = new Valida($form_dt_castracao, 'Data da Castração');
  $valida_descricao->TamMinimo(10);
  $erro .= $valida_descricao->PegaErros();

  if ($form_dt_castracao > $_data) $erro .= "Data maior que o dia de hoje <br>";

  $valida_descricao = new Valida($form_observacao_dt_castracao, 'Observação');
  $valida_descricao->TamMinimo(10);
  $erro .= $valida_descricao->PegaErros();
}

if (!$erro && isset($data)) {
  $query->begin();
  $query->exec("SELECT ta.id_tutor,ta.nome,ta.dt_castracao FROM canil.tutor_animais ta WHERE id_tutor_animais=$id_tutor_animais");
  $query->proximo();
  $nome_animal = $query->record[1];
  $dt_castracao = $query->record[2];
  $query->insertTupla('canil.tutor_movimentacoes', array(
    $id_tutor,
    "Alterada data de castração de $dt_castracao do animal $nome_animal para '$form_dt_castracao'. Motivo: $form_observacao_dt_castracao",
    "I",
    $_login,
    $_ip,
    $_data,
    $_hora
  ));
  $query->exec("UPDATE canil.tutor_animais SET dt_castracao='$form_dt_castracao',login='$_login',ip='$_ip',dt_alteracao='$_data',hr_alteracao='$_hora' WHERE id_tutor_animais=$id_tutor_animais");
  $query->commit();
}


if (isset($dt_agendamento)) {
  include "../class/class.valida.php";
  $erro = '';

  $valida_descricao = new Valida($form_dt_agendamento, 'Data do Agendamento');
  $valida_descricao->TamMinimo(10);
  $erro .= $valida_descricao->PegaErros();

  if ($form_dt_agendamento < $_data) $erro .= "Data menor que o dia de hoje <br>";

  $valida_descricao = new Valida($form_observacao_dt_agendamento, 'Observação');
  $valida_descricao->TamMinimo(10);
  $erro .= $valida_descricao->PegaErros();
}

if (!$erro && isset($dt_agendamento)) {
  $query->begin();
  $query->exec("SELECT ta.id_tutor,ta.nome,ta.dt_castracao FROM canil.tutor_animais ta WHERE id_tutor_animais=$id_tutor_animais");
  $query->proximo();
  $nome_animal = $query->record[1];
  $dt_castracao = $query->record[2];
  $query->insertTupla('canil.tutor_movimentacoes', array(
    $id_tutor,
    "Alterada data de agendamento do animal $nome_animal para '$form_dt_agendamento'. Motivo: $form_observacao_dt_agendamento",
    "I",
    $_login,
    $_ip,
    $_data,
    $_hora
  ));
  $query->exec("UPDATE canil.tutor_animais SET dt_agendamento='$form_dt_agendamento' WHERE id_tutor_animais=$id_tutor_animais");
  $query->commit();

  
}

if ($erro) echo callException($erro, 2);

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
                     ta.dt_castracao,
                     ta.castra_movel
              FROM   canil.tutor_animais ta INNER JOIN canil.tutor t USING(id_tutor)
              WHERE id_tutor_animais=" . $id_tutor_animais);
$query->proximo();
$em_edicao = 1;

?>


<section class="content">
  <div class="card p-0">

          <form method="post" action="<? echo $PHP_SELF; ?>">

            <input type="hidden" name="id_tutor_animais" value="<? echo $id_tutor_animais; ?>" />

            <div class="card-header border-bottom-2 mb-1 bg-light-2">
              <div class="col-12 col-md-12 text-center">

                <h5>
                  <b class="text-green text-center">Castração</b>
                </h5>

              </div>
            </div>

            <div class="form-row mx-4 my-4 text-left">

              <div class="form-group col-12 col-md-12">

                <label for="form_nome_animal" class="col-12 px-0"> Tutor:
                  <? echo $query->record[1]; ?>
                </label>

              </div>

              <div class="form-group col-12 col-md-12">

                <label for="form_nome_animal" class="col-12 px-0"> Telefones:
                  <? echo $query->record[8]; ?>
                </label>

              </div>

              <div class="form-group col-12 col-md-12">

                <label for="form_nome_animal" class="col-12 px-0"> Animal:
                  <? echo $query->record[2]; ?>
                </label>

              </div>

              <div class="form-group col-12 col-md-12">

                <label for="form_especie" class="col-12 px-0"> Espécie:
                  <? echo $query->record[3]; ?>
                </label>

              </div>

              <div class="form-group col-12 col-md-12">

                <label for="form_sexo" class="col-12 px-0"> Sexo:
                  <? echo $query->record[4]; ?>
                </label>

              </div>

              <div class="form-group col-12 col-md-12">

                <label for="form_idade" class="col-12 px-0"> Idade:
                  <? echo $query->record[5]; ?>
                </label>

              </div>

              <div class="form-group col-12 col-md-12">

                <label for="form_porte" class="col-12 px-0"> Porte:
                  <? echo $query->record[6]; ?>
                </label>

              </div>

              <div class="form-group col-12 col-md-12">

                <label for="form_pelagem" class="col-12 px-0"> Pelagem:
                  <? echo $query->record[7]; ?>
                </label>

              </div>

              <div class="form-group col-12 col-md-6">

                <label for="form_castra_movel" class="col-12 px-0"><span class="text-danger">*</span> Castra Móvel:
                </label>

                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">
                  <? if (!$edit_castra_movel) $form_castra_movel = $query->record["castra_movel"]; ?>
                  <label class="btn btn-light">
                    <input class="form_castra_movel" type="radio" name="form_castra_movel" id="form_castra_movel" value="S" <? if ($query->record["castra_movel"] == "S") echo "checked"; ?> required> Sim
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_castra_movel" type="radio" name="form_castra_movel" id="form_castra_movel" value="N" <? if ($query->record["castra_movel"] == "N") echo "checked" ?> required> Não
                  </label> &nbsp;


                </div>
              </div>

              <div class="form-group col-12 col-md-12">

                <label for="form_observação_castra_movel" class="col-12 px-0"> Observação:
                  <textarea name="form_observacao_castra_movel" class="borda_form" cols="100" rows="4"><? if ($edit_castra_movel) echo $form_observacao_castra_movel;
                                                                                                       else echo $query->record["observacao_castra_movel"]; ?></textarea>
                </label>

                <input type="submit" name="edit_castra_movel" value="Editar" class="btn btn-light" />

              </div>

              <div class="form-group col-12 col-md-6">

                <label for="form_situacao" class="col-12 px-0"><span class="text-danger">*</span> Situação:
                </label>

                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">
                  <? if (!$edit) $form_situacao = $query->record["ativo"]; ?>
                  <label class="btn btn-light">
                    <input required class="borda_form" type="radio" name="form_situacao" id="form_situacao" value="S" <? if ($form_situacao == "S") echo "checked" ?> />Ativo <br>
                  </label> &nbsp;

                  <label class="btn btn-light" title="Permite adicionar mais animais ao tutor">
                    <input required class="borda_form" type="radio" name="form_situacao" id="form_situacao" value="N" <? if ($form_situacao == "N") echo "checked" ?> />Inativo <br>
                  </label> &nbsp;

                  <label class="btn btn-light" title="Remove animal da lista de castrações e estatísticas">
                    <input required class="borda_form" type="radio" name="form_situacao" id="form_situacao" value="E" <? if ($form_situacao == "E") echo "checked" ?> />Excluído
                  </label> &nbsp;

                </div>
              </div>

              <div class="form-group col-12 col-md-12">

                <label for="form_observação" class="col-12 px-0"> Observação:
                  <textarea name="form_observacao" class="borda_form" cols="100" rows="4"><? if ($edit) echo $form_observacao;
                                                                                          else echo $query->record["observacao_situacao"]; ?></textarea>
                </label>

                <input type="submit" name="edit" value="Alterar Situação" class="btn btn-light" />

              </div>

              <? if ($query->record["dt_castracao"] <> "") { ?>

                <div class="form-group col-12 col-md-12">

                  <label for="form_chip" class="col-12 px-0"> Chip:
                    <input name="form_chip" type="text" class="borda_form" value="<? if ($chip) echo $form_chip;
                                                                                  else echo $query->record["nro_chip"]; ?>" size="15" maxlength="15" />
                  </label>

                </div>

                <div class="form-group col-12 col-md-12">

                  <label for="form_observacao_chip" class="col-12 px-0"> Observação:
                    <textarea name="form_observacao_chip" class="borda_form" cols="100" rows="4"><? if ($chip) echo $form_observacao_chip; ?></textarea>
                  </label>

                  <input type="submit" name="edit" value="Alterar Chip" class="btn btn-light" />

                </div>

                <div class="form-group col-12 col-md-12">

                  <label for="form_dt_castracao" class="col-12 px-0"> Data:
                    <input name="form_dt_castracao" type="text" class="borda_form" max="" value="<? if ($data) echo $form_dt_castracao;
                                                                                                else echo $query->record["dt_castracao"]; ?>" />
                  </label>

                </div>

                <div class="form-group col-12 col-md-12">

                  <label for="form_observacao_dt_castracao" class="col-12 px-0"> Observação:
                    <textarea name="form_observacao_dt_castracao" class="borda_form" cols="100" rows="4"><? if ($edit) echo $form_observacao_dt_castracao;
                                                                                                       else echo $query->record["observacao_dt_castracao"]; ?></textarea>
                  </label>

                  <input type="submit" name="edit" value="Alterar Data" class="btn btn-light" />

                </div>

              <? } else { ?>

                <div class="form-group col-12 col-md-12 text-center text-gray">
                  <label for="form_nome_animal" class="col-12 px-0">
                    <h5> Castração não realizada, não é possível realizar a troca de chip ou data. </h5>
                  </label>
                </div>

              <? } ?>

              <div class="form-group col-12 col-md-12">

                <label for="form_dt_agendamento" class="col-12 px-0"> Nova Data:
                  <input name="form_dt_agendamento" type="date" class="borda_form" max="" value="" />
                </label>

              </div>

              <div class="form-group col-12 col-md-12">

                <label for="form_observacao_dt_agendamento" class="col-12 px-0"> Observação:
                  <textarea name="form_observacao_dt_agendamento" class="borda_form" cols="100" rows="4"><? if ($dt_agendamento) echo $form_observacao_dt_agendamento;
                                                                                                       else echo $query->record["observacao_dt_agendamento"]; ?></textarea>
                </label>

                <input type="submit" name="edit" value="Alterar Data do Agendamento" class="btn btn-light" />

              </div>

            </div>

          </form>
    </div>
</section>

  <? include('../includes/dashboard/footer.php'); ?>