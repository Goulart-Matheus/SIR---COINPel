<div class="modal fade text-left" id="PROPRIETARIO_view" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">

                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtrar Proprietários
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row">

                        <div class="form-group col-md-6">
                            <label for="form_cpf">CPF</label>
                            <input autocomplete="off" type="text" class="form-control" name="form_cpf" id="form_cpf">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="form_inscricao_estadual">Inscrição Estadual</label>
                            <input autocomplete="off" type="text" class="form-control" name="form_inscricao_estadual" id="form_inscricao_estadual">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="form_nome">Nome</label>
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