<div class="modal fade text-left" id="CANILCONSULTAAVALIACAO_view" tabindex="-1" role="dialog" aria-hidden="true">

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

                                <label for="form_tutor" class="col-12 px-0"><span class="text-danger">*</span> Tutor:
                                </label>
                                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                                    <input type="text" name="form_tutor" class="form-control" maxlength="180" value="<?php if ($erro) echo $form_tutor; ?>">

                                </div>
                            </div>

                            <div class="form-group col-12 col-md-6">

                                <label for="form_animal" class="col-12 px-0"><span class="text-danger">*</span> Animal:
                                </label>
                                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                                    <input type="text" name="form_animal" class="form-control" maxlength="180" value="<?php if ($erro) echo $form_animal; ?>">

                                </div>
                            </div>

                            <div class="form-group col-12 col-md-6">

                                <label for="form_nroConfere" class="col-12 px-0"><span class="text-danger">*</span> Nro Confere:
                                </label>
                                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                                    <label class="btn btn-light">
                                        <input class="form_nroConfere" type="radio" name="form_nroConfere" id="form_nroConfere" value="S" <? if (isset($erro) && $form_nroConfere == 'S') echo 'checked' ?>> Sim
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_nroConfere" type="radio" name="form_nroConfere" id="form_nroConfere" value="N" <? if (isset($erro) && $form_nroConfere == 'N') echo 'checked' ?>> Não
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_nroConfere" type="radio" name="form_nroConfere" id="form_nroConfere" value="" <? if (isset($erro) && $form_nroConfere == '') echo 'checked' ?>> Todos
                                    </label> &nbsp;

                                </div>
                            </div>

                            <div class="form-group col-12 col-md-6">

                                <label for="form_satisfeito" class="col-12 px-0"><span class="text-danger">*</span> Satisfeito:
                                </label>
                                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                                    <label class="btn btn-light">
                                        <input class="form_satisfeito" type="radio" name="form_satisfeito" id="form_satisfeito" value="S" <? if (isset($erro) && $form_satisfeito == 'S') echo 'checked' ?>> Sim
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_satisfeito" type="radio" name="form_satisfeito" id="form_satisfeito" value="N" <? if (isset($erro) && $form_satisfeito == 'N') echo 'checked' ?>> Não
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_satisfeito" type="radio" name="form_satisfeito" id="form_satisfeito" value="" <? if (isset($erro) && $form_satisfeito == '') echo 'checked' ?>> Todos
                                    </label> &nbsp;

                                </div>
                            </div>

                            <div class="form-group col-12 col-md-6">

                                <label for="form_transtorno" class="col-12 px-0"><span class="text-danger">*</span> Transtorno:
                                </label>
                                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                                    <label class="btn btn-light">
                                        <input class="form_transtorno" type="radio" name="form_transtorno" id="form_transtorno" value="S" <? if (isset($erro) && $form_transtorno == 'S') echo 'checked' ?>> Sim
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_transtorno" type="radio" name="form_transtorno" id="form_transtorno" value="N" <? if (isset($erro) && $form_transtorno == 'N') echo 'checked' ?>> Não
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_transtorno" type="radio" name="form_transtorno" id="form_transtorno" value="" <? if (isset($erro) && $form_transtorno == '') echo 'checked' ?>> Todos
                                    </label> &nbsp;

                                </div>
                            </div>

                            <div class="form-group col-12 col-md-6">

                                <label for="form_material" class="col-12 px-0"><span class="text-danger">*</span> Material:
                                </label>
                                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                                    <label class="btn btn-light">
                                        <input class="form_material" type="radio" name="form_material" id="form_material" value="S" <? if (isset($erro) && $form_material == 'S') echo 'checked' ?>> Sim
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_material" type="radio" name="form_material" id="form_material" value="N" <? if (isset($erro) && $form_material == 'N') echo 'checked' ?>> Não
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_material" type="radio" name="form_material" id="form_material" value="" <? if (isset($erro) && $form_material == '') echo 'checked' ?>> Todos
                                    </label> &nbsp;

                                </div>
                            </div>

                            <div class="form-group col-12 col-md-6">

                                <label for="form_pagamento" class="col-12 px-0"><span class="text-danger">*</span> Pagamento:
                                </label>
                                <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                                    <label class="btn btn-light">
                                        <input class="form_pagamento" type="radio" name="form_pagamento" id="form_pagamento" value="S" <? if (isset($erro) && $form_pagamento == 'S') echo 'checked' ?>> Sim
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_pagamento" type="radio" name="form_pagamento" id="form_pagamento" value="N" <? if (isset($erro) && $form_pagamento == 'N') echo 'checked' ?>> Não
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_pagamento" type="radio" name="form_pagamento" id="form_pagamento" value="" <? if (isset($erro) && $form_pagamento == '') echo 'checked' ?>> Todos
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