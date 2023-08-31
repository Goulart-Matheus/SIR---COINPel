<div class="modal fade text-left" id="MICRORREGIAO_view" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">

                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtrar Microterritório
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row">

                        <div class="form-group col-12 col-md-6">
                            <label for="form_nome"><span class="text-danger">*</span> Microterritório</label>
                            <input type="text" class="form-control" name="form_nome" id="form_nome" maxlength="100" value="<? if ($erro) echo $form_nome; ?>">
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <label for="form_habilitado"><span class="text-danger">*</span> Habilitado</label>
                            <select name="form_habilitado" id="form_habilitado" class="form-control">
                                <option value="S" <? if ($erro && $form_habilitado == 'S') echo 'selected'  ?>>Sim</option>
                                <option value="N" <? if ($erro && $form_habilitado == 'N') echo 'selected'; ?>>Não</option>
                            </select>
                        </div>

                    </div>                 

                </div>

                <div class="modal-footer bg-light-2 text-center">
                    <button type="submit" name="filter" class="btn btn-light">
                        <i class="fa-solid fa-filter text-green"></i>
                        Filtrar
                    </button>
                </div>

            </div>

            

        </form>

    </div>

</div>