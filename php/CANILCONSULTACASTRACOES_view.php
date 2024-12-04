<div class="modal fade text-left" id="CANILCONSULTACASTRACOES_view" tabindex="-1" role="dialog" aria-hidden="true">

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

        <div class="card-body pt-0">
          <div class="mx-2 my-2">
            <div class="form-row">

              <div class="form-group col-12 col-md-6">

                <label for="form_tutor" class="col-12 px-0"><span class="text-danger">*</span> Tutor/CPF:
                </label>
                <input type="text" name="form_tutor" class="borda_form" size="50" maxlength="100" value="<? if ($erro) echo $form_tutor; ?>">
              </div>


              <div class="form-group col-12 col-md-6">

                <label for="form_tipo" class="col-12 px-0"><span class="text-danger">*</span> Tipo:
                </label>
                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                  <label class="btn btn-light">
                    <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="T" <? if (isset($erro) && $form_tipo == 'Canina') echo 'checked' ?>> Tutor
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="P" <? if (isset($erro) && $form_tipo == 'Felina') echo 'checked' ?>> Protetor
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

                <label for="form_animal" class="col-12 px-0"><span class="text-danger">*</span> Animal:
                </label>
                <input type="text" name="form_animal" class="borda_form" size="50" maxlength="100" value="<? if ($erro) echo $form_animal; ?>">
              </div>

              <div class="form-group col-12 col-md-6">

                <label for="form_especie" class="col-12 px-0"><span class="text-danger">*</span> Espécie:
                </label>
                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                  <label class="btn btn-light">
                    <input class="form_especie" type="radio" name="form_especie" id="form_especie" value="S" <? if (isset($erro) && $form_especie == 'Canina') echo 'checked' ?>> Canina
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_especie" type="radio" name="form_especie" id="form_especie" value="N" <? if (isset($erro) && $form_especie == 'Felina') echo 'checked' ?>> Felina
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_especie" type="radio" name="form_especie" id="form_especie" value="" <? if (isset($erro) && $form_especie == '') echo 'checked' ?>> Todos
                  </label> &nbsp;

                </div>
              </div>


              <div class="form-group col-12 col-md-6">

                <label for="form_sexo" class="col-12 px-0"><span class="text-danger">*</span> Sexo:
                </label>
                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                  <label class="btn btn-light">
                    <input class="form_sexo" type="radio" name="form_sexo" id="form_sexo" value="F" <? if (isset($erro) && $form_sexo == 'S') echo 'checked' ?>> Macho
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_sexo" type="radio" name="form_sexo" id="form_sexo" value="M" <? if (isset($erro) && $form_sexo == 'N') echo 'checked' ?>> Fêmea
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_sexo" type="radio" name="form_sexo" id="form_sexo" value="" <? if (isset($erro) && $form_sexo == '') echo 'checked' ?>> Todos
                  </label> &nbsp;

                </div>
              </div>


              <div class="form-group col-12 col-md-6">

                <label for="form_deRua" class="col-12 px-0"><span class="text-danger">*</span> De Rua:
                </label>
                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                  <label class="btn btn-light">
                    <input class="form_deRua" type="radio" name="form_deRua" id="form_deRua" value="S" <? if (isset($erro) && $form_deRua == 'S') echo 'checked' ?>> Sim
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_deRua" type="radio" name="form_deRua" id="form_deRua" value="N" <? if (isset($erro) && $form_deRua == 'N') echo 'checked' ?>> Não
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_deRua" type="radio" name="form_deRua" id="form_deRua" value="" <? if (isset($erro) && $form_deRua == '') echo 'checked' ?>> Todos
                  </label> &nbsp;

                </div>
              </div>

              <div class="form-group col-12 col-md-6">

                <label for="form_situacao" class="col-12 px-0"><span class="text-danger">*</span> Situação:
                </label>
                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                  <label class="btn btn-light">
                    <input class="form_situacao" type="radio" name="form_situacao" id="form_situacao" value="R" <? if (isset($erro) && $form_situacao == 'R') echo 'checked' ?>> Realizada
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_situacao" type="radio" name="form_situacao" id="form_situacao" value="N" <? if (isset($erro) && $form_situacao == 'N') echo 'checked' ?>> Não Realizada
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_situacao" type="radio" name="form_situacao" id="form_situacao" value="A" <? if (isset($erro) && $form_situacao == 'A') echo 'checked' ?>> Aguardando
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_situacao" type="radio" name="form_situacao" id="form_situacao" value="" <? if (isset($erro) && $form_situacao == '') echo 'checked' ?>> Todos
                  </label> &nbsp;

                </div>
              </div>


              <div class="form-group col-12 col-md-6">

                <label for="form_chip" class="col-12 px-0"><span class="text-danger">*</span> Chip:
                </label>

                <input type="text" name="form_chip" class="borda_form" size="20" maxlength="20" value="<? if ($erro) echo $form_chip; ?>">

              </div>


              <div class="form-group col-12 col-md-12 row">

                <label for="form_agendamentos" class="col-12 px-0 mx-3"><span class="text-danger">*</span> Agendamentos realizados entre:
                </label>

                <div class="col-md-5 mr-5 ml-3 font-weight-bold"> Data inicial:
                  <input type="date" name="form_dt_i" class="form-control" value="<?php if ($erro) echo $form_dt_i; ?>">
                </div>

                <div class="col-md-5 ml-5 font-weight-bold"> Data final:
                  <input type="date" name="form_dt_f" class="form-control " value="<?php if ($erro) echo $form_dt_f; ?>">
                </div>

              </div>


              <div class="form-group col-12 col-md-6">

                <label for="form_castramvl" class="col-12 px-0"><span class="text-danger">*</span> Castra Móvel:
                </label>
                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                  <label class="btn btn-light">
                    <input class="form_castramvl" type="radio" name="form_castramvl" id="form_castramvl" value="S" <? if (isset($erro) && $form_castramvl == 'S') echo 'checked' ?>> Sim
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_castramvl" type="radio" name="form_castramvl" id="form_castramvl" value="N" <? if (isset($erro) && $form_castramvl == 'N') echo 'checked' ?>> Não
                  </label> &nbsp;

                  <label class="btn btn-light">
                    <input class="form_castramvl" type="radio" name="form_castramvl" id="form_castramvl" value="" <? if (isset($erro) && $form_castramvl == '') echo 'checked' ?>> Todos
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
