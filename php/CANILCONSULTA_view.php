<div class="modal fade text-left" id="CANILCONSULTA_view" tabindex="-1" role="dialog" aria-hidden="true">

  <div class="modal-dialog modal-xl" role="document">

    <div class="modal-content">

      <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">

        <div class="modal-header bg-light-2">
          <h5 class="modal-title">
            <i class="fas fa-filter text-green"></i>
            Filtrar Listagem
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="card-body pt-0 ">
          <div class="mx-2 my-2">
            <div class="form-row">

              <div class="form-group col-12 col-md-6">

                <label for="form_tipo" class="col-12 px-0"><span class="text-danger">*</span> Tipo:
                </label>
                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                  <label class="btn btn-light">
                    <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="T" <? if (isset($erro) && $form_tipo == 'T') echo 'checked' ?>> Tutor
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="P" <? if (isset($erro) && $form_tipo == 'P') echo 'checked' ?>> Protetor
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="C" <? if (isset($erro) && $form_tipo == 'C') echo 'checked' ?>> Canil
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="F" <? if (isset($erro) && $form_tipo == 'F') echo 'checked' ?>> Fiscalização
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="A" <? if (isset($erro) && $form_tipo == 'A') echo 'checked' ?>> Abrigo
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="" <? if (isset($erro) && $form_tipo == '') echo 'checked' ?>> Todos
                  </label> &nbsp;

                </div>
              </div>

              <div class="form-group col-12 col-md-6">

                <label for="form_adotante" class="col-12 px-0"><span class="text-danger">*</span> Adotante:
                </label>
                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                  <label class="btn btn-light">
                    <input class="form_adotante" type="radio" name="form_adotante" id="form_adotante" value="S" <? if (isset($erro) && $form_adotante == 'S') echo 'checked' ?>> Sim
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_adotante" type="radio" name="form_adotante" id="form_adotante" value="N" <? if (isset($erro) && $form_adotante == 'N') echo 'checked' ?>> Não
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_adotante" type="radio" name="form_adotante" id="form_adotante" value="" <? if (isset($erro) && $form_adotante == '') echo 'checked' ?>> Todos
                  </label> &nbsp;

                </div>
              </div>

              <div class="form-group col-12 col-md-12 row">

                <label for="form_cadastro" class="col-12 px-0 mx-3"><span class="text-danger">*</span> Cadastros realizados entre:
                </label>
                <div class="col-md-5 mr-5 ml-3 font-weight-bold"> Data inicial:
                  <input type="date" name="form_dt_i" class="form-control" value="<?php if ($erro) echo $form_dt_i; ?>">
                </div>
                <div class="col-md-5 ml-5 font-weight-bold"> Data final:
                  <input type="date" name="form_dt_f" class="form-control " value="<?php if ($erro) echo $form_dt_f; ?>">
                </div>
              </div>

              <div class="form-group col-12 col-md-12">

                <label for="form_usuario" class="col-12 px-0"><span class="text-danger">*</span> Abertos por:
                </label>
                <div class="col-sm-11">
                  <select name="form_login" class="form-control col-12">
                    <option value="">Todos</option>
                    <?php $where_tipo_fila = "WHERE atendimento='S'";
                    include('../includes/inc_select_usuario.php'); ?>
                  </select>
                </div>
              </div>

              <div class="form-group col-12 col-md-6">

                <label for="form_macroregiao" class="col-12 px-0"><span class="text-danger">*</span> Macroregião:
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
                  <select name="form_microregiao" class="form-control col-12">
                    <?php include('../includes/inc_select_microregiao.php'); ?>
                  </select>
                </div>
              </div>

              <div class="form-group col- col-md-6">

                <label for="form_posoperatorio" class="col-12 px-0"><span class="text-danger">*</span> Pós Operatório:
                </label>
                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                  <label class="btn btn-light">
                    <input class="form_posoperatorio" type="radio" name="form_posoperatorio" id="form_posoperatorio" value="S" <? if (isset($erro) && $form_posoperatorio == 'S') echo 'checked' ?>> Sim
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_posoperatorio" type="radio" name="form_posoperatorio" id="form_posoperatorio" value="N" <? if (isset($erro) && $form_posoperatorio == 'N') echo 'checked' ?>> Não
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_posoperatorio" type="radio" name="form_posoperatorio" id="form_posoperatorio" value="N" <? if (isset($erro) && $form_posoperatorio == 'N') echo 'checked' ?>> Todos
                  </label> &nbsp;

                </div>
              </div>

              <div class="form-group col-12 col-md-6">

                <label for="form_statuscast" class="col-12 px-0"><span class="text-danger">*</span> Status Castração:
                </label>
                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                  <label class="btn btn-light">
                    <input class="form_statuscast" type="radio" name="form_statuscast" id="form_statuscast" value="A" <? if (isset($erro) && $form_statuscast == 'A') echo 'checked' ?>> Aguardando
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_statuscast" type="radio" name="form_statuscast" id="form_statuscast" value="R" <? if (isset($erro) && $form_statuscast == 'R') echo 'checked' ?>> Realizada
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_statuscast" type="radio" name="form_statuscast" id="form_statuscast" value="" <? if (isset($erro) && $form_statuscast == '') echo 'checked' ?>> Todos
                  </label> &nbsp;

                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="modal-footer bg-light-2 text-center">
          <input type="submit" name="filter" class="btn btn-green" value="Filtrar">
        </div>

      </form>
    </div>
  </div>
</div>
