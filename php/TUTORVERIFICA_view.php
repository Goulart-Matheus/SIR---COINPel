<div class="modal fade text-left" id="TUTORVERIFICA_view" tabindex="-1" role="dialog" aria-hidden="true">

  <div class="modal-dialog modal-xl" role="document">

    <div class="modal-content">

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">

          <div class="card-body pt-0">
            <div class="mx-2 my-2">
              <div class="form-row">

                  <div class="form-group col-12 col-md-6">

                    <label for="form_nome" class="col-12 px-0"><span class="text-danger">*</span> Nome do Tutor:
                    </label>
                    <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                      <input type="text" name="form_nome" class="form-control" maxlength="180" value="<?php if ($erro) echo $form_nome; ?>">

                    </div>
                  </div>

                  <div class="form-group col-12 col-md-6">

                    <label for="form_cpf" class="col-12 px-0"><span class="text-danger">*</span> CPF:
                    </label>
                    <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                      <input type="text" name="form_cpf" class="form-control" maxlength="180" value="<?php if ($erro) echo $form_cpf; ?>">

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