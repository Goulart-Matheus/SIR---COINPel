<?php


include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include('../includes/dashboard/header.php');
include('../function/function.string.php');
include('../function/function.date.php');


$queryaux = new Query($bd);

include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Listagem', 'fa-solid fa-search', 'TUTORVERIFICA_viewDados.php');
$tab->setTab('Animal', 'fa-solid fa-plus', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

function retorna_dt_agendamento($query, $especie = "", $sexo = "")
{
  $TotalCastracoesDia = 10;

  if ($especie == "Canina" and $sexo == "F") {
    $where = " AND 
            (SELECT COUNT(*) FROM canil.tutor_animais b
             WHERE b.dt_agendamento=a.dt_agendamento AND especie='Canina' AND sexo='F')<5";
  }
  // Procura se tem data livre
  $query->exec("SELECT dt_agendamento 
                from canil.tutor_animais
                where dt_agendamento>current_date  $where
                group by dt_agendamento
                having count(*)<$TotalCastracoesDia
                order by dt_agendamento
                limit 1");
  //Se não tem pega a maior
  if ($query->rows() == 0) {
    $query->exec("SELECT MAX(dt_agendamento) FROM canil.tutor_animais");
  }
  $query->proximo();
  $data = $query->record[0];

  if ($data <> "") {
    $query->exec("SELECT COUNT(*) FROM canil.tutor_animais WHERE dt_agendamento='$data' $where");
    $query->proximo();
    $castracoes = $query->record[0];
  } else {
    $castracoes = 0;
    $data = strftime("%Y-%m-%d");
  }
  if ($castracoes >= $TotalCastracoesDia) {
    while (true) {
      $data = date("Y-m-d", mktime(0, 0, 0, substr($data, 5, 2), substr($data, 8, 2) + 1, substr($data, 0, 4)));
      $dia_da_semana = diasemana($data);
      if (($dia_da_semana == 1 or $dia_da_semana == 3 or $dia_da_semana == 5) and $query->returnColuna('feriado', 'data', $data, 'data') == "") break;

      //if ($data>'2023-10-01') break;
    }
  }
  return ($data);
}

if (isset($removeragenda) and is_numeric($id_tutor_animais)) {
  $query->exec("UPDATE canil.tutor_animais SET dt_agendamento=NULL WHERE id_tutor_animais=$id_tutor_animais");
  $query->exec("SELECT ta.nome FROM canil.tutor_animais ta WHERE ta.id_tutor_animais=$id_tutor_animais");
  $query->proximo();
  $nome_animal = $query->record[0];

  $query->insertTupla('canil.tutor_movimentacoes', array(
    $id_tutor,
    "Removido agedamento do animal $nome_animal.",
    "I",
    $_login,
    $_ip,
    $_data,
    $_hora
  ));
}
if (isset($reagendar) and is_numeric($id_tutor_animais)) {
  $query->exec("SELECT ta.nome,ta.especie,ta.sexo FROM canil.tutor_animais ta WHERE ta.id_tutor_animais=$id_tutor_animais");
  $query->proximo();
  $nome_animal = $query->record[0];
  $especie     = $query->record["especie"];
  $sexo        = $query->record["sexo"];

  $dt_agendamento = retorna_dt_agendamento($query, $especie, $sexo);
  $query->exec("UPDATE canil.tutor_animais SET dt_agendamento='$dt_agendamento' WHERE id_tutor_animais=$id_tutor_animais");

  $query->insertTupla('canil.tutor_movimentacoes', array(
    $id_tutor,
    "Reagendamento do animal $nome_animal. Nova data: $dt_agendamento",
    "I",
    $_login,
    $_ip,
    $_data,
    $_hora
  ));
}

if (isset($excluir) and $id_tutor_animais_servico_prestado <> "") {
  $query->begin();
  $query->exec("SELECT ta.nome , TO_CHAR(a.dt_avaliacao,'DD/MM/YYYY')
                 FROM canil.tutor_animais ta INNER JOIN canil.tutor_animais_servico_prestado a USING(id_tutor_animais)
                 WHERE id_tutor_animais_servico_prestado=$id_tutor_animais_servico_prestado");
  $query->proximo();
  $nome_animal = $query->record[0];
  $data = $query->record[1];
  $query->insertTupla('canil.tutor_movimentacoes', array(
    $id_tutor,
    "Excluída pesquisa de satisfação realizada em $data para o animal $nome_animal",
    "I",
    $_login,
    $_ip,
    $_data,
    $_hora
  ));
  $query->exec("DELETE FROM canil.tutor_animais_servico_prestado WHERE id_tutor_animais_servico_prestado=$id_tutor_animais_servico_prestado");
  $query->commit();
}

if (isset($edit)) {
  include "../class/class.valida.php";
  $erro = '';

  $valida_nome = new Valida(trim($form_nome), 'Nome');
  $valida_nome->TamMinimo(5);
  $erro .= $valida_nome->PegaErros();

  $valida_nome = new Valida(trim($form_cpf), 'CPF');
  $valida_nome->TamMinimo(11);
  $erro .= $valida_nome->PegaErros();

  $valida_nome = new Valida(trim($form_telefone), 'Telefone');
  $valida_nome->TamMinimo(1);
  $erro .= $valida_nome->PegaErros();

  $valida_nome = new Valida(trim($form_endereco), 'Endereão');
  $valida_nome->TamMinimo(1);
  $erro .= $valida_nome->PegaErros();

  $valida_solicitacao = new Valida($form_id_microregiao, 'Micro Região');
  $valida_solicitacao->TamMinimo(1);
  $erro .= $valida_solicitacao->PegaErros();
}


if (isset($recuperar) and $id_tutor_animais <> "") {
  $query->begin();
  $query->exec("SELECT ta.id_tutor,ta.nome FROM canil.tutor_animais ta WHERE id_tutor_animais=$id_tutor_animais");
  $query->proximo();
  $nome_animal = $query->record[1];
  $query->insertTupla('canil.tutor_movimentacoes', array(
    $id_tutor,
    "Animal  $nome_animal recolocado na listagem de castração",
    "I",
    $_login,
    $_ip,
    $_data,
    $_hora
  ));
  $query->exec("UPDATE canil.tutor_animais 
                  SET nro_chip=NULL,
                      dt_castracao=NULL,
                      observacao=NULL,
                      login_castracao=NULL 
                  WHERE id_tutor_animais=$id_tutor_animais");
  $query->commit();
}

if (!$erro && isset($edit)) {
  if (trim($_FILES["form_arquivo"]["name"]) <> '') {
    unlink("../attach/" . $anexo);
    $diretorio = "../attach";
    include('../includes/uploadarquivo.php');
  } else $arquivo_nome = $anexo;
}
if (!$erro && isset($edit)) {
  $query->begin();
  $query->insertTupla('canil.tutor_movimentacoes', array(
    $id_tutor,
    "Alterado dados pessoais de $form_nome",
    "I",
    $_login,
    $_ip,
    $_data,
    $_hora
  ));
  $itens = array(
    $form_nome,
    retira_acentos(trim($form_nome)),
    $form_cpf,
    $form_telefone,
    $form_endereco,
    $form_id_microregiao,
    $form_ponto_referencia,
    $_login,
    $_ip,
    $_data,
    $_hora,
    $form_tipo,
    $arquivo_nome,
    $form_adotante,
    $form_posoperatorio
  );
  $where = array(0 => array('id_tutor', $id_tutor));
  $query->updateTupla('canil.tutor', $itens, $where);
  echo $query->sql;
  $query->commit();
}

if (!$erro && isset($add)) {
  $query->begin();

  $query->exec("SELECT tipo FROM canil.tutor WHERE id_tutor=" . $id_tutor);
  $query->proximo();
  if ($query->record[0] == "T") $dt_agendamento = retorna_dt_agendamento($query, $form_especie, $form_sexo);
  else $dt_agendamento = "NULL";

  $query->insertTupla('canil.tutor_movimentacoes', array(
    $id_tutor,
    "Cadastrado animal $form_nome_animal",
    "I",
    $_login,
    $_ip,
    $_data,
    $_hora
  ));


  if ($dt_agendamento <> "NULL") {
    $query->insertTupla('canil.tutor_movimentacoes', array(
      $id_tutor,
      "Agendamento do animal $form_nome_animal para o dia $dt_agendamento",
      "I",
      $_login,
      $_ip,
      $_data,
      $_hora
    ));
  }


  $query->insertTupla('canil.tutor_animais', array(
    $id_tutor,
    $form_nome_animal,
    $form_especie,
    $form_sexo,
    $form_idade,
    $form_porte,
    $form_pelagem,
    $_login,
    $_ip,
    $_data,
    $_hora,
    'NULL',
    'NULL',
    'NULL',
    'NULL',
    $form_rua,
    'S',
    'NUll',
    $dt_agendamento,
    $form_castra_movel,
    $form_id_abrigo
  ));

  $add = "";

}
$query->commit();

if ($erro) echo callException($erro, 2);

$query->exec("SELECT * FROM canil.tutor WHERE id_tutor = " . $id_tutor);
$query->proximo();
$Tipo = $query->record["tipo"];
?>
<section class="content">
  <div class="card p-0">

    <div class="card-header border-bottom-2 mb-1 bg-light-2">
      <div class="col-12 col-md-12 text-center">

        <h5>
          <b class="text-green text-center">Dados do Tutor</b>
        </h5>

      </div>
    </div>

    <div class="card-body">
      
      <form name="form" method="post" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data">

        <div class="form-row">
          
          <div class="form-group col- col-md-6">

            <label for="form_nome" class="col-12 px-0"><span class="text-danger">*</span> Nome:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons" required>

              <input type="text" class="form-control" name="form_nome" id="form_nome" value="<? if ($edit) echo $form_nome;
                                                                                              else echo $query->record["nome"]; ?>">

            </div>
          </div>

          <div class="form-group col- col-md-6">

            <label for="form_cpf" class="col-12 px-0"><span class="text-danger">*</span> CPF:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons" required>

              <input type="text" class="form-control" name="form_cpf" id="form_cpf" value="<? if ($erro) echo $form_cpf;
                                                                                            else echo $query->record["cpf"]; ?>">

            </div>
          </div>

          <div class="form-group col-12 col-md-6">
              <?if (!$edit) $form_tipo=$query->record["tipo"]?>
            <label for="form_tipo" class="col-12 px-0"><span class="text-danger">*</span> Tipo:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

              <label class="btn btn-light">
                <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="T" <? if ($form_tipo == "T" or ($form_tipo == "")) echo "checked"; ?> required> Tutor
              </label> &nbsp;

              <label class="btn btn-light">
                <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="P" <? if ($form_tipo == "P") echo "checked"; ?> required> Protetor
              </label> &nbsp;

              <label class="btn btn-light">
                <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="C" <? if ($form_tipo == "C") echo "checked"; ?> required> Canil
              </label> &nbsp;

              <label class="btn btn-light">
                <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="F" <? if ($form_tipo == "F") echo "checked"; ?> required> Fiscalização
              </label> &nbsp;

              <label class="btn btn-light">
                <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="A" <? if ($form_tipo == "A") echo "checked"; ?> required> Abrigo
              </label> &nbsp;

            </div>
          </div>

          <div class="form-group col-12 col-md-6">
            <?if (!$edit) $form_adotante=$query->record["adotante"]?>
            <label for="form_adotante" class="col-12 px-0"><span class="text-danger">*</span> Adotante:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

              <label class="btn btn-light">
                <input class="form_adotante" type="radio" name="form_adotante" id="form_adotante" value="S" <? if ($form_adotante == 'S') echo 'checked'; ?> required> Sim
              </label> &nbsp;

              <label class="btn btn-light">
                <input class="form_adotante" type="radio" name="form_adotante" id="form_adotante" value="N" <? if ($form_adotante == 'N') echo 'checked'; ?> required> Não
              </label> &nbsp;

            </div>
          </div>

          <div class="form-group col-12 col-md-6">
            <?if (!$edit) $form_posoperatorio=$query->record["posoperatorio"]?>
            <label for="form_posoperatorio" class="col-12 px-0"><span class="text-danger">*</span> Pós Operatório:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

              <label class="btn btn-light">
                <input class="form_posoperatorio" type="radio" name="form_posoperatorio" id="form_posoperatorio" value="S" <? if ($form_posoperatorio == 'S') echo 'checked'; ?> required> Sim
              </label> &nbsp;

              <label class="btn btn-light">
                <input class="form_posoperatorio" type="radio" name="form_posoperatorio" id="form_posoperatorio" value="N" <? if ($form_posoperatorio == 'N') echo 'checked'; ?> required> Não
              </label> &nbsp;

            </div>
          </div>

          <div class="form-group col-12 col-md-6">

            <label for="for_arqivo" class="col-12 px-0"> Documento:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

              <input type="file" name="form_arquivo" class="borda_form" size="60" maxlength="25" value="">

            </div>
          </div>

          <div class="form-group col- col-md-6">

            <label for="form_telefone" class="col-12 px-0"><span class="text-danger">*</span> Telefones:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons" required>

              <input type="text" class="form-control" name="form_telefone" id="form_telefone" value="<? if ($edit) echo $form_telefone;
                                                                                                      else echo $query->record["telefones"] ?>">

            </div>
          </div>

          <div class="form-group col- col-md-6">

            <label for="form_endereco" class="col-12 px-0"><span class="text-danger">*</span> Endereço:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons" required>

              <input type="text" class="form-control" name="form_endereco" id="form_endereco" value="<? if ($edit) echo $form_endereco;
                                                                                                      else echo $query->record["endereco"]; ?>">

            </div>
          </div>


          <div class="form-group col-12 col-md-6">

            <label for="form_macroregiao" class="col-12 px-0"> Macroregião:
            </label>
            <div class="col-md-8 mx-1">
              <select name="form_macroregiao" class="form-control col-12">
                <?php include('../includes/inc_select_macroregiao.php'); ?>
              </select>
            </div>
          </div>

          <div class="form-group col-12 col-md-6">

            <label for="form_microregiao" class="col-12 px-0"><span class="text-danger">*</span> Microregião:
            </label>
            <div class="col-md-8 mx-1">
              <select name="form_microregiao" class="form-control col-12" required>
                <?php
                if ($edit) $variavel_do_banco = $form_id_microregiao;
                else  $variavel_do_banco = $query->record["id_microregiao"];
                include('../includes/inc_select_microregiao.php'); ?>
              </select>
            </div>
          </div>

          <div class="form-group col- col-md-6">

            <label for="form_ponto_referencia" class="col-12 px-0"> Ponto de Referência:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

              <input type="text" class="form-control" name="form_ponto_referencia" id="form_ponto_referencia" value="<? if ($edit) echo $form_ponto_referencia;
                                                                                                                      else echo $query->record["ponto_referencia"];  ?>">

            </div>
          </div>

        </div>

      </form>

    </div>
    
  </div>
</section>

<section class="content">
<form name="form" method="post" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data">
  <div class="card p-0">

    <div class="card-header border-bottom-2 mb-1 bg-light-2">
      <div class="col-12 col-md-12 text-center">

        <h5>
          <b class="text-green text-center">Cadastro do Animal</b>
        </h5>

      </div>
    </div>

    <div class="card-body">

        <div class="form-row">

          <div class="form-group col- col-md-6">

            <label for="form_nome_animal" class="col-12 px-0"><span class="text-danger">*</span> Nome:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

              <input type="text" class="form-control" name="form_nome_animal" id="form_nome_animal" value="<? if ($add) echo $form_nome_animal; ?>" required>

            </div>
          </div>

          <div class="form-group col- col-md-6">

            <label for="form_idade" class="col-12 px-0"><span class="text-danger">*</span> Idade:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

              <input type="text" class="form-control" name="form_idade" id="form_idade" value="<? if ($add) echo $form_idade; ?>" required>

            </div>
          </div>

          <div class="form-group col-12 col-md-6">

            <label for="form_porte" class="col-12 px-0"><span class="text-danger">*</span> Porte:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

              <label class="btn btn-light">
                <input class="form_porte" type="radio" name="form_porte" id="form_porte" value="P" <? if ($add) echo $form_porte ?> required> Pequeno
              </label> &nbsp;

              <label class="btn btn-light">
                <input class="form_porte" type="radio" name="form_porte" id="form_porte" value="M" <? if ($add) echo $form_porte ?> required> Médio
              </label> &nbsp;

              <label class="btn btn-light">
                <input class="form_porte" type="radio" name="form_porte" id="form_porte" value="G" <? if ($add) echo $form_porte ?> required> Grande
              </label> &nbsp;

            </div>
          </div>

          <div class="form-group col-12 col-md-6">

            <label for="form_especie" class="col-12 px-0"><span class="text-danger">*</span> Espécie:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

              <label class="btn btn-light">
                <input class="form_especie" type="radio" name="form_especie" id="form_especie" value="Canina" <? if ($add) echo $form_especie ?> required> Canina
              </label> &nbsp;

              <label class="btn btn-light">
                <input class="form_especie" type="radio" name="form_especie" id="form_especie" value="Felina" <? if ($add) echo $form_especie ?> required> Felina
              </label> &nbsp;

            </div>
          </div>

          <div class="form-group col-12 col-md-6">

            <label for="form_sexo" class="col-12 px-0"><span class="text-danger">*</span> Sexo:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

              <label class="btn btn-light">
                <input class="form_sexo" type="radio" name="form_sexo" id="form_sexo" value="M" <? if ($add) echo $form_sexo; ?> required> Macho
              </label> &nbsp;

              <label class="btn btn-light">
                <input class="form_sexo" type="radio" name="form_sexo" id="form_sexo" value="F" <? if ($add) echo $form_sexo ?> required> Fêmea
              </label> &nbsp;

            </div>
          </div>

          <div class="form-group col-12 col-md-6">

            <label for="form_rua" class="col-12 px-0"><span class="text-danger">*</span> De Rua:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

              <label class="btn btn-light">
                <input class="form_rua" type="radio" name="form_rua" id="form_rua" value="S" <? if ($add) echo $form_rua ?> required> Sim
              </label> &nbsp;

              <label class="btn btn-light">
                <input class="form_rua" type="radio" name="form_rua" id="form_rua" value="N" <? if ($add) echo $form_rua ?> required> Não
              </label> &nbsp;

            </div>
          </div>

          <div class="form-group col-12 col-md-6">

            <label for="form_castra_movel" class="col-12 px-0"><span class="text-danger">*</span> Castra Móvel:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

              <label class="btn btn-light">
                <input class="form_castra_movel" type="radio" name="form_castra_movel" id="form_castra_movel" value="S" <? if ($add) echo $form_nome ?> required> Sim
              </label> &nbsp;

              <label class="btn btn-light">
                <input class="form_castra_movel" type="radio" name="form_castra_movel" id="form_castra_movel" value="N" <? if ($add) echo $form_nome ?> required> Não
              </label> &nbsp;

            </div>
          </div>

          <div class="form-group col-12 col-md-6">

            <label for="form_id_abrigo" class="col-12 px-0"> Abrigo:
            </label>
            <div class="col-md-8 mx-1">
              <select name="form_id_abrigo" class="form-control col-12">
                <?php
                $where_abrigo = " AND id_tutor <> $id_tutor";
                include('../includes/inc_select_abrigo.php'); ?>
              </select>
            </div>
          </div>

          <div class="form-group col-12 col-md-12">

            <label for="form_pelagem" class="col-12 px-0"> Descrição:
            </label>
            <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

              <textarea name="form_pelagem" class="form_group" cols="138" rows="3"><? if ($add) echo $form_pelagem;
                                                                                  else { ?>
Raça: 
Bravio (S/N): 
Pelagem: 
<? } ?>
            </textarea>
            </div>
          </div>

        </div>

      
    </div>

      <div class="card-footer bg-light-2">
          <?
          $btns = array('clean', 'save');
          include('../includes/dashboard/footer_forms.php');
          ?>
      </div>

  </div>
  </form>
</section>

<?php
$queryaux->exec("SELECT count(*) FROM   canil.tutor_animais  WHERE ativo='S' and id_tutor='$id_tutor'");
$queryaux->proximo();
$NroAnimais = $queryaux->record[0];
?>

<?php
$queryaux = new Query($bd);
$queryaux->exec("SELECT ta.id_tutor_animais,
                                        ta.nome,
                                        ta.especie,
                                        ta.sexo,
                                        ta.idade,
                                        ta.porte,
                                        ta.pelagem,
                                        ta.login,
                                        TO_CHAR(ta.dt_alteracao,'DD/MM/YYYY') ,
                                        ta.hr_alteracao,
                                        ta.nro_chip,
                                        ta.login_castracao,
                                        TO_CHAR(ta.dt_castracao,'DD/MM/YYYY') as dt_castracao,
                                        ta.observacao,
                                        ta.rua,
                                        ta.ativo,
                                        TO_CHAR(ta.dt_agendamento,'DD/MM/YYYY') as dt_agendamento,
                                        ta.castra_movel,
                                        t.nome AS abrigo
                                  FROM  canil.tutor_animais ta LEFT JOIN canil.tutor t ON ta.id_abrigo=t.id_tutor
                                  WHERE ta.id_tutor='$id_tutor' 
                                  ORDER BY ta.nro_chip DESC,ta.nome");

$n = $queryaux->rows();
if ($n > 0) {
?>
<section class="content">
  <div class="card p-0">

        <div class="card-header border-bottom-2 mb-1 bg-light-2">
          <div class="col-12 col-md-12 text-center">

            <h5>
              <b class="text-green text-center">Atividades</b>
            </h5>

          </div>
        </div>

        <style>
          /* Estilo para as células e linhas */
          table.atv td,
          table.atv th {
          border-bottom: 1px solid #E6E6FA;
          /* Apenas a borda inferior */
          padding: 5px;
          /* Espaçamento interno */
            }
        </style>

        <tr>
          <td>
            <br>
            <table class="my-5 atv" width="95%" border="0" align="center" cellspacing="1" cellpadding="3">
              <tr align="center" class="bg-light text-green">
                <td class="text-green"><strong>Nome</strong></td>
                <td class="text-green"><strong>Espécie</strong></td>
                <td class="text-green"><strong>Sexo</strong></td>
                <td class="text-green"><strong>Idade</strong></td>
                <td class="text-green"><strong>Porte</strong></td>
                <td class="text-green"><strong>Descrição</strong></td>
                <td class="text-green"><strong>Rua</strong></td>
                <td class="text-green"><strong>Castra Móvel</strong></td>
                <td class="text-green"><strong>Cadastro</strong></td>
                <td class="text-green"><strong>Chip</strong></td>
                <td class="text-green"><strong>Responsável</strong></td>
                <td class="text-green"><strong>Data</strong></td>
                <td class="text-green"><strong>Obs</strong></td>
                <td class="text-green"><strong>Agend.</strong></td>
                <td class="text-green"></td>
              </tr>
              <? while ($n--) {
                $queryaux->proximo();
                $js_onclick = "OnClick=javascript:window.location=('CADASTROANIMAL_edit.php?id_tutor=$id_tutor&id_tutor_animais=" . $queryaux->record[0] . "')";

                if ($queryaux->record["ativo"] == "E") {
                  $marcaA = "<s>";
                  $marcaB = "</s>";
                } elseif ($queryaux->record["ativo"] == "N") {
                  $marcaA = "<i>";
                  $marcaB = "</i>";
                } else {
                  $marcaA = "<b>";
                  $marcaB = "</b>";
                }
              ?>
              <tr align="center" class="entered">
                <td <? echo $js_onclick; ?>><? echo $marcaA . $queryaux->record[1] . $marcaB; ?></td>
                <td <? echo $js_onclick; ?>><? echo $marcaA . $queryaux->record[2] . $marcaB; ?></td>
                <td <? echo $js_onclick; ?>><? echo $marcaA . $queryaux->record[3] . $marcaB; ?></td>
                <td <? echo $js_onclick; ?>><? echo $marcaA . $queryaux->record[4] . $marcaB; ?></td>
                <td <? echo $js_onclick; ?>><? echo $marcaA . $queryaux->record[5] . $marcaB; ?></td>
                <td <? echo $js_onclick; ?>><? echo $marcaA . $queryaux->record[6] . $marcaB; ?></td>
                <td <? echo $js_onclick; ?>><? echo $marcaA . $queryaux->record["rua"] . $marcaB;; ?></td>
                <td <? echo $js_onclick; ?>><? echo $marcaA . $queryaux->record["castra_movel"] . $marcaB;; ?></td>
                <td <? echo $js_onclick; ?>><? echo $marcaA . $queryaux->record[7] . ", " . $queryaux->record[8] . " - " . $queryaux->record[9] . $marcaB; ?></td>
                <td <? echo $js_onclick; ?>><? echo $marcaA . $queryaux->record[10] . $marcaB; ?></td>
                <td <? echo $js_onclick; ?>><? echo $marcaA . $queryaux->record[11] . $marcaB; ?></td>
                <td <? echo $js_onclick; ?>><? echo $marcaA . $queryaux->record[12] . $marcaB; ?></td>
                <td <? echo $js_onclick; ?>><? echo $marcaA . $queryaux->record[13] . $marcaB; ?></td>
                <td>
                  <a title="Agendar/Reagendar"
                    href="CADASTROANIMAL_form.php?reagendar=1&id_tutor=<?= $id_tutor ?>&id_tutor_animais=<?= $queryaux->record[0] ?>">
                    <img src="../img/atualizar.gif" width="15" height="15">
                  </a>
                  <a title="Agendar para determinada Data"
                    href="CADASTROANIMAL_edit.php?reagendar=1&id_tutor=<?= $id_tutor ?>&id_tutor_animais=<?= $queryaux->record[0] ?>">
                    <img src="../img/agenda.gif" width="15" height="15">
                  </a>

                  <?
                  if ($queryaux->record["dt_agendamento"] <> "") { ?>
                    <a title="Remover Agendamento"
                      href="CADASTROANIMAL_form.php?removeragenda=1&id_tutor=<?= $id_tutor ?>&id_tutor_animais=<?= $queryaux->record[0] ?>">
                      <img src="../img/erro.gif" width="15" height="15">
                    </a>
                  <? } ?>
                  <?= $queryaux->record["dt_agendamento"] ?>

                </td>
                <td>
                  <a title="Avaliação do Serviço Prestado"
                    href="CADASTROANIMALAVALIACAO_form.php?id_tutor=<?= $id_tutor ?>&id_tutor_animais=<?= $queryaux->record[0] ?>">
                    <img src="../img/survey.png" width="15" height="15">
                  </a>
                  <?
                  if ($queryaux->record["nro_chip"] == "" and $queryaux->record["dt_castracao"] <> "") { // A Castração não foi realizada
                  ?>
                    <a title="Retorna a Listagem de Castrações"
                      href="CADASTROANIMAL_form.php?recuperar=1&id_tutor=<?= $id_tutor ?>&id_tutor_animais=<?= $queryaux->record[0] ?>">
                      <img src="../img/atualizar.gif" width="15" height="15">
                    </a>
                  <? } ?>
                </td>
        </tr>
        <?
                if ($queryaux->record["abrigo"] <> "") {
        ?>
          <td class="entered" colspan="15" <? echo $js_onclick; ?>>Abrigo: <? echo $marcaA . $queryaux->record["abrigo"] . $marcaB; ?></td>
          </tr>
        <? } ?>
      <?   } ?>
      </table>
      <br>
      </td>
      </tr>

      <? } else { ?>
        
        <tr>
          <h5 class="text-center text-gray my-5" >Nenhuma Animal Cadastrado</h5>
        </tr>

  <? } ?>


    <?php
    $queryaux = new Query($bd);
    $queryaux->exec("SELECT TO_CHAR(a.dt_avaliacao,'DD/MM/YYYY'),
                                          b.nome,
                                          a.nro_confere,
                                          a.satisfeito,
                                          a.transtorno,
                                          a.material,
                                          a.pagamento,
                                          a.observacao,
                                          a.login,
                                          TO_CHAR(a.dt_alteracao,'DD/MM/YYYY'),
                                          a.hr_alteracao,
                                          a.id_tutor_animais_servico_prestado
                                    FROM   canil.tutor_animais_servico_prestado a INNER JOIN canil.tutor_animais b USING(id_tutor_animais)
                                    WHERE b.id_tutor='$id_tutor'
                                    ORDER BY a.dt_avaliacao DESC");

    $n = $queryaux->rows();
    if ($n > 0) {
    ?>

      <tr>
        <td>
          <br>
          <table class="my-5 atv" width="95%" border="0" align="center" cellspacing="1" cellpadding="3">

            <tr>
              <td colspan="11"><strong>Avaliações</strong></td>
            </tr>

            <tr>
              <td class="text-green"><strong>Data</strong></td>
              <td class="text-green"><strong>Animal</strong></td>
              <td class="text-green"><strong>Nro Confere</strong></td>
              <td class="text-green"><strong>Satisfeito</strong></td>
              <td class="text-green"><strong>Transtorno</strong></td>
              <td class="text-green"><strong>Material</strong></td>
              <td class="text-green"><strong>Pagamento</strong></td>
              <td class="text-green"><strong>Obs</strong></td>
              <td class="text-green"><strong>Usuário</strong></td>
              <td class="text-green"><strong>Data</strong></td>
              <td class="text-green"><strong>Hora</strong></td>
              <td class="text-green"><strong></strong></td>
            </tr>
            
            <? while ($n--) {
              $js_onclick = "";
              $queryaux->proximo();
            ?>
              <td <? echo $js_onclick; ?>><? echo $queryaux->record[0]; ?></td>
              <td <? echo $js_onclick; ?>><? echo $queryaux->record[1]; ?></td>
              <td <? echo $js_onclick; ?>><? echo $queryaux->record[2]; ?></td>
              <td <? echo $js_onclick; ?>><? echo $queryaux->record[3]; ?></td>
              <td <? echo $js_onclick; ?>><? echo $queryaux->record[4]; ?></td>
              <td <? echo $js_onclick; ?>><? echo $queryaux->record[5]; ?></td>
              <td <? echo $js_onclick; ?>><? echo $queryaux->record[6]; ?></td>
              <td <? echo $js_onclick; ?>><? echo $queryaux->record[7]; ?></td>
              <td <? echo $js_onclick; ?>><? echo $queryaux->record[8]; ?></td>
              <td <? echo $js_onclick; ?>><? echo $queryaux->record[9]; ?></td>
              <td <? echo $js_onclick; ?>><? echo $queryaux->record[10]; ?></td>
              <td> <a href="?id_tutor=<? echo $id_tutor; ?>&id_tutor_animais_servico_prestado=<? echo $queryaux->record[11]; ?>&excluir=1" title="Excluir Avaliação">X</a> </td>
      </tr>
    <?   } ?>
    </table>
    <br>
    </td>
    </tr>

      <? } else { ?>
        
            <tr>
              <h5 class="text-center text-gray my-5" >Nenhuma Avaliação Realizada</h5>
            </tr>

      <? } ?>
      
      <?php
      $queryaux = new Query($bd);
      $queryaux->exec("SELECT observacao,
                        login,
                              TO_CHAR(dt_alteracao,'DD/MM/YYYY'),
                              hr_alteracao
                        FROM   canil.tutor_movimentacoes
                       WHERE id_tutor='$id_tutor'
                    ORDER BY id_tutor_movimentacoes DESC");

      $n = $queryaux->rows();
      if ($n > 0) {
      ?>
        <tr>
          <td>
            <br>
            <table class="my-5 atv" width="95%" border="0" align="center" cellspacing="1" cellpadding="3">
              <tr class="bg-light">
                <td class="text-green"><strong>Observação</strong></td>
                <td class="text-green"><strong>Usuário</strong></td>
                <td class="text-green"><strong>Data</strong></td>
                <td class="text-green"><strong>Hora</strong></td>
              </tr>
              <? while ($n--) {
                $js_onclick = "";
                $queryaux->proximo();

              ?>
              <tr class="entered">
                <td <? echo $js_onclick; ?>><? echo $queryaux->record[0]; ?></td>
                <td <? echo $js_onclick; ?>><? echo $queryaux->record[1]; ?></td>
                <td <? echo $js_onclick; ?>><? echo $queryaux->record[2]; ?></td>
                <td <? echo $js_onclick; ?>><? echo $queryaux->record[3]; ?></td>
              </tr>
        </tr>
      <?   } ?>
      </table>
      <br>
      </td>
      </tr>
      <? } else { ?>
        
        <tr>
          <h5 class="text-center text-gray my-5">Nenhuma Observação Realizada</h5>
        </tr>

  <? } ?>


  </div>
</section>
  <? include('../includes/dashboard/footer.php'); ?>