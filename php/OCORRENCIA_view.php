<div class="modal fade text-left" id="OCORRENCIA_view" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">



            <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtrar Ocorrências
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">




                        <div class="form-group col-12 col-md-4">
                            <label for="form_protocolo"></span> Protocolo</label>
                            <input type="text" class="form-control" name="form_protocolo" id="form_protocolo">
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_comunicante"></span> Nome do Comunicante</label>
                            <input type="text" class="form-control" name="form_comunicante" id="form_comunicante">
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_email"></span> E-mail</label>
                            <input type="mail" class="form-control" name="form_email" id="form_email">
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_telefone"></span> Telefone</label>
                            <input type="text" class="form-control telefone" name="form_telefone" id="form_telefone">
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_data"></span> Data</label>
                            <input type="date" class="form-control" name="form_data" id="form_data">
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_descricao"></span> Descriçao</label>
                            <input type="text" class="form-control" name="form_descricao" id="form_descricao">
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_endereco"></span>Endereço</label>
                            <input type="text" class="form-control" name="form_endereco" id="form_endereco" maxlength="100">
                        </div>


                        <div class="form-group col-12 col-md-4">
                            <label for="form_regiao_administrativa"></span> Região Administrativa</label>
                            <select name="form_regiao_administrativa" id="form_regiao_administrativa" class="form-control select2_id_regiao_administrativa">
                                <?
                                $form_elemento = $erro ? $form_regiao_administrativa : "";
                                include("../includes/inc_select_regiao_administrativa.php"); ?>
                            </select>
                            <div class="invalid-feedback">
                                Selecione a região administrativa.
                            </div>
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_status"></span> Situação </label>
                            <select name="form_status" id="form_status" class="form-control">
                                <option value="">Selecione a Situação:</option>
                                <option value="N">Nova Ocorrência</option>
                                <option value="A">Em atendimento</option>
                                <option value="F">Atendimento Finalizado</option>

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