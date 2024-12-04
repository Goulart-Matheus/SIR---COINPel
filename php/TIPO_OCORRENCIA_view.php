<div class="modal fade text-left" id="TIPO_OCORRENCIA_view" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">

                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtrar Tipo de Ocorrência
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row">


                        <div class="form-group col-12 col-md-6">
                            <label for="form_orgao" class="">Proprietário</label>
                            <select name="form_orgao" class="form-control select2_orgao">
                                <?
                                $where = "";
                                include "../includes/inc_select_orgao.php";
                                ?>
                            </select>
                        </div>


                        <div class="form-group col-12 col-md-6">
                            <label for="form_nome">Nome da ocorrencia</label>
                            <input autocomplete="off" type="text" class="form-control" name="form_nome" id="form_nome">
                        </div>

                        <div class="form-group col-12 col-md-6">

                            <label for="form_flag">Tipo</label>
                            <select class="form-control" name="form_flag" id="form_flag">
                                <option value="A">Administrativo</option>
                                <option value="P">Público</option>
                            </select>
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

<script>
    $(document).ready(function() {

        if ($(".select2_orgao").length > 0) {
            $(".select2_orgao").attr('data-live-search', 'true');
            $(".select2_orgao").select2({
                width: '100%'
            });
        }
    });
</script>