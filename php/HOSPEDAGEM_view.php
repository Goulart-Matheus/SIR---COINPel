<div class="modal fade text-left" id="HOSPEDAGEM_view" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">



            <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtrar Atendimentos
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">

                        


                        <div class="form-group col-12 col-md-4">
                            <label for="form_id_animal"></span>Ficha do Animal</label>
                            <select name="form_id_animal" id="form_id_animal" class="form-control select2_form_animal_modal">
                                <?
                                $form_elemento = $erro ? $form_id_animal : "";
                                include("../includes/inc_select_animal.php"); ?>
                            </select>
                            <div class="invalid-feedback">
                                Escolha uma ficha.
                            </div>


                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_nro_chip"></span> Numero do Chip</label>
                            <select name="form_nro_chip" id="form_nro_chip" class="form-control select2_form_chip_modal">
                                <?php
                                    $form_elemento = $erro ? $form_nro_chip : "";
                                    include("../includes/inc_select_chip.php"); 
                                ?>
                            </select>
                            <div class="invalid-feedback">
                                Escolha um chip
                            </div>
                            
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_endereco_recolhimento"></span> Endereço de Recolhimento</label>
                            <input type="text" class="form-control" name="form_endereco_recolhimento" id="form_endereco_recolhimento" maxlength="100" value="<? if ($erro) echo $form_endereco_recolhimento; ?>">
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_id_bairro"></span> Bairro</label>
                            <select name="form_id_bairro" id="form_id_bairro" class="form-control">
                                <?
                                $form_elemento = $erro ? $form_id_bairro : "";
                                include("../includes/inc_select_bairro.php"); ?>
                            </select>
                            <div class="invalid-feedback">
                                Escolha o bairro.
                            </div>
                        </div>


                        <div class="form-group col-12 col-md-4">
                            <label for="form_id_responsavel"></span> Responsavel</label>
                            <select name="form_id_responsavel" id="form_id_responsavel" class="form-control select2_id_responsavel_modal">
                                <?
                                $form_elemento = $erro ? $form_id_responsavel : "";
                                include("../includes/inc_select_responsavel.php"); ?>
                            </select>
                            <div class="invalid-feedback">
                                Escolha o Responsavel.
                            </div>
                        </div>


                        <div class="form-group col-12 col-md-4">
                            <label for="form_dt_entrada"></span>Data Entrada</label>
                            <input type="date" class="form-control" name="form_dt_entrada" id="form_dt_entrada" maxlength="100" value="">
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_dt_retirada"></span> Data Retirada</label>
                            <input type="date" class="form-control" name="form_dt_retirada" id="form_dt_retirada" maxlength="100" value="">
                        </div>


                        <div class="form-group col-12 col-md-4">
                            <label for="form_id_motivo"></span> Motivo</label>
                            <select name="form_id_motivo" id="form_id_motivo" class="form-control">
                                <?
                                $form_elemento = $erro ? $form_id_motivo : "";
                                include("../includes/inc_select_motivo.php"); ?>
                            </select>
                            <div class="invalid-feedback">
                                Escolha o Motivo
                            </div>
                        </div>


                        <div class="form-group col-12 col-md-4">
                            <label for="form_situacao"><span class="text-danger">*</span> Situação</label>
                            <select class="form-control" name="form_situacao" id="form_situacao">
                                <option value="S"  >Em Atendimento</option>
                                <option value="N" >Atendimento Finalizado</option>
                                <option value="T" >Todos</option>
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
<script src="../assets/js/jquery.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

    if ($(".select2_form_animal_modal").length > 0) {
        $(".select2_form_animal_modal").attr('data-live-search', 'true');

        $(".select2_form_animal_modal").select2({
            width: '100%'
        });
    }

    if ($(".select2_form_chip_modal").length > 0) {
        $(".select2_form_chip_modal").attr('data-live-search', 'true');

        $(".select2_form_chip_modal").select2({
            width: '100%'
        });
    }

    if ($(".select2_id_responsavel_modal").length > 0) {
                $(".select2_id_responsavel_modal").attr('data-live-search', 'true');

                $(".select2_id_responsavel_modal").select2({
                    width: '100%'
                });
    }

});


    
    
</script>