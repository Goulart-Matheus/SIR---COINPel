<div class="modal fade text-left" id="ANIMAL_view" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">

                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtrar Registro de Habitantes
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row">

                        <div class="form-group col-12 col-md-4">
                            <label for="form_nro_ficha"></span> Numero Ficha</label>
                            <input type="text" class="form-control" name="form_nro_ficha" id="form_nro_ficha" maxlength="100" value="<? if ($erro) echo $form_nro_ficha; ?>">
                        </div>


                        <div class="form-group col-12 col-md-4">
                            <label for="form_nro_chip"></span> Numero Chip</label>
                            <input type="text" class="form-control" name="form_nro_chip" id="form_nro_chip" maxlength="100" value="<? if ($erro) echo $form_nro_chip; ?>">
                        </div>


                        <div class="form-group col-12 col-md-4">
                            <label for="form_sexo"></span> Sexo</label>
                            <select name="form_sexo" id="form_sexo" class="form-control">
                                <option value="">Selecione o sexo:</option>
                                <option value="M">Macho</option>
                                <option value="F">Fêmea</option>
                            </select>
                            <div class="invalid-feedback">
                                Escolha o sexo do animal.
                            </div>
                        </div>


                        <div class="form-group col-12 col-md-6">
                            <label for="form_id_pelagem"></span>Pelagem</label>
                            <select name="form_id_pelagem" id="form_id_pelagem" class="form-control">
                                <?
                                $form_elemento = $erro ? $form_id_pelagem : "";
                                include("../includes/inc_select_pelagem.php"); ?>
                            </select>
                            <div class="invalid-feedback">
                                Escolha a Pelagem
                            </div>
                        </div>


                        <div class="form-group col-12 col-md-6">
                            <label for="form_id_especie"></span>Espécie</label>
                            <select name="form_id_especie" id="form_id_especie" class="form-control">
                                <?
                                $form_elemento = $erro ? $form_id_especie : "";
                                include("../includes/inc_select_especie.php"); ?>
                            </select>
                            <div class="invalid-feedback">
                                Escolha Especie
                            </div>
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