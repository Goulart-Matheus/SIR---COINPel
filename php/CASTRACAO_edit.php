<?

include('../includes/session.php');
include('../includes/dashboard/header.php');
include('../includes/variaveisAmbiente.php');

include('../class/class.tab.php');
$tab = new Tab();

$tab->setTab('Listagem', 'fas fa-file-code', 'CASTRACAOLISTAGEM_viewDados.php');
$tab->setTab('Castração', 'fas fa-file-code',  $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

if (!is_numeric($id_tutor_animais)) exit;

if (isset($edit)) {
  include "../class/class.valida.php";
  $erro = '';

  $valida_descricao = new Valida($form_dt_castracao, 'Data da Castração');
  $valida_descricao->TamMinimo(10);
  $erro .= $valida_descricao->PegaErros();

  if ($form_dt_castracao > $_data) $erro .= "Data maior que o dia de hoje <br>";

  if ($form_realizada == "S") {
    $valida_descricao = new Valida($form_chip, 'Chip');
    $valida_descricao->TamMinimo(15);
    $erro .= $valida_descricao->PegaErros();
  } else {
    $valida_descricao = new Valida($form_observacao, 'Observação');
    $valida_descricao->TamMinimo(10);
    $erro .= $valida_descricao->PegaErros();
  }
}

if (!$erro && isset($edit)) {
  $query->begin();
  $query->exec("SELECT ta.id_tutor,ta.nome FROM canil.tutor_animais ta WHERE id_tutor_animais=$id_tutor_animais");
  $query->proximo();
  $id_tutor    = $query->record[0];
  $nome_animal = $query->record[1];
  if ($form_realizada == "S") {
    $query->insertTupla('canil.tutor_movimentacoes', array(
      $id_tutor,
      "Realizada a castração do animal $nome_animal'. Observação: $form_observacao",
      "I",
      $_login,
      $_ip,
      $_data,
      $_hora
    ));
    $query->exec("UPDATE canil.tutor_animais 
                    SET nro_chip='$form_chip',
                        dt_castracao='$form_dt_castracao',
                        observacao='$form_observacao',
                        login_castracao='$_login' 
                    WHERE id_tutor_animais=$id_tutor_animais");
  } else {
    $query->exec("UPDATE canil.tutor_animais 
                      SET nro_chip=NULL,dt_castracao='$form_dt_castracao',observacao='$form_observacao',login_castracao='$_login' 
                      WHERE id_tutor_animais=$id_tutor_animais");
    $query->insertTupla('canil.tutor_movimentacoes', array(
      $id_tutor,
      "Não Realizada a castração do animal $nome_animal'. Observação: $form_observacao",
      "I",
      $_login,
      $_ip,
      $_data,
      $_hora
    ));
  }
  $query->commit();
  exit;
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
                     ta.dt_castracao
              FROM   canil.tutor_animais ta INNER JOIN canil.tutor t USING(id_tutor)
              WHERE id_tutor_animais = " . $id_tutor_animais);
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

        <div class="form-group col-12 col-md-12">

          <label for="form_chip" class="col-12 px-0"> Chip:
            <input name="form_chip" type="text" class="borda_form" value="<? if ($edit) echo $form_descricao; ?>" size="15" maxlength="15" required />
          </label>

        </div>

        <div class="form-group col-12 col-md-12">

          <label for="form_dt_castracao" class="col-12 px-0"> Nova Data:
            <input name="form_dt_castracao" type="date" class="borda_form" max="<?=$_data?>" value="<? if($edit) echo $form_dt_castracao; ?>" />
          </label>

        </div>

        <div class="form-group col-12 col-md-12">

          <label for="form_observacao" class="col-12 px-0"> Observação:
            <textarea name="form_observacao" class="borda_form" cols="100" rows="4"><? if ($edit) echo $form_observacao; ?></textarea>
          </label>

        </div>

        <div class="form-group col-12 col-md-6">

          <label for="form_realizada" class="col-12 px-0"><span class="text-danger">*</span> Castração Realizada:
          </label>

          <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">
            <label class="btn btn-light">
              <input class="form_realizada" type="radio" name="form_realizada" id="form_realizada" value="S" <? if ($form_realizada == "S") echo "checked" ?> required> Sim
            </label> &nbsp;

            <label class="btn btn-light">
              <input class="form_realizada" type="radio" name="form_realizada" id="form_realizada" value="N" <? if ($form_realizada == "N") echo "checked"?> required> Não
            </label> &nbsp;


          </div>
        </div>

      </div>

      <div class="card-footer border-top-0 bg-transparent">

        <div class="card-footer bg-light-2">
          <?
          $btns = array('clean', 'edit');
          include('../includes/dashboard/footer_forms.php');
          ?>
        </div>

      </div>

    </form>
  </div>
</section>

<? include('../includes/dashboard/footer.php'); ?>