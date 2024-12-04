<div class="modal fade text-left" id="PROPRIEDADE_view" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">




            <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtrar Propriedades
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="form_proprietario" class="">Proprietário</label>
                            <select name="form_proprietario" class="form-control select2_proprietario">
                                <?
                                $where = "";
                                include "../includes/inc_select_proprietario.php";
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <label for="form_nome">Nome da propriedade</label>
                            <input autocomplete="off" type="text" class="form-control" name="form_nome" id="form_nome">
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

        if ($(".select2_proprietario").length > 0) {
            $(".select2_proprietario").attr('data-live-search', 'true');

            $(".select2_proprietario").select2({
                width: '100%'
            });
        }
    });
</script>