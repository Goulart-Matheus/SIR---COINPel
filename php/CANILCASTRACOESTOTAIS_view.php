<div class="modal fade text-left" id="CANILCASTRACOESTOTAIS_view" tabindex="-1" role="dialog" aria-hidden="true">

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
          <div class="form-row">

            <div class="form-group col-12 col-md-6">

              <label for="form_tipo" class="col-12 px-0"><span class="text-danger">*</span> Tipo:
              </label>
              <div class="btn-group btn-group-toggle" data-toggle="buttons">

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
                  <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="" <? if (isset($erro) && $form_tipo == '') echo 'checked' ?>> Todos
                </label> &nbsp;

              </div>
            </div>

            <div class="form-group col-12 col-md-6">

              <label for="form_especie" class="col-12 px-0"><span class="text-danger">*</span> Espécie:
              </label>
              <div class="btn-group btn-group-toggle" data-toggle="buttons">

                <label class="btn btn-light">
                  <input class="form_especie" type="radio" name="form_especie" id="form_especie" value="C" <? if (isset($erro) && $form_especie == 'C') echo 'checked' ?>> Canina
                </label> &nbsp;

                <label class="btn btn-light">
                  <input class="form_especie" type="radio" name="form_especie" id="form_especie" value="F" <? if (isset($erro) && $form_especie == 'F') echo 'checked' ?>> Felina
                </label> &nbsp;

                <label class="btn btn-light">
                  <input class="form_especie" type="radio" name="form_especie" id="form_especie" value="" <? if (isset($erro) && $form_especie == '') echo 'checked' ?>> Todos
                </label> &nbsp;

              </div>
            </div>

            <div class="form-group col-12 col-md-6">

              <label for="form_sexo" class="col-12 px-0"><span class="text-danger">*</span> Sexo:
              </label>
              <div class="btn-group btn-group-toggle" data-toggle="buttons">

                <label class="btn btn-light">
                  <input class="form_sexo" type="radio" name="form_sexo" id="form_sexo" value="M" <? if (isset($erro) && $form_sexo == 'M') echo 'checked' ?>> Macho
                </label> &nbsp;

                <label class="btn btn-light">
                  <input class="form_sexo" type="radio" name="form_sexo" id="form_sexo" value="F" <? if (isset($erro) && $form_sexo == 'F') echo 'checked' ?>> Fêmea
                </label> &nbsp;

                <label class="btn btn-light">
                  <input class="form_sexo" type="radio" name="form_sexo" id="form_sexo" value="" <? if (isset($erro) && $form_sexo == '') echo 'checked' ?>> Todos
                </label> &nbsp;

              </div>
            </div>

            <div class="form-group col-12 col-md-6">

              <label for="form_deRua" class="col-12 px-0"><span class="text-danger">*</span> De Rua:
              </label>
              <div class="btn-group btn-group-toggle" data-toggle="buttons">

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

            <div class="form-group col-6 col-md-6">

              <label for="form_situacao" class="col-12 px-0"><span class="text-danger">*</span> Situação:
              </label>
              <div class="btn-group btn-group-toggle" data-toggle="buttons">

                <label class="btn btn-light">
                  <input class="form_situacao" type="radio" name="form_situacao" id="form_situacao" value="S" <? if (isset($erro) && $form_situacao == 'S') echo 'checked' ?>> Realizada
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

              <label for="form_deRua" class="col-12 px-0"><span class="text-danger">*</span> Castrações realizadas entre:
              </label>

              <input type="date" name="form_dt_i" class="form-control d-inline" style="width: auto;" value="<?php if ($erro) echo $form_dt_i; ?>"> e
              <input type="date" name="form_dt_f" class="form-control d-inline" style="width: auto;" value="<?php if ($erro) echo $form_dt_f; ?>">

            </div>

          </div>


        </div>

        <!-- Botões -->
        <div class="modal-footer bg-light-2 text-center">
          <input type="submit" name="filter" class="btn btn-green" value="Filtrar">
        </div>

      </form>
    </div>
  </div>
</div>