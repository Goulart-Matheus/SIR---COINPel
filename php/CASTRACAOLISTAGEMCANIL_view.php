<div class="modal fade text-left" id="CASTRACAOLISTAGEMCANIL_view" tabindex="-1" role="dialog" aria-hidden="true">

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

                            <div class="form-group col-12 col-md-12">

                                <label for="form_tipo" class="col-12 px-0"><span class="text-danger">*</span> Tipo:
                                </label>
                                <div class="btn-group btn-group-toggle " data-toggle="buttons">

                                    <label class="btn btn-light">
                                        <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="P" <? if (isset($erro) && $form_tipo == 'P') echo 'checked' ?>> Protetores
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="F" <? if (isset($erro) && $form_tipo == 'F') echo 'checked' ?>> Fiscalização
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="C" <? if (isset($erro) && $form_tipo == 'C') echo 'checked' ?>> Canil
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="T" <? if (isset($erro) && $form_tipo == 'T') echo 'checked' ?>> Tutores
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

                                <label for="form_castra_movel" class="col-12 px-0"><span class="text-danger">*</span> Castra Móvel:
                                </label>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">

                                    <label class="btn btn-light">
                                        <input class="form_castra_movel" type="radio" name="form_castra_movel" id="form_castra_movel" value="S" <? if (isset($erro) && $form_castra_movel == 'S') echo 'checked' ?>> Sim
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_castra_movel" type="radio" name="form_castra_movel" id="form_castra_movel" value="N" <? if (isset($erro) && $form_castra_movel == 'N') echo 'checked' ?>> Não
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_castra_movel" type="radio" name="form_castra_movel" id="form_castra_movel" value="" <? if (isset($erro) && $form_castra_movel == '') echo 'checked' ?>> Todos
                                    </label> &nbsp;

                                </div>
                            </div>

                            <div class="form-group col-12 col-md-6">

                                <label for="form_especie" class="col-12 px-0"><span class="text-danger">*</span> Espécie:
                                </label>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">

                                    <label class="btn btn-light">
                                        <input class="form_especie" type="radio" name="form_especie" id="form_especie" value="Canina" <? if (isset($erro) && $form_especie == 'Canina') echo 'checked' ?>> Canina
                                    </label> &nbsp;

                                    <label class="btn btn-light">
                                        <input class="form_especie" type="radio" name="form_especie" id="form_especie" value="Felina" <? if (isset($erro) && $form_especie == 'Felina') echo 'checked' ?>> Felina
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

                                <label for="form_nome" class="col-12 px-0"><span class="text-danger">*</span> Nome:
                                </label>
                                <div class="mx-3">

                                    <input type="text" name="form_nome" class="form-control" maxlength="180" value="<?php if ($erro) echo $form_nome; ?>">

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