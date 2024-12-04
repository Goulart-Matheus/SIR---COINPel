<div class="modal fade text-left" id="URM_view" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">

                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtrar URM
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row">

                        <div class="form-group col-12 col-md-6">
                            <label for="form_valor">Valor</label>
                            <input type="text" class="form-control" name="form_valor" id="form_valor">
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <label for="form_ativo">Ativo</label>
                            <select class="form-control" name="form_ativo" id="form_ativo">
                                <option value="S">Sim</option>
                                <option value="N">Não</option>
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <label for="form_mes_referencia">Mês Referencia</label>
                            <input type="text" class="form-control" name="form_mes_referencia" id="form_mes_referencia">
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <label for="form_ano_referencia">Ano Referencia</label>
                            <input type="text" class="form-control" name="form_ano_referencia" id="form_ano_referencia">
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light-2 text-center">
                    <button type="submit" name="filter" class="btn btn-light">
                        <i class="fa-solid fa-filter text-green"></i>
                        Filtrar
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>