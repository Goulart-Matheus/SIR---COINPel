<div class="modal fade text-left" id="BAIRRO_view" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">

                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtrar Bairros
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row">

                        <div class="form-group col-12 col-md-6">

                            <label for="form_bairro">Bairro</label>
                            <input name="form_bairro" id="form_bairro" class="form-control" >

                        </div>

                        <div class="form-group col-12 col-md-6">

                            <label for="form_habilitado">Habilitado</label>
                            <select class="form-control" name="form_habilitado" id="form_habilitado">
                                <option value="S">Sim</option>
                                <option value="N">Não</option>                                
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

            </form>

        </div>

    </div>

</div>